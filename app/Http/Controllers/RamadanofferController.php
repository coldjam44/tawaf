<?php

namespace App\Http\Controllers;

use App\Models\hotel;
use App\Models\ramadanoffer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class RamadanofferController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ramadanoffers = ramadanoffer::paginate(5);
        $hotels = hotel::all();
        return view('pages.ramadanoffers.ramadanoffers', compact('ramadanoffers','hotels'));
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
            'price' => 'required',
            'image' => 'required',
            'number_of_night' => 'required',
            'roomtype_en' => 'required',
            'roomtype_ar' => 'required',
            'hotelname_en' => 'required',
            'hotelname_ar' => 'required',
            'title_en' => 'required',
            'title_ar' => 'required',
                      'breakfast_price' => 'required',
            'suhoor_price' => 'required',

        ],[
            'price.required' => 'price is required',
            'image.required' => 'image is required',
            'number_of_night.required' => 'number of night is required',
            'roomtype_en.required' => 'number of room en is required',
            'roomtype_ar.required' => 'number of room ar is required',
            'hotelname_en.required' => 'hotel id is required',
            'hotelname_ar.required' => 'hotel id is required',
            'title_en.required' => 'title en is required',
            'title_ar.required' => 'title ar is required',
                      'breakfast_price.required' => 'breakfast_price is required',

                      'suhoor_price.required' => 'suhoor_price is required',

        ]);
        try{
        $ramadanoffer=new ramadanoffer();
        $ramadanoffer->price=$request->price;
                  $ramadanoffer->breakfast_price=$request->breakfast_price;
                  $ramadanoffer->suhoor_price=$request->suhoor_price;


        $ramadanoffer->number_of_night=$request->number_of_night;
        $ramadanoffer->roomtype_en=$request->roomtype_en;
        $ramadanoffer->roomtype_ar=$request->roomtype_ar;
        $ramadanoffer->title_en=$request->title_en;
        $ramadanoffer->title_ar=$request->title_ar;
        $ramadanoffer->hotelname_en=$request->hotelname_en;
        $ramadanoffer->hotelname_ar=$request->hotelname_ar;
        $image = $request->image;
            $imagename = time() . '.' . $image->getClientOriginalExtension();
            $request->image->move(public_path('ramadanoffer'), $imagename);

            $ramadanoffer->image = $imagename;
        $ramadanoffer->save();
        return redirect()->back()->with('success', 'Ramadan Offer Added Successfully');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Something went wrong');
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(ramadanoffer $ramadanoffer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ramadanoffer $ramadanoffer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $request->validate([
            'price' => 'required',
            //'image' => 'required',
            'number_of_night' => 'required',
            'roomtype_en' => 'required',
            'roomtype_ar' => 'required',
            'title_en' => 'required',
            'title_ar' => 'required',
            'hotelname_en' => 'required',
            'hotelname_ar' => 'required',
           'breakfast_price' => 'required',
            'suhoor_price' => 'required',
        ],[
       'price.required' => 'price is required',
       //'image.required' => 'image is required',
       'number_of_night.required' => 'number of night is required',
       'roomtype_en.required' => 'number of room en is required',
       'roomtype_ar.required' => 'number of room ar is required',
       'title_en.required' => 'title en is required',
       'title_ar.required' => 'title ar is required',
       'hotelname_en.required' => 'hotel id is required',
       'hotelname_ar.required' => 'hotel id is required',
           'breakfast_price.required' => 'breakfast_price is required',

                      'suhoor_price.required' => 'suhoor_price is required',
        ]);
        try{
        $ramadanoffer=ramadanoffer::find($request->id);
        $ramadanoffer->price=$request->price;
            $ramadanoffer->breakfast_price=$request->breakfast_price;
                  $ramadanoffer->suhoor_price=$request->suhoor_price;

        $ramadanoffer->number_of_night=$request->number_of_night;
        $ramadanoffer->roomtype_en=$request->roomtype_en;
        $ramadanoffer->roomtype_ar=$request->roomtype_ar;
        $ramadanoffer->title_en=$request->title_en;
        $ramadanoffer->title_ar=$request->title_ar;
        $ramadanoffer->hotelname_en=$request->hotelname_en;
        $ramadanoffer->hotelname_ar=$request->hotelname_ar;
        if ($request->hasFile('image')) {
            // Delete the old image if it exists
            $oldImage = public_path('ramadanoffer/' . $ramadanoffer->image);
            if (File::exists($oldImage)) {
                File::delete($oldImage);
            }

            // Upload the new image
            $image = $request->image;
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('ramadanoffer'), $imageName);

            // Update the image name in the database
            $ramadanoffer->image = $imageName;
        }
        $ramadanoffer->save();
        return redirect()->back()->with('success', 'Ramadan Offer Updated Successfully');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $ramadanoffer=ramadanoffer::find($id);
        $ramadanoffer->delete();
        return redirect()->back()->with('success', 'Ramadan Offer Deleted Successfully');
    }
}
