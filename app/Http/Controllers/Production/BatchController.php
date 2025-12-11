<?php

namespace App\Http\Controllers\Production;

use App\Helpers\CodeGeneratorHelper;
use App\Http\Controllers\Controller;
use App\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BatchController extends Controller
{
    //
    use LogsActivity;
    
    /**
     * Listing of batches
     */
    public function index(Request $request)
    {
        $query = DB::table('batches as b')
            ->join('products as p', 'b.product_id', '=', 'p.product_id')
            ->leftJoin('work_orders as wo', 'b.work_order_id', '=', 'wo.work_order_id')
            ->select(
                'b.batch_id',
                'b.batch_code',
                'b.production_date',
                'b.quantity_produced',
                'b.quantity_approved',
                'b.quantity_rejected',
                'b.status',
                'b.created_at',
                'p.product_code',
                'p.product_name',
                'p.product_type',
                'wo.work_order_code',
                DB::raw('(b.quantity_produced - b.quantity_approved - b.quantity_rejected) as pending_qc'),
                DB::raw('CASE 
                    WHEN b.quantity_produced > 0 
                    THEN ROUND((b.quantity_approved / b.quantity_produced) * 100, 2)
                    ELSE 0 
                END as approval_rate')
            );
        
        // Search filter (multi-column)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('b.batch_code', 'like', "%{$search}%")
                ->orWhere('p.product_name', 'like', "%{$search}%")
                ->orWhere('p.product_code', 'like', "%{$search}%")
                ->orWhere('wo.work_order_code', 'like', "%{$search}%");
            });
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('b.status', $request->status);
        }
        
        // Filter by product
        if ($request->filled('product_id')) {
            $query->where('b.product_id', $request->product_id);
        }
        
        // Filter by work order assignment
        if ($request->filled('work_order')) {
            if ($request->work_order === 'unassigned') {
                $query->whereNull('b.work_order_id');
            } elseif ($request->work_order === 'assigned') {
                $query->whereNotNull('b.work_order_id');
            }
        }
        
        // Filter by date range
        if ($request->filled('date_from')) {
            $query->where('b.production_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('b.production_date', '<=', $request->date_to);
        }
        
        // Filter by QC status
        if ($request->filled('qc_filter')) {
            switch ($request->qc_filter) {
                case 'pending': // Pending QC
                    $query->whereRaw('(b.quantity_produced - b.quantity_approved - b.quantity_rejected) > 0');
                    break;
                case 'approved': // Fully approved
                    $query->whereRaw('b.quantity_approved = b.quantity_produced');
                    break;
                case 'rejected': // Has rejected items
                    $query->where('b.quantity_rejected', '>', 0);
                    break;
                case 'partial': // Partially approved
                    $query->whereRaw('b.quantity_approved > 0 AND b.quantity_approved < b.quantity_produced');
                    break;
            }
        }
        
        // Sorting
        $sortBy = $request->get('sort_by', 'production_date');
        $sortOrder = $request->get('sort_order', 'desc');
        
        // Validate sort order
        $sortOrder = in_array(strtolower($sortOrder), ['asc', 'desc']) ? strtolower($sortOrder) : 'desc';
        
        // Whitelist allowed sort columns
        $allowedSort = [
            'batch_code',
            'product_name',
            'production_date',
            'quantity_produced',
            'quantity_approved',
            'quantity_rejected',
            'pending_qc',
            'approval_rate',
            'status',
            'created_at'
        ];
        
        if (in_array($sortBy, $allowedSort)) {
            if ($sortBy === 'product_name') {
                $query->orderBy('p.product_name', $sortOrder);
            } elseif ($sortBy === 'batch_code') {
                $query->orderBy('b.batch_code', $sortOrder);
            } elseif ($sortBy === 'production_date') {
                $query->orderBy('b.production_date', $sortOrder);
            } elseif ($sortBy === 'quantity_produced') {
                $query->orderBy('b.quantity_produced', $sortOrder);
            } elseif ($sortBy === 'quantity_approved') {
                $query->orderBy('b.quantity_approved', $sortOrder);
            } elseif ($sortBy === 'quantity_rejected') {
                $query->orderBy('b.quantity_rejected', $sortOrder);
            } elseif ($sortBy === 'status') {
                $query->orderBy('b.status', $sortOrder);
            } elseif ($sortBy === 'created_at') {
                $query->orderBy('b.created_at', $sortOrder);
            } elseif ($sortBy === 'pending_qc') {
                // Computed column - Calculation
                $query->orderByRaw("(b.quantity_produced - b.quantity_approved - b.quantity_rejected) {$sortOrder}");
            } elseif ($sortBy === 'approval_rate') {
                // Computed column - Complex CASE
                $query->orderByRaw("CASE 
                    WHEN b.quantity_produced > 0 
                    THEN ROUND((b.quantity_approved / b.quantity_produced) * 100, 2)
                    ELSE 0 
                END {$sortOrder}");
            }
        } else {
            // Fallback to default
            $query->orderByDesc('b.production_date')->orderByDesc('b.created_at');
        }
        
        // Add secondary sort for consistency when dates are same
        if ($sortBy !== 'created_at' && $sortBy !== 'production_date') {
            $query->orderByDesc('b.production_date');
        }
        
        $batches = $query->paginate(20)->withQueryString();
        
        $products = DB::table('products')
            ->where('product_type', 'Finished Goods')
            ->where('is_active', 1)
            ->select('product_id', 'product_code', 'product_name')
            ->orderBy('product_name')
            ->get();
        
        return view('production.batches.index', compact('batches', 'products'));
    }

    /**
     * Show the form for creating 
     */
    public function create()
    {
        // Get in-progress work orders
        $workOrders = DB::table('work_orders as wo')
            ->join('products as p', 'wo.product_id', '=', 'p.product_id')
            ->join('units_of_measure as uom', 'p.uom_id', '=', 'uom.uom_id')
            ->where('wo.status', 'In Progress')
            ->select(
                'wo.work_order_id',
                'wo.work_order_code',
                'wo.quantity_ordered',
                'wo.quantity_produced',
                'p.product_id',
                'p.product_code',
                'p.product_name',
                'uom.uom_code',
                DB::raw('(wo.quantity_ordered - wo.quantity_produced) as remaining_quantity')
            )
            ->orderBy('wo.scheduled_start')
            ->get();
        
        // Get finished goods products (for manual batch without WO)
        $products = DB::table('products')
            ->join('units_of_measure as uom', 'products.uom_id', '=', 'uom.uom_id')
            ->where('products.product_type', 'Finished Goods')
            ->where('products.is_active', 1)
            ->select(
                'products.product_id',
                'products.product_code',
                'products.product_name',
                'uom.uom_code'
            )
            ->orderBy('products.product_name')
            ->get();
        
        return view('production.batches.create', compact('workOrders', 'products'));
    }

    /**
     * Store a newly created batch
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'work_order_id' => ['nullable', 'exists:work_orders,work_order_id'],
            'product_id' => ['required', 'exists:products,product_id'],
            'production_date' => ['required', 'date', 'before_or_equal:today'],
            'quantity_produced' => ['required', 'numeric', 'min:0.0001'],
        ], [
            'work_order_id.exists' => 'Selected work order is invalid.',
            
            'product_id.required' => 'Please select a product.',
            'product_id.exists' => 'Selected product is invalid.',
            
            'production_date.required' => 'Production date is required.',
            'production_date.date' => 'Please provide a valid date.',
            'production_date.before_or_equal' => 'Production date cannot be in the future.',
            
            'quantity_produced.required' => 'Quantity produced is required.',
            'quantity_produced.numeric' => 'Quantity must be a number.',
            'quantity_produced.min' => 'Quantity must be greater than 0.',
        ]);
        
        DB::beginTransaction();
        try {
            // Generate unique batch code
            $batchCode = CodeGeneratorHelper::generateBatchCode();
            
            // Get product info
            $product = DB::table('products')->where('product_id', $validated['product_id'])->first();
            
            // Get work order info if provided
            $workOrder = null;
            if ($validated['work_order_id']) {
                $workOrder = DB::table('work_orders')
                    ->where('work_order_id', $validated['work_order_id'])
                    ->first();
                
                // Check if quantity exceeds work order remaining
                $remaining = $workOrder->quantity_ordered - $workOrder->quantity_produced;
                if ($validated['quantity_produced'] > $remaining) {
                    return back()
                        ->withInput()
                        ->with('error', "Quantity produced ({$validated['quantity_produced']}) exceeds work order remaining quantity ({$remaining})");
                }
            }
            
            // Insert Batch
            $batchId = DB::table('batches')->insertGetId([
                'batch_code' => $batchCode,
                'work_order_id' => $validated['work_order_id'] ?? null,
                'product_id' => $validated['product_id'],
                'production_date' => $validated['production_date'],
                'quantity_produced' => $validated['quantity_produced'],
                'quantity_approved' => 0,
                'quantity_rejected' => 0,
                'status' => 'Pending QC',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            // Update work order quantity_produced if linked
            if ($validated['work_order_id']) {
                DB::table('work_orders')
                    ->where('work_order_id', $validated['work_order_id'])
                    ->increment('quantity_produced', $validated['quantity_produced']);
            }
            
            // Log CREATE
            $this->logCreate(
                'Production - Batches',
                'batches',
                $batchId,
                [
                    'batch_code' => $batchCode,
                    'work_order_code' => $workOrder->work_order_code ?? null,
                    'product_name' => $product->product_name,
                    'product_code' => $product->product_code,
                    'production_date' => $validated['production_date'],
                    'quantity_produced' => $validated['quantity_produced'],
                    'status' => 'Pending QC',
                ]
            );
            
            DB::commit();
            
            return redirect()
                ->route('production.batches.show', $batchCode)
                ->with('success', 'Batch created successfully');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Failed to create batch: ' . $e->getMessage());
        }
    }

    /**
     * Show specified batch
     */
    public function show($batchCode)
    {
        $batch = DB::table('batches as b')
            ->join('products as p', 'b.product_id', '=', 'p.product_id')
            ->join('units_of_measure as uom', 'p.uom_id', '=', 'uom.uom_id')
            ->leftJoin('work_orders as wo', 'b.work_order_id', '=', 'wo.work_order_id')
            ->where('b.batch_code', $batchCode)
            ->select(
                'b.*',
                'p.product_code',
                'p.product_name',
                'p.product_type',
                'uom.uom_code',
                'uom.uom_name',
                'wo.work_order_code',
                'wo.quantity_ordered',
                DB::raw('(b.quantity_produced - b.quantity_approved - b.quantity_rejected) as pending_qc'),
                DB::raw('CASE 
                    WHEN b.quantity_produced > 0 
                    THEN ROUND((b.quantity_approved / b.quantity_produced) * 100, 2)
                    ELSE 0 
                END as approval_rate'),
                DB::raw('CASE 
                    WHEN b.quantity_produced > 0 
                    THEN ROUND((b.quantity_rejected / b.quantity_produced) * 100, 2)
                    ELSE 0 
                END as rejection_rate')
            )
            ->first();
        
        if (!$batch) {
            abort(404, 'Batch not found');
        }
        
        // Log VIEW
        $this->logView(
            'Production - Batches',
            "Viewed batch: {$batch->batch_code} for {$batch->product_name}"
        );
        
        // Get QC inspections for this batch
        $qcInspections = DB::table('quality_control as qc')
            ->join('employees as e', 'qc.inspector_id', '=', 'e.employee_id')
            ->where('qc.batch_id', $batch->batch_id)
            ->select(
                'qc.qc_id',
                'qc.qc_code',
                'qc.inspection_date',
                'qc.inspection_type',
                'qc.result',
                'qc.quantity_inspected',
                'qc.quantity_passed',
                'qc.quantity_failed',
                'qc.remarks',
                DB::raw("CONCAT(e.first_name, ' ', e.last_name) as inspector_name")
            )
            ->orderByDesc('qc.inspection_date')
            ->get();
        
        // Get lot info if batch is assigned to lot
        $lot = DB::table('lots')
            ->where('product_id', $batch->product_id)
            ->where('manufacture_date', $batch->production_date)
            ->first();
        
        return view('production.batches.show', compact('batch', 'qcInspections', 'lot'));
    }

    /**
     * Show the form for editing
     */
    public function edit($batchCode)
    {
        $batch = DB::table('batches')
            ->where('batch_code', $batchCode)
            ->first();
        
        if (!$batch) {
            abort(404, 'Batch not found');
        }
        
        // Only pending batches can be edited
        if ($batch->status !== 'Pending QC') {
            return back()->with('error', 'Only batches pending QC can be edited');
        }
        
        $workOrders = DB::table('work_orders as wo')
            ->join('products as p', 'wo.product_id', '=', 'p.product_id')
            ->join('units_of_measure as uom', 'p.uom_id', '=', 'uom.uom_id')
            ->where('wo.status', 'In Progress')
            ->select(
                'wo.work_order_id',
                'wo.work_order_code',
                'wo.quantity_ordered',
                'wo.quantity_produced',
                'p.product_id',
                'p.product_code',
                'p.product_name',
                'uom.uom_code'
            )
            ->orderBy('wo.scheduled_start')
            ->get();
        
        $products = DB::table('products')
            ->join('units_of_measure as uom', 'products.uom_id', '=', 'uom.uom_id')
            ->where('products.product_type', 'Finished Goods')
            ->where('products.is_active', 1)
            ->select(
                'products.product_id',
                'products.product_code',
                'products.product_name',
                'uom.uom_code'
            )
            ->orderBy('products.product_name')
            ->get();
        
        return view('production.batches.edit', compact('batch', 'workOrders', 'products'));
    }

    /**
     * Update the specified batch
     */
    public function update(Request $request, $batchCode)
    {
        $batch = DB::table('batches')->where('batch_code', $batchCode)->first();
        
        if (!$batch) {
            abort(404, 'Batch not found');
        }
        
        // Only pending batches can be updated
        if ($batch->status !== 'Pending QC') {
            return back()->with('error', 'Only batches pending QC can be updated');
        }
        
        $validated = $request->validate([
            'work_order_id' => ['nullable', 'exists:work_orders,work_order_id'],
            'product_id' => ['required', 'exists:products,product_id'],
            'production_date' => ['required', 'date', 'before_or_equal:today'],
            'quantity_produced' => ['required', 'numeric', 'min:0.0001'],
        ]);
        
        DB::beginTransaction();
        try {
            // Capture OLD data
            $oldBatch = DB::table('batches as b')
                ->join('products as p', 'b.product_id', '=', 'p.product_id')
                ->leftJoin('work_orders as wo', 'b.work_order_id', '=', 'wo.work_order_id')
                ->where('b.batch_id', $batch->batch_id)
                ->select('b.*', 'p.product_name', 'p.product_code', 'wo.work_order_code')
                ->first();
            
            // Adjust work order quantity_produced if WO changed
            if ($batch->work_order_id && $batch->work_order_id != $validated['work_order_id']) {
                // Decrease from old WO
                DB::table('work_orders')
                    ->where('work_order_id', $batch->work_order_id)
                    ->decrement('quantity_produced', $batch->quantity_produced);
            }
            
            // Get new product info
            $newProduct = DB::table('products')->where('product_id', $validated['product_id'])->first();
            $newWorkOrder = null;
            if ($validated['work_order_id']) {
                $newWorkOrder = DB::table('work_orders')
                    ->where('work_order_id', $validated['work_order_id'])
                    ->first();
            }
            
            // Update Batch
            DB::table('batches')
                ->where('batch_id', $batch->batch_id)
                ->update([
                    'work_order_id' => $validated['work_order_id'] ?? null,
                    'product_id' => $validated['product_id'],
                    'production_date' => $validated['production_date'],
                    'quantity_produced' => $validated['quantity_produced'],
                    'updated_at' => now(),
                ]);
            
            // Increase new WO if linked
            if ($validated['work_order_id']) {
                DB::table('work_orders')
                    ->where('work_order_id', $validated['work_order_id'])
                    ->increment('quantity_produced', $validated['quantity_produced']);
            }
            
            // Log UPDATE
            $this->logUpdate(
                'Production - Batches',
                'batches',
                $batch->batch_id,
                [
                    'batch_code' => $oldBatch->batch_code,
                    'work_order_code' => $oldBatch->work_order_code,
                    'product_name' => $oldBatch->product_name,
                    'product_code' => $oldBatch->product_code,
                    'production_date' => $oldBatch->production_date,
                    'quantity_produced' => $oldBatch->quantity_produced,
                ],
                [
                    'batch_code' => $batch->batch_code,
                    'work_order_code' => $newWorkOrder->work_order_code ?? null,
                    'product_name' => $newProduct->product_name,
                    'product_code' => $newProduct->product_code,
                    'production_date' => $validated['production_date'],
                    'quantity_produced' => $validated['quantity_produced'],
                ]
            );
            
            DB::commit();
            
            return redirect()
                ->route('production.batches.show', $batchCode)
                ->with('success', 'Batch updated successfully');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Failed to update batch: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified batch
     */
    public function destroy($batchCode)
    {
        $batch = DB::table('batches as b')
            ->join('products as p', 'b.product_id', '=', 'p.product_id')
            ->where('b.batch_code', $batchCode)
            ->select('b.*', 'p.product_name', 'p.product_code')
            ->first();
        
        if (!$batch) {
            abort(404, 'Batch not found');
        }
        
        // Only pending batches can be deleted
        if ($batch->status !== 'Pending QC') {
            return back()->with('error', 'Only batches pending QC can be deleted');
        }
        
        // Check if batch has QC inspections
        $qcCount = DB::table('quality_control')->where('batch_id', $batch->batch_id)->count();
        if ($qcCount > 0) {
            return back()->with('error', 'Cannot delete batch that has QC inspections');
        }
        
        DB::beginTransaction();
        try {
            // Adjust work order quantity_produced if linked
            if ($batch->work_order_id) {
                DB::table('work_orders')
                    ->where('work_order_id', $batch->work_order_id)
                    ->decrement('quantity_produced', $batch->quantity_produced);
            }
            
            // Delete batch
            DB::table('batches')->where('batch_id', $batch->batch_id)->delete();
            
            // Log DELETE
            $this->logDelete(
                'Production - Batches',
                'batches',
                $batch->batch_id,
                [
                    'batch_code' => $batch->batch_code,
                    'product_name' => $batch->product_name,
                    'product_code' => $batch->product_code,
                    'production_date' => $batch->production_date,
                    'quantity_produced' => $batch->quantity_produced,
                    'status' => $batch->status,
                ]
            );
            
            DB::commit();
            
            return redirect()
                ->route('production.batches.index')
                ->with('success', 'Batch deleted successfully');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete batch: ' . $e->getMessage());
        }
    }
    
    /**
     * Export batches
     */
    public function export(Request $request)
    {
        $this->logExport('Production - Batches', 'Exported batch tracking list');
        
        $query = DB::table('batches as b')
            ->join('products as p', 'b.product_id', '=', 'p.product_id')
            ->leftJoin('work_orders as wo', 'b.work_order_id', '=', 'wo.work_order_id')
            ->select(
                'b.batch_code',
                'b.production_date',
                'p.product_code',
                'p.product_name',
                'p.product_type',
                'wo.work_order_code',
                'b.quantity_produced',
                'b.quantity_approved',
                'b.quantity_rejected',
                DB::raw('(b.quantity_produced - b.quantity_approved - b.quantity_rejected) as pending_qc'),
                DB::raw('CASE 
                    WHEN b.quantity_produced > 0 
                    THEN ROUND((b.quantity_approved / b.quantity_produced) * 100, 2)
                    ELSE 0 
                END as approval_rate'),
                'b.status',
                'b.created_at'
            );
        
        // Apply same filters as index
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('b.batch_code', 'like', "%{$search}%")
                ->orWhere('p.product_name', 'like', "%{$search}%")
                ->orWhere('p.product_code', 'like', "%{$search}%")
                ->orWhere('wo.work_order_code', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('status')) {
            $query->where('b.status', $request->status);
        }
        
        if ($request->filled('product_id')) {
            $query->where('b.product_id', $request->product_id);
        }
        
        if ($request->filled('work_order')) {
            if ($request->work_order === 'unassigned') {
                $query->whereNull('b.work_order_id');
            } elseif ($request->work_order === 'assigned') {
                $query->whereNotNull('b.work_order_id');
            }
        }
        
        if ($request->filled('date_from')) {
            $query->where('b.production_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('b.production_date', '<=', $request->date_to);
        }
        
        if ($request->filled('qc_filter')) {
            switch ($request->qc_filter) {
                case 'pending':
                    $query->whereRaw('(b.quantity_produced - b.quantity_approved - b.quantity_rejected) > 0');
                    break;
                case 'approved':
                    $query->whereRaw('b.quantity_approved = b.quantity_produced');
                    break;
                case 'rejected':
                    $query->where('b.quantity_rejected', '>', 0);
                    break;
                case 'partial':
                    $query->whereRaw('b.quantity_approved > 0 AND b.quantity_approved < b.quantity_produced');
                    break;
            }
        }
        
        // Apply same sorting as index
        $sortBy = $request->get('sort_by', 'production_date');
        $sortOrder = $request->get('sort_order', 'desc');
        $sortOrder = in_array(strtolower($sortOrder), ['asc', 'desc']) ? strtolower($sortOrder) : 'desc';
        
        $allowedSort = [
            'batch_code', 'product_name', 'production_date', 'quantity_produced',
            'quantity_approved', 'quantity_rejected', 'pending_qc', 'approval_rate',
            'status', 'created_at'
        ];
        
        if (in_array($sortBy, $allowedSort)) {
            if ($sortBy === 'product_name') {
                $query->orderBy('p.product_name', $sortOrder);
            } elseif ($sortBy === 'batch_code') {
                $query->orderBy('b.batch_code', $sortOrder);
            } elseif ($sortBy === 'production_date') {
                $query->orderBy('b.production_date', $sortOrder);
            } elseif ($sortBy === 'quantity_produced') {
                $query->orderBy('b.quantity_produced', $sortOrder);
            } elseif ($sortBy === 'quantity_approved') {
                $query->orderBy('b.quantity_approved', $sortOrder);
            } elseif ($sortBy === 'quantity_rejected') {
                $query->orderBy('b.quantity_rejected', $sortOrder);
            } elseif ($sortBy === 'status') {
                $query->orderBy('b.status', $sortOrder);
            } elseif ($sortBy === 'created_at') {
                $query->orderBy('b.created_at', $sortOrder);
            } elseif ($sortBy === 'pending_qc') {
                $query->orderByRaw("(b.quantity_produced - b.quantity_approved - b.quantity_rejected) {$sortOrder}");
            } elseif ($sortBy === 'approval_rate') {
                $query->orderByRaw("CASE 
                    WHEN b.quantity_produced > 0 
                    THEN ROUND((b.quantity_approved / b.quantity_produced) * 100, 2)
                    ELSE 0 
                END {$sortOrder}");
            }
        } else {
            $query->orderByDesc('b.production_date')->orderByDesc('b.created_at');
        }
        
        if ($sortBy !== 'created_at' && $sortBy !== 'production_date') {
            $query->orderByDesc('b.production_date');
        }
        
        $batches = $query->limit(10000)->get();
        
        $filename = 'batch_tracking_export_' . now()->format('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];
        
        $callback = function() use ($batches) {
            $file = fopen('php://output', 'w');
            
            // Header CSV
            fputcsv($file, [
                'Batch Code',
                'Production Date',
                'Product Code',
                'Product Name',
                'Product Type',
                'Work Order',
                'Qty Produced',
                'Qty Approved',
                'Qty Rejected',
                'Pending QC',
                'Approval Rate (%)',
                'Status',
                'Created At'
            ]);
            
            // Data rows
            foreach ($batches as $batch) {
                fputcsv($file, [
                    $batch->batch_code,
                    $batch->production_date,
                    $batch->product_code,
                    $batch->product_name,
                    $batch->product_type,
                    $batch->work_order_code ?? 'Manual Batch',
                    $batch->quantity_produced,
                    $batch->quantity_approved,
                    $batch->quantity_rejected,
                    $batch->pending_qc,
                    $batch->approval_rate,
                    $batch->status,
                    $batch->created_at,
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    /**
     * Print batch label
     */
    public function printLabel($batchCode)
    {
        $batch = DB::table('batches as b')
            ->join('products as p', 'b.product_id', '=', 'p.product_id')
            ->where('b.batch_code', $batchCode)
            ->select('b.*', 'p.product_name', 'p.product_code')
            ->first();
        
        if (!$batch) {
            abort(404, 'Batch not found');
        }
        
        $this->logPrint('Production - Batches', "Printed batch label: {$batch->batch_code}");
        
        // Return print view
        return view('production.batches.print-label', compact('batch'));
    }
}
