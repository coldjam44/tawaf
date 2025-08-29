<?php

namespace App\Http\Controllers;

use App\Models\RealEstateCompany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class RealEstateCompanyController extends Controller
{
    public function index()
    {
        $companies = RealEstateCompany::orderBy('created_at', 'desc')->get();
        return view('real-estate-company.index', compact('companies'));
    }

    public function store(Request $request)
    {
        // Schema safeguard - create table if it doesn't exist
        if (!Schema::hasTable('real_estate_companies')) {
            Schema::create('real_estate_companies', function (Blueprint $table) {
                $table->id();
                $table->string('company_logo')->nullable();
                $table->string('company_name_ar');
                $table->string('company_name_en');
                $table->text('short_description_ar')->nullable();
                $table->text('short_description_en')->nullable();
                $table->text('about_company_ar')->nullable();
                $table->text('about_company_en')->nullable();
                $table->json('company_images')->nullable();
                $table->string('contact_number')->nullable();
                $table->text('features_ar')->nullable();
                $table->text('features_en')->nullable();
                $table->text('properties_communities_ar')->nullable();
                $table->text('properties_communities_en')->nullable();
                $table->string('adm_number')->nullable();
                $table->string('cn_number')->nullable();
                $table->timestamps();
            });
        }

        $request->validate([
            'company_logo' => 'nullable|file',
            'company_name_ar' => 'required|string|max:255',
            'company_name_en' => 'required|string|max:255',
            'short_description_ar' => 'nullable|string',
            'short_description_en' => 'nullable|string',
            'about_company_ar' => 'nullable|string',
            'about_company_en' => 'nullable|string',
            'company_images.*' => 'nullable|file',
            'contact_number' => 'nullable|string|max:20',
            'features_ar' => 'nullable|string',
            'features_en' => 'nullable|string',
            'properties_communities_ar' => 'nullable|string',
            'properties_communities_en' => 'nullable|string',
            'adm_number' => 'nullable|string|max:50',
            'cn_number' => 'nullable|string|max:50',
        ]);

        $data = $request->only([
            'company_name_ar', 'company_name_en', 'short_description_ar', 'short_description_en',
            'about_company_ar', 'about_company_en', 'contact_number', 'features_ar', 'features_en',
            'properties_communities_ar', 'properties_communities_en', 'adm_number', 'cn_number'
        ]);

        // Handle company logo
        if ($request->hasFile('company_logo')) {
            $logo = $request->file('company_logo');
            $logoName = 'company_logo_' . time() . '.' . $logo->getClientOriginalExtension();
            $logo->move(public_path('real-estate-companies'), $logoName);
            $data['company_logo'] = $logoName;
        }

        // Handle company images
        $companyImages = [];
        if ($request->hasFile('company_images')) {
            foreach ($request->file('company_images') as $image) {
                $imageName = 'company_image_' . time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('real-estate-companies'), $imageName);
                $companyImages[] = $imageName;
            }
        }
        $data['company_images'] = json_encode($companyImages);

        RealEstateCompany::create($data);

        return redirect()->route('real-estate-company.index')
            ->with('success', 'تم إضافة شركة التطوير العقاري بنجاح');
    }

    public function edit(string $id)
    {
        $company = RealEstateCompany::findOrFail($id);
        return view('real-estate-company.edit', compact('company'));
    }

    public function update(Request $request, string $id)
    {
        $company = RealEstateCompany::findOrFail($id);

        $request->validate([
            'company_logo' => 'nullable|file',
            'company_name_ar' => 'required|string|max:255',
            'company_name_en' => 'required|string|max:255',
            'short_description_ar' => 'nullable|string',
            'short_description_en' => 'nullable|string',
            'about_company_ar' => 'nullable|string',
            'about_company_en' => 'nullable|string',
            'company_images.*' => 'nullable|file',
            'contact_number' => 'nullable|string|max:20',
            'features_ar' => 'nullable|string',
            'features_en' => 'nullable|string',
            'properties_communities_ar' => 'nullable|string',
            'properties_communities_en' => 'nullable|string',
            'adm_number' => 'nullable|string|max:50',
            'cn_number' => 'nullable|string|max:50',
        ]);

        $data = $request->only([
            'company_name_ar', 'company_name_en', 'short_description_ar', 'short_description_en',
            'about_company_ar', 'about_company_en', 'contact_number', 'features_ar', 'features_en',
            'properties_communities_ar', 'properties_communities_en', 'adm_number', 'cn_number'
        ]);

        // Handle company logo
        if ($request->hasFile('company_logo')) {
            // Delete old logo
            if ($company->company_logo && file_exists(public_path('real-estate-companies/' . $company->company_logo))) {
                unlink(public_path('real-estate-companies/' . $company->company_logo));
            }
            
            $logo = $request->file('company_logo');
            $logoName = 'company_logo_' . time() . '.' . $logo->getClientOriginalExtension();
            $logo->move(public_path('real-estate-companies'), $logoName);
            $data['company_logo'] = $logoName;
        }

        // Handle company images
        if ($request->hasFile('company_images')) {
            // Delete old images
            $oldImages = json_decode($company->company_images, true) ?? [];
            foreach ($oldImages as $oldImage) {
                if (file_exists(public_path('real-estate-companies/' . $oldImage))) {
                    unlink(public_path('real-estate-companies/' . $oldImage));
                }
            }

            $companyImages = [];
            foreach ($request->file('company_images') as $image) {
                $imageName = 'company_image_' . time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('real-estate-companies'), $imageName);
                $companyImages[] = $imageName;
            }
            $data['company_images'] = json_encode($companyImages);
        }

        $company->update($data);

        return redirect()->route('real-estate-company.index')
            ->with('success', 'تم تحديث شركة التطوير العقاري بنجاح');
    }

    public function destroy(string $id)
    {
        $company = RealEstateCompany::findOrFail($id);

        // Delete logo
        if ($company->company_logo && file_exists(public_path('real-estate-companies/' . $company->company_logo))) {
            unlink(public_path('real-estate-companies/' . $company->company_logo));
        }

        // Delete company images
        $companyImages = json_decode($company->company_images, true) ?? [];
        foreach ($companyImages as $image) {
            if (file_exists(public_path('real-estate-companies/' . $image))) {
                unlink(public_path('real-estate-companies/' . $image));
            }
        }

        $company->delete();

        return redirect()->route('real-estate-company.index')
            ->with('success', 'تم حذف شركة التطوير العقاري بنجاح');
    }
}
