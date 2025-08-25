<?php

namespace App\Http\Controllers;

use App\Models\amenity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class AmenityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $amenitys = amenity::paginate(5);
        return view('pages.amenities.amenities', compact('amenitys'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.amenities.create');

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name_en' => 'required',
            'name_ar' => 'required',
            'image' => 'required',
        ],[
            'name_en.required' => 'English Name is required',
            'name_ar.required' => 'Arabic Name is required',
            'image.required' => 'Image is required',
        ]);
        try{
        $amenity=new amenity();
        $amenity->name_en=$request->name_en;
        $amenity->name_ar=$request->name_ar;
        $image = $request->image;
        $imagename = time() . '.' . $image->getClientOriginalExtension();
        $request->image->move(public_path('amenity'), $imagename);
        $amenity->image=$imagename;
        $amenity->save();
        return redirect()->route('amenitys.index')->with('success', 'FindStay Added Successfully');
        }catch(\Exception $e){
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(amenity $amenity)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(amenity $amenity)
    {
        return view('pages.amenities.edit', compact('amenity'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, amenity $amenity)
    {
        $request->validate([
            'name_en' => 'required',
            'name_ar' => 'required',
            //'image' => 'required',
        ],[
            'name_en.required' => 'English Name is required',
            'name_ar.required' => 'Arabic Name is required',
            //'image.required' => 'Image is required',
        ]);
        try{
        $amenity->name_en=$request->name_en;
        $amenity->name_ar=$request->name_ar;
        if ($request->hasFile('image')) {
            // Delete the old image if it exists
            $oldImage = public_path('amenity/' . $amenity->image);
            if (File::exists($oldImage)) {
                File::delete($oldImage);
            }

            // Upload the new image
            $image = $request->image;
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('amenity'), $imageName);

            // Update the image name in the database
            $amenity->image = $imageName;
        }
        $amenity->save();
        return redirect()->route('amenitys.index')->with('success', 'FindStay updated successfully');
        }catch(\Exception $e){
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $amenity=amenity::find($id);
        $amenity->delete();
        return redirect()->back()->with('success', 'Amenity Deleted Successfully');
    }
}
