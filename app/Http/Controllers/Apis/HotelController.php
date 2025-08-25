<?php

namespace App\Http\Controllers\Apis;

use Log;
use App\Models\hotel;
use Illuminate\Http\Request;
use App\Http\Trait\GeneralTrait;
use App\Http\Controllers\Controller;
use App\Models\customerreview;
use Carbon\Carbon;


class HotelController extends Controller
{
    use GeneralTrait;







public function index(Request $request)
{
    // قراءة الفلاتر من الطلب
    $minPrice = $request->input('minprice');
    $maxPrice = $request->input('maxprice');
    $minDiscountPrice = $request->input('min_discount_price');
    $maxDiscountPrice = $request->input('max_discount_price');
    $rate = $request->input('rate');
    $amenity = $request->input('amenity');
    $position = $request->input('position');
    $hotelId = $request->input('hotel_id');
    $perPage = 4; // عدد الفنادق في كل صفحة

    // بناء استعلام مع الفلاتر
    $query = Hotel::with(['amenities', 'availableRooms', 'features', 'pricings']);

    // فلترة حسب hotel_id
    if ($hotelId !== null) {
        $query->where('id', $hotelId);
    }

    // فلترة حسب السعر الأدنى والأعلى في "pricings"
    if ($minPrice !== null) {
        $query->whereHas('pricings', function ($q) use ($minPrice) {
            $q->where('price', '>=', $minPrice);
        });
    }

    if ($maxPrice !== null) {
        $query->whereHas('pricings', function ($q) use ($maxPrice) {
            $q->where('price', '<=', $maxPrice);
        });
    }

    // فلترة حسب السعر المخفض (discount_price)
    if ($minDiscountPrice !== null) {
        $query->whereHas('pricings', function ($q) use ($minDiscountPrice) {
            $q->where('discount_price', '>=', $minDiscountPrice);
        });
    }

    if ($maxDiscountPrice !== null) {
        $query->whereHas('pricings', function ($q) use ($maxDiscountPrice) {
            $q->where('discount_price', '<=', $maxDiscountPrice);
        });
    }

    // فلترة حسب التقييم
    if ($rate !== null) {
        $query->where('rate', '>=', $rate);
    }

    // فلترة حسب التسهيلات (amenities)
    if ($amenity !== null) {
        $amenitiesNames = is_array($amenity) ? $amenity : explode(',', $amenity);
        $query->whereHas('amenities', function ($q) use ($amenitiesNames) {
            $q->whereIn('name_en', $amenitiesNames)
              ->orWhereIn('name_ar', $amenitiesNames);
        });
    }

    // فلترة حسب الموقع
    if ($position !== null) {
        $query->where(function ($q) use ($position) {
            $q->where('possition_en', 'like', "%{$position}%")
              ->orWhere('possition_ar', 'like', "%{$position}%");
        });
    }

    // جلب البيانات مع التصفح
    $hotels = $query->paginate($perPage);

    // معالجة البيانات لتحويلها إلى تنسيق API
    $images_data = $hotels->getCollection()->map(function ($hotel) {
        $imagesArray = json_decode($hotel->image, true);

        // جلب جميع التقييمات للفندق
        $reviews = CustomerReview::where('hotel_id', $hotel->id)->get();

        // استخراج التقييمات كقائمة
        $allRates = $reviews->pluck('rate')->toArray();

        // حساب متوسط التقييم
        $averageRate = count($allRates) > 0 ? collect($allRates)->avg() : 0;

        // معالجة الغرف المتاحة (Available Rooms)
        $availableRooms = $hotel->availableRooms->map(function ($room) {
            $roomImagesArray = json_decode($room->availableroom_image, true);

            return [
                'id' => $room->id,
                'availableroom_type_en' => $room->availableroom_type_en,
                'availableroom_type_ar' => $room->availableroom_type_ar,
                'availableroom_price' => $room->availableroom_price,
                'hotel_id' => $room->hotel_id,
                'availableroom_image' => !empty($roomImagesArray)
                    ? collect($roomImagesArray)->map(function ($imageName) {
                        return url('availableroom_image/' . $imageName);
                    })->toArray()
                    : [],
            ];
        });

        // معالجة الميزات (Features)
        $features = $hotel->features->map(function ($feature) {
            return [
                'id' => $feature->id,
                'name_en' => $feature->name_en,
                'name_ar' => $feature->name_ar,
            ];
        });

        // تحديد التسعير الحالي بناءً على التاريخ
        $currentDate = Carbon::now();
        $validPricing = $hotel->pricings
            ->filter(function ($pricing) use ($currentDate) {
                return $currentDate->between(Carbon::parse($pricing->start_date), Carbon::parse($pricing->end_date));
            })
            ->first();

        // إذا لم يكن هناك تسعير حالي، نعرض القديم فقط إذا لم يوجد غيره
        $fallbackPricing = $hotel->pricings->sortByDesc('end_date')->first();

        $finalPricing = $validPricing ?? $fallbackPricing;

        return [
            'id' => $hotel->id,
            'name_en' => $hotel->name_en,
            'name_ar' => $hotel->name_ar,
            'possition_ar' => $hotel->possition_ar,
            'possition_en' => $hotel->possition_en,
            'overview_en' => $hotel->overview_en,
            'overview_ar' => $hotel->overview_ar,
            'rate' => $hotel->rate,
            'all_rates' => $allRates,
            'average_rate' => number_format($averageRate, 2),
            'location_map' => $hotel->location_map,
            'amenities' => $hotel->amenities->map(function ($amenity) {
                $image = !empty($amenity->image) ? url('amenity/' . $amenity->image) : null;

                return [
                    'id' => $amenity->id,
                    'name_en' => $amenity->name_en ?? 'Name Not Available',
                    'name_ar' => $amenity->name_ar ?? 'اسم غير متاح',
                    'image' => $image,
                ];
            }),
            'image' => !empty($imagesArray)
                ? collect($imagesArray)->map(function ($imageName) {
                    return url('hotel/' . $imageName);
                })->toArray()
                : [],
            'available_rooms' => $availableRooms,
            'features' => $features,
            //'pricing_start_date' => $finalPricing ? $finalPricing->start_date : null,
            //'pricing_end_date' => $finalPricing ? $finalPricing->end_date : null,
            'price' => $finalPricing ? $finalPricing->price : null,
            'discount_price' => $finalPricing ? $finalPricing->discount_price : null,
        ];
    });

    // إعادة البيانات مع معلومات التصفح
    return $this->returnData('hotels', $images_data, [
        'current_page' => $hotels->currentPage(),
        'total_pages' => $hotels->lastPage(),
        'total_hotels' => $hotels->total(),
    ]);
}


// public function index(Request $request)
// {
//     // قراءة الفلاتر من الطلب
//     $minPrice = $request->input('minprice');
//     $maxPrice = $request->input('maxprice');
//     $rate = $request->input('rate');
//     $amenity = $request->input('amenity'); // يمكن أن تكون مصفوفة أو سلسلة مفصولة بفواصل
//     $position = $request->input('position');
//     $perPage = 4; // عدد الفنادق في كل صفحة

//     // بناء استعلام مع الفلاتر
//     $query = Hotel::with(['amenities', 'availableRooms', 'features', 'customerreviews']); // تحميل العلاقات

//     // فلترة حسب السعر الأدنى
//     if ($minPrice !== null) {
//         $query->where('price', '>=', $minPrice);
//     }

//     // فلترة حسب السعر الأعلى
//     if ($maxPrice !== null) {
//         $query->where('price', '<=', $maxPrice);
//     }

//     // فلترة حسب التقييم
//     if ($rate !== null) {
//         $query->where('rate', '=', $rate);
//     }

//     // فلترة حسب التسهيلات (amenities)
//     if ($amenity !== null) {
//         $amenitiesNames = is_array($amenity) ? $amenity : explode(',', $amenity); // تحويل السلسلة إلى مصفوفة إذا لزم الأمر
//         $query->whereHas('amenities', function ($q) use ($amenitiesNames) {
//             $q->whereIn('name_en', $amenitiesNames)
//               ->orWhereIn('name_ar', $amenitiesNames);
//         });
//     }

//     // فلترة حسب الموقع
//     if ($position !== null) {
//         $query->where(function ($q) use ($position) {
//             $q->where('possition_en', 'like', "%{$position}%")
//               ->orWhere('possition_ar', 'like', "%{$position}%");
//         });
//     }

//     // جلب البيانات مع التصفح
//     $hotels = $query->paginate($perPage);

//     // معالجة البيانات لتحويلها إلى تنسيق API
//     $images_data = $hotels->getCollection()->map(function ($hotel) {
//         $imagesArray = json_decode($hotel->image, true); // فك تشفير حقل الصورة

//         // معالجة الغرف المتاحة (Available Rooms)
//         $availableRooms = $hotel->availableRooms->map(function ($room) {
//             $roomImagesArray = json_decode($room->availableroom_image, true);

//             return [
//                 'id' => $room->id,
//                 'availableroom_type_en' => $room->availableroom_type_en,
//                 'availableroom_type_ar' => $room->availableroom_type_ar,
//                 'availableroom_price' => $room->availableroom_price,
//                 'hotel_id' => $room->hotel_id,
//                 'availableroom_image' => !empty($roomImagesArray)
//                     ? collect($roomImagesArray)->map(function ($imageName) {
//                         return url('availableroom_image/' . $imageName);
//                     })->toArray()
//                     : [],
//             ];
//         });

//         // معالجة الميزات (Features)
//         $features = $hotel->features->map(function ($feature) {
//             return [
//                 'id' => $feature->id,
//                 'name_en' => $feature->name_en,
//                 'name_ar' => $feature->name_ar,
//             ];
//         });

//         // معالجة المراجعات (Customer Reviews)
//         $customerReviews = $hotel->customerreviews->map(function ($review) {
//             return [
//                 'id' => $review->id,
//                 'name' => $review->name,
//                 'description' => $review->description,
//                 'rate' => $review->rate,
//                 'average_rate' => number_format($review->hotel->customerreviews->avg('rate'), 2), // متوسط التقييم
//                 'image' => url('customerreview/' . $review->image),
//                 'hotel_id' => $review->hotel_id,
//             ];
//         });

//         return [
//             'id' => $hotel->id,
//             'name_en' => $hotel->name_en,
//             'name_ar' => $hotel->name_ar,
//             'possition_ar' => $hotel->possition_ar,
//             'possition_en' => $hotel->possition_en,
//             'overview_en' => $hotel->overview_en,
//             'overview_ar' => $hotel->overview_ar,
//             'rate' => $hotel->rate,
//             'price' => $hotel->price,
//             'x_access' => $hotel->x_access,
//             'y_access' => $hotel->y_access,
//             'privacy_ar' => $hotel->privacy_ar,
//             'privacy_en' => $hotel->privacy_en,
//             'amenities' => $hotel->amenities->map(function ($amenity) {
//                 return [
//                     'id' => $amenity->id,
//                     'name_en' => $amenity->name_en ?? 'Name Not Available',
//                     'name_ar' => $amenity->name_ar ?? 'اسم غير متاح',
//                 ];
//             }),
//             'image' => !empty($imagesArray)
//                 ? collect($imagesArray)->map(function ($imageName) {
//                     return url('hotel/' . $imageName);
//                 })->toArray()
//                 : [],
//             'available_rooms' => $availableRooms,
//             'features' => $features,
//             'customer_reviews' => $customerReviews, // إضافة المراجعات للفندق
//         ];
//     });

//     // إعادة البيانات مع معلومات التصفح
//     return $this->returnData('hotels', $images_data, [
//         'current_page' => $hotels->currentPage(),
//         'total_pages' => $hotels->lastPage(),
//         'total_hotels' => $hotels->total(),
//     ]);
// }







