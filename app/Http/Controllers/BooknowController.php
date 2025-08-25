<?php

namespace App\Http\Controllers;

use App\Models\booknow;
use App\Models\avilableroom;
use App\Models\ramadanoffer;

use Illuminate\Http\Request;

class BooknowController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $booknows = booknow::paginate(5);
        $rooms=avilableroom::all();
              $offers=ramadanoffer::all();

      
        return view('pages.booknows.booknows', compact('booknows','rooms','offers'));
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
            'fullname' => 'required',
            'phonenumber' => 'required',
            'specialrequest'=>'nullable',
          
            'room_id' => 'required',
            'numberofroom' => 'required',

        ],[
            'fullname.required' => 'Full Name is required',
            'phonenumber.required' => 'Phone Number is required',
            //'specialrequest.required' => 'Special Request is required',
           
            //'room_id.required' => 'Room is required',
            'numberofroom.required' => 'Number of Room is required',
        ]);
        try{
        $booknow = new booknow();
        $booknow->fullname = $request->fullname;
        $booknow->phonenumber = $request->phonenumber;
        $booknow->specialrequest = $request->specialrequest;
        $booknow->check_in_ar = $request->check_in_ar;
        $booknow->check_in_en = $request->check_in_en;
        $booknow->check_out_ar = $request->check_out_ar;
        $booknow->check_out_en = $request->check_out_en;
        $booknow->numberofadult=$request->numberofadult;
        $booknow->numberofchild=$request->numberofchild;
        $booknow->age_child = json_encode($request->age_child);

        $booknow->room_id = $request->room_id;
                  $booknow->offer_id = $request->offer_id;

        $booknow->totalprice = $request->totalprice;
        $booknow->numberofroom = $request->numberofroom;
        $booknow->save();
        return redirect()->route('booknows.index')->with('success', 'BookNow created successfully');
        }catch(\Exception $e){
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(booknow $booknow)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(booknow $booknow)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, booknow $booknow)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $booknow = booknow::find($id);
        $booknow->delete();
        return redirect()->back()->with('success', 'BookNow Deleted Successfully');
    }
}
