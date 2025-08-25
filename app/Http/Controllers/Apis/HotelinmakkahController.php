<?php

namespace App\Http\Controllers\Apis;

use Illuminate\Http\Request;
use App\Models\hotelinmakkah;
use App\Http\Trait\GeneralTrait;
use App\Http\Controllers\Controller;

class HotelinmakkahController extends Controller
{
    use GeneralTrait;
public function index()
{
    $hotelinmakkahs = hotelinmakkah::with(['hotels' => function ($query) {
        $query->orderBy('created_at', 'desc'); // ترتيب الفنادق من الأحدث إلى الأقدم داخل العلاقة
    }, 'hotels.pricings'])
        ->orderBy('created_at', 'desc') // ترتيب الأقسام من الأحدث إلى الأقدم
        ->get();

    $hotelinmakkahs->transform(function ($hotelinmakkah) {
        // أخذ 4 فنادق فقط بعد التأكد من ترتيبها في الاستعلام
        $hotelinmakkah->hotels = $hotelinmakkah->hotels->take(4)->values();

        $hotelinmakkah->hotels->transform(function ($hotel) {
            // إزالة pivot
            $hotel->makeHidden('pivot');

            // معالجة الصور
            $images = json_decode($hotel->image, true);
            $baseUrl = asset('hotel');
            $hotel->image = $images ? array_map(fn($image) => $baseUrl . '/' . $image, $images) : [];

            // تحديد التسعير الحالي بناءً على التاريخ
            $currentDate = now();
            $validPricing = $hotel->pricings
                ->filter(fn($pricing) => $currentDate->between($pricing->start_date, $pricing->end_date))
                ->first();

            // إذا لم يكن هناك تسعير حالي، نعرض الأحدث فقط
            $fallbackPricing = $hotel->pricings->sortByDesc('end_date')->first();
            $finalPricing = $validPricing ?? $fallbackPricing;

            // إضافة السعر والخصم كحقول مباشرة
            $hotel->price = $finalPricing ? $finalPricing->price : null;
            $hotel->discount_price = $finalPricing ? $finalPricing->discount_price : null;

            // إزالة العلاقة pricings من الإخراج
            $hotel->unsetRelation('pricings');

            return $hotel;
        });

        return $hotelinmakkah;
    });

    return $this->returnData('hotelinmakkahs', $hotelinmakkahs);
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


           $hotelinmakkah = hotelinmakkah::create(array_merge(
               $request->only([
                   'title_ar','title_en','description_ar','description_en',
               ])

           ));

           // Sync amenities
           $hotelinmakkah->hotels()->sync($request->hotel_ids);

           $hotelinmakkah->save();
           return $this->returnData('hotelinmakkah', $hotelinmakkah);
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
           $hotelinmakkah = hotelinmakkah::find($request->id);
           $hotelinmakkah->update(array_merge(
               $request->only([
                   'title_ar','title_en','description_ar','description_en'
               ]),

           ));
           //$hotelinhome->amenities()->sync($request->amenity_ids);
           $hotelinmakkah->hotels()->sync($request->input('hotel_ids', [])); // تحديث العلاقة

           return $this->returnData('hotelinmakkah', $hotelinmakkah);
           }catch(\Exception $e){
            return $this->returnError('E001','error');
           }
       }

       public function destroy($id)
       {
           $hotelinmakkah = hotelinmakkah::find($id);
           $hotelinmakkah->delete();
           if(!$hotelinmakkah){
               return $this->returnError('E001','data not found');
           }else{
               return $this->returnSuccessMessage('data deleted');
           }
       }

}
