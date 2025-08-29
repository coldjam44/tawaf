<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Developer;

class DeveloperController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // عرض 10 مطورين لكل صفحة مع تحميل العلاقة مع الشركة
        $developers = Developer::with('company')->paginate(10);
        
        // إذا تم تمرير company_id، قم بتمريره للفيو
        $companyId = $request->get('company_id');
        $company = null;
        
        if ($companyId) {
            $company = \App\Models\RealEstateCompany::find($companyId);
        }
        
        return view('developers.index', compact('developers', 'company'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // ممكن نستغنى عنه إذا الفورم موجود بالصفحة نفسها
    }

    /**
     * Store a newly created resource in storage.
     */
     public function store(Request $request)
{
    $request->validate([
        'name_en' => 'required|string|max:255',
        'name_ar' => 'required|string|max:255',
        'email'   => 'required|email|unique:developers,email',
        'phone'   => 'required|string|max:20|unique:developers,phone',
        'image'   => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        'company_id' => 'nullable|exists:real_estate_companies,id',
    ]);

    $imageName = null;
    if ($request->hasFile('image')) {
        $imageName = time() . '.' . $request->image->extension();
        $request->image->storeAs('public/developers', $imageName);
    }

    Developer::create([
        'name_en' => $request->name_en,
        'name_ar' => $request->name_ar,
        'email'   => $request->email,
        'phone'   => $request->phone,
        'image'   => $imageName,
        'company_id' => $request->company_id,
    ]);

    return redirect()->route('developers.index')->with('success', 'Developer added successfully.');
}



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $developer = Developer::findOrFail($id);
        return view('developers.edit', compact('developer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $developer = Developer::findOrFail($id);

        $request->validate([
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'email'   => 'required|email|unique:developers,email,' . $developer->id,
            'phone'   => 'required|string|max:20|unique:developers,phone,' . $developer->id,
            'company_id' => 'nullable|exists:real_estate_companies,id',
        ]);

        $developer->update([
            'name_en' => $request->name_en,
            'name_ar' => $request->name_ar,
            'email'   => $request->email,
            'phone'   => $request->phone,
            'company_id' => $request->company_id,
        ]);

        return redirect()->route('developers.index')->with('success', 'Developer updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $developer = Developer::findOrFail($id);
        $developer->delete();

        return redirect()->route('developers.index')->with('success', 'Developer deleted successfully.');
    }
}
