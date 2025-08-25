<?php

namespace App\Http\Controllers;

use App\Models\hotel;
use App\Models\feature;
use Illuminate\Http\Request;

class FeatureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $features = feature::paginate(5);
        $hotels = hotel::all();
        return view('pages.features.features', compact('features','hotels'));
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
            'name_en' => 'required',
            'name_ar' => 'required',
            'hotel_id' => 'required',
        ],[
            'name_en.required' => 'English Name is required',
            'name_ar.required' => 'Arabic Name is required',
            'hotel_id.required' => 'Hotel is required',

        ]);
        try{
        $feature=new feature();
        $feature->name_en=$request->name_en;
        $feature->name_ar=$request->name_ar;
        $feature->hotel_id=$request->hotel_id;
        $feature->save();
        return redirect()->route('features.index')->with('success', 'Feature Added Successfully');
        }catch(\Exception $e){
            return redirect()->route('features.index')->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(feature $feature)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(feature $feature)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $request->validate([
            'name_en' => 'required',
            'name_ar' => 'required',
            'hotel_id' => 'required',
        ],[
            'name_en.required' => 'English Name is required',
            'name_ar.required' => 'Arabic Name is required',
            'hotel_id.required' => 'Hotel is required',

        ]);
        try{
        $feature=feature::find($request->id);
        $feature->name_en=$request->name_en;
        $feature->name_ar=$request->name_ar;
        $feature->hotel_id=$request->hotel_id;
        $feature->save();
        return redirect()->route('features.index')->with('success', 'Feature Updated Successfully');
        }catch(\Exception $e){
            return redirect()->route('features.index')->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $feature = feature::find($id);
        $feature->delete();
        return redirect()->back()->with('success', 'Feature Deleted Successfully');
    }
}