    public function store(Request $request)
{
    // التحقق من صحة المدخلات
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
        // رفع صور الفندق
        $hotelImages = [];
        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $file) {
                // التحقق من صحة الملف
                if ($file->isValid()) {
                    $imageName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('hotel'), $imageName);  // تخزين الصور
                    $hotelImages[] = $imageName;
                } else {
                    return $this->returnError('E002', 'Invalid image file');
                }
            }
        } else {
            return $this->returnError('E003', 'No image files uploaded');
        }

        // إنشاء الفندق في قاعدة البيانات
         $hotel = Hotel::create(array_merge(
            $request->only([
                'name_ar', 'name_en', 'possition_ar', 'possition_en',
                'overview_en', 'overview_ar', 'rate', 'location_map'
            ]),
            ['image' => json_encode($hotelImages)]
        ));
      
       // إضافة الأسعار والفترات
        foreach ($request->pricing as $pricing) {
            $hotel->pricings()->create([
                'start_date' => $pricing['start_date'],
                'end_date' => $pricing['end_date'],
                'price' => $pricing['price'],
                'discount_price' => $pricing['discount_price'] ?? null,
            ]);
        }
      

        // ربط التسهيلات بالفندق
        $amenityIds = $request->input('amenity_ids');  // سيتم إرسالها كمصفوفة
        if (empty($amenityIds)) {
            return $this->returnError('E004', 'No amenities selected');
        }

        // ربط التسهيلات بالفندق عبر العلاقة many-to-many
        $hotel->amenities()->sync($amenityIds);
        $hotel->save();

        // إعادة البيانات بعد الحفظ
        return $this->returnData('hotel', $hotel);
    } catch (\Throwable $th) {
        // تسجيل الخطأ في السجلات
        Log::error('Error occurred while storing hotel: ' . $th->getMessage());
        return $this->returnError('E001', 'An error occurred');
    }
}


    // public function update(Request $request, $id)
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
    //         'price' => 'required',
    //         'x_access' => 'required',
    //         'y_access' => 'required',

    //         'privacy_ar' => 'required',
    //         'privacy_en' => 'required',
    //         'amenity_ids' => 'required|array',
    //         'amenity_ids.*' => 'exists:amenities,id',
    //     ]);

    //     try {
    //         // جلب بيانات الفندق
    //         $hotel = Hotel::findOrFail($id);

    //         // تحديث صور الفندق إذا تم رفع صور جديدة
    //         $hotelImages = json_decode($hotel->image, true) ?? [];
    //         if ($request->hasFile('image')) {
    //             foreach ($request->file('image') as $file) {
    //                 $imageName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
    //                 $file->move(public_path('hotel'), $imageName);
    //                 $hotelImages[] = $imageName;
    //             }
    //         }



    //         // تحديث بيانات الفندق
    //         $hotel->update(array_merge(
    //             $request->only([
    //                 'name_ar',
    //                 'name_en',
    //                 'possition_ar',
    //                 'possition_en',
    //                 'overview_en',
    //                 'overview_ar',
    //                 'feature_ar',
    //                 'feature_en',
    //                 'rate',
    //                 'price',
    //                 'x_access',
    //                 'y_access',
    //                 'privacy_ar',
    //                 'privacy_en',
    //             ]),
    //             [
    //                 'image' => json_encode($hotelImages),
    //             ]
    //         ));

    //         // تحديث الـ amenities المرتبطة
    //         $hotel->amenities()->sync($request->amenity_ids);
    //         $hotel->save();

    //         return $this->returnData('hotel', $hotel);
    //     } catch (\Throwable $th) {
    //         return $this->returnError('E001', 'error');
    //     }
    // }
    public function update(Request $request, $id)
{
    // التحقق من صحة المدخلات
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
      
       foreach ($request->pricing as $pricing) {
            $hotel->pricings()->create([
                'start_date' => $pricing['start_date'],
                'end_date' => $pricing['end_date'],
                'price' => $pricing['price'],
                'discount_price' => $pricing['discount_price'] ?? null,
            ]);
        }


        // تحديث الخدمات
        $hotel->amenities()->sync($request->amenity_ids);

        // إعادة البيانات بعد التحديث
        return $this->returnData('hotel', $hotel);

    } catch (\Throwable $th) {
        // تسجيل الخطأ في السجلات
        Log::error('Error occurred while updating hotel: ' . $th->getMessage());
        return $this->returnError('E001', 'An error occurred');
    }
}


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
        if (!$hotel) {
            return $this->returnError('E001', 'data not found');
        } else {
            return $this->returnSuccessMessage('data deleted');
        }
    }
}
