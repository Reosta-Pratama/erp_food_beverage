<?php

namespace App\Http\Controllers\Inventory;

use App\Helpers\CodeGeneratorHelper;
use App\Http\Controllers\Controller;
use App\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QualityControlController extends Controller
{
    //
    use LogsActivity;
    
    /**
     * Display QC inspections with filtering
     */
    public function index(Request $request)
    {
        $this->logView('Quality Control', 'Viewed QC inspections list');

        $query = DB::table('quality_control as qc')
            ->join('products as p', 'qc.product_id', '=', 'p.product_id')
            ->join('employees as e', 'qc.inspector_id', '=', 'e.employee_id')
            ->leftJoin('work_orders as wo', 'qc.work_order_id', '=', 'wo.work_order_id')
            ->leftJoin('batches as b', 'qc.batch_id', '=', 'b.batch_id')
            ->select(
                'qc.qc_id',
                'qc.qc_code',
                'qc.inspection_type',
                'qc.inspection_date',
                'qc.result',
                'qc.quantity_inspected',
                'qc.quantity_passed',
                'qc.quantity_failed',
                'p.product_code',
                'p.product_name',
                'e.first_name as inspector_first_name',
                'e.last_name as inspector_last_name',
                'wo.work_order_code',
                'b.batch_code',
                'qc.created_at'
            );

        // Filter by inspection type
        if ($request->filled('inspection_type')) {
            $query->where('qc.inspection_type', $request->inspection_type);
        }

        // Filter by result
        if ($request->filled('result')) {
            $query->where('qc.result', $request->result);
        }

        // Filter by product
        if ($request->filled('product_id')) {
            $query->where('qc.product_id', $request->product_id);
        }

        // Filter by inspector
        if ($request->filled('inspector_id')) {
            $query->where('qc.inspector_id', $request->inspector_id);
        }

        // Filter by work order
        if ($request->filled('work_order_id')) {
            $query->where('qc.work_order_id', $request->work_order_id);
        }

        // Filter by batch
        if ($request->filled('batch_id')) {
            $query->where('qc.batch_id', $request->batch_id);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('qc.inspection_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('qc.inspection_date', '<=', $request->date_to);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('qc.qc_code', 'like', "%{$search}%")
                ->orWhere('p.product_name', 'like', "%{$search}%")
                ->orWhere('p.product_code', 'like', "%{$search}%")
                ->orWhere('wo.work_order_code', 'like', "%{$search}%")
                ->orWhere('b.batch_code', 'like', "%{$search}%");
            });
        }

        $inspections = $query->orderByDesc('qc.inspection_date')
            ->orderByDesc('qc.created_at')
            ->paginate(20);

        // Get filter options
        $inspectionTypes = DB::table('quality_control')
            ->select('inspection_type', DB::raw('COUNT(*) as count'))
            ->groupBy('inspection_type')
            ->orderBy('inspection_type')
            ->pluck('count', 'inspection_type');

        $results = DB::table('quality_control')
            ->select('result', DB::raw('COUNT(*) as count'))
            ->groupBy('result')
            ->orderBy('result')
            ->pluck('count', 'result');

        $products = DB::table('products')
            ->where('is_active', 1)
            ->orderBy('product_name')
            ->get(['product_id', 'product_code', 'product_name']);

        $inspectors = DB::table('employees')
            ->where('employment_status', 'Active')
            ->orderBy('first_name')
            ->get(['employee_id', 'first_name', 'last_name', 'employee_code']);

        $workOrders = DB::table('work_orders')
            ->whereIn('status', ['In Progress', 'Completed'])
            ->orderByDesc('created_at')
            ->limit(100)
            ->get(['work_order_id', 'work_order_code']);

        $batches = DB::table('batches')
            ->whereIn('status', ['Pending QC', 'In QC', 'Passed', 'Failed'])
            ->orderByDesc('created_at')
            ->limit(100)
            ->get(['batch_id', 'batch_code']);

        return view('quality-control.inspections.index', compact(
            'inspections',
            'inspectionTypes',
            'results',
            'products',
            'inspectors',
            'workOrders',
            'batches'
        ));
    }

    /**
     * Show create form
     */
    public function create()
    {
        // Get products
        $products = DB::table('products')
            ->where('is_active', 1)
            ->orderBy('product_name')
            ->get(['product_id', 'product_code', 'product_name']);

        // Get inspectors (employees)
        $inspectors = DB::table('employees')
            ->where('employment_status', 'Active')
            ->orderBy('first_name')
            ->get(['employee_id', 'employee_code', 'first_name', 'last_name']);

        // Get pending work orders
        $workOrders = DB::table('work_orders as wo')
            ->join('products as p', 'wo.product_id', '=', 'p.product_id')
            ->whereIn('wo.status', ['In Progress', 'Completed'])
            ->orderByDesc('wo.created_at')
            ->limit(50)
            ->get([
                'wo.work_order_id',
                'wo.work_order_code',
                'wo.quantity_produced',
                'p.product_name'
            ]);

        // Get batches pending QC
        $batches = DB::table('batches as b')
            ->join('products as p', 'b.product_id', '=', 'p.product_id')
            ->whereIn('b.status', ['Pending QC', 'In QC'])
            ->orderByDesc('b.created_at')
            ->limit(50)
            ->get([
                'b.batch_id',
                'b.batch_code',
                'b.quantity_produced',
                'b.work_order_id',
                'p.product_name'
            ]);

        return view('quality-control.inspections.create', compact(
            'products',
            'inspectors',
            'workOrders',
            'batches'
        ));
    }

    /**
     * Store new QC inspection
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'inspection_type' => ['required', 'string', 'in:Incoming,In-Process,Final,Random'],
            'product_id' => ['required', 'exists:products,product_id'],
            'work_order_id' => ['nullable', 'exists:work_orders,work_order_id'],
            'batch_id' => ['nullable', 'exists:batches,batch_id'],
            'inspection_date' => ['required', 'date'],
            'inspector_id' => ['required', 'exists:employees,employee_id'],
            'quantity_inspected' => ['required', 'numeric', 'min:0.0001'],
            'quantity_passed' => ['required', 'numeric', 'min:0'],
            'quantity_failed' => ['required', 'numeric', 'min:0'],
            'result' => ['required', 'string', 'in:Passed,Failed,Conditional'],
            'remarks' => ['nullable', 'string'],
            
            // QC Parameters
            'parameters' => ['nullable', 'array'],
            'parameters.*.parameter_name' => ['required_with:parameters', 'string', 'max:150'],
            'parameters.*.standard_value' => ['nullable', 'string', 'max:100'],
            'parameters.*.actual_value' => ['nullable', 'string', 'max:100'],
            'parameters.*.unit' => ['nullable', 'string', 'max:50'],
            'parameters.*.is_passed' => ['required_with:parameters', 'boolean'],
        ], [
            'inspection_type.required' => 'Please select an inspection type.',
            'inspection_type.in' => 'Invalid inspection type selected.',
            
            'product_id.required' => 'Please select a product.',
            'product_id.exists' => 'Selected product is invalid.',
            
            'work_order_id.exists' => 'Selected work order is invalid.',
            'batch_id.exists' => 'Selected batch is invalid.',
            
            'inspection_date.required' => 'Inspection date is required.',
            'inspection_date.date' => 'Invalid inspection date format.',
            
            'inspector_id.required' => 'Please select an inspector.',
            'inspector_id.exists' => 'Selected inspector is invalid.',
            
            'quantity_inspected.required' => 'Quantity inspected is required.',
            'quantity_inspected.numeric' => 'Quantity inspected must be a number.',
            'quantity_inspected.min' => 'Quantity inspected must be greater than zero.',
            
            'quantity_passed.required' => 'Quantity passed is required.',
            'quantity_passed.numeric' => 'Quantity passed must be a number.',
            'quantity_passed.min' => 'Quantity passed cannot be negative.',
            
            'quantity_failed.required' => 'Quantity failed is required.',
            'quantity_failed.numeric' => 'Quantity failed must be a number.',
            'quantity_failed.min' => 'Quantity failed cannot be negative.',
            
            'result.required' => 'Please select inspection result.',
            'result.in' => 'Invalid inspection result.',
            
            'parameters.array' => 'Invalid parameters format.',
            'parameters.*.parameter_name.required_with' => 'Parameter name is required.',
            'parameters.*.parameter_name.max' => 'Parameter name is too long.',
            'parameters.*.is_passed.required_with' => 'Parameter result is required.',
        ]);

        // Validate quantities
        $totalQuantity = $validated['quantity_passed'] + $validated['quantity_failed'];
        if ($totalQuantity != $validated['quantity_inspected']) {
            return back()
                ->withInput()
                ->with('error', 'Total of passed and failed quantities must equal inspected quantity.');
        }

        DB::beginTransaction();
        try {
            // Generate QC Code
            $qcCode = CodeGeneratorHelper::generateQCCode();

            // Insert QC Inspection
            $qcId = DB::table('quality_control')->insertGetId([
                'qc_code' => $qcCode,
                'inspection_type' => $validated['inspection_type'],
                'work_order_id' => $validated['work_order_id'] ?? null,
                'batch_id' => $validated['batch_id'] ?? null,
                'product_id' => $validated['product_id'],
                'inspection_date' => $validated['inspection_date'],
                'inspector_id' => $validated['inspector_id'],
                'result' => $validated['result'],
                'quantity_inspected' => $validated['quantity_inspected'],
                'quantity_passed' => $validated['quantity_passed'],
                'quantity_failed' => $validated['quantity_failed'],
                'remarks' => $validated['remarks'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Insert QC Parameters
            if (!empty($validated['parameters'])) {
                $parameterData = [];
                foreach ($validated['parameters'] as $param) {
                    $parameterData[] = [
                        'qc_id' => $qcId,
                        'parameter_name' => $param['parameter_name'],
                        'standard_value' => $param['standard_value'] ?? null,
                        'actual_value' => $param['actual_value'] ?? null,
                        'unit' => $param['unit'] ?? null,
                        'is_passed' => $param['is_passed'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                DB::table('qc_parameters')->insert($parameterData);
            }

            // Update batch status if batch is linked
            if ($validated['batch_id']) {
                $batchStatus = match($validated['result']) {
                    'Passed' => 'Passed',
                    'Failed' => 'Failed',
                    'Conditional' => 'In QC',
                    default => 'In QC'
                };

                DB::table('batches')
                    ->where('batch_id', $validated['batch_id'])
                    ->update([
                        'quantity_approved' => $validated['result'] === 'Passed' 
                            ? $validated['quantity_passed'] 
                            : 0,
                        'quantity_rejected' => $validated['result'] === 'Failed' 
                            ? $validated['quantity_failed'] 
                            : 0,
                        'status' => $batchStatus,
                        'updated_at' => now(),
                    ]);
            }

            // Log CREATE
            $this->logCreate(
                'Quality Control',
                'quality_control',
                $qcId,
                [
                    'qc_code' => $qcCode,
                    'inspection_type' => $validated['inspection_type'],
                    'product_id' => $validated['product_id'],
                    'inspection_date' => $validated['inspection_date'],
                    'result' => $validated['result'],
                    'quantity_inspected' => $validated['quantity_inspected'],
                    'quantity_passed' => $validated['quantity_passed'],
                    'quantity_failed' => $validated['quantity_failed'],
                    'parameters_count' => count($validated['parameters'] ?? []),
                ]
            );

            DB::commit();

            return redirect()
                ->route('quality-control.inspections.index')
                ->with('success', 'QC inspection created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Failed to create QC inspection: ' . $e->getMessage());
        }
    }

    /**
     * Show QC inspection details
     */
    public function show($qcCode)
    {
        $qc = DB::table('quality_control as qc')
            ->join('products as p', 'qc.product_id', '=', 'p.product_id')
            ->join('employees as e', 'qc.inspector_id', '=', 'e.employee_id')
            ->join('departments as d', 'e.department_id', '=', 'd.department_id')
            ->join('positions as pos', 'e.position_id', '=', 'pos.position_id')
            ->leftJoin('work_orders as wo', 'qc.work_order_id', '=', 'wo.work_order_id')
            ->leftJoin('batches as b', 'qc.batch_id', '=', 'b.batch_id')
            ->leftJoin('units_of_measure as uom', 'p.uom_id', '=', 'uom.uom_id')
            ->where('qc.qc_code', $qcCode)
            ->select(
                'qc.*',
                'p.product_code',
                'p.product_name',
                'p.product_type',
                'uom.uom_name',
                'uom.uom_code',
                'e.employee_code',
                'e.first_name as inspector_first_name',
                'e.last_name as inspector_last_name',
                'e.email as inspector_email',
                'd.department_name',
                'pos.position_name',
                'wo.work_order_code',
                'wo.quantity_ordered',
                'wo.quantity_produced as wo_quantity_produced',
                'b.batch_code',
                'b.quantity_produced as batch_quantity_produced',
                'b.status as batch_status'
            )
            ->first();

        if (!$qc) {
            abort(404, 'QC inspection not found');
        }

        // Log VIEW
        $this->logView(
            'Quality Control',
            "Viewed QC inspection: {$qc->qc_code}"
        );

        // Get QC Parameters
        $parameters = DB::table('qc_parameters')
            ->where('qc_id', $qc->qc_id)
            ->orderBy('parameter_id')
            ->get();

        return view('quality-control.inspections.show', compact('qc', 'parameters'));
    }

    /**
     * Show edit form
     */
    public function edit($qcCode)
    {
        $qc = DB::table('quality_control')
            ->where('qc_code', $qcCode)
            ->first();

        if (!$qc) {
            abort(404, 'QC inspection not found');
        }

        // Get products
        $products = DB::table('products')
            ->where('is_active', 1)
            ->orderBy('product_name')
            ->get(['product_id', 'product_code', 'product_name']);

        // Get inspectors
        $inspectors = DB::table('employees')
            ->where('employment_status', 'Active')
            ->orderBy('first_name')
            ->get(['employee_id', 'employee_code', 'first_name', 'last_name']);

        // Get work orders
        $workOrders = DB::table('work_orders as wo')
            ->join('products as p', 'wo.product_id', '=', 'p.product_id')
            ->whereIn('wo.status', ['In Progress', 'Completed'])
            ->orderByDesc('wo.created_at')
            ->limit(50)
            ->get([
                'wo.work_order_id',
                'wo.work_order_code',
                'wo.quantity_produced',
                'p.product_name'
            ]);

        // Get batches
        $batches = DB::table('batches as b')
            ->join('products as p', 'b.product_id', '=', 'p.product_id')
            ->whereIn('b.status', ['Pending QC', 'In QC', 'Passed', 'Failed'])
            ->orderByDesc('b.created_at')
            ->limit(50)
            ->get([
                'b.batch_id',
                'b.batch_code',
                'b.quantity_produced',
                'b.work_order_id',
                'p.product_name'
            ]);

        // Get existing parameters
        $parameters = DB::table('qc_parameters')
            ->where('qc_id', $qc->qc_id)
            ->get();

        return view('quality-control.inspections.edit', compact(
            'qc',
            'products',
            'inspectors',
            'workOrders',
            'batches',
            'parameters'
        ));
    }

    /**
     * Update QC inspection
     */
    public function update(Request $request, $qcCode)
    {
        $qc = DB::table('quality_control')
            ->where('qc_code', $qcCode)
            ->first();

        if (!$qc) {
            abort(404, 'QC inspection not found');
        }

        $validated = $request->validate([
            'inspection_type' => ['required', 'string', 'in:Incoming,In-Process,Final,Random'],
            'product_id' => ['required', 'exists:products,product_id'],
            'work_order_id' => ['nullable', 'exists:work_orders,work_order_id'],
            'batch_id' => ['nullable', 'exists:batches,batch_id'],
            'inspection_date' => ['required', 'date'],
            'inspector_id' => ['required', 'exists:employees,employee_id'],
            'quantity_inspected' => ['required', 'numeric', 'min:0.0001'],
            'quantity_passed' => ['required', 'numeric', 'min:0'],
            'quantity_failed' => ['required', 'numeric', 'min:0'],
            'result' => ['required', 'string', 'in:Passed,Failed,Conditional'],
            'remarks' => ['nullable', 'string'],
            
            // QC Parameters
            'parameters' => ['nullable', 'array'],
            'parameters.*.parameter_name' => ['required_with:parameters', 'string', 'max:150'],
            'parameters.*.standard_value' => ['nullable', 'string', 'max:100'],
            'parameters.*.actual_value' => ['nullable', 'string', 'max:100'],
            'parameters.*.unit' => ['nullable', 'string', 'max:50'],
            'parameters.*.is_passed' => ['required_with:parameters', 'boolean'],
        ], [
            'inspection_type.required' => 'Please select an inspection type.',
            'inspection_type.in' => 'Invalid inspection type selected.',
            
            'product_id.required' => 'Please select a product.',
            'product_id.exists' => 'Selected product is invalid.',
            
            'work_order_id.exists' => 'Selected work order is invalid.',
            'batch_id.exists' => 'Selected batch is invalid.',
            
            'inspection_date.required' => 'Inspection date is required.',
            'inspection_date.date' => 'Invalid inspection date format.',
            
            'inspector_id.required' => 'Please select an inspector.',
            'inspector_id.exists' => 'Selected inspector is invalid.',
            
            'quantity_inspected.required' => 'Quantity inspected is required.',
            'quantity_inspected.numeric' => 'Quantity inspected must be a number.',
            'quantity_inspected.min' => 'Quantity inspected must be greater than zero.',
            
            'quantity_passed.required' => 'Quantity passed is required.',
            'quantity_passed.numeric' => 'Quantity passed must be a number.',
            'quantity_passed.min' => 'Quantity passed cannot be negative.',
            
            'quantity_failed.required' => 'Quantity failed is required.',
            'quantity_failed.numeric' => 'Quantity failed must be a number.',
            'quantity_failed.min' => 'Quantity failed cannot be negative.',
            
            'result.required' => 'Please select inspection result.',
            'result.in' => 'Invalid inspection result.',
            
            'parameters.array' => 'Invalid parameters format.',
            'parameters.*.parameter_name.required_with' => 'Parameter name is required.',
            'parameters.*.parameter_name.max' => 'Parameter name is too long.',
            'parameters.*.is_passed.required_with' => 'Parameter result is required.',
        ]);

        // Validate quantities
        $totalQuantity = $validated['quantity_passed'] + $validated['quantity_failed'];
        if ($totalQuantity != $validated['quantity_inspected']) {
            return back()
                ->withInput()
                ->with('error', 'Total of passed and failed quantities must equal inspected quantity.');
        }

        DB::beginTransaction();
        try {
            // Capture old data
            $oldParameters = DB::table('qc_parameters')
                ->where('qc_id', $qc->qc_id)
                ->count();

            $oldData = [
                'inspection_type' => $qc->inspection_type,
                'product_id' => $qc->product_id,
                'work_order_id' => $qc->work_order_id,
                'batch_id' => $qc->batch_id,
                'inspection_date' => $qc->inspection_date,
                'inspector_id' => $qc->inspector_id,
                'quantity_inspected' => $qc->quantity_inspected,
                'quantity_passed' => $qc->quantity_passed,
                'quantity_failed' => $qc->quantity_failed,
                'result' => $qc->result,
                'parameters_count' => $oldParameters,
            ];

            // Update QC Inspection
            DB::table('quality_control')
                ->where('qc_id', $qc->qc_id)
                ->update([
                    'inspection_type' => $validated['inspection_type'],
                    'work_order_id' => $validated['work_order_id'] ?? null,
                    'batch_id' => $validated['batch_id'] ?? null,
                    'product_id' => $validated['product_id'],
                    'inspection_date' => $validated['inspection_date'],
                    'inspector_id' => $validated['inspector_id'],
                    'result' => $validated['result'],
                    'quantity_inspected' => $validated['quantity_inspected'],
                    'quantity_passed' => $validated['quantity_passed'],
                    'quantity_failed' => $validated['quantity_failed'],
                    'remarks' => $validated['remarks'] ?? null,
                    'updated_at' => now(),
                ]);

            // Sync QC Parameters
            DB::table('qc_parameters')->where('qc_id', $qc->qc_id)->delete();

            if (!empty($validated['parameters'])) {
                $parameterData = [];
                foreach ($validated['parameters'] as $param) {
                    $parameterData[] = [
                        'qc_id' => $qc->qc_id,
                        'parameter_name' => $param['parameter_name'],
                        'standard_value' => $param['standard_value'] ?? null,
                        'actual_value' => $param['actual_value'] ?? null,
                        'unit' => $param['unit'] ?? null,
                        'is_passed' => $param['is_passed'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                DB::table('qc_parameters')->insert($parameterData);
            }

            // Update batch status if batch is linked
            if ($validated['batch_id']) {
                $batchStatus = match($validated['result']) {
                    'Passed' => 'Passed',
                    'Failed' => 'Failed',
                    'Conditional' => 'In QC',
                    default => 'In QC'
                };

                DB::table('batches')
                    ->where('batch_id', $validated['batch_id'])
                    ->update([
                        'quantity_approved' => $validated['result'] === 'Passed' 
                            ? $validated['quantity_passed'] 
                            : 0,
                        'quantity_rejected' => $validated['result'] === 'Failed' 
                            ? $validated['quantity_failed'] 
                            : 0,
                        'status' => $batchStatus,
                        'updated_at' => now(),
                    ]);
            }

            // Log UPDATE
            $this->logUpdate(
                'Quality Control',
                'quality_control',
                $qc->qc_id,
                $oldData,
                [
                    'inspection_type' => $validated['inspection_type'],
                    'product_id' => $validated['product_id'],
                    'work_order_id' => $validated['work_order_id'] ?? null,
                    'batch_id' => $validated['batch_id'] ?? null,
                    'inspection_date' => $validated['inspection_date'],
                    'inspector_id' => $validated['inspector_id'],
                    'quantity_inspected' => $validated['quantity_inspected'],
                    'quantity_passed' => $validated['quantity_passed'],
                    'quantity_failed' => $validated['quantity_failed'],
                    'result' => $validated['result'],
                    'parameters_count' => count($validated['parameters'] ?? []),
                ]
            );

            DB::commit();

            return redirect()
                ->route('quality-control.inspections.index')
                ->with('success', 'QC inspection updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Failed to update QC inspection: ' . $e->getMessage());
        }
    }

    /**
     * Delete QC inspection
     */
    public function destroy($qcCode)
    {
        $qc = DB::table('quality_control')
            ->where('qc_code', $qcCode)
            ->first();

        if (!$qc) {
            abort(404, 'QC inspection not found');
        }

        DB::beginTransaction();
        try {
            // Capture old data
            $parametersCount = DB::table('qc_parameters')
                ->where('qc_id', $qc->qc_id)
                ->count();

            $oldData = [
                'qc_code' => $qc->qc_code,
                'inspection_type' => $qc->inspection_type,
                'product_id' => $qc->product_id,
                'inspection_date' => $qc->inspection_date,
                'result' => $qc->result,
                'quantity_inspected' => $qc->quantity_inspected,
                'parameters_count' => $parametersCount,
            ];

            // Delete QC parameters
            DB::table('qc_parameters')->where('qc_id', $qc->qc_id)->delete();

            // Reset batch status if linked
            if ($qc->batch_id) {
                DB::table('batches')
                    ->where('batch_id', $qc->batch_id)
                    ->update([
                        'quantity_approved' => 0,
                        'quantity_rejected' => 0,
                        'status' => 'Pending QC',
                        'updated_at' => now(),
                    ]);
            }

            // Delete QC inspection
            DB::table('quality_control')->where('qc_id', $qc->qc_id)->delete();

            // Log DELETE
            $this->logDelete(
                'Quality Control',
                'quality_control',
                $qc->qc_id,
                $oldData
            );

            DB::commit();

            return redirect()
                ->route('quality-control.inspections.index')
                ->with('success', 'QC inspection deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->with('error', 'Failed to delete QC inspection: ' . $e->getMessage());
        }
    }

    /**
     * Approve batch based on QC result
     */
    public function approveBatch($qcCode)
    {
        $qc = DB::table('quality_control')
            ->where('qc_code', $qcCode)
            ->first();
        if (!$qc) {
            abort(404, 'QC inspection not found');
        }

        if (!$qc->batch_id) {
            return back()->with('error', 'No batch linked to this QC inspection.');
        }

        if ($qc->result !== 'Passed') {
            return back()->with('error', 'Only passed QC inspections can approve batches.');
        }

        DB::beginTransaction();
        try {
            // Update batch status to Passed
            DB::table('batches')
                ->where('batch_id', $qc->batch_id)
                ->update([
                    'status' => 'Passed',
                    'quantity_approved' => $qc->quantity_passed,
                    'quantity_rejected' => $qc->quantity_failed,
                    'updated_at' => now(),
                ]);

            // Log approval
            $this->logApproval(
                'Quality Control',
                'batches',
                $qc->batch_id,
                'approved',
                "Batch approved based on QC inspection {$qc->qc_code}"
            );

            DB::commit();

            return back()->with('success', 'Batch approved successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to approve batch: ' . $e->getMessage());
        }
    }

    /**
     * Reject batch based on QC result
     */
    public function rejectBatch($qcCode)
    {
        $qc = DB::table('quality_control')
            ->where('qc_code', $qcCode)
            ->first();

        if (!$qc) {
            abort(404, 'QC inspection not found');
        }

        if (!$qc->batch_id) {
            return back()->with('error', 'No batch linked to this QC inspection.');
        }

        if ($qc->result !== 'Failed') {
            return back()->with('error', 'Only failed QC inspections can reject batches.');
        }

        DB::beginTransaction();
        try {
            // Update batch status to Failed
            DB::table('batches')
                ->where('batch_id', $qc->batch_id)
                ->update([
                    'status' => 'Failed',
                    'quantity_approved' => 0,
                    'quantity_rejected' => $qc->quantity_failed,
                    'updated_at' => now(),
                ]);

            // Log rejection
            $this->logApproval(
                'Quality Control',
                'batches',
                $qc->batch_id,
                'rejected',
                "Batch rejected based on QC inspection {$qc->qc_code}"
            );

            DB::commit();

            return back()->with('success', 'Batch rejected successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to reject batch: ' . $e->getMessage());
        }
    }

    /**
     * Export QC inspections to CSV
     */
    public function export(Request $request)
    {
        $this->logExport('Quality Control', 'Exported QC inspections to CSV');

        $query = DB::table('quality_control as qc')
            ->join('products as p', 'qc.product_id', '=', 'p.product_id')
            ->join('employees as e', 'qc.inspector_id', '=', 'e.employee_id')
            ->leftJoin('work_orders as wo', 'qc.work_order_id', '=', 'wo.work_order_id')
            ->leftJoin('batches as b', 'qc.batch_id', '=', 'b.batch_id')
            ->select(
                'qc.qc_code',
                'qc.inspection_type',
                'qc.inspection_date',
                'p.product_code',
                'p.product_name',
                'wo.work_order_code',
                'b.batch_code',
                'qc.quantity_inspected',
                'qc.quantity_passed',
                'qc.quantity_failed',
                'qc.result',
                DB::raw("CONCAT(e.first_name, ' ', e.last_name) as inspector_name"),
                'qc.remarks',
                'qc.created_at'
            );

        // Apply filters
        if ($request->filled('inspection_type')) {
            $query->where('qc.inspection_type', $request->inspection_type);
        }
        if ($request->filled('result')) {
            $query->where('qc.result', $request->result);
        }
        if ($request->filled('product_id')) {
            $query->where('qc.product_id', $request->product_id);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('qc.inspection_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('qc.inspection_date', '<=', $request->date_to);
        }

        $inspections = $query->orderByDesc('qc.inspection_date')
            ->limit(5000)
            ->get();

        $filename = 'qc_inspections_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($inspections) {
            $file = fopen('php://output', 'w');

            // Header
            fputcsv($file, [
                'QC Code',
                'Inspection Type',
                'Inspection Date',
                'Product Code',
                'Product Name',
                'Work Order',
                'Batch',
                'Qty Inspected',
                'Qty Passed',
                'Qty Failed',
                'Result',
                'Inspector',
                'Remarks',
                'Created At'
            ]);

            // Data
            foreach ($inspections as $qc) {
                fputcsv($file, [
                    $qc->qc_code,
                    $qc->inspection_type,
                    $qc->inspection_date,
                    $qc->product_code,
                    $qc->product_name,
                    $qc->work_order_code ?? '-',
                    $qc->batch_code ?? '-',
                    $qc->quantity_inspected,
                    $qc->quantity_passed,
                    $qc->quantity_failed,
                    $qc->result,
                    $qc->inspector_name,
                    $qc->remarks ?? '-',
                    $qc->created_at,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Print QC inspection report
     */
    public function print($qcCode)
    {
        $qc = DB::table('quality_control as qc')
            ->join('products as p', 'qc.product_id', '=', 'p.product_id')
            ->join('employees as e', 'qc.inspector_id', '=', 'e.employee_id')
            ->leftJoin('work_orders as wo', 'qc.work_order_id', '=', 'wo.work_order_id')
            ->leftJoin('batches as b', 'qc.batch_id', '=', 'b.batch_id')
            ->leftJoin('units_of_measure as uom', 'p.uom_id', '=', 'uom.uom_id')
            ->where('qc.qc_code', $qcCode)
            ->select(
                'qc.*',
                'p.product_code',
                'p.product_name',
                'uom.uom_name',
                'e.first_name as inspector_first_name',
                'e.last_name as inspector_last_name',
                'wo.work_order_code',
                'b.batch_code'
            )
            ->first();

        if (!$qc) {
            abort(404, 'QC inspection not found');
        }

        // Get parameters
        $parameters = DB::table('qc_parameters')
            ->where('qc_id', $qc->qc_id)
            ->get();

        // Get company profile
        $company = DB::table('company_profile')->first();

        // Log PRINT
        $this->logPrint('Quality Control', "Printed QC inspection report: {$qc->qc_code}");

        return view('quality-control.inspections.print', compact('qc', 'parameters', 'company'));
    }
}
