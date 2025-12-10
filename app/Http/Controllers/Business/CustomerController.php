<?php

namespace App\Http\Controllers\Business;

use App\Helpers\CodeGeneratorHelper;
use App\Http\Controllers\Controller;
use App\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    //
    use LogsActivity;

    /**
     * Display customers with filtering
     */
    public function index(Request $request)
    {
        $query = DB::table('customers as c')
            ->leftJoin('sales_orders as so', 'c.customer_id', '=', 'so.customer_id')
            ->select(
                'c.customer_id',
                'c.customer_code',
                'c.customer_name',
                'c.contact_person',
                'c.email',
                'c.phone',
                'c.city',
                'c.country',
                'c.customer_type',
                'c.is_active',
                'c.credit_limit',
                'c.created_at',
                DB::raw('COUNT(DISTINCT so.so_id) as total_orders'),
                DB::raw('COALESCE(SUM(CASE WHEN so.status = "Pending" THEN 1 ELSE 0 END), 0) as pending_orders'),
                DB::raw('COALESCE(SUM(CASE WHEN so.payment_status = "Unpaid" THEN so.total_amount ELSE 0 END), 0) as outstanding_balance')
            )
            ->groupBy(
                'c.customer_id',
                'c.customer_code',
                'c.customer_name',
                'c.contact_person',
                'c.email',
                'c.phone',
                'c.city',
                'c.country',
                'c.customer_type',
                'c.is_active',
                'c.credit_limit',
                'c.created_at'
            );

        // Filter by status
        if ($request->filled('is_active')) {
            $query->where('c.is_active', $request->is_active);
        }

        // Filter by customer type
        if ($request->filled('customer_type')) {
            $query->where('c.customer_type', $request->customer_type);
        }

        // Filter by country
        if ($request->filled('country')) {
            $query->where('c.country', $request->country);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('c.customer_name', 'like', "%{$search}%")
                  ->orWhere('c.customer_code', 'like', "%{$search}%")
                  ->orWhere('c.contact_person', 'like', "%{$search}%")
                  ->orWhere('c.email', 'like', "%{$search}%");
            });
        }

        $customers = $query->orderBy('c.customer_name')
            ->paginate(20);

        // Get filter options
        $customerTypes = DB::table('customers')
            ->select('customer_type', DB::raw('COUNT(*) as count'))
            ->groupBy('customer_type')
            ->pluck('count', 'customer_type');

        $countries = DB::table('customers')
            ->whereNotNull('country')
            ->select('country', DB::raw('COUNT(*) as count'))
            ->groupBy('country')
            ->orderBy('country')
            ->pluck('count', 'country');

        $stats = [
            'total' => DB::table('customers')->count(),
            'active' => DB::table('customers')->where('is_active', 1)->count(),
            'inactive' => DB::table('customers')->where('is_active', 0)->count(),
            'retail' => DB::table('customers')->where('customer_type', 'Retail')->count(),
            'wholesale' => DB::table('customers')->where('customer_type', 'Wholesale')->count(),
        ];

        return view('sales.customers.index', compact('customers', 'customerTypes', 'countries', 'stats'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        return view('sales.customers.create');
    }

    /**
     * Store new customer
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:200', 'unique:customers,customer_name'],
            'contact_person' => ['nullable', 'string', 'max:150'],
            'email' => ['nullable', 'email', 'max:150'],
            'phone' => ['nullable', 'string', 'max:20'],
            'shipping_address' => ['nullable', 'string'],
            'billing_address' => ['nullable', 'string'],
            'city' => ['nullable', 'string', 'max:100'],
            'country' => ['nullable', 'string', 'max:100'],
            'tax_id' => ['nullable', 'string', 'max:50'],
            'payment_terms' => ['nullable', 'string', 'max:100'],
            'credit_limit' => ['nullable', 'numeric', 'min:0'],
            'customer_type' => ['required', 'string', 'in:Retail,Wholesale,Distributor,Corporate'],
            'is_active' => ['boolean'],
        ], [
            'customer_name.required' => 'Customer name is required.',
            'customer_name.max' => 'Customer name is too long.',
            'customer_name.unique' => 'This customer name already exists.',
            
            'email.email' => 'Please enter a valid email address.',
            
            'credit_limit.numeric' => 'Credit limit must be a number.',
            'credit_limit.min' => 'Credit limit cannot be negative.',
            
            'customer_type.required' => 'Please select a customer type.',
            'customer_type.in' => 'Invalid customer type selected.',
        ]);

        DB::beginTransaction();
        try {
            $customerCode = CodeGeneratorHelper::generateCustomerCode();

            // Insert customer
            $customerId = DB::table('customers')->insertGetId([
                'customer_code' => $customerCode,
                'customer_name' => $validated['customer_name'],
                'contact_person' => $validated['contact_person'] ?? null,
                'email' => $validated['email'] ?? null,
                'phone' => $validated['phone'] ?? null,
                'shipping_address' => $validated['shipping_address'] ?? null,
                'billing_address' => $validated['billing_address'] ?? null,
                'city' => $validated['city'] ?? null,
                'country' => $validated['country'] ?? null,
                'tax_id' => $validated['tax_id'] ?? null,
                'payment_terms' => $validated['payment_terms'] ?? null,
                'credit_limit' => $validated['credit_limit'] ?? null,
                'customer_type' => $validated['customer_type'],
                'is_active' => $validated['is_active'] ?? 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Log CREATE
            $this->logCreate(
                'Sales Management - Customers',
                'customers',
                $customerId,
                [
                    'customer_code' => $customerCode,
                    'customer_name' => $validated['customer_name'],
                    'customer_type' => $validated['customer_type'],
                    'email' => $validated['email'] ?? null,
                    'phone' => $validated['phone'] ?? null,
                    'is_active' => $validated['is_active'] ?? 1,
                ]
            );

            DB::commit();

            return redirect()
                ->route('sales.customers.index')
                ->with('success', 'Customer created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Failed to create customer: ' . $e->getMessage());
        }
    }

    /**
     * Show customer details
     */
    public function show($customerCode)
    {
        $customer = DB::table('customers')
            ->where('customer_code', $customerCode)
            ->first();

        if (!$customer) {
            abort(404, 'Customer not found');
        }

        // Log VIEW
        $this->logView(
            'Sales Management - Customers',
            "Viewed customer: {$customer->customer_name} ({$customerCode})"
        );

        // Get sales orders
        $salesOrders = DB::table('sales_orders as so')
            ->where('so.customer_id', $customer->customer_id)
            ->select(
                'so.so_code',
                'so.order_date',
                'so.requested_delivery',
                'so.total_amount',
                'so.status',
                'so.payment_status'
            )
            ->orderByDesc('so.order_date')
            ->limit(10)
            ->get();

        // Get statistics
        $stats = [
            'total_orders' => DB::table('sales_orders')
                ->where('customer_id', $customer->customer_id)
                ->count(),
            'pending_orders' => DB::table('sales_orders')
                ->where('customer_id', $customer->customer_id)
                ->where('status', 'Pending')
                ->count(),
            'total_revenue' => DB::table('sales_orders')
                ->where('customer_id', $customer->customer_id)
                ->whereIn('status', ['Approved', 'Completed'])
                ->sum('total_amount'),
            'outstanding_balance' => DB::table('sales_orders')
                ->where('customer_id', $customer->customer_id)
                ->where('payment_status', 'Unpaid')
                ->sum('total_amount'),
        ];

        return view('sales.customers.show', compact('customer', 'salesOrders', 'stats'));
    }

    /**
     * Show edit form
     */
    public function edit($customerCode)
    {
        $customer = DB::table('customers')
            ->where('customer_code', $customerCode)
            ->first();

        if (!$customer) {
            abort(404, 'Customer not found');
        }

        return view('sales.customers.edit', compact('customer'));
    }

    /**
     * Update customer
     */
    public function update(Request $request, $customerCode)
    {
        $customer = DB::table('customers')
            ->where('customer_code', $customerCode)
            ->first();

        if (!$customer) {
            abort(404, 'Customer not found');
        }

        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:200', 'unique:customers,customer_name,' . $customer->customer_id . ',customer_id'],
            'contact_person' => ['nullable', 'string', 'max:150'],
            'email' => ['nullable', 'email', 'max:150'],
            'phone' => ['nullable', 'string', 'max:20'],
            'shipping_address' => ['nullable', 'string'],
            'billing_address' => ['nullable', 'string'],
            'city' => ['nullable', 'string', 'max:100'],
            'country' => ['nullable', 'string', 'max:100'],
            'tax_id' => ['nullable', 'string', 'max:50'],
            'payment_terms' => ['nullable', 'string', 'max:100'],
            'credit_limit' => ['nullable', 'numeric', 'min:0'],
            'customer_type' => ['required', 'string', 'in:Retail,Wholesale,Distributor,Corporate'],
            'is_active' => ['boolean'],
        ], [
            'customer_name.required' => 'Customer name is required.',
            'customer_name.unique' => 'This customer name already exists.',
            'email.email' => 'Please enter a valid email address.',
            'credit_limit.numeric' => 'Credit limit must be a number.',
            'credit_limit.min' => 'Credit limit cannot be negative.',
            'customer_type.required' => 'Please select a customer type.',
        ]);

        DB::beginTransaction();
        try {
            // Capture old data
            $oldData = [
                'customer_name' => $customer->customer_name,
                'customer_type' => $customer->customer_type,
                'contact_person' => $customer->contact_person,
                'email' => $customer->email,
                'phone' => $customer->phone,
                'credit_limit' => $customer->credit_limit,
                'is_active' => $customer->is_active,
            ];

            // Update customer
            DB::table('customers')
                ->where('customer_id', $customer->customer_id)
                ->update([
                    'customer_name' => $validated['customer_name'],
                    'contact_person' => $validated['contact_person'] ?? null,
                    'email' => $validated['email'] ?? null,
                    'phone' => $validated['phone'] ?? null,
                    'shipping_address' => $validated['shipping_address'] ?? null,
                    'billing_address' => $validated['billing_address'] ?? null,
                    'city' => $validated['city'] ?? null,
                    'country' => $validated['country'] ?? null,
                    'tax_id' => $validated['tax_id'] ?? null,
                    'payment_terms' => $validated['payment_terms'] ?? null,
                    'credit_limit' => $validated['credit_limit'] ?? null,
                    'customer_type' => $validated['customer_type'],
                    'is_active' => $validated['is_active'] ?? 1,
                    'updated_at' => now(),
                ]);

            // Log UPDATE
            $this->logUpdate(
                'Sales Management - Customers',
                'customers',
                $customer->customer_id,
                $oldData,
                [
                    'customer_name' => $validated['customer_name'],
                    'customer_type' => $validated['customer_type'],
                    'contact_person' => $validated['contact_person'] ?? null,
                    'email' => $validated['email'] ?? null,
                    'phone' => $validated['phone'] ?? null,
                    'credit_limit' => $validated['credit_limit'] ?? null,
                    'is_active' => $validated['is_active'] ?? 1,
                ]
            );

            DB::commit();

            return redirect()
                ->route('sales.customers.index')
                ->with('success', 'Customer updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Failed to update customer: ' . $e->getMessage());
        }
    }

    /**
     * Delete customer
     */
    public function destroy($customerCode)
    {
        $customer = DB::table('customers')
            ->where('customer_code', $customerCode)
            ->first();

        if (!$customer) {
            abort(404, 'Customer not found');
        }

        // Check if customer has sales orders
        $hasOrders = DB::table('sales_orders')
            ->where('customer_id', $customer->customer_id)
            ->exists();

        if ($hasOrders) {
            return back()->with('error', 'Cannot delete customer with existing sales orders. Please deactivate instead.');
        }

        DB::beginTransaction();
        try {
            // Capture old data
            $oldData = [
                'customer_code' => $customer->customer_code,
                'customer_name' => $customer->customer_name,
                'customer_type' => $customer->customer_type,
                'email' => $customer->email,
                'phone' => $customer->phone,
            ];

            // Delete customer
            DB::table('customers')
                ->where('customer_id', $customer->customer_id)
                ->delete();

            // Log DELETE
            $this->logDelete(
                'Sales Management - Customers',
                'customers',
                $customer->customer_id,
                $oldData
            );

            DB::commit();

            return redirect()
                ->route('sales.customers.index')
                ->with('success', 'Customer deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete customer: ' . $e->getMessage());
        }
    }

    /**
     * Toggle customer status
     */
    public function toggleStatus($customerCode)
    {
        $customer = DB::table('customers')
            ->where('customer_code', $customerCode)
            ->first();

        if (!$customer) {
            abort(404, 'Customer not found');
        }

        DB::beginTransaction();
        try {
            $newStatus = !$customer->is_active;

            DB::table('customers')
                ->where('customer_id', $customer->customer_id)
                ->update([
                    'is_active' => $newStatus,
                    'updated_at' => now(),
                ]);

            // Log activity
            $this->logActivity(
                'Status Changed',
                "Changed customer {$customer->customer_name} status to " . ($newStatus ? 'Active' : 'Inactive'),
                'Sales Management - Customers'
            );

            DB::commit();

            $message = $newStatus ? 'Customer activated successfully.' : 'Customer deactivated successfully.';

            return back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to toggle customer status: ' . $e->getMessage());
        }
    }

    /**
     * Export customers to CSV
     */
    public function export(Request $request)
    {
        $this->logExport('Sales Management - Customers', 'Exported customers to CSV');

        $query = DB::table('customers')
            ->select(
                'customer_code',
                'customer_name',
                'customer_type',
                'contact_person',
                'email',
                'phone',
                'shipping_address',
                'billing_address',
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
        if ($request->filled('customer_type')) {
            $query->where('customer_type', $request->customer_type);
        }
        if ($request->filled('country')) {
            $query->where('country', $request->country);
        }

        $customers = $query->orderBy('customer_name')
            ->limit(5000)
            ->get();

        $filename = 'customers_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($customers) {
            $file = fopen('php://output', 'w');

            // Header
            fputcsv($file, [
                'Customer Code',
                'Customer Name',
                'Customer Type',
                'Contact Person',
                'Email',
                'Phone',
                'Shipping Address',
                'Billing Address',
                'City',
                'Country',
                'Tax ID',
                'Payment Terms',
                'Credit Limit',
                'Status',
                'Created At'
            ]);

            // Data
            foreach ($customers as $customer) {
                fputcsv($file, [
                    $customer->customer_code,
                    $customer->customer_name,
                    $customer->customer_type,
                    $customer->contact_person ?? '-',
                    $customer->email ?? '-',
                    $customer->phone ?? '-',
                    $customer->shipping_address ?? '-',
                    $customer->billing_address ?? '-',
                    $customer->city ?? '-',
                    $customer->country ?? '-',
                    $customer->tax_id ?? '-',
                    $customer->payment_terms ?? '-',
                    $customer->credit_limit ?? '0',
                    $customer->is_active ? 'Active' : 'Inactive',
                    $customer->created_at,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
