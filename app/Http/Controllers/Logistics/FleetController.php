<?php

namespace App\Http\Controllers\Logistics;

use App\Helpers\CodeGeneratorHelper;
use App\Http\Controllers\Controller;
use App\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FleetController extends Controller
{
    //
    use LogsActivity;

    /*
    |--------------------------------------------------------------------------
    | DRIVERS MANAGEMENT
    |--------------------------------------------------------------------------
    */
    
    /**
     * List drivers 
     */
    public function driversIndex(Request $request)
    {
        $query = DB::table('drivers as d')
            ->join('employees as e', 'd.employee_id', '=', 'e.employee_id')
            ->join('departments as dep', 'e.department_id', '=', 'dep.department_id')
            ->join('positions as pos', 'e.position_id', '=', 'pos.position_id')
            ->select(
                'd.driver_id',
                'd.license_number',
                'd.license_type',
                'd.license_expiry',
                'd.is_available',
                'd.created_at',
                'e.employee_id',
                'e.employee_code',
                'e.first_name',
                'e.last_name',
                'e.phone',
                'e.email',
                'dep.department_name',
                'pos.position_name'
            );

        // Filter by availability
        if ($request->filled('is_available')) {
            $query->where('d.is_available', $request->is_available);
        }

        // Filter by license expiry
        if ($request->filled('expiry_status')) {
            switch ($request->expiry_status) {
                case 'expired':
                    $query->whereRaw('d.license_expiry < CURDATE()');
                    break;
                case '30_days':
                    $query->whereRaw('DATEDIFF(d.license_expiry, CURDATE()) BETWEEN 0 AND 30');
                    break;
                case '90_days':
                    $query->whereRaw('DATEDIFF(d.license_expiry, CURDATE()) BETWEEN 0 AND 90');
                    break;
            }
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('e.first_name', 'like', "%{$search}%")
                  ->orWhere('e.last_name', 'like', "%{$search}%")
                  ->orWhere('e.employee_code', 'like', "%{$search}%")
                  ->orWhere('d.license_number', 'like', "%{$search}%");
            });
        }

        $drivers = $query->orderBy('e.first_name')
            ->paginate(20);

        $stats = [
            'total' => DB::table('drivers')->count(),
            'available' => DB::table('drivers')->where('is_available', 1)->count(),
            'busy' => DB::table('drivers')->where('is_available', 0)->count(),
            'expired_license' => DB::table('drivers')->whereRaw('license_expiry < CURDATE()')->count(),
        ];

        return view('logistics.fleet.drivers.index', compact('drivers', 'stats'));
    }

    /**
     * Create form
     */
    public function driversCreate()
    {
        // Get employees who are not yet drivers
        $employees = DB::table('employees as e')
            ->leftJoin('drivers as d', 'e.employee_id', '=', 'd.employee_id')
            ->whereNull('d.driver_id')
            ->where('e.employment_status', 'Active')
            ->orderBy('e.first_name')
            ->get([
                'e.employee_id',
                'e.employee_code',
                'e.first_name',
                'e.last_name',
                'e.phone'
            ]);

        return view('logistics.fleet.drivers.create', compact('employees'));
    }

    /**
     * Store new driver
     */
    public function driversStore(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => ['required', 'exists:employees,employee_id', 'unique:drivers,employee_id'],
            'license_number' => ['required', 'string', 'max:50', 'unique:drivers,license_number'],
            'license_type' => ['required', 'string', 'max:20'],
            'license_expiry' => ['required', 'date', 'after:today'],
        ], [
            'employee_id.required' => 'Please select an employee.',
            'employee_id.exists' => 'Selected employee is invalid.',
            'employee_id.unique' => 'This employee is already registered as a driver.',
            
            'license_number.required' => 'License number is required.',
            'license_number.unique' => 'This license number is already registered.',
            
            'license_type.required' => 'License type is required.',
            
            'license_expiry.required' => 'License expiry date is required.',
            'license_expiry.date' => 'Invalid date format.',
            'license_expiry.after' => 'License expiry date must be in the future.',
        ]);

        DB::beginTransaction();
        try {
            // Insert driver
            $driverId = DB::table('drivers')->insertGetId([
                'employee_id' => $validated['employee_id'],
                'license_number' => $validated['license_number'],
                'license_type' => $validated['license_type'],
                'license_expiry' => $validated['license_expiry'],
                'is_available' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Log CREATE
            $this->logCreate(
                'Logistics - Fleet Management',
                'drivers',
                $driverId,
                [
                    'employee_id' => $validated['employee_id'],
                    'license_number' => $validated['license_number'],
                    'license_type' => $validated['license_type'],
                    'license_expiry' => $validated['license_expiry'],
                ]
            );

            DB::commit();

            return redirect()
                ->route('logistics.fleet.drivers.index')
                ->with('success', 'Driver registered successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Failed to register driver: ' . $e->getMessage());
        }
    }

    /**
     * Show edit driver form
     */
    public function driversEdit($driverId)
    {
        $driver = DB::table('drivers as d')
            ->join('employees as e', 'd.employee_id', '=', 'e.employee_id')
            ->where('d.driver_id', $driverId)
            ->select('d.*', 'e.first_name', 'e.last_name', 'e.employee_code')
            ->first();

        if (!$driver) {
            abort(404, 'Driver not found');
        }

        return view('logistics.fleet.drivers.edit', compact('driver'));
    }

    /**
     * Update driver
     */
    public function driversUpdate(Request $request, $driverId)
    {
        $driver = DB::table('drivers')->where('driver_id', $driverId)->first();

        if (!$driver) {
            abort(404, 'Driver not found');
        }

        $validated = $request->validate([
            'license_number' => ['required', 'string', 'max:50', 'unique:drivers,license_number,' . $driverId . ',driver_id'],
            'license_type' => ['required', 'string', 'max:20'],
            'license_expiry' => ['required', 'date'],
        ], [
            'license_number.required' => 'License number is required.',
            'license_number.unique' => 'This license number is already registered.',
            'license_type.required' => 'License type is required.',
            'license_expiry.required' => 'License expiry date is required.',
        ]);

        DB::beginTransaction();
        try {
            // Capture old data
            $oldData = [
                'license_number' => $driver->license_number,
                'license_type' => $driver->license_type,
                'license_expiry' => $driver->license_expiry,
            ];

            // Update driver
            DB::table('drivers')
                ->where('driver_id', $driverId)
                ->update([
                    'license_number' => $validated['license_number'],
                    'license_type' => $validated['license_type'],
                    'license_expiry' => $validated['license_expiry'],
                    'updated_at' => now(),
                ]);

            // Log UPDATE
            $this->logUpdate(
                'Logistics - Fleet Management',
                'drivers',
                $driverId,
                $oldData,
                [
                    'license_number' => $validated['license_number'],
                    'license_type' => $validated['license_type'],
                    'license_expiry' => $validated['license_expiry'],
                ]
            );

            DB::commit();

            return redirect()
                ->route('logistics.fleet.drivers.index')
                ->with('success', 'Driver updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Failed to update driver: ' . $e->getMessage());
        }
    }

    /**
     * Delete driver
     */
    public function driversDestroy($driverId)
    {
        $driver = DB::table('drivers')->where('driver_id', $driverId)->first();

        if (!$driver) {
            abort(404, 'Driver not found');
        }

        // Check if driver has deliveries
        $hasDeliveries = DB::table('deliveries')
            ->where('driver_id', $driverId)
            ->exists();

        if ($hasDeliveries) {
            return back()->with('error', 'Cannot delete driver with existing delivery records.');
        }

        DB::beginTransaction();
        try {
            // Capture old data
            $oldData = [
                'employee_id' => $driver->employee_id,
                'license_number' => $driver->license_number,
                'license_type' => $driver->license_type,
                'license_expiry' => $driver->license_expiry,
            ];

            // Delete driver
            DB::table('drivers')->where('driver_id', $driverId)->delete();

            // Log DELETE
            $this->logDelete(
                'Logistics - Fleet Management',
                'drivers',
                $driverId,
                $oldData
            );

            DB::commit();

            return redirect()
                ->route('logistics.fleet.drivers.index')
                ->with('success', 'Driver deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete driver: ' . $e->getMessage());
        }
    }

    /**
     * Toggle driver availability
     */
    public function toggleDriverAvailability($driverId)
    {
        $driver = DB::table('drivers')->where('driver_id', $driverId)->first();

        if (!$driver) {
            abort(404, 'Driver not found');
        }

        DB::beginTransaction();
        try {
            $newStatus = !$driver->is_available;

            DB::table('drivers')
                ->where('driver_id', $driverId)
                ->update([
                    'is_available' => $newStatus,
                    'updated_at' => now(),
                ]);

            // Log activity
            $this->logActivity(
                'Availability Changed',
                "Driver availability changed to " . ($newStatus ? 'Available' : 'Busy'),
                'Logistics - Fleet Management'
            );

            DB::commit();

            $message = $newStatus ? 'Driver is now available.' : 'Driver marked as busy.';

            return back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update driver availability: ' . $e->getMessage());
        }
    }

    /*
    |--------------------------------------------------------------------------
    | VEHICLES MANAGEMENT
    |--------------------------------------------------------------------------
    */
    
    /**
     * Display vehicles list
     */
    public function vehiclesIndex(Request $request)
    {
        $this->logView('Logistics - Fleet Management', 'Viewed vehicles list');

        $query = DB::table('vehicles')
            ->select('*');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by vehicle type
        if ($request->filled('vehicle_type')) {
            $query->where('vehicle_type', $request->vehicle_type);
        }

        // Filter by insurance expiry
        if ($request->filled('insurance_status')) {
            switch ($request->insurance_status) {
                case 'expired':
                    $query->whereRaw('insurance_expiry < CURDATE()');
                    break;
                case '30_days':
                    $query->whereRaw('DATEDIFF(insurance_expiry, CURDATE()) BETWEEN 0 AND 30');
                    break;
            }
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('vehicle_code', 'like', "%{$search}%")
                  ->orWhere('license_plate', 'like', "%{$search}%")
                  ->orWhere('manufacturer', 'like', "%{$search}%");
            });
        }

        $vehicles = $query->orderBy('vehicle_code')
            ->paginate(20);

        // Get filter options
        $vehicleTypes = DB::table('vehicles')
            ->select('vehicle_type', DB::raw('COUNT(*) as count'))
            ->groupBy('vehicle_type')
            ->pluck('count', 'vehicle_type');

        $statuses = DB::table('vehicles')
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');

        $stats = [
            'total' => DB::table('vehicles')->count(),
            'available' => DB::table('vehicles')->where('status', 'Available')->count(),
            'in_use' => DB::table('vehicles')->where('status', 'In Use')->count(),
            'maintenance' => DB::table('vehicles')->where('status', 'Maintenance')->count(),
            'expired_insurance' => DB::table('vehicles')->whereRaw('insurance_expiry < CURDATE()')->count(),
        ];

        return view('logistics.fleet.vehicles.index', compact('vehicles', 'vehicleTypes', 'statuses', 'stats'));
    }

    /**
     * Show create vehicle form
     */
    public function vehiclesCreate()
    {
        return view('logistics.fleet.vehicles.create');
    }

    /**
     * Store new vehicle
     */
    public function vehiclesStore(Request $request)
    {
        $validated = $request->validate([
            'vehicle_type' => ['required', 'string', 'in:Truck,Van,Pickup,Motorcycle,Other'],
            'license_plate' => ['required', 'string', 'max:20', 'unique:vehicles,license_plate'],
            'manufacturer' => ['nullable', 'string', 'max:100'],
            'model' => ['nullable', 'string', 'max:100'],
            'capacity_kg' => ['nullable', 'integer', 'min:0'],
            'fuel_consumption' => ['nullable', 'numeric', 'min:0'],
            'purchase_date' => ['nullable', 'date'],
            'insurance_expiry' => ['nullable', 'date'],
            'status' => ['required', 'string', 'in:Available,In Use,Maintenance,Inactive'],
        ], [
            'vehicle_type.required' => 'Please select a vehicle type.',
            'vehicle_type.in' => 'Invalid vehicle type selected.',
            
            'license_plate.required' => 'License plate is required.',
            'license_plate.unique' => 'This license plate is already registered.',
            
            'capacity_kg.integer' => 'Capacity must be a whole number.',
            'capacity_kg.min' => 'Capacity cannot be negative.',
            
            'status.required' => 'Please select vehicle status.',
        ]);

        DB::beginTransaction();
        try {
            // Generate vehicle code
            $vehicleCode = CodeGeneratorHelper::generateVehicleCode();

            // Insert vehicle
            $vehicleId = DB::table('vehicles')->insertGetId([
                'vehicle_code' => $vehicleCode,
                'vehicle_type' => $validated['vehicle_type'],
                'license_plate' => $validated['license_plate'],
                'manufacturer' => $validated['manufacturer'] ?? null,
                'model' => $validated['model'] ?? null,
                'capacity_kg' => $validated['capacity_kg'] ?? null,
                'fuel_consumption' => $validated['fuel_consumption'] ?? null,
                'purchase_date' => $validated['purchase_date'] ?? null,
                'insurance_expiry' => $validated['insurance_expiry'] ?? null,
                'status' => $validated['status'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Log CREATE
            $this->logCreate(
                'Logistics - Fleet Management',
                'vehicles',
                $vehicleId,
                [
                    'vehicle_code' => $vehicleCode,
                    'vehicle_type' => $validated['vehicle_type'],
                    'license_plate' => $validated['license_plate'],
                    'status' => $validated['status'],
                ]
            );

            DB::commit();

            return redirect()
                ->route('logistics.fleet.vehicles.index')
                ->with('success', 'Vehicle registered successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Failed to register vehicle: ' . $e->getMessage());
        }
    }

    /**
     * Show edit vehicle form
     */
    public function vehiclesEdit($vehicleCode)
    {
        $vehicle = DB::table('vehicles')
            ->where('vehicle_code', $vehicleCode)
            ->first();

        if (!$vehicle) {
            abort(404, 'Vehicle not found');
        }

        return view('logistics.fleet.vehicles.edit', compact('vehicle'));
    }

    /**
     * Update vehicle
     */
    public function vehiclesUpdate(Request $request, $vehicleCode)
    {
        $vehicle = DB::table('vehicles')
            ->where('vehicle_code', $vehicleCode)
            ->first();

        if (!$vehicle) {
            abort(404, 'Vehicle not found');
        }

        $validated = $request->validate([
            'vehicle_type' => ['required', 'string', 'in:Truck,Van,Pickup,Motorcycle,Other'],
            'license_plate' => ['required', 'string', 'max:20', 'unique:vehicles,license_plate,' . $vehicle->vehicle_id . ',vehicle_id'],
            'manufacturer' => ['nullable', 'string', 'max:100'],
            'model' => ['nullable', 'string', 'max:100'],
            'capacity_kg' => ['nullable', 'integer', 'min:0'],
            'fuel_consumption' => ['nullable', 'numeric', 'min:0'],
            'purchase_date' => ['nullable', 'date'],
            'insurance_expiry' => ['nullable', 'date'],
            'status' => ['required', 'string', 'in:Available,In Use,Maintenance,Inactive'],
        ]);

        DB::beginTransaction();
        try {
            // Capture old data
            $oldData = [
                'vehicle_type' => $vehicle->vehicle_type,
                'license_plate' => $vehicle->license_plate,
                'capacity_kg' => $vehicle->capacity_kg,
                'status' => $vehicle->status,
            ];

            // Update vehicle
            DB::table('vehicles')
                ->where('vehicle_id', $vehicle->vehicle_id)
                ->update([
                    'vehicle_type' => $validated['vehicle_type'],
                    'license_plate' => $validated['license_plate'],
                    'manufacturer' => $validated['manufacturer'] ?? null,
                    'model' => $validated['model'] ?? null,
                    'capacity_kg' => $validated['capacity_kg'] ?? null,
                    'fuel_consumption' => $validated['fuel_consumption'] ?? null,
                    'purchase_date' => $validated['purchase_date'] ?? null,
                    'insurance_expiry' => $validated['insurance_expiry'] ?? null,
                    'status' => $validated['status'],
                    'updated_at' => now(),
                ]);

            // Log UPDATE
            $this->logUpdate(
                'Logistics - Fleet Management',
                'vehicles',
                $vehicle->vehicle_id,
                $oldData,
                [
                    'vehicle_type' => $validated['vehicle_type'],
                    'license_plate' => $validated['license_plate'],
                    'capacity_kg' => $validated['capacity_kg'] ?? null,
                    'status' => $validated['status'],
                ]
            );

            DB::commit();

            return redirect()
                ->route('logistics.fleet.vehicles.index')
                ->with('success', 'Vehicle updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Failed to update vehicle: ' . $e->getMessage());
        }
    }

    /**
     * Delete vehicle
     */
    public function vehiclesDestroy($vehicleCode)
    {
        $vehicle = DB::table('vehicles')
            ->where('vehicle_code', $vehicleCode)
            ->first();

        if (!$vehicle) {
            abort(404, 'Vehicle not found');
        }

        // Check if vehicle has deliveries
        $hasDeliveries = DB::table('deliveries')
            ->where('vehicle_id', $vehicle->vehicle_id)
            ->exists();

        if ($hasDeliveries) {
            return back()->with('error', 'Cannot delete vehicle with existing delivery records.');
        }

        DB::beginTransaction();
        try {
            // Capture old data
            $oldData = [
                'vehicle_code' => $vehicle->vehicle_code,
                'vehicle_type' => $vehicle->vehicle_type,
                'license_plate' => $vehicle->license_plate,
                'status' => $vehicle->status,
            ];

            // Delete vehicle
            DB::table('vehicles')
                ->where('vehicle_id', $vehicle->vehicle_id)
                ->delete();

            // Log DELETE
            $this->logDelete(
                'Logistics - Fleet Management',
                'vehicles',
                $vehicle->vehicle_id,
                $oldData
            );

            DB::commit();

            return redirect()
                ->route('logistics.fleet.vehicles.index')
                ->with('success', 'Vehicle deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete vehicle: ' . $e->getMessage());
        }
    }
}
