<?php

namespace App\Http\Controllers\Bussiness;

use App\Helpers\CodeGeneratorHelper;
use App\Http\Controllers\Controller;
use App\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupplierController extends Controller
{
    //
    use LogsActivity;
    
    /**
     * Display suppliers with filtering
     */
    public function index(Request $request)
    {
        $query = DB::table('suppliers as s')
            ->leftJoin('purchase_orders as po', 's.supplier_id', '=', 'po.supplier_id')
            ->select(
                's.supplier_id',
                's.supplier_code',
                's.supplier_name',
                's.contact_person',
                's.email',
                's.phone',
                's.city',
                's.country',
                's.is_active',
                's.credit_limit',
                's.created_at',
                DB::raw('COUNT(DISTINCT po.po_id) as total_orders'),
                DB::raw('COALESCE(SUM(CASE WHEN po.status = "Pending" THEN 1 ELSE 0 END), 0) as pending_orders')
            )
            ->groupBy(
                's.supplier_id',
                's.supplier_code',
                's.supplier_name',
                's.contact_person',
                's.email',
                's.phone',
                's.city',
                's.country',
                's.is_active',
                's.credit_limit',
                's.created_at'
            );

        // Filter by status
        if ($request->filled('is_active')) {
            $query->where('s.is_active', $request->is_active);
        }

        // Filter by country
        if ($request->filled('country')) {
            $query->where('s.country', $request->country);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('s.supplier_name', 'like', "%{$search}%")
                  ->orWhere('s.supplier_code', 'like', "%{$search}%")
                  ->orWhere('s.contact_person', 'like', "%{$search}%")
                  ->orWhere('s.email', 'like', "%{$search}%");
            });
        }

        $suppliers = $query->orderBy('s.supplier_name')
            ->paginate(20);

        // Get filter options
        $countries = DB::table('suppliers')
            ->whereNotNull('country')
            ->select('country', DB::raw('COUNT(*) as count'))
            ->groupBy('country')
            ->orderBy('country')
            ->pluck('count', 'country');

        $stats = [
            'total' => DB::table('suppliers')->count(),
            'active' => DB::table('suppliers')->where('is_active', 1)->count(),
            'inactive' => DB::table('suppliers')->where('is_active', 0)->count(),
        ];

        return view('purchase.suppliers.index', compact('suppliers', 'countries', 'stats'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        return view('purchase.suppliers.create');
    }

    /**
     * Store new supplier
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_name' => ['required', 'string', 'max:200', 'unique:suppliers,supplier_name'],
            'contact_person' => ['nullable', 'string', 'max:150'],
            'email' => ['nullable', 'email', 'max:150'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
            'city' => ['nullable', 'string', 'max:100'],
            'country' => ['nullable', 'string', 'max:100'],
            'tax_id' => ['nullable', 'string', 'max:50'],
            'payment_terms' => ['nullable', 'string', 'max:100'],
            'credit_limit' => ['nullable', 'numeric', 'min:0'],
            'is_active' => ['boolean'],
        ], [
            'supplier_name.required' => 'Supplier name is required.',
            'supplier_name.max' => 'Supplier name is too long.',
            'supplier_name.unique' => 'This supplier name already exists.',
            'email.email' => 'Please enter a valid email address.',
            'credit_limit.numeric' => 'Credit limit must be a number.',
            'credit_limit.min' => 'Credit limit cannot be negative.',
        ]);

        DB::beginTransaction();
        try {
            // Generate supplier code
            $supplierCode = CodeGeneratorHelper::generateSupplierCode();

            // Insert supplier
            $supplierId = DB::table('suppliers')->insertGetId([
                'supplier_code' => $supplierCode,
                'supplier_name' => $validated['supplier_name'],
                'contact_person' => $validated['contact_person'] ?? null,
                'email' => $validated['email'] ?? null,
                'phone' => $validated['phone'] ?? null,
                'address' => $validated['address'] ?? null,
                'city' => $validated['city'] ?? null,
                'country' => $validated['country'] ?? null,
                'tax_id' => $validated['tax_id'] ?? null,
                'payment_terms' => $validated['payment_terms'] ?? null,
                'credit_limit' => $validated['credit_limit'] ?? null,
                'is_active' => $validated['is_active'] ?? 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Log CREATE
            $this->logCreate(
                'Purchase Management - Suppliers',
                'suppliers',
                $supplierId,
                [
                    'supplier_code' => $supplierCode,
                    'supplier_name' => $validated['supplier_name'],
                    'email' => $validated['email'] ?? null,
                    'phone' => $validated['phone'] ?? null,
                    'is_active' => $validated['is_active'] ?? 1,
                ]
            );

            DB::commit();

            return redirect()
                ->route('purchase.suppliers.index')
                ->with('success', 'Supplier created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Failed to create supplier: ' . $e->getMessage());
        }
    }

    /**
     * Show supplier details
     */
    public function show($supplierCode)
    {
        $supplier = DB::table('suppliers')
            ->where('supplier_code', $supplierCode)
            ->first();

        if (!$supplier) {
            abort(404, 'Supplier not found');
        }

        // Log VIEW
        $this->logView(
            'Purchase Management - Suppliers',
            "Viewed supplier: {$supplier->supplier_name} ({$supplierCode})"
        );

        // Get purchase orders
        $purchaseOrders = DB::table('purchase_orders as po')
            ->where('po.supplier_id', $supplier->supplier_id)
            ->select(
                'po.po_code',
                'po.order_date',
                'po.expected_delivery',
                'po.total_amount',
                'po.status'
            )
            ->orderByDesc('po.order_date')
            ->limit(10)
            ->get();

        // Get statistics
        $stats = [
            'total_orders' => DB::table('purchase_orders')
                ->where('supplier_id', $supplier->supplier_id)
                ->count(),
            'pending_orders' => DB::table('purchase_orders')
                ->where('supplier_id', $supplier->supplier_id)
                ->where('status', 'Pending')
                ->count(),
            'total_spent' => DB::table('purchase_orders')
                ->where('supplier_id', $supplier->supplier_id)
                ->whereIn('status', ['Approved', 'Completed'])
                ->sum('total_amount'),
        ];

        return view('purchase.suppliers.show', compact('supplier', 'purchaseOrders', 'stats'));
    }

    /**
     * Show edit form
     */
    public function edit($supplierCode)
    {
        $supplier = DB::table('suppliers')
            ->where('supplier_code', $supplierCode)
            ->first();

        if (!$supplier) {
            abort(404, 'Supplier not found');
        }

        return view('purchase.suppliers.edit', compact('supplier'));
    }

    /**
     * Update supplier
     */
    public function update(Request $request, $supplierCode)
    {
        $supplier = DB::table('suppliers')
            ->where('supplier_code', $supplierCode)
            ->first();

        if (!$supplier) {
            abort(404, 'Supplier not found');
        }

        $validated = $request->validate([
            'supplier_name' => ['required', 'string', 'max:200', 'unique:suppliers,supplier_name,' . $supplier->supplier_id . ',supplier_id'],
            'contact_person' => ['nullable', 'string', 'max:150'],
            'email' => ['nullable', 'email', 'max:150'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
            'city' => ['nullable', 'string', 'max:100'],
            'country' => ['nullable', 'string', 'max:100'],
            'tax_id' => ['nullable', 'string', 'max:50'],
            'payment_terms' => ['nullable', 'string', 'max:100'],
            'credit_limit' => ['nullable', 'numeric', 'min:0'],
            'is_active' => ['boolean'],
        ], [
            'supplier_name.required' => 'Supplier name is required.',
            'supplier_name.max' => 'Supplier name is too long.',
            'supplier_name.unique' => 'This supplier name already exists.',
            'email.email' => 'Please enter a valid email address.',
            'credit_limit.numeric' => 'Credit limit must be a number.',
            'credit_limit.min' => 'Credit limit cannot be negative.',
        ]);

        DB::beginTransaction();
        try {
            // Capture old data
            $oldData = [
                'supplier_name' => $supplier->supplier_name,
                'contact_person' => $supplier->contact_person,
                'email' => $supplier->email,
                'phone' => $supplier->phone,
                'credit_limit' => $supplier->credit_limit,
                'is_active' => $supplier->is_active,
            ];

            // Update supplier
            DB::table('suppliers')
                ->where('supplier_id', $supplier->supplier_id)
                ->update([
                    'supplier_name' => $validated['supplier_name'],
                    'contact_person' => $validated['contact_person'] ?? null,
                    'email' => $validated['email'] ?? null,
                    'phone' => $validated['phone'] ?? null,
                    'address' => $validated['address'] ?? null,
                    'city' => $validated['city'] ?? null,
                    'country' => $validated['country'] ?? null,
                    'tax_id' => $validated['tax_id'] ?? null,
                    'payment_terms' => $validated['payment_terms'] ?? null,
                    'credit_limit' => $validated['credit_limit'] ?? null,
                    'is_active' => $validated['is_active'] ?? 1,
                    'updated_at' => now(),
                ]);

            // Log UPDATE
            $this->logUpdate(
                'Purchase Management - Suppliers',
                'suppliers',
                $supplier->supplier_id,
                $oldData,
                [
                    'supplier_name' => $validated['supplier_name'],
                    'contact_person' => $validated['contact_person'] ?? null,
                    'email' => $validated['email'] ?? null,
                    'phone' => $validated['phone'] ?? null,
                    'credit_limit' => $validated['credit_limit'] ?? null,
                    'is_active' => $validated['is_active'] ?? 1,
                ]
            );

            DB::commit();

            return redirect()
                ->route('purchase.suppliers.index')
                ->with('success', 'Supplier updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Failed to update supplier: ' . $e->getMessage());
        }
    }

    /**
     * Delete supplier
     */
    public function destroy($supplierCode)
    {
        $supplier = DB::table('suppliers')
            ->where('supplier_code', $supplierCode)
            ->first();

        if (!$supplier) {
            abort(404, 'Supplier not found');
        }

        // Check if supplier has purchase orders
        $hasOrders = DB::table('purchase_orders')
            ->where('supplier_id', $supplier->supplier_id)
            ->exists();

        if ($hasOrders) {
            return back()->with('error', 'Cannot delete supplier with existing purchase orders. Please deactivate instead.');
        }

        DB::beginTransaction();
        try {
            // Capture old data
            $oldData = [
                'supplier_code' => $supplier->supplier_code,
                'supplier_name' => $supplier->supplier_name,
                'email' => $supplier->email,
                'phone' => $supplier->phone,
            ];

            // Delete supplier
            DB::table('suppliers')
                ->where('supplier_id', $supplier->supplier_id)
                ->delete();

            // Log DELETE
            $this->logDelete(
                'Purchase Management - Suppliers',
                'suppliers',
                $supplier->supplier_id,
                $oldData
            );

            DB::commit();

            return redirect()
                ->route('purchase.suppliers.index')
                ->with('success', 'Supplier deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete supplier: ' . $e->getMessage());
        }
    }

    /**
     * Toggle supplier status
     */
    public function toggleStatus($supplierCode)
    {
        $supplier = DB::table('suppliers')
            ->where('supplier_code', $supplierCode)
            ->first();

        if (!$supplier) {
            abort(404, 'Supplier not found');
        }

        DB::beginTransaction();
        try {
            $newStatus = !$supplier->is_active;

            DB::table('suppliers')
                ->where('supplier_id', $supplier->supplier_id)
                ->update([
                    'is_active' => $newStatus,
                    'updated_at' => now(),
                ]);

            // Log activity
            $this->logActivity(
                'Status Changed',
                "Changed supplier {$supplier->supplier_name} status to " . ($newStatus ? 'Active' : 'Inactive'),
                'Purchase Management - Suppliers'
            );

            DB::commit();

            $message = $newStatus ? 'Supplier activated successfully.' : 'Supplier deactivated successfully.';

            return back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to toggle supplier status: ' . $e->getMessage());
        }
    }

    /**
     * Export suppliers to CSV
     */
    public function export(Request $request)
    {
        $this->logExport('Purchase Management - Suppliers', 'Exported suppliers to CSV');

        $query = DB::table('suppliers')
            ->select(
                'supplier_code',
                'supplier_name',
                'contact_person',
                'email',
                'phone',
                'address',
                'city',
                'country',
                'tax_id',
                'payment_terms',
                'credit_limit',
                'is_active',
                'created_at'
            );

        // Apply filters
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }
        if ($request->filled('country')) {
            $query->where('country', $request->country);
        }

        $suppliers = $query->orderBy('supplier_name')
            ->limit(5000)
            ->get();

        $filename = 'suppliers_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($suppliers) {
            $file = fopen('php://output', 'w');

            // Header
            fputcsv($file, [
                'Supplier Code',
                'Supplier Name',
                'Contact Person',
                'Email',
                'Phone',
                'Address',
                'City',
                'Country',
                'Tax ID',
                'Payment Terms',
                'Credit Limit',
                'Status',
                'Created At'
            ]);

            // Data
            foreach ($suppliers as $supplier) {
                fputcsv($file, [
                    $supplier->supplier_code,
                    $supplier->supplier_name,
                    $supplier->contact_person ?? '-',
                    $supplier->email ?? '-',
                    $supplier->phone ?? '-',
                    $supplier->address ?? '-',
                    $supplier->city ?? '-',
                    $supplier->country ?? '-',
                    $supplier->tax_id ?? '-',
                    $supplier->payment_terms ?? '-',
                    $supplier->credit_limit ?? '0',
                    $supplier->is_active ? 'Active' : 'Inactive',
                    $supplier->created_at,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
