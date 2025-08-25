<?php

namespace App\Http\Controllers;

use App\Models\hotel;
use Illuminate\Http\Request;
use App\Models\hotelinmadina;

class HotelinmadinaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $hotelinmadinas = hotelinmadina::paginate(5);
        //$hotels=hotel::all();
        $hotels = Hotel::where('possition_en', 'Madina')->orWhere('possition_ar', 'مدينة')->get();


        return view('pages.hotelinmadinas.hotelinmadinas', compact('hotelinmadinas','hotels'));
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


        $hotelinmadina = hotelinmadina::create(array_merge(
            $request->only([
                'title_ar','title_en','description_ar','description_en',
            ])

        ));

        // Sync amenities
        $hotelinmadina->hotels()->sync($request->hotel_ids);

        $hotelinmadina->save();
        return redirect()->back()->with('success', 'Hotel in Home Added Successfully');
        }catch(\Exception $e){
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(hotelinmadina $hotelinmadina)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(hotelinmadina $hotelinmadina)
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
        $hotelinmadina = hotelinmadina::find($request->id);
        $hotelinmadina->update(array_merge(
            $request->only([
                'title_ar','title_en','description_ar','description_en'
            ]),

        ));
        //$hotelinhome->amenities()->sync($request->amenity_ids);
        $hotelinmadina->hotels()->sync($request->input('hotel_ids', [])); // تحديث العلاقة

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
        $hotelinmadina = hotelinmadina::find($id);
        $hotelinmadina->delete();
        return redirect()->back()->with('success', 'Hotel in Home Deleted Successfully');
    }
}
