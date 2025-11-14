<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CompanyProfileController extends Controller
{
    //
    use LogsActivity;

    /**
     * Show company profile
     */
    public function index()
    {
        $profile = DB::table('company_profile')->first();
        
        // Log viewing company profile
        $this->logView('Settings - Company Profile', 'Viewed company profile');
        
        return view('admin.settings.company-profile.index', compact('profile'));
    }

    /**
     * Edit company profile
     */
    public function edit()
    {
        $profile = DB::table('company_profile')->first();
        
        return view('admin.settings.company-profile.edit', compact('profile'));
    }

    /**
     * Update company profile
     */
    public function update(Request $request)
    {
        $messages = [
            'company_name.required' => 'The company name is required.',
            'company_name.string' => 'The company name must be a valid text.',
            'company_name.max' => 'The company name cannot exceed 200 characters.',

            'legal_name.string' => 'The legal name must be a valid text.',
            'legal_name.max' => 'The legal name cannot exceed 200 characters.',

            'tax_id.string' => 'The tax ID must be a valid text.',
            'tax_id.max' => 'The tax ID cannot exceed 50 characters.',

            'address.string' => 'The address must be a valid text.',

            'city.string' => 'The city name must be a valid text.',
            'city.max' => 'The city name cannot exceed 100 characters.',

            'country.string' => 'The country name must be a valid text.',
            'country.max' => 'The country name cannot exceed 100 characters.',

            'postal_code.string' => 'The postal code must be a valid text.',
            'postal_code.max' => 'The postal code cannot exceed 20 characters.',

            'phone.string' => 'The phone number must be a valid text.',
            'phone.max' => 'The phone number cannot exceed 20 characters.',

            'email.email' => 'Please enter a valid email address.',
            'email.max' => 'The email address cannot exceed 150 characters.',

            'website.url' => 'Please enter a valid website URL.',
            'website.max' => 'The website URL cannot exceed 200 characters.',

            'logo.image' => 'The logo must be a valid image file.',
            'logo.max' => 'The logo file size cannot exceed 2MB.',
            'logo.mimes' => 'The logo must be in JPG, JPEG, or PNG format.',
        ];

        $validated = $request->validate([
            'company_name' => ['required', 'string', 'max:200'],
            'legal_name' => ['nullable', 'string', 'max:200'],
            'tax_id' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string'],
            'city' => ['nullable', 'string', 'max:100'],
            'country' => ['nullable', 'string', 'max:100'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'phone' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:150'],
            'website' => ['nullable', 'url', 'max:200'],
            'logo' => ['nullable', 'image', 'max:2048', 'mimes:jpg,jpeg,png'],
        ], $messages);

        DB::beginTransaction();
        try {
            $profile = DB::table('company_profile')->first();
            
            // Handle logo upload
            if ($request->hasFile('logo')) {
                // Delete old logo if exists
                if ($profile && $profile->logo_path) {
                    Storage::disk('public')->delete($profile->logo_path);
                }
                
                $uniqueName = time() . '_' . Str::random(20) . '.' . $request->file('logo')->extension();
                $request->file('logo')->storeAs('company/logo', $uniqueName, 'public');

                $validated['logo_path'] = 'company/logo/' . $uniqueName;

            }

            if ($profile) {
                // Capture old data for audit
                $oldData = [
                    'company_name' => $profile->company_name,
                    'legal_name' => $profile->legal_name,
                    'tax_id' => $profile->tax_id,
                    'address' => $profile->address,
                    'city' => $profile->city,
                    'country' => $profile->country,
                    'postal_code' => $profile->postal_code,
                    'phone' => $profile->phone,
                    'email' => $profile->email,
                    'website' => $profile->website,
                ];

                // Update existing profile
                DB::table('company_profile')
                    ->where('company_id', $profile->company_id)
                    ->update([
                        'company_name' => $validated['company_name'],
                        'legal_name' => $validated['legal_name'] ?? null,
                        'tax_id' => $validated['tax_id'] ?? null,
                        'address' => $validated['address'] ?? null,
                        'city' => $validated['city'] ?? null,
                        'country' => $validated['country'] ?? null,
                        'postal_code' => $validated['postal_code'] ?? null,
                        'phone' => $validated['phone'] ?? null,
                        'email' => $validated['email'] ?? null,
                        'website' => $validated['website'] ?? null,
                        'logo_path' => $validated['logo_path'] ?? $profile->logo_path,
                        'updated_at' => now(),
                    ]);

                // Log UPDATE
                $this->logUpdate(
                    'Settings - Company Profile',
                    'company_profile',
                    $profile->company_id,
                    $oldData,
                    $validated
                );
            } else {
                // Create new profile
                $companyId = DB::table('company_profile')->insertGetId([
                    'company_name' => $validated['company_name'],
                    'legal_name' => $validated['legal_name'] ?? null,
                    'tax_id' => $validated['tax_id'] ?? null,
                    'address' => $validated['address'] ?? null,
                    'city' => $validated['city'] ?? null,
                    'country' => $validated['country'] ?? null,
                    'postal_code' => $validated['postal_code'] ?? null,
                    'phone' => $validated['phone'] ?? null,
                    'email' => $validated['email'] ?? null,
                    'website' => $validated['website'] ?? null,
                    'logo_path' => $validated['logo_path'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Log CREATE
                $this->logCreate(
                    'Settings - Company Profile',
                    'company_profile',
                    $companyId,
                    $validated
                );
            }

            DB::commit();
            
            return redirect()
                ->route('admin.settings.company-profile.index')
                ->with('success', 'Company profile updated successfully');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Delete uploaded logo if transaction fails
            if (isset($validated['logo_path'])) {
                Storage::disk('public')->delete($validated['logo_path']);
            }
            
            return back()
                ->withInput()
                ->with('error', 'Failed to update company profile: ' . $e->getMessage());
        }
    }

    /**
     * Delete company logo
     */
    public function deleteLogo()
    {
        DB::beginTransaction();
        try {
            $profile = DB::table('company_profile')->first();
            
            if ($profile && $profile->logo_path) {
                Storage::disk('public')->delete($profile->logo_path);
                
                DB::table('company_profile')
                    ->where('company_id', $profile->company_id)
                    ->update([
                        'logo_path' => null,
                        'updated_at' => now(),
                    ]);

                // Log logo deletion
                $this->logActivity(
                    'Deleted',
                    'Deleted company logo',
                    'Settings - Company Profile'
                );
            }

            DB::commit();
            
            return back()->with('success', 'Company logo deleted successfully');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete logo: ' . $e->getMessage());
        }
    }
}