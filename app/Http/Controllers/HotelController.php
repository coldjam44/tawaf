<?php

namespace App\Http\Controllers;

use App\Models\hotel;
use App\Models\place;
use App\Models\amenity;
use Illuminate\Http\Request;

class HotelController extends Controller
{
 // $hotel = new hotel();
            // $hotel->name_en = $request->name_en;
            // $hotel->name_ar = $request->name_ar;
            // $hotel->place_id = $request->place_id;
            // $hotel->rate = $request->rate;
            // $hotel->price_one_night = $request->price_one_night;
            // $hotel->number_of_night = $request->number_of_night;
            // $hotel->total_price = $request->price_one_night * $request->number_of_night;
            //  if ($request->hasFile('image')) {
            //     $imageNames = [];
            //     foreach ($request->file('image') as $file) {
            //         $imageName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            //         $file->move(public_path('hotel'), $imageName);
            //         $imageNames[] = $imageName;
            //     }
            //     $hotel->image = json_encode($imageNames);
           // }
            // $hotel->save();
    /**
     * Display a listing of the resource.
     */
 public function index()
{
    $hotels = Hotel::with(['amenities', 'pricings'])->paginate(5); // تأكد من إضافة 'pricings'
    return view('pages.hotels.hotels', compact('hotels'));
}




    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $amenities = amenity::all(); // Fetch all amenities
        return view('pages.hotels.create', compact('amenities'));
    }


    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'name_en' => 'required',
    //         'name_ar' => 'required',
    //         'possition_ar' => 'required',
    //         'possition_en' => 'required',
    //         'overview_en' => 'required',
    //         'overview_ar' => 'required',
    //         'feature_ar' => 'required',
    //         'feature_en' => 'required',
    //         'rate' => 'required|integer|between:1,7',
    //         'price'=>'required',
    //         'image' => 'required',
    //         'location_ar' => 'required',
    //         'location_en' => 'required',
    //         'availableroom_image' => 'required',
    //         'availableroom_type_ar' => 'required',
    //         'availableroom_type_en' => 'required',
    //         'availableroom_price' => 'required',
    //         'privacy_ar' => 'required',
    //         'privacy_en' => 'required',
    //         'amenity_ids' => 'required|array',
    //         'amenity_ids.*' => 'exists:amenities,id',



    //     ],[
    //         'name_en.required' => 'English Name is required',
    //         'name_ar.required' => 'Arabic Name is required',
    //         'possition_ar.required' => 'Arabic Position is required',
    //         'possition_en.required' => 'English Position is required',
    //         'overview_en.required' => 'English Overview is required',
    //         'overview_ar.required' => 'Arabic Overview is required',
    //         'feature_ar.required' => 'Arabic Feature is required',
    //         'feature_en.required' => 'English Feature is required',
    //         'rate.required' => 'Rate is required',
    //         'price.required' => 'Price is required',
    //         'image.required' => 'Image is required',
    //         'location_ar.required' => 'Arabic Location is required',
    //         'location_en.required' => 'English Location is required',
    //         'availableroom_image.required' => 'Available Room Image is required',
    //         'availableroom_type_ar.required' => 'Arabic Available Room Type is required',
    //         'availableroom_type_en.required' => 'English Available Room Type is required',
    //         'availableroom_price.required' => 'Available Room Price is required',
    //         'privacy_ar.required' => 'Arabic Privacy is required',
    //         'privacy_en.required' => 'English Privacy is required',
    //         'amenity_ids.required' => 'Amenity is required',
    //         'amenity_ids.*.exists' => 'Amenity not found',

    //     ]);

    //     try {


    //         $hotel = Hotel::create($request->only([
    //         'name_ar',
    //         'name_en',
    //         'possition_ar',
    //         'possition_en',
    //         'overview_en', 'overview_ar', 'feature_ar', 'feature_en',
    //         'rate','price',
    //         'location_ar', 'location_en', 'availableroom_image', 'availableroom_price',
    //         'availableroom_type_ar', 'availableroom_type_en', 'privacy_ar', 'privacy_en',

    //         ]));
    //         if ($request->hasFile('image')) {
    //             $imageNames = [];
    //             foreach ($request->file('image') as $file) {
    //                 $imageName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
    //                 $file->move(public_path('hotel'), $imageName);
    //                 $imageNames[] = $imageName;
    //             }
    //             $hotel->image = json_encode($imageNames);
    //         }

    //         if ($request->hasFile('availableroom_image')) {
    //             $roomImageNames = [];
    //             foreach ($request->file('availableroom_image') as $file) {
    //                 $imageName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
    //                 $file->move(public_path('availableroom_image'), $imageName);
    //                 $roomImageNames[] = $imageName;
    //             }
    //             $hotel->availableroom_image = json_encode($roomImageNames);
    //         }

    //         $hotel->save();
    //         return redirect()->back()->with('success', 'Hotel Added Successfully');
    //     } catch (\Throwable $th) {
    //         return redirect()->back()->with('error', 'Something went wrong');
    //     }
    // }


  public function store(Request $request)
{
    $request->validate([
        'name_en' => 'required',
        'name_ar' => 'required',
        'possition_ar' => 'required',
        'possition_en' => 'required',
        'overview_en' => 'required',
        'overview_ar' => 'required',
        'rate' => 'required|integer|between:1,7',
        //'price' => 'required',
        'image' => 'required|array',
        'location_map' => 'required',
        'amenity_ids' => 'required|array',
        'amenity_ids.*' => 'exists:amenities,id',
        'pricing' => 'required|array', // الأسعار والفترات
        'pricing.*.start_date' => 'required|date',
        'pricing.*.end_date' => 'required|date|after_or_equal:pricing.*.start_date',
        'pricing.*.price' => 'required|numeric|min:0',
        'pricing.*.discount_price' => 'nullable|numeric|min:0',
    ]);

    try {
        // رفع الصور
        $hotelImages = [];
        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $file) {
                $imageName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('hotel'), $imageName);
                $hotelImages[] = $imageName;
            }
        }

        // إنشاء الفندق
        $hotel = Hotel::create(array_merge(
            $request->only([
                'name_ar', 'name_en', 'possition_ar', 'possition_en',
                'overview_en', 'overview_ar', 'rate', 'location_map'
            ]),
            ['image' => json_encode($hotelImages)]
        ));

        // ربط المرافق
        $hotel->amenities()->sync($request->amenity_ids);

        // إضافة الأسعار والفترات
        foreach ($request->pricing as $pricing) {
            $hotel->pricings()->create([
                'start_date' => $pricing['start_date'],
                'end_date' => $pricing['end_date'],
                'price' => $pricing['price'],
                'discount_price' => $pricing['discount_price'] ?? null,
            ]);
        }

        return redirect()->route('hotels.index')->with('success', 'Hotel Added Successfully');
    } catch (\Throwable $th) {
        return redirect()->back()->with('error', 'Something went wrong: ' . $th->getMessage());
    }
}


    /**
     * Display the specified resource.
     */
    public function show(hotel $hotel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(hotel $hotel)
    {
      $hotel->load('pricings');
        $amenities = amenity::all(); // Fetch all amenities

        return view('pages.hotels.edit', compact('hotel','amenities'));

    }

    /**
     * Update the specified resource in storage.
     */
  public function update(Request $request, $id)
{
   $request->validate([
        'name_en' => 'required',
        'name_ar' => 'required',
        'possition_ar' => 'required',
        'possition_en' => 'required',
        'overview_en' => 'required',
        'overview_ar' => 'required',
        'rate' => 'required|integer|between:1,7',
        'image' => 'nullable|array',
        'location_map' => 'required',
        'amenity_ids' => 'required|array',
        'amenity_ids.*' => 'exists:amenities,id',
        'pricing' => 'required|array', // الأسعار والفترات
        'pricing.*.start_date' => 'required|date',
        'pricing.*.end_date' => 'required|date|after_or_equal:pricing.*.start_date',
        'pricing.*.price' => 'required|numeric|min:0',
        'pricing.*.discount_price' => 'nullable|numeric|min:0',
    ]);

    try {
        // الحصول على الفندق المحدد
        $hotel = Hotel::findOrFail($id);

        // إذا كانت هناك صور جديدة، تحميلها
        $hotelImages = [];
        if ($request->hasFile('image')) {
            // حذف الصور القديمة من المجلد (اختياري)
            $existingImages = json_decode($hotel->image, true);
            foreach ($existingImages as $image) {
                $imagePath = public_path('hotel/' . $image);
                if (file_exists($imagePath)) {
                    unlink($imagePath); // حذف الصورة القديمة
                }
            }

            // تحميل الصور الجديدة
            foreach ($request->file('image') as $file) {
                $imageName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('hotel'), $imageName);
                $hotelImages[] = $imageName;
            }
        } else {
            // في حالة عدم وجود صور جديدة، نحتفظ بالصور القديمة
            $hotelImages = json_decode($hotel->image, true); // إذا لم تكن هناك صور جديدة نحتفظ بالصور القديمة
        }

        // تحديث البيانات
        $hotel->update(array_merge(
            $request->only([
                'name_ar', 'name_en', 'possition_ar', 'possition_en',
                'overview_en', 'overview_ar', 'rate', 'location_map'
            ]),
            ['image' => json_encode($hotelImages)]
        ));

        // تحديث الخدمات
        $hotel->amenities()->sync($request->amenity_ids);
      
        $hotel->pricings()->delete(); // حذف الأسعار القديمة
        foreach ($request->pricing as $pricing) {
            $hotel->pricings()->create([
                'start_date' => $pricing['start_date'],
                'end_date' => $pricing['end_date'],
                'price' => $pricing['price'],
                'discount_price' => $pricing['discount_price'] ?? null,
            ]);
        }


        return redirect()->route('hotels.index')->with('success', 'Hotel Updated Successfully');
    } catch (\Throwable $th) {
        return redirect()->back()->with('error', 'Something went wrong: ' . $th->getMessage());
    }
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $hotel = hotel::find($id);
        if (!$hotel) {
            return redirect()->back()->with('error', 'Model image not found');
        }

        // Decode the JSON-encoded image array
        $images = json_decode($hotel->image);

        if (is_array($images)) {
            foreach ($images as $image) {
                $imagePath = public_path('hotel/' . $image);
                if (file_exists($imagePath)) {
                    unlink($imagePath); // Delete the image file
                }
            }
        }


        // Delete the database record
        $hotel->delete();

        return redirect()->back()->with('success', 'Model image and associated files deleted successfully');
        }
}
