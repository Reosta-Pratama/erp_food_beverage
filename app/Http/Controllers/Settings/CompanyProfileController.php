<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
        ], [
            'company_name.required' => 'Company name is required.',
            'company_name.max' => 'Company name cannot exceed 200 characters.',
            'email.email' => 'Please provide a valid email address.',
            'website.url' => 'Please provide a valid website URL.',
            'logo.image' => 'Logo must be an image file.',
            'logo.max' => 'Logo size cannot exceed 2MB.',
            'logo.mimes' => 'Logo must be a JPG, JPEG, or PNG file.',
        ]);

        DB::beginTransaction();
        try {
            $profile = DB::table('company_profile')->first();
            
            // Handle logo upload
            if ($request->hasFile('logo')) {
                // Delete old logo if exists
                if ($profile && $profile->logo_path) {
                    Storage::disk('public')->delete($profile->logo_path);
                }
                
                $logoPath = $request->file('logo')->store('company/logo', 'public');
                $validated['logo_path'] = $logoPath;
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
                $oldLogoPath = $profile->logo_path;
                
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