<?php

namespace App\Http\Controllers\Apis;

use App\Models\hotel;
use App\Models\hotelinhome;
use Illuminate\Http\Request;
use App\Http\Trait\GeneralTrait;
use App\Http\Controllers\Controller;

class HotelinhomeController extends Controller
{
use GeneralTrait;
 public function index()
    {
        $hotelinhomes = hotelinhome::with('hotels')->get(); // تحميل العلاقة
        //$hotels=hotel::all();
     $hotelinhomes->transform(function ($hotelinhome) {
        $hotelinhome->hotels->transform(function ($hotel) {
            // إزالة pivot
            $hotel->makeHidden('pivot');
            
            // فك تشفير الصور وتحويلها إلى روابط مباشرة
            $images = json_decode($hotel->image, true);
            $baseUrl = asset('hotel');
            $hotel->image = $images ? array_map(fn($image) => $baseUrl . '/' . $image, $images) : [];
            
            return $hotel;
        });

        return $hotelinhome;
    });
        return $this->returnData('hotelinhomes', $hotelinhomes);
    }  




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


        $hotelinhome = hotelinhome::create(array_merge(
            $request->only([
                'title_ar','title_en','description_ar','description_en',
            ])

        ));

        // Sync amenities
        $hotelinhome->hotels()->sync($request->hotel_ids);

        $hotelinhome->save();
        return $this->returnData('hotelinhome', $hotelinhome);
        }catch(\Exception $e){
            return $this->returnError('E001','error');
        }
    }

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
        $hotelinhome = hotelinhome::find($request->id);
        $hotelinhome->update(array_merge(
            $request->only([
                'title_ar','title_en','description_ar','description_en'
            ]),

        ));
        //$hotelinhome->amenities()->sync($request->amenity_ids);
        $hotelinhome->hotels()->sync($request->input('hotel_ids', [])); // تحديث العلاقة

        return $this->returnData('hotelinhome', $hotelinhome);
        }catch(\Exception $e){
            return $this->returnError('E001','error');
        }
    }

    public function destroy($id)
    {
        $hotelinhome = hotelinhome::find($id);
        $hotelinhome->delete();
        if(!$hotelinhome){
            return $this->returnError('E001','data not found');
        }else{
            return $this->returnSuccessMessage('data deleted');
        }
    }

}
