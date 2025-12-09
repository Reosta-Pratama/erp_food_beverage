<?php

namespace App\Http\Controllers\Production;

use App\Helpers\CodeGeneratorHelper;
use App\Http\Controllers\Controller;
use App\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductionPlanningController extends Controller
{
    //
    use LogsActivity;
    
    /**
     * Display a listing of production plans
     */
    public function index(Request $request)
    {
        $query = DB::table('production_planning as pp')
            ->leftJoin('users as u', 'pp.created_by', '=', 'u.user_id')
            ->select(
                'pp.plan_id',
                'pp.plan_code',
                'pp.plan_date',
                'pp.start_date',
                'pp.end_date',
                'pp.status',
                'pp.created_at',
                'u.full_name as created_by_name',
                DB::raw('(SELECT COUNT(*) FROM planned_productions WHERE plan_id = pp.plan_id) as products_count'),
                DB::raw('DATEDIFF(pp.end_date, pp.start_date) as duration_days')
            );
        
        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('pp.plan_code', 'like', "%{$search}%")
                  ->orWhere('u.full_name', 'like', "%{$search}%");
            });
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('pp.status', $request->status);
        }
        
        // Filter by date range
        if ($request->filled('start_date')) {
            $query->where('pp.start_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->where('pp.end_date', '<=', $request->end_date);
        }

        // Filter by created by
        if ($request->filled('created_by')) {
            $query->where('pp.created_by', $request->created_by);
        }

        // Sorting
        $sortField = $request->get('sort', 'pp.created_at');
        $sortDirection = $request->get('direction', 'desc');
        
        // Validate sort field
        $allowedSorts = ['pp.plan_code', 'pp.plan_date', 'pp.start_date', 'pp.end_date', 'pp.status', 'pp.created_at', 'duration_days', 'products_count'];
        if (!in_array($sortField, $allowedSorts)) {
            $sortField = 'pp.created_at';
        }

        // Validate sort direction
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'desc';
        }

        
        $plans = $query->paginate(20)->withQueryString();
        
        // Get users for filter
        $users = DB::table('users')
            ->whereIn('user_id', function($query) {
                $query->select('created_by')
                    ->from('production_planning')
                    ->distinct();
            })
            ->select('user_id', 'full_name')
            ->orderBy('full_name')
            ->get();

            // Get statistics
        $stats = [
            'total' => DB::table('production_planning')->count(),
            'draft' => DB::table('production_planning')->where('status', 'Draft')->count(),
            'approved' => DB::table('production_planning')->where('status', 'Approved')->count(),
            'in_progress' => DB::table('production_planning')->where('status', 'In Progress')->count(),
            'completed' => DB::table('production_planning')->where('status', 'Completed')->count(),
        ];
        
        return view('production.planning.index', compact('plans', 'users', 'stats'));
    }

    /**
     * Show the form for creating a new production plan
     */
    public function create()
    {
        // Get finished goods products with BOM
        $products = DB::table('products as p')
            ->join('product_categories as pc', 'p.category_id', '=', 'pc.category_id')
            ->join('units_of_measure as uom', 'p.uom_id', '=', 'uom.uom_id')
            ->leftJoin('bill_of_materials as bom', function($join) {
                $join->on('p.product_id', '=', 'bom.product_id')
                     ->where('bom.is_active', 1);
            })
            ->where('p.product_type', 'Finished Goods')
            ->where('p.is_active', 1)
            ->select(
                'p.product_id',
                'p.product_code',
                'p.product_name',
                'p.standard_cost',
                'pc.category_name',
                'uom.uom_id',
                'uom.uom_code',
                'uom.uom_name',
                DB::raw('COUNT(DISTINCT bom.bom_id) as bom_count')
            )
            ->groupBy('p.product_id', 'p.product_code', 'p.product_name', 'p.standard_cost', 
                     'pc.category_name', 'uom.uom_id', 'uom.uom_code', 'uom.uom_name')
            ->orderBy('p.product_name')
            ->get();
        
        $uoms = DB::table('units_of_measure')
            ->select('uom_id', 'uom_code', 'uom_name', 'uom_type')
            ->orderBy('uom_name')
            ->get();
        
        return view('production.planning.create', compact('products', 'uoms'));
    }

    /**
     * Store a newly created production plan
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'plan_date' => ['required', 'date'],
            'start_date' => ['required', 'date', 'after_or_equal:plan_date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'notes' => ['nullable', 'string'],
            
            // Planned Productions
            'productions' => ['required', 'array', 'min:1'],
            'productions.*.product_id' => ['required', 'exists:products,product_id'],
            'productions.*.planned_quantity' => ['required', 'numeric', 'min:0.0001'],
            'productions.*.uom_id' => ['required', 'exists:units_of_measure,uom_id'],
            'productions.*.target_date' => ['required', 'date', 'after_or_equal:' . $request->start_date, 'before_or_equal:' . $request->end_date],
            'productions.*.priority' => ['required', 'in:Low,Medium,High,Urgent'],
        ], [
            'plan_date.required' => 'Plan date is required.',
            'plan_date.date' => 'Please provide a valid plan date.',
            
            'start_date.required' => 'Start date is required.',
            'start_date.after_or_equal' => 'Start date must be on or after plan date.',
            
            'end_date.required' => 'End date is required.',
            'end_date.after_or_equal' => 'End date must be on or after start date.',
            
            'productions.required' => 'Production plan must have at least one product.',
            'productions.min' => 'Production plan must have at least one product.',
            
            'productions.*.product_id.required' => 'Product is required for each item.',
            'productions.*.product_id.exists' => 'Selected product is invalid.',
            
            'productions.*.planned_quantity.required' => 'Planned quantity is required.',
            'productions.*.planned_quantity.numeric' => 'Quantity must be a number.',
            'productions.*.planned_quantity.min' => 'Quantity must be greater than 0.',
            
            'productions.*.uom_id.required' => 'Unit of measure is required.',
            'productions.*.uom_id.exists' => 'Selected unit is invalid.',
            
            'productions.*.target_date.required' => 'Target date is required.',
            'productions.*.target_date.after_or_equal' => 'Target date must be within plan period.',
            'productions.*.target_date.before_or_equal' => 'Target date must be within plan period.',
            
            'productions.*.priority.required' => 'Priority is required.',
            'productions.*.priority.in' => 'Invalid priority selected.',
        ]);
        
        DB::beginTransaction();
        try {
            // Generate unique plan code
            $planCode = CodeGeneratorHelper::generatePlanCode();
            
            // Insert Production Plan
            $planId = DB::table('production_planning')->insertGetId([
                'plan_code' => $planCode,
                'plan_date' => $validated['plan_date'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'status' => 'Draft',
                'created_by' => Auth::id(),
                'notes' => $validated['notes'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            // Insert Planned Productions
            $productionsData = [];
            foreach ($validated['productions'] as $production) {
                $productionsData[] = [
                    'plan_id' => $planId,
                    'product_id' => $production['product_id'],
                    'planned_quantity' => $production['planned_quantity'],
                    'uom_id' => $production['uom_id'],
                    'target_date' => $production['target_date'],
                    'priority' => $production['priority'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            
            DB::table('planned_productions')->insert($productionsData);
            
            // Log CREATE
            $this->logCreate(
                'Production - Planning',
                'production_planning',
                $planId,
                [
                    'plan_code' => $planCode,
                    'plan_date' => $validated['plan_date'],
                    'start_date' => $validated['start_date'],
                    'end_date' => $validated['end_date'],
                    'duration_days' => now()->parse($validated['end_date'])->diffInDays($validated['start_date']),
                    'status' => 'Draft',
                    'products_count' => count($validated['productions']),
                    'created_by' => Auth::user()->full_name,
                ]
            );
            
            DB::commit();
            
            return redirect()
                ->route('production.planning.show', $planCode)
                ->with('success', 'Production plan created successfully');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Failed to create production plan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified production plan
     */
    public function show($planCode)
    {
        $plan = DB::table('production_planning as pp')
            ->leftJoin('users as u', 'pp.created_by', '=', 'u.user_id')
            ->where('pp.plan_code', $planCode)
            ->select(
                'pp.*',
                'u.full_name as created_by_name',
                'u.email as created_by_email',
                DB::raw('DATEDIFF(pp.end_date, pp.start_date) as duration_days')
            )
            ->first();
        
        if (!$plan) {
            abort(404, 'Production plan not found');
        }
        
        // Log VIEW
        $this->logView(
            'Production - Planning',
            "Viewed production plan: {$plan->plan_code} ({$plan->start_date} to {$plan->end_date})"
        );
        
        // Get planned productions with product details
        $productions = DB::table('planned_productions as plp')
            ->join('products as p', 'plp.product_id', '=', 'p.product_id')
            ->join('units_of_measure as uom', 'plp.uom_id', '=', 'uom.uom_id')
            ->leftJoin('bill_of_materials as bom', function($join) {
                $join->on('p.product_id', '=', 'bom.product_id')
                     ->where('bom.is_active', 1);
            })
            ->where('plp.plan_id', $plan->plan_id)
            ->select(
                'plp.*',
                'p.product_code',
                'p.product_name',
                'p.product_type',
                'p.standard_cost',
                'uom.uom_code',
                'uom.uom_name',
                'bom.bom_id',
                'bom.bom_code',
                DB::raw('(plp.planned_quantity * p.standard_cost) as estimated_cost')
            )
            ->orderBy('plp.priority', 'desc')
            ->orderBy('plp.target_date')
            ->get();
        
        // Calculate total estimated cost
        $totalEstimatedCost = $productions->sum('estimated_cost');
        
        // Get work orders created from this plan
        $workOrders = DB::table('work_orders as wo')
            ->join('products as p', 'wo.product_id', '=', 'p.product_id')
            ->whereIn('wo.product_id', $productions->pluck('product_id'))
            ->whereBetween('wo.scheduled_start', [$plan->start_date, $plan->end_date])
            ->select(
                'wo.work_order_id',
                'wo.work_order_code',
                'wo.quantity_ordered',
                'wo.quantity_produced',
                'wo.status',
                'p.product_name'
            )
            ->orderBy('wo.scheduled_start')
            ->get();
        
        return view('production.planning.show', compact('plan', 'productions', 'totalEstimatedCost', 'workOrders'));
    }

    /**
     * Show the form for editing the specified production plan
     */
    public function edit($planCode)
    {
        $plan = DB::table('production_planning')
            ->where('plan_code', $planCode)
            ->first();
        
        if (!$plan) {
            abort(404, 'Production plan not found');
        }
        
        // Only draft plans can be edited
        if ($plan->status !== 'Draft') {
            return back()->with('error', 'Only draft plans can be edited');
        }
        
        $products = DB::table('products as p')
            ->join('product_categories as pc', 'p.category_id', '=', 'pc.category_id')
            ->join('units_of_measure as uom', 'p.uom_id', '=', 'uom.uom_id')
            ->leftJoin('bill_of_materials as bom', function($join) {
                $join->on('p.product_id', '=', 'bom.product_id')
                     ->where('bom.is_active', 1);
            })
            ->where('p.product_type', 'Finished Goods')
            ->where('p.is_active', 1)
            ->select(
                'p.product_id',
                'p.product_code',
                'p.product_name',
                'p.standard_cost',
                'pc.category_name',
                'uom.uom_id',
                'uom.uom_code',
                'uom.uom_name',
                DB::raw('COUNT(DISTINCT bom.bom_id) as bom_count')
            )
            ->groupBy('p.product_id', 'p.product_code', 'p.product_name', 'p.standard_cost',
                     'pc.category_name', 'uom.uom_id', 'uom.uom_code', 'uom.uom_name')
            ->orderBy('p.product_name')
            ->get();
        
        $uoms = DB::table('units_of_measure')
            ->select('uom_id', 'uom_code', 'uom_name', 'uom_type')
            ->orderBy('uom_name')
            ->get();
        
        // Get existing planned productions
        $plannedProductions = DB::table('planned_productions')
            ->where('plan_id', $plan->plan_id)
            ->get();
        
        return view('production.planning.edit', compact('plan', 'products', 'uoms', 'plannedProductions'));
    }

    /**
     * Update the specified production plan
     */
    public function update(Request $request, $planCode)
    {
        $plan = DB::table('production_planning')->where('plan_code', $planCode)->first();
        
        if (!$plan) {
            abort(404, 'Production plan not found');
        }
        
        // Only draft plans can be updated
        if ($plan->status !== 'Draft') {
            return back()->with('error', 'Only draft plans can be updated');
        }
        
        $validated = $request->validate([
            'plan_date' => ['required', 'date'],
            'start_date' => ['required', 'date', 'after_or_equal:plan_date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'notes' => ['nullable', 'string'],
            
            'productions' => ['required', 'array', 'min:1'],
            'productions.*.product_id' => ['required', 'exists:products,product_id'],
            'productions.*.planned_quantity' => ['required', 'numeric', 'min:0.0001'],
            'productions.*.uom_id' => ['required', 'exists:units_of_measure,uom_id'],
            'productions.*.target_date' => ['required', 'date', 'after_or_equal:' . $request->start_date, 'before_or_equal:' . $request->end_date],
            'productions.*.priority' => ['required', 'in:Low,Medium,High,Urgent'],
        ]);
        
        DB::beginTransaction();
        try {
            // Capture OLD data
            $oldPlan = DB::table('production_planning')
                ->where('plan_id', $plan->plan_id)
                ->first();
            
            $oldProductionsCount = DB::table('planned_productions')
                ->where('plan_id', $plan->plan_id)
                ->count();
            
            // Update Production Plan
            DB::table('production_planning')
                ->where('plan_id', $plan->plan_id)
                ->update([
                    'plan_date' => $validated['plan_date'],
                    'start_date' => $validated['start_date'],
                    'end_date' => $validated['end_date'],
                    'notes' => $validated['notes'] ?? null,
                    'updated_at' => now(),
                ]);
            
            // Delete old planned productions and insert new ones
            DB::table('planned_productions')->where('plan_id', $plan->plan_id)->delete();
            
            $productionsData = [];
            foreach ($validated['productions'] as $production) {
                $productionsData[] = [
                    'plan_id' => $plan->plan_id,
                    'product_id' => $production['product_id'],
                    'planned_quantity' => $production['planned_quantity'],
                    'uom_id' => $production['uom_id'],
                    'target_date' => $production['target_date'],
                    'priority' => $production['priority'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            
            DB::table('planned_productions')->insert($productionsData);
            
            // Log UPDATE
            $this->logUpdate(
                'Production - Planning',
                'production_planning',
                $plan->plan_id,
                [
                    'plan_code' => $oldPlan->plan_code,
                    'plan_date' => $oldPlan->plan_date,
                    'start_date' => $oldPlan->start_date,
                    'end_date' => $oldPlan->end_date,
                    'status' => $oldPlan->status,
                    'products_count' => $oldProductionsCount,
                ],
                [
                    'plan_code' => $plan->plan_code,
                    'plan_date' => $validated['plan_date'],
                    'start_date' => $validated['start_date'],
                    'end_date' => $validated['end_date'],
                    'status' => $plan->status,
                    'products_count' => count($validated['productions']),
                ]
            );
            
            DB::commit();
            
            return redirect()
                ->route('production.planning.show', $planCode)
                ->with('success', 'Production plan updated successfully');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Failed to update production plan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified production plan
     */
    public function destroy($planCode)
    {
        $plan = DB::table('production_planning')
            ->where('plan_code', $planCode)
            ->first();
        
        if (!$plan) {
            abort(404, 'Production plan not found');
        }
        
        // Only draft plans can be deleted
        if ($plan->status !== 'Draft') {
            return back()->with('error', 'Only draft plans can be deleted');
        }
        
        DB::beginTransaction();
        try {
            $productionsCount = DB::table('planned_productions')
                ->where('plan_id', $plan->plan_id)
                ->count();
            
            // Delete planned productions first
            DB::table('planned_productions')->where('plan_id', $plan->plan_id)->delete();
            
            // Delete production plan
            DB::table('production_planning')->where('plan_id', $plan->plan_id)->delete();
            
            // Log DELETE
            $this->logDelete(
                'Production - Planning',
                'production_planning',
                $plan->plan_id,
                [
                    'plan_code' => $plan->plan_code,
                    'plan_date' => $plan->plan_date,
                    'start_date' => $plan->start_date,
                    'end_date' => $plan->end_date,
                    'status' => $plan->status,
                    'products_count' => $productionsCount,
                ]
            );
            
            DB::commit();
            
            return redirect()
                ->route('production.planning.index')
                ->with('success', 'Production plan deleted successfully');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete production plan: ' . $e->getMessage());
        }
    }
    
    /**
     * Approve production plan
     */
    public function approve($planCode)
    {
        $plan = DB::table('production_planning')
            ->where('plan_code', $planCode)
            ->first();
        
        if (!$plan) {
            abort(404, 'Production plan not found');
        }
        
        if ($plan->status !== 'Draft') {
            return back()->with('error', 'Only draft plans can be approved');
        }
        
        DB::beginTransaction();
        try {
            DB::table('production_planning')
                ->where('plan_id', $plan->plan_id)
                ->update([
                    'status' => 'Approved',
                    'updated_at' => now(),
                ]);
            
            // Log APPROVAL
            $this->logApproval(
                'Production - Planning',
                'production_planning',
                $plan->plan_id,
                'approved',
                "Production plan {$plan->plan_code} approved for execution"
            );
            
            DB::commit();
            
            return back()->with('success', 'Production plan approved successfully');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to approve plan: ' . $e->getMessage());
        }
    }
    
    /**
     * Export production plan
     */
    public function export()
    {
        $this->logExport('Production - Planning', 'Exported production plans list');
        
        // Your export logic here
    }
}
