<?php

namespace App\Http\Controllers\Apis;

use App\Models\hotel;
use Illuminate\Http\Request;
use App\Models\customerreview;
use App\Http\Trait\GeneralTrait;
use App\Http\Controllers\Controller;

class CustomerreviewController extends Controller
{
use GeneralTrait;
    // public function index()
    // {
    //     $customerreviews = customerreview::all();
    //     $hotels = hotel::all();
    //     $amenitys = $customerreviews->map(function ($amenity) {
    //         // إذا كانت صورة موجودة، يتم تحويلها إلى رابط كامل
    //         if ($amenity->image) {
    //             $amenity->image = url('customerreview/' . $amenity->image);
    //         }
    //         return $amenity;
    //     });
    //     return $this->returnData('customerreviews',$amenitys);
    // }

    public function index(Request $request)
    {
        $hotelId = $request->input('hotel_id'); // الحصول على hotel_id من الطلب
        $perPage = 5; // عدد المراجعات في كل صفحة

        // استعلام المراجعات
        $query = CustomerReview::query();

        // فلترة حسب hotel_id إذا كان موجودًا
        if ($hotelId) {
            $query->where('hotel_id', $hotelId);
        }

        // تنفيذ الاستعلام مع التصفح
        $customerreviews = $query->paginate($perPage);

        // معالجة البيانات لإضافة رابط الصورة الكامل وحساب average_rate
        $reviewsWithFullImageUrl = $customerreviews->getCollection()->map(function ($review) {
            // تحديث الصورة إذا كانت موجودة
            if ($review->image) {
                $review->image = url('customerreview/' . $review->image);
            }

            // حساب أحدث average_rate لكل فندق
            $averageRate = CustomerReview::where('hotel_id', $review->hotel_id)->avg('rate');
            $review->average_rate = number_format($averageRate, 2); // تنسيق الرقم العشري (اختياري)

            return $review;
        });

        // إعداد البيانات للتصفح مع المراجعات المعدلة
        return $this->returnData('customerreviews', $reviewsWithFullImageUrl, [
            'current_page' => $customerreviews->currentPage(),
            'total_pages' => $customerreviews->lastPage(),
            'total_reviews' => $customerreviews->total(),
        ]);
    }



    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'rate' => 'required',
            'hotel_id'=>'required',
        ],[
        'name.required' => 'Name is required',
        'description.required' => 'Description is required',
        'rate.required' => 'Rate is required',
        'hotel_id.required' => 'Hotel is required',
        ]);
        try {
            // Save the new review
            $customerreview = new customerreview();
            $customerreview->name = $request->name;
            $customerreview->description = $request->description;
            $customerreview->rate = $request->rate;
            $customerreview->hotel_id = $request->hotel_id;

            if ($request->hasFile('image')) {
        $image = $request->file('image');
        $imagename = time() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('customerreview'), $imagename);
        $customerreview->image = $imagename;
    }
            // Save the review
            $customerreview->save();

            // Calculate the new average rate for the hotel
            //$averageRate = customerreview::where('hotel_id', $request->hotel_id)->avg('rate');
            $averageRate = CustomerReview::where('hotel_id', $request->hotel_id)->avg('rate');

            // Update the average_rate column for this review
            $customerreview->average_rate = $averageRate;
            $customerreview->save();

            return $this->returnData('customerreview',$customerreview);
        } catch (\Exception $e) {
            return $this->returnError('E001','error');
        }
    }

    public function destroy($id)
    {
        $customerreview = customerreview::find($id);
        $customerreview->delete();
        if(!$customerreview){
            return $this->returnError('E001','data not found');
        }else{
            return $this->returnSuccessMessage('data deleted');
        }    }
}
