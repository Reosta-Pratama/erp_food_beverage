<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CurrencyController extends Controller
{
    //
    use LogsActivity;
    
    /**
     * List currencies
     */
    public function index()
    {
        $this->logView('Settings - Currencies', 'Viewed currencies list');
        
        $currencies = DB::table('currencies')
            ->orderByDesc('is_base_currency')
            ->orderBy('currency_code')
            ->get();
        
        return view('admin.settings.currencies.index', compact('currencies'));
    }

    /**
     * Create form
     */
    public function create()
    {
        return view('admin.settings.currencies.create');
    }

    /**
     * Store currency
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'currency_code' => ['required', 'string', 'size:3', 'unique:currencies,currency_code', 'regex:/^[A-Z]{3}$/'],
            'currency_name' => ['required', 'string', 'max:100'],
            'symbol' => ['nullable', 'string', 'max:10'],
            'exchange_rate' => ['required', 'numeric', 'min:0.000001', 'max:999999.999999'],
            'is_base_currency' => ['nullable', 'boolean'],
        ], [
            'currency_code.required' => 'Currency code is required.',
            'currency_code.size' => 'Currency code must be exactly 3 characters.',
            'currency_code.unique' => 'This currency code already exists.',
            'currency_code.regex' => 'Currency code must be 3 uppercase letters (e.g., USD, EUR, IDR).',
            'currency_name.required' => 'Currency name is required.',
            'exchange_rate.required' => 'Exchange rate is required.',
            'exchange_rate.min' => 'Exchange rate must be greater than 0.',
        ]);

        DB::beginTransaction();
        try {
            // If this is set as base currency, unset others
            if ($request->boolean('is_base_currency')) {
                DB::table('currencies')
                    ->where('is_base_currency', true)
                    ->update(['is_base_currency' => false]);
            }

            $currencyId = DB::table('currencies')->insertGetId([
                'currency_code' => strtoupper($validated['currency_code']),
                'currency_name' => $validated['currency_name'],
                'symbol' => $validated['symbol'] ?? null,
                'exchange_rate' => $validated['exchange_rate'],
                'is_base_currency' => $request->boolean('is_base_currency'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Log CREATE
            $this->logCreate('Settings - Currencies', 'currencies', $currencyId, $validated);

            DB::commit();
            
            return redirect()
                ->route('admin.settings.currencies.index')
                ->with('success', 'Currency created successfully');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Failed to create currency: ' . $e->getMessage());
        }
    }

    /**
     * Show currency details
     */
    public function show($currencyCode)
    {
        $currency = DB::table('currencies')
            ->where('currency_code', $currencyCode)
            ->first();
        
        if (!$currency) {
            abort(404, 'Currency not found');
        }
        
        return view('admin.settings.currencies.show', compact('currency'));
    }

    /**
     * Edit currency
     */
    public function edit($currencyCode)
    {
        $currency = DB::table('currencies')
            ->where('currency_code', $currencyCode)
            ->first();
        
        if (!$currency) {
            abort(404, 'Currency not found');
        }
        
        return view('admin.settings.currencies.edit', compact('currency'));
    }

    /**
     * Update currency
     */
    public function update(Request $request, $currencyCode)
    {
        $currency = DB::table('currencies')
            ->where('currency_code', $currencyCode)
            ->first();
        
        if (!$currency) {
            abort(404, 'Currency not found');
        }

        $validated = $request->validate([
            'currency_code' => ['required', 'string', 'size:3', 'unique:currencies,currency_code,' . $currency->currency_id . ',currency_id', 'regex:/^[A-Z]{3}$/'],
            'currency_name' => ['required', 'string', 'max:100'],
            'symbol' => ['nullable', 'string', 'max:10'],
            'exchange_rate' => ['required', 'numeric', 'min:0.000001', 'max:999999.999999'],
            'is_base_currency' => ['nullable', 'boolean'],
        ], [
            'currency_code.required' => 'Currency code is required.',
            'currency_code.size' => 'Currency code must be exactly 3 characters.',
            'currency_code.unique' => 'This currency code already exists.',
            'currency_code.regex' => 'Currency code must be 3 uppercase letters.',
            'currency_name.required' => 'Currency name is required.',
            'exchange_rate.required' => 'Exchange rate is required.',
            'exchange_rate.min' => 'Exchange rate must be greater than 0.',
        ]);

        DB::beginTransaction();
        try {
            // Capture old data
            $oldData = [
                'currency_code' => $currency->currency_code,
                'currency_name' => $currency->currency_name,
                'symbol' => $currency->symbol,
                'exchange_rate' => $currency->exchange_rate,
                'is_base_currency' => $currency->is_base_currency,
                'updated_at' => $currency->updated_at,
            ];

            // If this is set as base currency, unset others
            if ($request->boolean('is_base_currency')) {
                DB::table('currencies')
                    ->where('is_base_currency', true)
                    ->where('currency_id', '!=', $currency->currency_id)
                    ->update(['is_base_currency' => false]);
            }

            DB::table('currencies')
                ->where('currency_id', $currency->currency_id)
                ->update([
                    'currency_code' => strtoupper($validated['currency_code']),
                    'currency_name' => $validated['currency_name'],
                    'symbol' => $validated['symbol'] ?? null,
                    'exchange_rate' => $validated['exchange_rate'],
                    'is_base_currency' => $request->boolean('is_base_currency'),
                    'updated_at' => now(),
                ]);

            // Log UPDATE
            $this->logUpdate('Settings - Currencies', 'currencies', $currency->currency_id, $oldData, $validated);

            DB::commit();
            
            return redirect()
                ->route('admin.settings.currencies.index')
                ->with('success', 'Currency updated successfully');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Failed to update currency: ' . $e->getMessage());
        }
    }

    /**
     * Delete currency
     */
    public function destroy($currencyCode)
    {
        $currency = DB::table('currencies')
            ->where('currency_code', $currencyCode)
            ->first();
        
        if (!$currency) {
            abort(404, 'Currency not found');
        }

        // Prevent deleting base currency
        if ($currency->is_base_currency) {
            return back()->with('error', 'Cannot delete the base currency');
        }

        DB::beginTransaction();
        try {
            // Capture data before deletion
            $oldData = [
                'currency_code' => $currency->currency_code,
                'currency_name' => $currency->currency_name,
                'symbol' => $currency->symbol,
                'exchange_rate' => $currency->exchange_rate,
            ];

            DB::table('currencies')
                ->where('currency_id', $currency->currency_id)
                ->delete();

            // Log DELETE
            $this->logDelete('Settings - Currencies', 'currencies', $currency->currency_id, $oldData);

            DB::commit();
            
            return redirect()
                ->route('admin.settings.currencies.index')
                ->with('success', 'Currency deleted successfully');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete currency: ' . $e->getMessage());
        }
    }

    /**
     * Set as base currency
     */
    public function setBase($currencyCode)
    {
        $currency = DB::table('currencies')
            ->where('currency_code', $currencyCode)
            ->first();
        
        if (!$currency) {
            abort(404, 'Currency not found');
        }

        DB::beginTransaction();
        try {
            // Unset all base currencies
            DB::table('currencies')
                ->where('is_base_currency', true)
                ->update(['is_base_currency' => false]);

            // Set this as base
            DB::table('currencies')
                ->where('currency_id', $currency->currency_id)
                ->update([
                    'is_base_currency' => true,
                    'exchange_rate' => 1.000000,
                    'updated_at' => now(),
                ]);

            // Log special action
            $this->logActivity(
                'Updated',
                "Set {$currency->currency_name} ({$currency->currency_code}) as base currency",
                'Settings - Currencies'
            );

            DB::commit();
            
            return back()->with('success', 'Base currency updated successfully');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update base currency: ' . $e->getMessage());
        }
    }
}

