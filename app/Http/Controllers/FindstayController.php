<?php

namespace App\Http\Controllers;

use App\Models\place;
use App\Models\findstay;
use Illuminate\Http\Request;

class FindstayController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $findstays = findstay::paginate(5);
        return view('pages.findstays.findstays', compact('findstays'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    $places = place::all();
        return view('pages.findstays.create', compact('places'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'check_in_ar' => 'required',
            'check_in_en' => 'required',
            'check_out_ar' => 'required',
            'check_out_en' => 'required',
            'place_id' => 'required',

            'numberofroom' => 'required',

        ],[
        'check_in_ar.required' => 'English Check In Date is required',
        'check_in_en.required' => 'Arabic Check In Date is required',
        'check_out_ar.required' => 'English Check Out Date is required',
        'check_out_en.required' => 'Arabic Check Out Date is required',
        //'place_en.required' => 'English Place is required',
        //'place_ar.required' => 'Arabic Place is required',
        'numberofroom.required' => 'Number of Room is required',
        'place_id.required' => 'place is required',

        ]);
        try {

        $findstay = new findstay();
        $findstay->check_in_ar = $request->check_in_ar;
        $findstay->check_in_en = $request->check_in_en;
        $findstay->check_out_ar = $request->check_out_ar;
        $findstay->check_out_en = $request->check_out_en;
        $findstay->place_id = $request->place_id;
        $findstay->numberofadult = $request->numberofadult;
        $findstay->numberofchild = $request->numberofchild;
                      $findstay->age_child = json_encode($request->age_child);

        $findstay->numberofroom = $request->numberofroom;

        $findstay->save();
        return redirect()->route('findstays.index')->with('success', 'FindStay Added Successfully');
    }
    catch (\Throwable $th) {
        return redirect()->back()->with('error', 'Something went wrong');
    }
}

    /**
     * Display the specified resource.
     */
    public function show(findstay $findstay)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(findstay $findstay)
    {
        $places = Place::all(); // Fetch all places for the dropdown

        return view('pages.findstays.edit', compact('findstay', 'places'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, findstay $findstay)
    {
        $request->validate([
            'check_in_ar' => 'required',
            'check_in_en' => 'required',
            'check_out_ar' => 'required',
            'check_out_en' => 'required',
            'place_id' => 'required',

            'numberofroom' => 'required',

        ],[
        'check_in_ar.required' => 'English Check In Date is required',
        'check_in_en.required' => 'Arabic Check In Date is required',
        'check_out_ar.required' => 'English Check Out Date is required',
        'check_out_en.required' => 'Arabic Check Out Date is required',
        //'place_en.required' => 'English Place is required',
        //'place_ar.required' => 'Arabic Place is required',
        'numberofroom.required' => 'Number of Room is required',
        'place_id.required' => 'place is required',

        ]);
        try {

            $findstay->update($request->all());

        return redirect()->route('findstays.index')->with('success', 'FindStay updated successfully');
    }
    catch (\Throwable $th) {
        return redirect()->back()->with('error', 'Something went wrong');
    }
        }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $findstay = findstay::find($id);
        $findstay->delete();
        return redirect()->back()->with('success', 'FindStay Deleted Successfully');
    }
}
