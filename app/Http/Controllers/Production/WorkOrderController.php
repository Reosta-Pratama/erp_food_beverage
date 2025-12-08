<?php

namespace App\Http\Controllers\Production;

use App\Helpers\CodeGeneratorHelper;
use App\Http\Controllers\Controller;
use App\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WorkOrderController extends Controller
{
    //
    use LogsActivity;
    
    /**
     * Display a listing of work orders
     */
    public function index(Request $request)
    {
        $query = DB::table('work_orders as wo')
            ->join('products as p', 'wo.product_id', '=', 'p.product_id')
            ->leftJoin('bill_of_materials as bom', 'wo.bom_id', '=', 'bom.bom_id')
            ->leftJoin('employees as e', 'wo.assigned_to', '=', 'e.employee_id')
            ->select(
                'wo.work_order_id',
                'wo.work_order_code',
                'wo.quantity_ordered',
                'wo.quantity_produced',
                'wo.scheduled_start',
                'wo.scheduled_end',
                'wo.actual_start',
                'wo.actual_end',
                'wo.status',
                'wo.created_at',
                'p.product_code',
                'p.product_name',
                'p.product_type',
                'bom.bom_code',
                DB::raw("CONCAT(e.first_name, ' ', e.last_name) as assigned_to_name"),
                DB::raw('(wo.quantity_ordered - wo.quantity_produced) as remaining_quantity'),
                DB::raw('CASE 
                    WHEN wo.quantity_ordered > 0 
                    THEN ROUND((wo.quantity_produced / wo.quantity_ordered) * 100, 2)
                    ELSE 0 
                END as completion_percentage')
            );
        
        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('wo.work_order_code', 'like', "%{$search}%")
                  ->orWhere('p.product_name', 'like', "%{$search}%")
                  ->orWhere('p.product_code', 'like', "%{$search}%");
            });
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('wo.status', $request->status);
        }
        
        // Filter by product
        if ($request->filled('product_id')) {
            $query->where('wo.product_id', $request->product_id);
        }
        
        // Filter by date range
        if ($request->filled('start_date')) {
            $query->where('wo.scheduled_start', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->where('wo.scheduled_end', '<=', $request->end_date);
        }
        
        $workOrders = $query->orderByDesc('wo.created_at')
            ->paginate(20);
        
        // Get products for filter
        $products = DB::table('products')
            ->where('product_type', 'Finished Goods')
            ->where('is_active', 1)
            ->select('product_id', 'product_code', 'product_name')
            ->orderBy('product_name')
            ->get();
        
        return view('production.work-orders.index', compact('workOrders', 'products'));
    }

    /**
     * Show the form for creating a new work order
     */
    public function create()
    {
        // Get finished goods with active BOM
        $products = DB::table('products as p')
            ->join('bill_of_materials as bom', function($join) {
                $join->on('p.product_id', '=', 'bom.product_id')
                     ->where('bom.is_active', 1);
            })
            ->join('units_of_measure as uom', 'p.uom_id', '=', 'uom.uom_id')
            ->where('p.product_type', 'Finished Goods')
            ->where('p.is_active', 1)
            ->select(
                'p.product_id',
                'p.product_code',
                'p.product_name',
                'p.standard_cost',
                'uom.uom_code',
                'uom.uom_name',
                'bom.bom_id',
                'bom.bom_code',
                'bom.bom_version'
            )
            ->orderBy('p.product_name')
            ->get();
        
        // Get employees for assignment
        $employees = DB::table('employees')
            ->where('employment_status', 'Active')
            ->select(
                'employee_id',
                'employee_code',
                DB::raw("CONCAT(first_name, ' ', last_name) as full_name")
            )
            ->orderBy('first_name')
            ->get();
        
        return view('production.work-orders.create', compact('products', 'employees'));
    }

    /**
     * Store a newly created work order
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,product_id'],
            'bom_id' => ['nullable', 'exists:bill_of_materials,bom_id'],
            'quantity_ordered' => ['required', 'numeric', 'min:0.0001'],
            'scheduled_start' => ['required', 'date'],
            'scheduled_end' => ['required', 'date', 'after_or_equal:scheduled_start'],
            'assigned_to' => ['nullable', 'exists:employees,employee_id'],
            'instructions' => ['nullable', 'string'],
        ], [
            'product_id.required' => 'Please select a product.',
            'product_id.exists' => 'Selected product is invalid.',
            
            'bom_id.exists' => 'Selected BOM is invalid.',
            
            'quantity_ordered.required' => 'Quantity ordered is required.',
            'quantity_ordered.numeric' => 'Quantity must be a number.',
            'quantity_ordered.min' => 'Quantity must be greater than 0.',
            
            'scheduled_start.required' => 'Scheduled start date is required.',
            'scheduled_start.date' => 'Please provide a valid start date.',
            
            'scheduled_end.required' => 'Scheduled end date is required.',
            'scheduled_end.after_or_equal' => 'End date must be on or after start date.',
            
            'assigned_to.exists' => 'Selected employee is invalid.',
        ]);
        
        DB::beginTransaction();
        try {
            // Generate unique work order code
            $woCode = CodeGeneratorHelper::generateWorkOrderCode();
            
            // Get product info
            $product = DB::table('products')->where('product_id', $validated['product_id'])->first();
            
            // Get BOM info if provided
            $bom = null;
            if ($validated['bom_id']) {
                $bom = DB::table('bill_of_materials')
                    ->where('bom_id', $validated['bom_id'])
                    ->first();
            }
            
            // Insert Work Order
            $workOrderId = DB::table('work_orders')->insertGetId([
                'work_order_code' => $woCode,
                'product_id' => $validated['product_id'],
                'bom_id' => $validated['bom_id'] ?? null,
                'quantity_ordered' => $validated['quantity_ordered'],
                'quantity_produced' => 0,
                'scheduled_start' => $validated['scheduled_start'],
                'scheduled_end' => $validated['scheduled_end'],
                'actual_start' => null,
                'actual_end' => null,
                'status' => 'Pending',
                'assigned_to' => $validated['assigned_to'] ?? null,
                'instructions' => $validated['instructions'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            // If BOM exists, create work order materials from BOM items
            if ($validated['bom_id']) {
                $bomItems = DB::table('bom_items')
                    ->where('bom_id', $validated['bom_id'])
                    ->get();
                
                $woMaterialsData = [];
                foreach ($bomItems as $item) {
                    // Calculate required quantity based on ordered quantity
                    $requiredQty = $item->quantity_required * $validated['quantity_ordered'];
                    
                    // Add scrap percentage
                    if ($item->scrap_percentage > 0) {
                        $requiredQty = $requiredQty * (1 + ($item->scrap_percentage / 100));
                    }
                    
                    $woMaterialsData[] = [
                        'work_order_id' => $workOrderId,
                        'material_id' => $item->material_id,
                        'quantity_required' => $requiredQty,
                        'quantity_consumed' => 0,
                        'uom_id' => $item->uom_id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                
                if (!empty($woMaterialsData)) {
                    DB::table('work_order_materials')->insert($woMaterialsData);
                }
            }
            
            // Get assigned employee name
            $assignedToName = null;
            if ($validated['assigned_to']) {
                $employee = DB::table('employees')
                    ->where('employee_id', $validated['assigned_to'])
                    ->first();
                $assignedToName = $employee->first_name . ' ' . $employee->last_name;
            }
            
            // Log CREATE
            $this->logCreate(
                'Production - Work Orders',
                'work_orders',
                $workOrderId,
                [
                    'work_order_code' => $woCode,
                    'product_name' => $product->product_name,
                    'product_code' => $product->product_code,
                    'bom_code' => $bom->bom_code ?? null,
                    'quantity_ordered' => $validated['quantity_ordered'],
                    'scheduled_start' => $validated['scheduled_start'],
                    'scheduled_end' => $validated['scheduled_end'],
                    'status' => 'Pending',
                    'assigned_to' => $assignedToName,
                    'materials_count' => count($woMaterialsData ?? []),
                ]
            );
            
            DB::commit();
            
            return redirect()
                ->route('production.work-orders.show', $woCode)
                ->with('success', 'Work order created successfully');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Failed to create work order: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified work order
     */
    public function show($woCode)
    {
        $workOrder = DB::table('work_orders as wo')
            ->join('products as p', 'wo.product_id', '=', 'p.product_id')
            ->join('units_of_measure as uom', 'p.uom_id', '=', 'uom.uom_id')
            ->leftJoin('bill_of_materials as bom', 'wo.bom_id', '=', 'bom.bom_id')
            ->leftJoin('employees as e', 'wo.assigned_to', '=', 'e.employee_id')
            ->where('wo.work_order_code', $woCode)
            ->select(
                'wo.*',
                'p.product_code',
                'p.product_name',
                'p.product_type',
                'p.standard_cost',
                'uom.uom_code',
                'uom.uom_name',
                'bom.bom_code',
                'bom.bom_version',
                DB::raw("CONCAT(e.first_name, ' ', e.last_name) as assigned_to_name"),
                'e.employee_code',
                DB::raw('(wo.quantity_ordered - wo.quantity_produced) as remaining_quantity'),
                DB::raw('CASE 
                    WHEN wo.quantity_ordered > 0 
                    THEN ROUND((wo.quantity_produced / wo.quantity_ordered) * 100, 2)
                    ELSE 0 
                END as completion_percentage'),
                DB::raw('TIMESTAMPDIFF(HOUR, wo.actual_start, COALESCE(wo.actual_end, NOW())) as actual_hours')
            )
            ->first();
        
        if (!$workOrder) {
            abort(404, 'Work order not found');
        }
        
        // Log VIEW
        $this->logView(
            'Production - Work Orders',
            "Viewed work order: {$workOrder->work_order_code} for {$workOrder->product_name}"
        );
        
        // Get work order materials
        $materials = DB::table('work_order_materials as wom')
            ->join('products as m', 'wom.material_id', '=', 'm.product_id')
            ->join('units_of_measure as uom', 'wom.uom_id', '=', 'uom.uom_id')
            ->where('wom.work_order_id', $workOrder->work_order_id)
            ->select(
                'wom.*',
                'm.product_code as material_code',
                'm.product_name as material_name',
                'm.product_type as material_type',
                'm.standard_cost',
                'uom.uom_code',
                'uom.uom_name',
                DB::raw('(wom.quantity_required - wom.quantity_consumed) as remaining'),
                DB::raw('(wom.quantity_consumed * m.standard_cost) as consumed_cost')
            )
            ->get();
        
        // Get batches produced from this work order
        $batches = DB::table('batches as b')
            ->join('products as p', 'b.product_id', '=', 'p.product_id')
            ->where('b.work_order_id', $workOrder->work_order_id)
            ->select(
                'b.batch_id',
                'b.batch_code',
                'b.production_date',
                'b.quantity_produced',
                'b.quantity_approved',
                'b.quantity_rejected',
                'b.status',
                'p.product_name'
            )
            ->orderByDesc('b.production_date')
            ->get();
        
        // Calculate total material cost
        $totalMaterialCost = $materials->sum('consumed_cost');
        
        return view('production.work-orders.show', compact('workOrder', 'materials', 'batches', 'totalMaterialCost'));
    }

    /**
     * Show the form for editing the specified work order
     */
    public function edit($woCode)
    {
        $workOrder = DB::table('work_orders')
            ->where('work_order_code', $woCode)
            ->first();
        
        if (!$workOrder) {
            abort(404, 'Work order not found');
        }
        
        // Only pending work orders can be edited
        if (!in_array($workOrder->status, ['Pending', 'Ready'])) {
            return back()->with('error', 'Only pending or ready work orders can be edited');
        }
        
        $products = DB::table('products as p')
            ->join('bill_of_materials as bom', function($join) {
                $join->on('p.product_id', '=', 'bom.product_id')
                     ->where('bom.is_active', 1);
            })
            ->join('units_of_measure as uom', 'p.uom_id', '=', 'uom.uom_id')
            ->where('p.product_type', 'Finished Goods')
            ->where('p.is_active', 1)
            ->select(
                'p.product_id',
                'p.product_code',
                'p.product_name',
                'p.standard_cost',
                'uom.uom_code',
                'uom.uom_name',
                'bom.bom_id',
                'bom.bom_code',
                'bom.bom_version'
            )
            ->orderBy('p.product_name')
            ->get();
        
        $employees = DB::table('employees')
            ->where('employment_status', 'Active')
            ->select(
                'employee_id',
                'employee_code',
                DB::raw("CONCAT(first_name, ' ', last_name) as full_name")
            )
            ->orderBy('first_name')
            ->get();
        
        return view('production.work-orders.edit', compact('workOrder', 'products', 'employees'));
    }

    /**
     * Update the specified work order
     */
    public function update(Request $request, $woCode)
    {
        $workOrder = DB::table('work_orders')->where('work_order_code', $woCode)->first();
        
        if (!$workOrder) {
            abort(404, 'Work order not found');
        }
        
        // Only pending work orders can be updated
        if (!in_array($workOrder->status, ['Pending', 'Ready'])) {
            return back()->with('error', 'Only pending or ready work orders can be updated');
        }
        
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,product_id'],
            'bom_id' => ['nullable', 'exists:bill_of_materials,bom_id'],
            'quantity_ordered' => ['required', 'numeric', 'min:0.0001'],
            'scheduled_start' => ['required', 'date'],
            'scheduled_end' => ['required', 'date', 'after_or_equal:scheduled_start'],
            'assigned_to' => ['nullable', 'exists:employees,employee_id'],
            'instructions' => ['nullable', 'string'],
        ]);
        
        DB::beginTransaction();
        try {
            // Capture OLD data
            $oldWO = DB::table('work_orders as wo')
                ->join('products as p', 'wo.product_id', '=', 'p.product_id')
                ->leftJoin('bill_of_materials as bom', 'wo.bom_id', '=', 'bom.bom_id')
                ->leftJoin('employees as e', 'wo.assigned_to', '=', 'e.employee_id')
                ->where('wo.work_order_id', $workOrder->work_order_id)
                ->select(
                    'wo.*',
                    'p.product_name',
                    'p.product_code',
                    'bom.bom_code',
                    DB::raw("CONCAT(e.first_name, ' ', e.last_name) as assigned_to_name")
                )
                ->first();
            
            // Get new product and BOM info
            $newProduct = DB::table('products')->where('product_id', $validated['product_id'])->first();
            $newBom = null;
            if ($validated['bom_id']) {
                $newBom = DB::table('bill_of_materials')->where('bom_id', $validated['bom_id'])->first();
            }
            
            $newAssignedTo = null;
            if ($validated['assigned_to']) {
                $employee = DB::table('employees')->where('employee_id', $validated['assigned_to'])->first();
                $newAssignedTo = $employee->first_name . ' ' . $employee->last_name;
            }
            
            // Update Work Order
            DB::table('work_orders')
                ->where('work_order_id', $workOrder->work_order_id)
                ->update([
                    'product_id' => $validated['product_id'],
                    'bom_id' => $validated['bom_id'] ?? null,
                    'quantity_ordered' => $validated['quantity_ordered'],
                    'scheduled_start' => $validated['scheduled_start'],
                    'scheduled_end' => $validated['scheduled_end'],
                    'assigned_to' => $validated['assigned_to'] ?? null,
                    'instructions' => $validated['instructions'] ?? null,
                    'updated_at' => now(),
                ]);
            
            // If BOM changed, regenerate work order materials
            if ($oldWO->bom_id != $validated['bom_id']) {
                DB::table('work_order_materials')->where('work_order_id', $workOrder->work_order_id)->delete();
                
                if ($validated['bom_id']) {
                    $bomItems = DB::table('bom_items')
                        ->where('bom_id', $validated['bom_id'])
                        ->get();
                    
                    $woMaterialsData = [];
                    foreach ($bomItems as $item) {
                        $requiredQty = $item->quantity_required * $validated['quantity_ordered'];
                        
                        if ($item->scrap_percentage > 0) {
                            $requiredQty = $requiredQty * (1 + ($item->scrap_percentage / 100));
                        }
                        
                        $woMaterialsData[] = [
                            'work_order_id' => $workOrder->work_order_id,
                            'material_id' => $item->material_id,
                            'quantity_required' => $requiredQty,
                            'quantity_consumed' => 0,
                            'uom_id' => $item->uom_id,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                    
                    if (!empty($woMaterialsData)) {
                        DB::table('work_order_materials')->insert($woMaterialsData);
                    }
                }
            }
            
            // Log UPDATE
            $this->logUpdate(
                'Production - Work Orders',
                'work_orders',
                $workOrder->work_order_id,
                [
                    'work_order_code' => $oldWO->work_order_code,
                    'product_name' => $oldWO->product_name,
                    'product_code' => $oldWO->product_code,
                    'bom_code' => $oldWO->bom_code,
                    'quantity_ordered' => $oldWO->quantity_ordered,
                    'scheduled_start' => $oldWO->scheduled_start,
                    'scheduled_end' => $oldWO->scheduled_end,
                    'assigned_to' => $oldWO->assigned_to_name,
                ],
                [
                    'work_order_code' => $workOrder->work_order_code,
                    'product_name' => $newProduct->product_name,
                    'product_code' => $newProduct->product_code,
                    'bom_code' => $newBom->bom_code ?? null,
                    'quantity_ordered' => $validated['quantity_ordered'],
                    'scheduled_start' => $validated['scheduled_start'],
                    'scheduled_end' => $validated['scheduled_end'],
                    'assigned_to' => $newAssignedTo,
                ]
            );
            
            DB::commit();
            
            return redirect()
                ->route('production.work-orders.show', $woCode)
                ->with('success', 'Work order updated successfully');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Failed to update work order: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified work order
     */
    public function destroy($woCode)
    {
        $workOrder = DB::table('work_orders as wo')
            ->join('products as p', 'wo.product_id', '=', 'p.product_id')
            ->where('wo.work_order_code', $woCode)
            ->select('wo.*', 'p.product_name', 'p.product_code')
            ->first();
        
        if (!$workOrder) {
            abort(404, 'Work order not found');
        }
        
        // Only pending work orders can be deleted
        if ($workOrder->status !== 'Pending') {
            return back()->with('error', 'Only pending work orders can be deleted');
        }
        
        // Check if there are batches
        $batchesCount = DB::table('batches')->where('work_order_id', $workOrder->work_order_id)->count();
        if ($batchesCount > 0) {
            return back()->with('error', 'Cannot delete work order that has batches');
        }
        
        DB::beginTransaction();
        try {
            $materialsCount = DB::table('work_order_materials')
                ->where('work_order_id', $workOrder->work_order_id)
                ->count();
            
            // Delete work order materials first
            DB::table('work_order_materials')->where('work_order_id', $workOrder->work_order_id)->delete();
            
            // Delete work order
            DB::table('work_orders')->where('work_order_id', $workOrder->work_order_id)->delete();
            
            // Log DELETE
            $this->logDelete(
                'Production - Work Orders',
                'work_orders',
                $workOrder->work_order_id,
                [
                    'work_order_code' => $workOrder->work_order_code,
                    'product_name' => $workOrder->product_name,
                    'product_code' => $workOrder->product_code,
                    'quantity_ordered' => $workOrder->quantity_ordered,
                    'scheduled_start' => $workOrder->scheduled_start,
                    'scheduled_end' => $workOrder->scheduled_end,
                    'status' => $workOrder->status,
                    'materials_count' => $materialsCount,
                ]
            );
            
            DB::commit();
            
            return redirect()
                ->route('production.work-orders.index')
                ->with('success', 'Work order deleted successfully');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete work order: ' . $e->getMessage());
        }
    }
    
    /**
     * Start work order production
     */
    public function start($woCode)
    {
        $workOrder = DB::table('work_orders')
            ->where('work_order_code', $woCode)
            ->first();
        
        if (!$workOrder) {
            abort(404, 'Work order not found');
        }
        
        if (!in_array($workOrder->status, ['Pending', 'Ready'])) {
            return back()->with('error', 'Only pending or ready work orders can be started');
        }
        
        DB::beginTransaction();
        try {
            DB::table('work_orders')
                ->where('work_order_id', $workOrder->work_order_id)
                ->update([
                    'status' => 'In Progress',
                    'actual_start' => now(),
                    'updated_at' => now(),
                ]);
            
            // Log special action
            $this->logActivity(
                'Started',
                "Work order {$workOrder->work_order_code} production started",
                'Production - Work Orders'
            );
            
            DB::commit();
            
            return back()->with('success', 'Work order started successfully');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to start work order: ' . $e->getMessage());
        }
    }
    
    /**
     * Complete work order
     */
    public function complete($woCode)
    {
        $workOrder = DB::table('work_orders')
            ->where('work_order_code', $woCode)
            ->first();
        
        if (!$workOrder) {
            abort(404, 'Work order not found');
        }
        
        if ($workOrder->status !== 'In Progress') {
            return back()->with('error', 'Only in-progress work orders can be completed');
        }
        
        DB::beginTransaction();
        try {
            DB::table('work_orders')
                ->where('work_order_id', $workOrder->work_order_id)
                ->update([
                    'status' => 'Completed',
                    'actual_end' => now(),
                    'updated_at' => now(),
                ]);
            
            // Log special action
            $this->logActivity(
                'Completed',
                "Work order {$workOrder->work_order_code} completed - Produced: {$workOrder->quantity_produced}/{$workOrder->quantity_ordered}",
                'Production - Work Orders'
            );
            
            DB::commit();
            
            return back()->with('success', 'Work order completed successfully');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to complete work order: ' . $e->getMessage());
        }
    }

    /**
     * Cancel work order
     */
    public function cancel(Request $request, $woCode)
    {
        $validated = $request->validate([
            'cancel_reason' => ['required', 'string'],
        ]);
        
        $workOrder = DB::table('work_orders')
            ->where('work_order_code', $woCode)
            ->first();
        
        if (!$workOrder) {
            abort(404, 'Work order not found');
        }
        
        if (in_array($workOrder->status, ['Completed', 'Cancelled'])) {
            return back()->with('error', 'Completed or cancelled work orders cannot be cancelled');
        }
        
        DB::beginTransaction();
        try {
            DB::table('work_orders')
                ->where('work_order_id', $workOrder->work_order_id)
                ->update([
                    'status' => 'Cancelled',
                    'notes' => ($workOrder->notes ?? '') . "\n\nCancellation Reason: " . $validated['cancel_reason'],
                    'updated_at' => now(),
                ]);
            
            // Log cancellation
            $this->logActivity(
                'Cancelled',
                "Work order {$workOrder->work_order_code} cancelled - Reason: {$validated['cancel_reason']}",
                'Production - Work Orders'
            );
            
            DB::commit();
            
            return back()->with('success', 'Work order cancelled successfully');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to cancel work order: ' . $e->getMessage());
        }
    }
    
    /**
     * Export work orders
     */
    public function export()
    {
        $this->logExport('Production - Work Orders', 'Exported work orders list');
        
        // Your export logic here
    }
    
    /**
     * Print work order
     */
    public function print($woCode)
    {
        $workOrder = DB::table('work_orders as wo')
            ->join('products as p', 'wo.product_id', '=', 'p.product_id')
            ->leftJoin('bill_of_materials as bom', 'wo.bom_id', '=', 'bom.bom_id')
            ->where('wo.work_order_code', $woCode)
            ->select('wo.*', 'p.product_name', 'bom.bom_code')
            ->first();
        
        if (!$workOrder) {
            abort(404, 'Work order not found');
        }
        
        $this->logPrint('Production - Work Orders', "Printed work order: {$workOrder->work_order_code}");
        
        // Return print view
        return view('production.work-orders.print', compact('workOrder'));
    }
}
