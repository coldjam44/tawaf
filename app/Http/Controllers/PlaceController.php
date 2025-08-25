<?php

namespace App\Http\Controllers;

use App\Models\place;
use Illuminate\Http\Request;

class PlaceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $places = place::paginate(5);
        return view('pages.places.places', compact('places'));
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
            ],[
            'name_en.required' => 'English Name is required',
            'name_ar.required' => 'Arabic Name is required',
            ]);
            try {

            $place = new place();
            $place->name_en = $request->name_en;
            $place->name_ar = $request->name_ar;
            $place->save();
            return redirect()->back()->with('success', 'Place Added Successfully');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $request->validate([
            'name_en' => 'required',
            'name_ar' => 'required',
        ],[
        'name_en.required' => 'English Name is required',
        'name_ar.required' => 'Arabic Name is required',
        ]);
        try {

            $place = place::find($request->id);
            $place->name_en = $request->name_en;
            $place->name_ar = $request->name_ar;
            $place->save();
            return redirect()->back()->with('success', 'Place Updated Successfully');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $place = place::find($id);
        $place->delete();
        return redirect()->back()->with('success', 'Place Deleted Successfully');
    }
}
