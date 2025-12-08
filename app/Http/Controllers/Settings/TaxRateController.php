<?php

namespace App\Http\Controllers\Settings;

use App\Helpers\CodeGeneratorHelper;
use App\Http\Controllers\Controller;
use App\LogsActivity;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TaxRateController extends Controller
{
    use LogsActivity;

    //
    /**
     * List tax rates
     */
    public function index()
    {
        $taxRates = DB::table('tax_rates')
            ->orderBy('is_active', 'desc')
            ->orderBy('tax_type')
            ->orderBy('tax_name')
            ->get();
        
        return view('admin.settings.tax-rates.index', compact('taxRates'));
    }

    /**
     * Create form
     */
    public function create()
    {
        return view('admin.settings.tax-rates.create');
    }

    /**
     * Store tax rate
     */
    public function store(Request $request)
    {
        if ($request->filled('flatpick-date')) {
            $effectiveDate = Carbon::parse($request->input('flatpick-date'));
            $request->merge([
                'effective_date' => $effectiveDate
            ]);
        }

        $validated = $request->validate([
            'tax_name' => ['required', 'string', 'max:100', 'unique:tax_rates,tax_name'],
            'tax_percentage' => ['required', 'numeric', 'min:0', 'max:100'],
            'tax_type' => ['required', 'string', 'max:30'],
            'effective_date' => ['required', 'date'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'tax_name.required' => 'Tax name is required.',
            'tax_name.unique' => 'This tax name already exists.',
            'tax_percentage.required' => 'Tax percentage is required.',
            'tax_percentage.min' => 'Tax percentage cannot be negative.',
            'tax_percentage.max' => 'Tax percentage cannot exceed 100%.',
            'tax_type.required' => 'Tax type is required.',
            'effective_date.required' => 'Effective date is required.',
            'effective_date.date' => 'Please provide a valid date.',
        ]);

        DB::beginTransaction();
        try {
            $taxCode = CodeGeneratorHelper::generateTaxRateCode();

            $taxRateId = DB::table('tax_rates')->insertGetId([
                'tax_code' => $taxCode,
                'tax_name' => $validated['tax_name'],
                'tax_percentage' => $validated['tax_percentage'],
                'tax_type' => $validated['tax_type'],
                'effective_date' => $validated['effective_date'],
                'is_active' => $request->boolean('is_active'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Log CREATE
            $this->logCreate('Settings - Tax Rates', 'tax_rates', $taxRateId, $validated);

            DB::commit();
            
            return redirect()
                ->route('admin.settings.tax-rates.index')
                ->with('success', 'Tax rate created successfully');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Failed to create tax rate: ' . $e->getMessage());
        }
    }

    /**
     * Show tax rate details
     */
    public function show($taxCode)
    {
        $taxRate = DB::table('tax_rates')
            ->where('tax_code', $taxCode)
            ->first();
        
        if (!$taxRate) {
            abort(404, 'Tax rate not found');
        }

        // Log VIEW
        $this->logView(
            'Setting - Tax Rate',
            "Viewed tax rate: {$taxRate->tax_name} (Code: {$taxCode})"
        );
        
        return view('admin.settings.tax-rates.show', compact('taxRate'));
    }

    /**
     * Edit tax rate
     */
    public function edit($taxCode)
    {
        $taxRate = DB::table('tax_rates')
            ->where('tax_code', $taxCode)
            ->first();
        
        if (!$taxRate) {
            abort(404, 'Tax rate not found');
        }
        
        return view('admin.settings.tax-rates.edit', compact('taxRate'));
    }

    /**
     * Update tax rate
     */
    public function update(Request $request, $taxCode)
    {
        $taxRate = DB::table('tax_rates')
            ->where('tax_code', $taxCode)
            ->first();
        
        if (!$taxRate) {
            abort(404, 'Tax rate not found');
        }

        if ($request->filled('flatpick-date')) {
            $effectiveDate = Carbon::parse($request->input('flatpick-date'));
            $request->merge([
                'effective_date' => $effectiveDate
            ]);
        }

        $validated = $request->validate([
            'tax_name' => ['required', 'string', 'max:100', 'unique:tax_rates,tax_name,' . $taxRate->tax_id . ',tax_id'],
            'tax_percentage' => ['required', 'numeric', 'min:0', 'max:100'],
            'tax_type' => ['required', 'string', 'max:30'],
            'effective_date' => ['required', 'date'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'tax_name.required' => 'Tax name is required.',
            'tax_name.unique' => 'This tax name already exists.',
            'tax_percentage.required' => 'Tax percentage is required.',
            'tax_percentage.min' => 'Tax percentage cannot be negative.',
            'tax_percentage.max' => 'Tax percentage cannot exceed 100%.',
            'tax_type.required' => 'Tax type is required.',
            'effective_date.required' => 'Effective date is required.',
        ]);

        DB::beginTransaction();
        try {
            // Capture old data
            $oldData = [
                'tax_name' => $taxRate->tax_name,
                'tax_percentage' => $taxRate->tax_percentage,
                'tax_type' => $taxRate->tax_type,
                'effective_date' => $taxRate->effective_date,
                'is_active' => $taxRate->is_active,
                'updated_at' => $taxRate->updated_at,
            ];

            DB::table('tax_rates')
                ->where('tax_id', $taxRate->tax_id)
                ->update([
                    'tax_name' => $validated['tax_name'],
                    'tax_percentage' => $validated['tax_percentage'],
                    'tax_type' => $validated['tax_type'],
                    'effective_date' => $validated['effective_date'],
                    'is_active' => $request->boolean('is_active'),
                    'updated_at' => now(),
                ]);

            // Log UPDATE
            $this->logUpdate('Settings - Tax Rates', 'tax_rates', $taxRate->tax_id, $oldData, $validated);

            DB::commit();
            
            return redirect()
                ->route('admin.settings.tax-rates.index')
                ->with('success', 'Tax rate updated successfully');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Failed to update tax rate: ' . $e->getMessage());
        }
    }

    /**
     * Delete tax rate
     */
    public function destroy($taxCode)
    {
        $taxRate = DB::table('tax_rates')
            ->where('tax_code', $taxCode)
            ->first();
        
        if (!$taxRate) {
            abort(404, 'Tax rate not found');
        }

        DB::beginTransaction();
        try {
            DB::table('tax_rates')
                ->where('tax_id', $taxRate->tax_id)
                ->delete();

            DB::commit();
            
            return redirect()
                ->route('admin.settings.tax-rates.index')
                ->with('success', 'Tax rate deleted successfully');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete tax rate: ' . $e->getMessage());
        }
    }

    /**
     * Toggle tax rate status
     */
    public function toggleStatus($taxCode)
    {
        $taxRate = DB::table('tax_rates')
            ->where('tax_code', $taxCode)
            ->first();
        
        if (!$taxRate) {
            abort(404, 'Tax rate not found');
        }

        DB::beginTransaction();
        try {
            DB::table('tax_rates')
                ->where('tax_id', $taxRate->tax_id)
                ->update([
                    'is_active' => !$taxRate->is_active,
                    'updated_at' => now(),
                ]);

            DB::commit();
            
            $status = !$taxRate->is_active ? 'activated' : 'deactivated';
            return back()->with('success', "Tax rate {$status} successfully");
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update tax rate status: ' . $e->getMessage());
        }
    }
}