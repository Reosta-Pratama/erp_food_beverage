<?php

namespace App\Http\Controllers\Bussiness;

use App\Helpers\CodeGeneratorHelper;
use App\Http\Controllers\Controller;
use App\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseReceiptController extends Controller
{
    //
    use LogsActivity;

    /**
     * List purchase receipts 
     */
    public function index(Request $request)
    {
        $this->logView('Purchase Management - Purchase Receipts', 'Viewed purchase receipts list');

        $query = DB::table('purchase_receipts as pr')
            ->join('purchase_orders as po', 'pr.po_id', '=', 'po.po_id')
            ->join('suppliers as s', 'pr.supplier_id', '=', 's.supplier_id')
            ->join('warehouses as w', 'pr.warehouse_id', '=', 'w.warehouse_id')
            ->join('employees as e', 'pr.received_by', '=', 'e.employee_id')
            ->select(
                'pr.receipt_id',
                'pr.receipt_code',
                'pr.receipt_date',
                'pr.status',
                'pr.notes',
                'pr.created_at',
                'po.po_code',
                'po.order_date',
                's.supplier_code',
                's.supplier_name',
                'w.warehouse_code',
                'w.warehouse_name',
                DB::raw("CONCAT(e.first_name, ' ', e.last_name) as received_by_name")
            );

        // Filter by status
        if ($request->filled('status')) {
            $query->where('pr.status', $request->status);
        }

        // Filter by supplier
        if ($request->filled('supplier_id')) {
            $query->where('pr.supplier_id', $request->supplier_id);
        }

        // Filter by warehouse
        if ($request->filled('warehouse_id')) {
            $query->where('pr.warehouse_id', $request->warehouse_id);
        }

        // Filter by PO
        if ($request->filled('po_id')) {
            $query->where('pr.po_id', $request->po_id);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('pr.receipt_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('pr.receipt_date', '<=', $request->date_to);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('pr.receipt_code', 'like', "%{$search}%")
                  ->orWhere('po.po_code', 'like', "%{$search}%")
                  ->orWhere('s.supplier_name', 'like', "%{$search}%");
            });
        }

        $receipts = $query->orderByDesc('pr.receipt_date')
            ->orderByDesc('pr.created_at')
            ->paginate(20);

        // Get filter options
        $statuses = DB::table('purchase_receipts')
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');

        $suppliers = DB::table('suppliers')
            ->where('is_active', 1)
            ->orderBy('supplier_name')
            ->get(['supplier_id', 'supplier_code', 'supplier_name']);

        $warehouses = DB::table('warehouses')
            ->where('is_active', 1)
            ->orderBy('warehouse_name')
            ->get(['warehouse_id', 'warehouse_code', 'warehouse_name']);

        $purchaseOrders = DB::table('purchase_orders')
            ->whereIn('status', ['Approved', 'Partial'])
            ->orderByDesc('order_date')
            ->limit(100)
            ->get(['po_id', 'po_code']);

        $stats = [
            'total' => DB::table('purchase_receipts')->count(),
            'completed' => DB::table('purchase_receipts')->where('status', 'Completed')->count(),
            'pending' => DB::table('purchase_receipts')->where('status', 'Pending')->count(),
        ];

        return view('purchase.receipts.index', compact(
            'receipts',
            'statuses',
            'suppliers',
            'warehouses',
            'purchaseOrders',
            'stats'
        ));
    }

    /**
     * Show create form
     */
    public function create()
    {
        // Get approved purchase orders with pending items
        $purchaseOrders = DB::table('purchase_orders as po')
            ->join('suppliers as s', 'po.supplier_id', '=', 's.supplier_id')
            ->whereIn('po.status', ['Approved', 'Partial'])
            ->orderByDesc('po.order_date')
            ->get([
                'po.po_id',
                'po.po_code',
                'po.order_date',
                'po.expected_delivery',
                'po.supplier_id',
                's.supplier_name'
            ]);

        // Get active warehouses
        $warehouses = DB::table('warehouses')
            ->where('is_active', 1)
            ->orderBy('warehouse_name')
            ->get(['warehouse_id', 'warehouse_code', 'warehouse_name']);

        // Get active employees for "received by"
        $employees = DB::table('employees')
            ->where('employment_status', 'Active')
            ->orderBy('first_name')
            ->get(['employee_id', 'employee_code', 'first_name', 'last_name']);

        return view('purchase.receipts.create', compact('purchaseOrders', 'warehouses', 'employees'));
    }

    /**
     * Store new purchase receipt
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'po_id' => ['required', 'exists:purchase_orders,po_id'],
            'receipt_date' => ['required', 'date'],
            'warehouse_id' => ['required', 'exists:warehouses,warehouse_id'],
            'received_by' => ['required', 'exists:employees,employee_id'],
            'notes' => ['nullable', 'string'],
            
            // Receipt items
            'items' => ['required', 'array', 'min:1'],
            'items.*.po_item_id' => ['required', 'exists:purchase_order_items,po_item_id'],
            'items.*.product_id' => ['required', 'exists:products,product_id'],
            'items.*.quantity_received' => ['required', 'numeric', 'min:0.0001'],
            'items.*.uom_id' => ['required', 'exists:units_of_measure,uom_id'],
            'items.*.manufacture_date' => ['nullable', 'date'],
            'items.*.expiry_date' => ['nullable', 'date', 'after:manufacture_date'],
            'items.*.condition' => ['required', 'string', 'in:Good,Damaged,Partial'],
            'items.*.notes' => ['nullable', 'string'],
        ], [
            'po_id.required' => 'Please select a purchase order.',
            'po_id.exists' => 'Selected purchase order is invalid.',
            
            'receipt_date.required' => 'Receipt date is required.',
            'receipt_date.date' => 'Invalid receipt date format.',
            
            'warehouse_id.required' => 'Please select a warehouse.',
            'warehouse_id.exists' => 'Selected warehouse is invalid.',
            
            'received_by.required' => 'Please select who received the items.',
            'received_by.exists' => 'Selected employee is invalid.',
            
            'items.required' => 'Please add at least one item.',
            'items.min' => 'Please add at least one item.',
            
            'items.*.po_item_id.required' => 'PO item is required.',
            'items.*.product_id.required' => 'Product is required.',
            'items.*.quantity_received.required' => 'Quantity received is required.',
            'items.*.quantity_received.min' => 'Quantity must be greater than zero.',
            'items.*.condition.required' => 'Item condition is required.',
        ]);

        DB::beginTransaction();
        try {
            // Get PO details
            $po = DB::table('purchase_orders')->where('po_id', $validated['po_id'])->first();

            // Generate receipt code
            $receiptCode = CodeGeneratorHelper::generateReceiptCode();

            // Insert purchase receipt
            $receiptId = DB::table('purchase_receipts')->insertGetId([
                'receipt_code' => $receiptCode,
                'po_id' => $validated['po_id'],
                'supplier_id' => $po->supplier_id,
                'receipt_date' => $validated['receipt_date'],
                'warehouse_id' => $validated['warehouse_id'],
                'received_by' => $validated['received_by'],
                'status' => 'Completed',
                'notes' => $validated['notes'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Process receipt items
            foreach ($validated['items'] as $item) {
                // Create or get lot
                $lotId = null;
                
                if (!empty($item['manufacture_date']) || !empty($item['expiry_date'])) {
                    $lotCode = CodeGeneratorHelper::generateLotCode();
                    
                    $lotId = DB::table('lots')->insertGetId([
                        'lot_code' => $lotCode,
                        'product_id' => $item['product_id'],
                        'manufacture_date' => $item['manufacture_date'] ?? now()->format('Y-m-d'),
                        'expiry_date' => $item['expiry_date'] ?? null,
                        'quantity' => $item['quantity_received'],
                        'status' => 'Active',
                        'supplier_id' => $po->supplier_id,
                        'notes' => "From PO: {$po->po_code}",
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    // Create expiry tracking if expiry date exists
                    if (!empty($item['expiry_date'])) {
                        $alertDate = \Carbon\Carbon::parse($item['expiry_date'])->subDays(30);
                        
                        DB::table('expiry_tracking')->insert([
                            'product_id' => $item['product_id'],
                            'lot_id' => $lotId,
                            'warehouse_id' => $validated['warehouse_id'],
                            'expiry_date' => $item['expiry_date'],
                            'quantity' => $item['quantity_received'],
                            'status' => 'Active',
                            'alert_date' => $alertDate,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }

                // Insert receipt item
                DB::table('purchase_receipt_items')->insert([
                    'receipt_id' => $receiptId,
                    'po_item_id' => $item['po_item_id'],
                    'product_id' => $item['product_id'],
                    'lot_id' => $lotId,
                    'quantity_received' => $item['quantity_received'],
                    'uom_id' => $item['uom_id'],
                    'manufacture_date' => $item['manufacture_date'] ?? null,
                    'expiry_date' => $item['expiry_date'] ?? null,
                    'condition' => $item['condition'],
                    'notes' => $item['notes'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Update PO item quantity received
                DB::table('purchase_order_items')
                    ->where('po_item_id', $item['po_item_id'])
                    ->increment('quantity_received', $item['quantity_received']);

                // Update inventory
                $inventory = DB::table('inventory')
                    ->where('product_id', $item['product_id'])
                    ->where('warehouse_id', $validated['warehouse_id'])
                    ->where('lot_id', $lotId)
                    ->first();

                if ($inventory) {
                    DB::table('inventory')
                        ->where('inventory_id', $inventory->inventory_id)
                        ->update([
                            'quantity_on_hand' => DB::raw("quantity_on_hand + {$item['quantity_received']}"),
                            'quantity_available' => DB::raw("quantity_available + {$item['quantity_received']}"),
                            'last_updated' => now(),
                        ]);
                } else {
                    DB::table('inventory')->insert([
                        'product_id' => $item['product_id'],
                        'warehouse_id' => $validated['warehouse_id'],
                        'location_id' => null,
                        'lot_id' => $lotId,
                        'quantity_on_hand' => $item['quantity_received'],
                        'quantity_reserved' => 0,
                        'quantity_available' => $item['quantity_received'],
                        'reorder_point' => null,
                        'reorder_quantity' => null,
                        'last_updated' => now(),
                    ]);
                }

                // Create stock movement
                $movementCode = CodeGeneratorHelper::generateMovementCode();
                
                DB::table('stock_movements')->insert([
                    'movement_code' => $movementCode,
                    'movement_type' => 'Purchase Receipt',
                    'product_id' => $item['product_id'],
                    'from_warehouse_id' => null,
                    'to_warehouse_id' => $validated['warehouse_id'],
                    'from_location_id' => null,
                    'to_location_id' => null,
                    'lot_id' => $lotId,
                    'quantity' => $item['quantity_received'],
                    'uom_id' => $item['uom_id'],
                    'movement_date' => $validated['receipt_date'],
                    'performed_by' => $validated['received_by'],
                    'reference_type' => 'Purchase Receipt',
                    'reference_id' => $receiptId,
                    'notes' => "Receipt from PO: {$po->po_code}",
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Check if PO is fully received
            $poItems = DB::table('purchase_order_items')
                ->where('po_id', $validated['po_id'])
                ->get();

            $fullyReceived = true;
            foreach ($poItems as $poItem) {
                if ($poItem->quantity_received < $poItem->quantity_ordered) {
                    $fullyReceived = false;
                    break;
                }
            }

            // Update PO status
            $newPoStatus = $fullyReceived ? 'Completed' : 'Partial';
            DB::table('purchase_orders')
                ->where('po_id', $validated['po_id'])
                ->update([
                    'status' => $newPoStatus,
                    'actual_delivery' => $validated['receipt_date'],
                    'updated_at' => now(),
                ]);

            // Log CREATE
            $this->logCreate(
                'Purchase Management - Purchase Receipts',
                'purchase_receipts',
                $receiptId,
                [
                    'receipt_code' => $receiptCode,
                    'po_id' => $validated['po_id'],
                    'receipt_date' => $validated['receipt_date'],
                    'warehouse_id' => $validated['warehouse_id'],
                    'received_by' => $validated['received_by'],
                    'items_count' => count($validated['items']),
                ]
            );

            DB::commit();

            return redirect()
                ->route('purchase.receipts.show', $receiptCode)
                ->with('success', 'Purchase receipt created successfully. Inventory has been updated.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Failed to create purchase receipt: ' . $e->getMessage());
        }
    }

    /**
     * Show purchase receipt details
     */
    public function show($receiptCode)
    {
        $receipt = DB::table('purchase_receipts as pr')
            ->join('purchase_orders as po', 'pr.po_id', '=', 'po.po_id')
            ->join('suppliers as s', 'pr.supplier_id', '=', 's.supplier_id')
            ->join('warehouses as w', 'pr.warehouse_id', '=', 'w.warehouse_id')
            ->join('employees as e', 'pr.received_by', '=', 'e.employee_id')
            ->where('pr.receipt_code', $receiptCode)
            ->select(
                'pr.*',
                'po.po_code',
                'po.order_date',
                'po.expected_delivery',
                's.supplier_code',
                's.supplier_name',
                's.contact_person',
                's.email as supplier_email',
                's.phone as supplier_phone',
                'w.warehouse_code',
                'w.warehouse_name',
                'e.employee_code',
                DB::raw("CONCAT(e.first_name, ' ', e.last_name) as received_by_name")
            )
            ->first();

        if (!$receipt) {
            abort(404, 'Purchase receipt not found');
        }

        // Log VIEW
        $this->logView(
            'Purchase Management - Purchase Receipts',
            "Viewed purchase receipt: {$receipt->receipt_code}"
        );

        // Get receipt items
        $items = DB::table('purchase_receipt_items as pri')
            ->join('products as p', 'pri.product_id', '=', 'p.product_id')
            ->join('units_of_measure as uom', 'pri.uom_id', '=', 'uom.uom_id')
            ->leftJoin('lots as l', 'pri.lot_id', '=', 'l.lot_id')
            ->where('pri.receipt_id', $receipt->receipt_id)
            ->select(
                'pri.*',
                'p.product_code',
                'p.product_name',
                'p.product_type',
                'uom.uom_name',
                'uom.uom_code',
                'l.lot_code'
            )
            ->get();

        return view('purchase.receipts.show', compact('receipt', 'items'));
    }

    /**
     * Show edit form
     */
    public function edit($receiptCode)
    {
        $receipt = DB::table('purchase_receipts')
            ->where('receipt_code', $receiptCode)
            ->first();

        if (!$receipt) {
            abort(404, 'Purchase receipt not found');
        }

        // Get warehouses
        $warehouses = DB::table('warehouses')
            ->where('is_active', 1)
            ->orderBy('warehouse_name')
            ->get(['warehouse_id', 'warehouse_code', 'warehouse_name']);

        // Get employees
        $employees = DB::table('employees')
            ->where('employment_status', 'Active')
            ->orderBy('first_name')
            ->get(['employee_id', 'employee_code', 'first_name', 'last_name']);

        // Get PO details
        $po = DB::table('purchase_orders')
            ->where('po_id', $receipt->po_id)
            ->first();

        // Get receipt items
        $items = DB::table('purchase_receipt_items as pri')
            ->join('products as p', 'pri.product_id', '=', 'p.product_id')
            ->join('units_of_measure as uom', 'pri.uom_id', '=', 'uom.uom_id')
            ->leftJoin('lots as l', 'pri.lot_id', '=', 'l.lot_id')
            ->where('pri.receipt_id', $receipt->receipt_id)
            ->select('pri.*', 'p.product_code', 'p.product_name', 'uom.uom_name', 'l.lot_code')
            ->get();

        return view('purchase.receipts.edit', compact('receipt', 'warehouses', 'employees', 'po', 'items'));
    }

    /**
     * Update purchase receipt
     */
    public function update(Request $request, $receiptCode)
    {
        $receipt = DB::table('purchase_receipts')
            ->where('receipt_code', $receiptCode)
            ->first();

        if (!$receipt) {
            abort(404, 'Purchase receipt not found');
        }

        $validated = $request->validate([
            'receipt_date' => ['required', 'date'],
            'warehouse_id' => ['required', 'exists:warehouses,warehouse_id'],
            'received_by' => ['required', 'exists:employees,employee_id'],
            'notes' => ['nullable', 'string'],
        ], [
            'receipt_date.required' => 'Receipt date is required.',
            'warehouse_id.required' => 'Please select a warehouse.',
            'received_by.required' => 'Please select who received the items.',
        ]);

        DB::beginTransaction();
        try {
            // Capture old data
            $oldData = [
                'receipt_date' => $receipt->receipt_date,
                'warehouse_id' => $receipt->warehouse_id,
                'received_by' => $receipt->received_by,
            ];

            // Update receipt
            DB::table('purchase_receipts')
                ->where('receipt_id', $receipt->receipt_id)
                ->update([
                    'receipt_date' => $validated['receipt_date'],
                    'warehouse_id' => $validated['warehouse_id'],
                    'received_by' => $validated['received_by'],
                    'notes' => $validated['notes'] ?? null,
                    'updated_at' => now(),
                ]);

            // Log UPDATE
            $this->logUpdate(
                'Purchase Management - Purchase Receipts',
                'purchase_receipts',
                $receipt->receipt_id,
                $oldData,
                [
                    'receipt_date' => $validated['receipt_date'],
                    'warehouse_id' => $validated['warehouse_id'],
                    'received_by' => $validated['received_by'],
                ]
            );

            DB::commit();

            return redirect()
                ->route('purchase.receipts.show', $receiptCode)
                ->with('success', 'Purchase receipt updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Failed to update purchase receipt: ' . $e->getMessage());
        }
    }

    /**
     * Delete purchase receipt
     */
    public function destroy($receiptCode)
    {
        $receipt = DB::table('purchase_receipts')
            ->where('receipt_code', $receiptCode)
            ->first();

        if (!$receipt) {
            abort(404, 'Purchase receipt not found');
        }

        DB::beginTransaction();
        try {
            // Get receipt items to reverse inventory
            $items = DB::table('purchase_receipt_items')
                ->where('receipt_id', $receipt->receipt_id)
                ->get();

            foreach ($items as $item) {
                // Reverse inventory
                DB::table('inventory')
                    ->where('product_id', $item->product_id)
                    ->where('warehouse_id', $receipt->warehouse_id)
                    ->where('lot_id', $item->lot_id)
                    ->update([
                        'quantity_on_hand' => DB::raw("quantity_on_hand - {$item->quantity_received}"),
                        'quantity_available' => DB::raw("quantity_available - {$item->quantity_received}"),
                        'last_updated' => now(),
                    ]);

                // Reverse PO item quantity
                DB::table('purchase_order_items')
                    ->where('po_item_id', $item->po_item_id)
                    ->decrement('quantity_received', $item->quantity_received);

                // Delete expiry tracking
                if ($item->lot_id) {
                    DB::table('expiry_tracking')
                        ->where('lot_id', $item->lot_id)
                        ->delete();

                    // Delete lot
                    DB::table('lots')
                        ->where('lot_id', $item->lot_id)
                        ->delete();
                }
            }

            // Delete stock movements
            DB::table('stock_movements')
                ->where('reference_type', 'Purchase Receipt')
                ->where('reference_id', $receipt->receipt_id)
                ->delete();

            // Capture old data
            $itemsCount = count($items);
            $oldData = [
                'receipt_code' => $receipt->receipt_code,
                'po_id' => $receipt->po_id,
                'receipt_date' => $receipt->receipt_date,
                'warehouse_id' => $receipt->warehouse_id,
                'items_count' => $itemsCount,
            ];

            // Delete receipt items
            DB::table('purchase_receipt_items')
                ->where('receipt_id', $receipt->receipt_id)
                ->delete();

            // Delete receipt
            DB::table('purchase_receipts')
                ->where('receipt_id', $receipt->receipt_id)
                ->delete();

            // Update PO status back to Approved
            DB::table('purchase_orders')
                ->where('po_id', $receipt->po_id)
                ->update([
                    'status' => 'Approved',
                    'actual_delivery' => null,
                    'updated_at' => now(),
                ]);

            // Log DELETE
            $this->logDelete(
                'Purchase Management - Purchase Receipts',
                'purchase_receipts',
                $receipt->receipt_id,
                $oldData
            );

            DB::commit();

            return redirect()
                ->route('purchase.receipts.index')
                ->with('success', 'Purchase receipt deleted successfully. Inventory has been reversed.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete purchase receipt: ' . $e->getMessage());
        }
    }

    /**
     * Export purchase receipts to CSV
     */
    public function export(Request $request)
    {
        $this->logExport('Purchase Management - Purchase Receipts', 'Exported purchase receipts to CSV');

        $query = DB::table('purchase_receipts as pr')
            ->join('purchase_orders as po', 'pr.po_id', '=', 'po.po_id')
            ->join('suppliers as s', 'pr.supplier_id', '=', 's.supplier_id')
            ->join('warehouses as w', 'pr.warehouse_id', '=', 'w.warehouse_id')
            ->join('employees as e', 'pr.received_by', '=', 'e.employee_id')
            ->select(
                'pr.receipt_code',
                'pr.receipt_date',
                'po.po_code',
                's.supplier_name',
                'w.warehouse_name',
                DB::raw("CONCAT(e.first_name, ' ', e.last_name) as received_by_name"),
                'pr.status',
                'pr.notes',
                'pr.created_at'
            );

        // Apply filters
        if ($request->filled('status')) {
            $query->where('pr.status', $request->status);
        }
        if ($request->filled('supplier_id')) {
            $query->where('pr.supplier_id', $request->supplier_id);
        }
        if ($request->filled('warehouse_id')) {
            $query->where('pr.warehouse_id', $request->warehouse_id);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('pr.receipt_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('pr.receipt_date', '<=', $request->date_to);
        }

        $receipts = $query->orderByDesc('pr.receipt_date')
            ->limit(5000)
            ->get();

        $filename = 'purchase_receipts_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($receipts) {
            $file = fopen('php://output', 'w');

            // Header
            fputcsv($file, [
                'Receipt Code',
                'Receipt Date',
                'PO Code',
                'Supplier',
                'Warehouse',
                'Received By',
                'Status',
                'Notes',
                'Created At'
            ]);

            // Data
            foreach ($receipts as $receipt) {
                fputcsv($file, [
                    $receipt->receipt_code,
                    $receipt->receipt_date,
                    $receipt->po_code,
                    $receipt->supplier_name,
                    $receipt->warehouse_name,
                    $receipt->received_by_name,
                    $receipt->status,
                    $receipt->notes ?? '-',
                    $receipt->created_at,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Print purchase receipt
     */
    public function print($receiptCode)
    {
        $receipt = DB::table('purchase_receipts as pr')
            ->join('purchase_orders as po', 'pr.po_id', '=', 'po.po_id')
            ->join('suppliers as s', 'pr.supplier_id', '=', 's.supplier_id')
            ->join('warehouses as w', 'pr.warehouse_id', '=', 'w.warehouse_id')
            ->join('employees as e', 'pr.received_by', '=', 'e.employee_id')
            ->where('pr.receipt_code', $receiptCode)
            ->select(
                'pr.*',
                'po.po_code',
                's.supplier_name',
                's.address as supplier_address',
                'w.warehouse_name',
                'w.address as warehouse_address',
                DB::raw("CONCAT(e.first_name, ' ', e.last_name) as received_by_name")
            )
            ->first();

        if (!$receipt) {
            abort(404, 'Purchase receipt not found');
        }

        // Get items
        $items = DB::table('purchase_receipt_items as pri')
            ->join('products as p', 'pri.product_id', '=', 'p.product_id')
            ->join('units_of_measure as uom', 'pri.uom_id', '=', 'uom.uom_id')
            ->leftJoin('lots as l', 'pri.lot_id', '=', 'l.lot_id')
            ->where('pri.receipt_id', $receipt->receipt_id)
            ->select(
                'pri.*',
                'p.product_code',
                'p.product_name',
                'uom.uom_name',
                'l.lot_code')
            ->get();
            
        // Get company profile
        $company = DB::table('company_profile')->first();

        // Log PRINT
        $this->logPrint('Purchase Management - Purchase Receipts', "Printed purchase receipt: {$receipt->receipt_code}");

        return view('purchase.receipts.print', compact('receipt', 'items', 'company'));
    }
}
