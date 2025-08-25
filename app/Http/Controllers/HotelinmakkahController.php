<?php

namespace App\Http\Controllers;

use App\Models\hotel;
use Illuminate\Http\Request;
use App\Models\hotelinmakkah;

class HotelinmakkahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $hotelinmakkahs = hotelinmakkah::paginate(5);
       // $hotels=hotel::all();
        $hotels = Hotel::where('possition_en', 'Makkah')->orWhere('possition_ar', 'مكة')->get();


        return view('pages.hotelinmakkahs.hotelinmakkahs', compact('hotelinmakkahs','hotels'));
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
            'title_ar' => 'required',
            'title_en' => 'required',
            'description_ar' => 'required',
            'description_en' => 'required',
            'hotel_ids' => 'required|array',
            'hotel_ids.*' => 'exists:hotels,id',
            ],[
            'title_ar.required' => 'Title Arabic is required',
            'title_en.required' => 'Title English is required',
            'description_ar.required' => 'Description Arabic is required',
            'description_en.required' => 'Description English is required',
           // 'hotel_id.required' => 'Hotel is required',

        ]);
        try{


        $hotelinmakkah = hotelinmakkah::create(array_merge(
            $request->only([
                'title_ar','title_en','description_ar','description_en',
            ])

        ));

        // Sync amenities
        $hotelinmakkah->hotels()->sync($request->hotel_ids);

        $hotelinmakkah->save();
        return redirect()->back()->with('success', 'Hotel in Home Added Successfully');
        }catch(\Exception $e){
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(hotelinmakkah $hotelinmakkah)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(hotelinmakkah $hotelinmakkah)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,)
    {
        $request->validate([
            'title_ar' => 'required',
            'title_en' => 'required',
            'description_ar' => 'required',
            'description_en' => 'required',
            'hotel_ids' => 'required|array',
            'hotel_ids.*' => 'exists:hotels,id',
            ],[
            'title_ar.required' => 'Title Arabic is required',
            'title_en.required' => 'Title English is required',
            'description_ar.required' => 'Description Arabic is required',
            'description_en.required' => 'Description English is required',
            'hotel_ids.required' => 'Hotel is required',
        ]);
        try{
        $hotelinmakkah = hotelinmakkah::find($request->id);
        $hotelinmakkah->update(array_merge(
            $request->only([
                'title_ar','title_en','description_ar','description_en'
            ]),

        ));
        //$hotelinhome->amenities()->sync($request->amenity_ids);
        $hotelinmakkah->hotels()->sync($request->input('hotel_ids', [])); // تحديث العلاقة

        return redirect()->back()->with('success', 'Hotel in Home Updated Successfully');
        }catch(\Exception $e){
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $hotelinmakkah = hotelinmakkah::find($id);
        $hotelinmakkah->delete();
        return redirect()->back()->with('success', 'Hotel in Home Deleted Successfully');
    }
}
