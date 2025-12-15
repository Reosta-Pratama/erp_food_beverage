<?php

namespace App\Http\Controllers\Business;

use App\Helpers\CodeGeneratorHelper;
use App\Http\Controllers\Controller;
use App\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PurchaseReturnController extends Controller
{
    //
    use LogsActivity;

    /**
     * Display a listing of purchase returns
     */
    public function index(Request $request)
    {
        $query = DB::table('purchase_returns as pr')
            ->join('suppliers', 'pr.supplier_id', '=', 'suppliers.supplier_id')
            ->leftJoin('purchase_receipts as receipt', 'pr.receipt_id', '=', 'receipt.receipt_id')
            ->leftJoin('users', 'pr.created_by', '=', 'users.user_id')
            ->select(
                'pr.pr_id',
                'pr.pr_code',
                'pr.supplier_id',
                'pr.receipt_id',
                'pr.return_date',
                'pr.return_reason',
                'pr.status',
                'pr.total_amount',
                'pr.notes',
                'pr.created_at',
                'suppliers.supplier_code',
                'suppliers.supplier_name',
                'receipt.receipt_code',
                'users.full_name as created_by_name'
            );

        // Filter by supplier
        if ($request->filled('supplier_id')) {
            $query->where('pr.supplier_id', $request->supplier_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('pr.status', $request->status);
        }

        // Filter by return reason
        if ($request->filled('return_reason')) {
            $query->where('pr.return_reason', $request->return_reason);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('pr.return_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('pr.return_date', '<=', $request->date_to);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('pr.pr_code', 'like', "%{$search}%")
                  ->orWhere('suppliers.supplier_name', 'like', "%{$search}%")
                  ->orWhere('receipt.receipt_code', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'return_date');
        $sortOrder = $request->get('sort_order', 'desc');
        
        $allowedSort = ['pr_code', 'return_date', 'supplier_name', 'status', 'total_amount', 'created_at'];
        if (in_array($sortBy, $allowedSort)) {
            $query->orderBy($sortBy, $sortOrder);
        }

        $purchaseReturns = $query->paginate(15)->withQueryString();

        // Get filter options
        $suppliers = DB::table('suppliers')
            ->where('is_active', 1)
            ->orderBy('supplier_name')
            ->get();

        $statuses = DB::table('purchase_returns')
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');

        $returnReasons = DB::table('purchase_returns')
            ->select('return_reason', DB::raw('COUNT(*) as count'))
            ->groupBy('return_reason')
            ->pluck('count', 'return_reason');

        return view('admin.purchase.returns.index', compact(
            'purchaseReturns',
            'suppliers',
            'statuses',
            'returnReasons'
        ));
    }

    /**
     * Show the form for creating a new purchase return
     */
    public function create()
    {
        // Get active suppliers
        $suppliers = DB::table('suppliers')
            ->where('is_active', 1)
            ->orderBy('supplier_name')
            ->get();

        // Get recent purchase receipts (last 90 days)
        $receipts = DB::table('purchase_receipts as pr')
            ->join('suppliers', 'pr.supplier_id', '=', 'suppliers.supplier_id')
            ->where('pr.receipt_date', '>=', now()->subDays(90))
            ->select(
                'pr.receipt_id',
                'pr.receipt_code',
                'pr.receipt_date',
                'suppliers.supplier_name'
            )
            ->orderBy('pr.receipt_date', 'desc')
            ->get();

        return view('admin.purchase.returns.create', compact('suppliers', 'receipts'));
    }

    /**
     * Store a newly created purchase return
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => ['required', 'exists:suppliers,supplier_id'],
            'receipt_id' => ['nullable', 'exists:purchase_receipts,receipt_id'],
            'return_date' => ['required', 'date', 'before_or_equal:today'],
            'return_reason' => ['required', 'string', 'max:30'],
            'notes' => ['nullable', 'string'],
            
            // Items
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,product_id'],
            'items.*.lot_id' => ['nullable', 'exists:lots,lot_id'],
            'items.*.quantity_returned' => ['required', 'numeric', 'min:0.0001'],
            'items.*.uom_id' => ['required', 'exists:units_of_measure,uom_id'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0'],
            'items.*.defect_type' => ['nullable', 'string', 'max:100'],
            'items.*.notes' => ['nullable', 'string'],
        ], [
            'supplier_id.required' => 'Supplier is required.',
            'return_date.required' => 'Return date is required.',
            'return_reason.required' => 'Return reason is required.',
            'items.required' => 'At least one item is required.',
            'items.min' => 'At least one item is required.',
        ]);

        DB::beginTransaction();
        try {
            // Generate PR code
            $prCode = CodeGeneratorHelper::generatePRCode();

            // Calculate total amount
            $totalAmount = collect($validated['items'])->sum(function($item) {
                return $item['quantity_returned'] * $item['unit_price'];
            });

            // Insert purchase return
            $prId = DB::table('purchase_returns')->insertGetId([
                'pr_code' => $prCode,
                'supplier_id' => $validated['supplier_id'],
                'receipt_id' => $validated['receipt_id'] ?? null,
                'return_date' => $validated['return_date'],
                'return_reason' => $validated['return_reason'],
                'status' => 'Pending',
                'total_amount' => $totalAmount,
                'created_by' => Auth::id(),
                'notes' => $validated['notes'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Insert purchase return items
            foreach ($validated['items'] as $item) {
                $lineTotal = $item['quantity_returned'] * $item['unit_price'];
                
                DB::table('purchase_return_items')->insert([
                    'pr_id' => $prId,
                    'product_id' => $item['product_id'],
                    'lot_id' => $item['lot_id'] ?? null,
                    'quantity_returned' => $item['quantity_returned'],
                    'uom_id' => $item['uom_id'],
                    'unit_price' => $item['unit_price'],
                    'line_total' => $lineTotal,
                    'defect_type' => $item['defect_type'] ?? null,
                    'notes' => $item['notes'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Log CREATE
            $this->logCreate(
                'Business Management - Purchase Returns',
                'purchase_returns',
                $prId,
                array_merge($validated, ['pr_code' => $prCode, 'total_amount' => $totalAmount])
            );

            DB::commit();

            return redirect()
                ->route('purchase.returns.show', $prCode)
                ->with('success', 'Purchase return created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Failed to create purchase return: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified purchase return
     */
    public function show($prCode)
    {
        $purchaseReturn = DB::table('purchase_returns as pr')
            ->join('suppliers', 'pr.supplier_id', '=', 'suppliers.supplier_id')
            ->leftJoin('purchase_receipts as receipt', 'pr.receipt_id', '=', 'receipt.receipt_id')
            ->leftJoin('users as creator', 'pr.created_by', '=', 'creator.user_id')
            ->where('pr.pr_code', $prCode)
            ->select(
                'pr.*',
                'suppliers.supplier_code',
                'suppliers.supplier_name',
                'suppliers.contact_person',
                'suppliers.email',
                'suppliers.phone',
                'suppliers.address',
                'receipt.receipt_code',
                'receipt.receipt_date',
                'creator.full_name as created_by_name'
            )
            ->first();

        if (!$purchaseReturn) {
            abort(404, 'Purchase return not found');
        }

        // Get return items
        $items = DB::table('purchase_return_items as pri')
            ->join('products', 'pri.product_id', '=', 'products.product_id')
            ->join('units_of_measure as uom', 'pri.uom_id', '=', 'uom.uom_id')
            ->leftJoin('lots', 'pri.lot_id', '=', 'lots.lot_id')
            ->where('pri.pr_id', $purchaseReturn->pr_id)
            ->select(
                'pri.*',
                'products.product_code',
                'products.product_name',
                'uom.uom_name',
                'lots.lot_code'
            )
            ->get();

        // Log VIEW
        $this->logView(
            'Business Management - Purchase Returns',
            "Viewed purchase return: {$prCode}"
        );

        return view('admin.purchase.returns.show', compact('purchaseReturn', 'items'));
    }

    /**
     * Show the form for editing the specified purchase return
     */
    public function edit($prCode)
    {
        $purchaseReturn = DB::table('purchase_returns')
            ->where('pr_code', $prCode)
            ->first();

        if (!$purchaseReturn) {
            abort(404, 'Purchase return not found');
        }

        // Only allow edit if status is Pending
        if ($purchaseReturn->status !== 'Pending') {
            return back()->with('error', 'Cannot edit purchase return with status: ' . $purchaseReturn->status);
        }

        // Get suppliers
        $suppliers = DB::table('suppliers')
            ->where('is_active', 1)
            ->orderBy('supplier_name')
            ->get();

        // Get receipts
        $receipts = DB::table('purchase_receipts as pr')
            ->join('suppliers', 'pr.supplier_id', '=', 'suppliers.supplier_id')
            ->where('pr.receipt_date', '>=', now()->subDays(90))
            ->select(
                'pr.receipt_id',
                'pr.receipt_code',
                'pr.receipt_date',
                'suppliers.supplier_name'
            )
            ->orderBy('pr.receipt_date', 'desc')
            ->get();

        // Get existing items
        $items = DB::table('purchase_return_items as pri')
            ->join('products', 'pri.product_id', '=', 'products.product_id')
            ->join('units_of_measure as uom', 'pri.uom_id', '=', 'uom.uom_id')
            ->leftJoin('lots', 'pri.lot_id', '=', 'lots.lot_id')
            ->where('pri.pr_id', $purchaseReturn->pr_id)
            ->select('pri.*', 'products.product_name', 'uom.uom_name', 'lots.lot_code')
            ->get();

        return view('admin.purchase.returns.edit', compact('purchaseReturn', 'suppliers', 'receipts', 'items'));
    }

    /**
     * Update the specified purchase return
     */
    public function update(Request $request, $prCode)
    {
        $purchaseReturn = DB::table('purchase_returns')
            ->where('pr_code', $prCode)
            ->first();

        if (!$purchaseReturn) {
            abort(404, 'Purchase return not found');
        }

        // Only allow update if status is Pending
        if ($purchaseReturn->status !== 'Pending') {
            return back()->with('error', 'Cannot update purchase return with status: ' . $purchaseReturn->status);
        }

        $validated = $request->validate([
            'supplier_id' => ['required', 'exists:suppliers,supplier_id'],
            'receipt_id' => ['nullable', 'exists:purchase_receipts,receipt_id'],
            'return_date' => ['required', 'date', 'before_or_equal:today'],
            'return_reason' => ['required', 'string', 'max:30'],
            'notes' => ['nullable', 'string'],
            
            // Items
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,product_id'],
            'items.*.lot_id' => ['nullable', 'exists:lots,lot_id'],
            'items.*.quantity_returned' => ['required', 'numeric', 'min:0.0001'],
            'items.*.uom_id' => ['required', 'exists:units_of_measure,uom_id'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0'],
            'items.*.defect_type' => ['nullable', 'string', 'max:100'],
            'items.*.notes' => ['nullable', 'string'],
        ]);

        DB::beginTransaction();
        try {
            // Capture old data
            $oldData = [
                'supplier_id' => $purchaseReturn->supplier_id,
                'receipt_id' => $purchaseReturn->receipt_id,
                'return_date' => $purchaseReturn->return_date,
                'return_reason' => $purchaseReturn->return_reason,
                'total_amount' => $purchaseReturn->total_amount,
                'notes' => $purchaseReturn->notes,
            ];

            // Calculate new total amount
            $totalAmount = collect($validated['items'])->sum(function($item) {
                return $item['quantity_returned'] * $item['unit_price'];
            });

            // Update purchase return
            DB::table('purchase_returns')
                ->where('pr_id', $purchaseReturn->pr_id)
                ->update([
                    'supplier_id' => $validated['supplier_id'],
                    'receipt_id' => $validated['receipt_id'] ?? null,
                    'return_date' => $validated['return_date'],
                    'return_reason' => $validated['return_reason'],
                    'total_amount' => $totalAmount,
                    'notes' => $validated['notes'] ?? null,
                    'updated_at' => now(),
                ]);

            // Delete old items and insert new ones
            DB::table('purchase_return_items')
                ->where('pr_id', $purchaseReturn->pr_id)
                ->delete();

            foreach ($validated['items'] as $item) {
                $lineTotal = $item['quantity_returned'] * $item['unit_price'];
                
                DB::table('purchase_return_items')->insert([
                    'pr_id' => $purchaseReturn->pr_id,
                    'product_id' => $item['product_id'],
                    'lot_id' => $item['lot_id'] ?? null,
                    'quantity_returned' => $item['quantity_returned'],
                    'uom_id' => $item['uom_id'],
                    'unit_price' => $item['unit_price'],
                    'line_total' => $lineTotal,
                    'defect_type' => $item['defect_type'] ?? null,
                    'notes' => $item['notes'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Log UPDATE
            $this->logUpdate(
                'Business Management - Purchase Returns',
                'purchase_returns',
                $purchaseReturn->pr_id,
                $oldData,
                array_merge($validated, ['total_amount' => $totalAmount])
            );

            DB::commit();

            return redirect()
                ->route('purchase.returns.show', $prCode)
                ->with('success', 'Purchase return updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Failed to update purchase return: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified purchase return
     */
    public function destroy($prCode)
    {
        $purchaseReturn = DB::table('purchase_returns')
            ->where('pr_code', $prCode)
            ->first();

        if (!$purchaseReturn) {
            abort(404, 'Purchase return not found');
        }

        // Only allow delete if status is Pending
        if ($purchaseReturn->status !== 'Pending') {
            return back()->with('error', 'Cannot delete purchase return with status: ' . $purchaseReturn->status);
        }

        DB::beginTransaction();
        try {
            // Capture old data
            $oldData = [
                'pr_code' => $purchaseReturn->pr_code,
                'supplier_id' => $purchaseReturn->supplier_id,
                'return_date' => $purchaseReturn->return_date,
                'return_reason' => $purchaseReturn->return_reason,
                'status' => $purchaseReturn->status,
                'total_amount' => $purchaseReturn->total_amount,
            ];

            // Delete return items first
            DB::table('purchase_return_items')
                ->where('pr_id', $purchaseReturn->pr_id)
                ->delete();

            // Delete purchase return
            DB::table('purchase_returns')
                ->where('pr_id', $purchaseReturn->pr_id)
                ->delete();

            // Log DELETE
            $this->logDelete(
                'Business Management - Purchase Returns',
                'purchase_returns',
                $purchaseReturn->pr_id,
                $oldData
            );

            DB::commit();

            return redirect()
                ->route('purchase.returns.index')
                ->with('success', 'Purchase return deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete purchase return: ' . $e->getMessage());
        }
    }

    /**
     * Approve purchase return
     */
    public function approve($prCode)
    {
        $purchaseReturn = DB::table('purchase_returns')
            ->where('pr_code', $prCode)
            ->first();

        if (!$purchaseReturn) {
            abort(404, 'Purchase return not found');
        }

        if ($purchaseReturn->status !== 'Pending') {
            return back()->with('error', 'Purchase return is not in Pending status.');
        }

        DB::beginTransaction();
        try {
            // Update status to Approved
            DB::table('purchase_returns')
                ->where('pr_id', $purchaseReturn->pr_id)
                ->update([
                    'status' => 'Approved',
                    'updated_at' => now(),
                ]);

            // TODO: Update inventory (reduce stock)
            // TODO: Create credit note or update accounts payable

            // Log approval
            $this->logApproval(
                'Business Management - Purchase Returns',
                'purchase_returns',
                $purchaseReturn->pr_id,
                'approved',
                'Purchase return approved'
            );

            DB::commit();

            return back()->with('success', 'Purchase return approved successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to approve purchase return: ' . $e->getMessage());
        }
    }

    /**
     * Reject purchase return
     */
    public function reject(Request $request, $prCode)
    {
        $validated = $request->validate([
            'rejection_notes' => ['required', 'string', 'max:500'],
        ]);

        $purchaseReturn = DB::table('purchase_returns')
            ->where('pr_code', $prCode)
            ->first();

        if (!$purchaseReturn) {
            abort(404, 'Purchase return not found');
        }

        if ($purchaseReturn->status !== 'Pending') {
            return back()->with('error', 'Purchase return is not in Pending status.');
        }

        DB::beginTransaction();
        try {
            // Update status to Rejected
            DB::table('purchase_returns')
                ->where('pr_id', $purchaseReturn->pr_id)
                ->update([
                    'status' => 'Rejected',
                    'notes' => ($purchaseReturn->notes ? $purchaseReturn->notes . "\n\n" : '') 
                              . "REJECTED: " . $validated['rejection_notes'],
                    'updated_at' => now(),
                ]);

            // Log rejection
            $this->logApproval(
                'Business Management - Purchase Returns',
                'purchase_returns',
                $purchaseReturn->pr_id,
                'rejected',
                $validated['rejection_notes']
            );

            DB::commit();

            return back()->with('success', 'Purchase return rejected.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to reject purchase return: ' . $e->getMessage());
        }
    }

    /**
     * Export purchase returns to CSV
     */
    public function export(Request $request)
    {
        $query = DB::table('purchase_returns as pr')
            ->join('suppliers', 'pr.supplier_id', '=', 'suppliers.supplier_id')
            ->leftJoin('purchase_receipts as receipt', 'pr.receipt_id', '=', 'receipt.receipt_id')
            ->select(
                'pr.pr_code',
                'pr.return_date',
                'suppliers.supplier_name',
                'receipt.receipt_code',
                'pr.return_reason',
                'pr.status',
                'pr.total_amount',
                'pr.notes'
            );

        // Apply same filters as index
        if ($request->filled('supplier_id')) {
            $query->where('pr.supplier_id', $request->supplier_id);
        }
        if ($request->filled('status')) {
            $query->where('pr.status', $request->status);
        }
        if ($request->filled('return_reason')) {
            $query->where('pr.return_reason', $request->return_reason);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('pr.return_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('pr.return_date', '<=', $request->date_to);
        }

        $returns = $query->orderBy('pr.return_date', 'desc')
            ->limit(5000)
            ->get();

        $filename = 'purchase_returns_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($returns) {
            $file = fopen('php://output', 'w');
            
            // Header
            fputcsv($file, [
                'Return Code',
                'Return Date',
                'Supplier',
                'Receipt Code',
                'Return Reason',
                'Status',
                'Total Amount',
                'Notes'
            ]);
            
            // Data
            foreach ($returns as $return) {
                fputcsv($file, [
                    $return->pr_code,
                    $return->return_date,
                    $return->supplier_name,
                    $return->receipt_code ?? '-',
                    $return->return_reason,
                    $return->status,
                    $return->total_amount,
                    $return->notes ?? '-',
                ]);
            }
            
            fclose($file);
        };

        // Log export
        $this->logExport(
            'Business Management - Purchase Returns',
            'Exported purchase returns to CSV'
        );

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Print purchase return
     */
    public function print($prCode)
    {
        $purchaseReturn = DB::table('purchase_returns as pr')
            ->join('suppliers', 'pr.supplier_id', '=', 'suppliers.supplier_id')
            ->leftJoin('purchase_receipts as receipt', 'pr.receipt_id', '=', 'receipt.receipt_id')
            ->where('pr.pr_code', $prCode)
            ->select('pr.*', 'suppliers.*', 'receipt.receipt_code')
            ->first();

        if (!$purchaseReturn) {
            abort(404, 'Purchase return not found');
        }

        // Get items
        $items = DB::table('purchase_return_items as pri')
            ->join('products', 'pri.product_id', '=', 'products.product_id')
            ->join('units_of_measure as uom', 'pri.uom_id', '=', 'uom.uom_id')
            ->leftJoin('lots', 'pri.lot_id', '=', 'lots.lot_id')
            ->where('pri.pr_id', $purchaseReturn->pr_id)
            ->select('pri.*', 'products.product_name', 'uom.uom_name', 'lots.lot_code')
            ->get();

        // Log print
        $this->logPrint(
            'Business Management - Purchase Returns',
            "Printed purchase return: {$prCode}"
        );

        return view('admin.purchase.returns.print', compact('purchaseReturn', 'items'));
    }
}
