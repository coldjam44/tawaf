<?php

namespace App\Http\Controllers;

use App\Models\promocode;
use Illuminate\Http\Request;

class PromocodeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $promocodes = promocode::paginate(5);
        return view('pages.promocodes.promocodes', compact('promocodes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'promocode' => 'required',
            'discount' => 'required',
        ],[
            'promocode.required' => 'Promocode is required',
            'discount.required' => 'Discount is required',
        ]);
        try {
            promocode::create($request->all());
            return redirect()->route('promocodes.index')->with('success', 'Promocode created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Promocode creation failed');
        }
       }

    /**
     * Display the specified resource.
     */
    public function show(promocode $promocode)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(promocode $promocode)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $request->validate([
            'promocode' => 'required',
            'discount' => 'required',
        ],[
            'promocode.required' => 'Promocode is required',
            'discount.required' => 'Discount is required',
        ]);
        try {
            $promocode = promocode::findOrFail($request->id);
            $promocode->update($request->all());
            return redirect()->route('promocodes.index')->with('success', 'Promocode updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Promocode update failed');
    }
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $promocode = promocode::find($id);
        $promocode->delete();
        return redirect()->back()->with('success', 'Promocode Deleted Successfully');
    }
}
