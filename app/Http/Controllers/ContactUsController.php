<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContactUs;

class ContactUsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contactInfo = ContactUs::first();
        return view('contact-us.index', compact('contactInfo'));
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'company_name_ar' => 'required|string|max:255',
            'company_name_en' => 'required|string|max:255',
            'broker_registration_number' => 'nullable|string|max:100',
            'phone_numbers' => 'required|array|min:1',
            'phone_numbers.*.number' => 'required|string|max:50',
            'phone_numbers.*.type' => 'nullable|string|max:50',
            'email_addresses' => 'required|array|min:1',
            'email_addresses.*.email' => 'required|email|max:100',
            'email_addresses.*.type' => 'nullable|string|max:50',
            'locations' => 'required|array|min:1',
            'locations.*.address_ar' => 'required|string|max:500',
            'locations.*.address_en' => 'required|string|max:500',
            'locations.*.map_embed' => 'nullable|string',
            'map_embed' => 'nullable|string',
        ]);

        ContactUs::create($request->all());

        return redirect()->route('contact-us.index')
            ->with('success', trans('main_trans.contact_us_created_successfully'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $contactInfo = ContactUs::findOrFail($id);
        return view('contact-us.show', compact('contactInfo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $contactInfo = ContactUs::findOrFail($id);
        return view('contact-us.edit', compact('contactInfo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'company_name_ar' => 'required|string|max:255',
            'company_name_en' => 'required|string|max:255',
            'broker_registration_number' => 'nullable|string|max:100',
            'phone_numbers' => 'required|array|min:1',
            'phone_numbers.*.number' => 'required|string|max:50',
            'phone_numbers.*.type' => 'nullable|string|max:50',
            'email_addresses' => 'required|array|min:1',
            'email_addresses.*.email' => 'required|email|max:100',
            'email_addresses.*.type' => 'nullable|string|max:50',
            'locations' => 'required|array|min:1',
            'locations.*.address_ar' => 'required|string|max:500',
            'locations.*.address_en' => 'required|string|max:500',
            'locations.*.map_embed' => 'nullable|string',
            'map_embed' => 'nullable|string',
        ]);

        $contactInfo = ContactUs::findOrFail($id);
        $contactInfo->update($request->all());

        return redirect()->route('contact-us.index')
            ->with('success', trans('main_trans.contact_us_updated_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $contactInfo = ContactUs::findOrFail($id);
        $contactInfo->delete();

        return redirect()->route('contact-us.index')
            ->with('success', trans('main_trans.contact_us_deleted_successfully'));
    }
}
