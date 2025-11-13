<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UnitOfMeasureController extends Controller
{
    //
    use LogsActivity;
    
    /**
     * List units of measure
     */
    public function index()
    {
        // Log viewing the list
        $this->logView('Settings - Units of Measure', 'Viewed units of measure list');

        $uoms = DB::table('units_of_measure')
            ->leftJoin('products', 'units_of_measure.uom_id', '=', 'products.uom_id')
            ->select(
                'units_of_measure.*',
                DB::raw('COUNT(DISTINCT products.product_id) as products_count')
            )
            ->groupBy(
                'units_of_measure.uom_id',
                'units_of_measure.uom_name',
                'units_of_measure.uom_code',
                'units_of_measure.uom_type',
                'units_of_measure.created_at',
                'units_of_measure.updated_at'
            )
            ->orderBy('units_of_measure.uom_type')
            ->orderBy('units_of_measure.uom_name')
            ->get();
        
        return view('admin.settings.uom.index', compact('uoms'));
    }

    /**
     * Create form
     */
    public function create()
    {
        return view('admin.settings.uom.create');
    }

    /**
     * Store unit of measure
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'uom_name' => ['required', 'string', 'max:100', 'unique:units_of_measure,uom_name'],
            'uom_type' => ['required', 'string', 'max:50'],
        ], [
            'uom_name.required' => 'Unit name is required.',
            'uom_name.unique' => 'This unit name already exists.',
            'uom_type.required' => 'Unit type is required.',
        ]);

        DB::beginTransaction();
        try {
            $uomId = DB::table('units_of_measure')->insertGetId([
                'uom_code' => strtoupper(Str::random(10)),
                'uom_name' => $validated['uom_name'],
                'uom_type' => $validated['uom_type'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Log CREATE action
            $this->logCreate(
                'Settings - Units of Measure',
                'units_of_measure',
                $uomId,
                $validated
            );

            DB::commit();
            
            return redirect()
                ->route('admin.settings.uom.index')
                ->with('success', 'Unit of measure created successfully');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Failed to create unit of measure: ' . $e->getMessage());
        }
    }

    /**
     * Show unit details
     */
    public function show($uomCode)
    {
        $uom = DB::table('units_of_measure')
            ->where('uom_code', $uomCode)
            ->first();
        
        if (!$uom) {
            abort(404, 'Unit of measure not found');
        }

        // Log viewing specific record
        $this->logView(
            'Settings - Units of Measure',
            "Viewed unit of measure: {$uom->uom_name} (Code: {$uomCode})"
        );

        $productsCount = DB::table('products')
            ->where('uom_id', $uom->uom_id)
            ->count();
        
        return view('admin.settings.uom.show', compact('uom', 'productsCount'));
    }

    /**
     * Edit unit of measure
     */
    public function edit($uomCode)
    {
        $uom = DB::table('units_of_measure')
            ->where('uom_code', $uomCode)
            ->first();
        
        if (!$uom) {
            abort(404, 'Unit of measure not found');
        }
        
        return view('admin.settings.uom.edit', compact('uom'));
    }

    /**
     * Update unit of measure
     */
    public function update(Request $request, $uomCode)
    {
        $uom = DB::table('units_of_measure')
            ->where('uom_code', $uomCode)
            ->first();
        
        if (!$uom) {
            abort(404, 'Unit of measure not found');
        }

        $validated = $request->validate([
            'uom_name' => ['required', 'string', 'max:100', 'unique:units_of_measure,uom_name,' . $uom->uom_id . ',uom_id'],
            'uom_type' => ['required', 'string', 'max:50'],
        ]);

        DB::beginTransaction();
        try {
            // Capture old data
            $oldData = [
                'uom_name' => $uom->uom_name,
                'uom_type' => $uom->uom_type,
            ];

            DB::table('units_of_measure')
                ->where('uom_id', $uom->uom_id)
                ->update([
                    'uom_name' => $validated['uom_name'],
                    'uom_type' => $validated['uom_type'],
                    'updated_at' => now(),
                ]);

            // Log UPDATE action
            $this->logUpdate(
                'Settings - Units of Measure',
                'units_of_measure',
                $uom->uom_id,
                $oldData,
                $validated
            );

            DB::commit();
            
            return redirect()
                ->route('admin.settings.uom.index')
                ->with('success', 'Unit of measure updated successfully');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Failed to update unit of measure: ' . $e->getMessage());
        }
    }

    /**
     * Delete unit of measure
     */
    public function destroy($uomCode)
    {
        $uom = DB::table('units_of_measure')
            ->where('uom_code', $uomCode)
            ->first();
        
        if (!$uom) {
            abort(404, 'Unit of measure not found');
        }

        // Check if used by products
        $productsCount = DB::table('products')
            ->where('uom_id', $uom->uom_id)
            ->count();

        if ($productsCount > 0) {
            return back()->with('error', 'Cannot delete unit of measure that is being used by products');
        }

        DB::beginTransaction();
        try {
            // Capture data before deletion
            $oldData = [
                'uom_name' => $uom->uom_name,
                'uom_type' => $uom->uom_type,
                'uom_code' => $uom->uom_code,
            ];

            DB::table('units_of_measure')
                ->where('uom_id', $uom->uom_id)
                ->delete();

            // Log DELETE action
            $this->logDelete(
                'Settings - Units of Measure',
                'units_of_measure',
                $uom->uom_id,
                $oldData
            );

            DB::commit();
            
            return redirect()
                ->route('admin.settings.uom.index')
                ->with('success', 'Unit of measure deleted successfully');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete unit of measure: ' . $e->getMessage());
        }
    }
}
