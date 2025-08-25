<?php

namespace App\Http\Controllers\Apis;

use App\Models\booknow;
use App\Models\avilableroom;
use Illuminate\Http\Request;
use App\Http\Trait\GeneralTrait;
use App\Http\Controllers\Controller;

class BooknowController extends Controller
{

use GeneralTrait;
    public function index()
    {
        $booknows = booknow::all()->map(function ($booknow) {
            // فك تشفير الحقل age_child إذا كان يحتوي على بيانات
            $booknow->age_child = $booknow->age_child ? json_decode($booknow->age_child, true) : [];
            return $booknow;
        });

        return $this->returnData('booknows', $booknows);
    }

  public function store(Request $request)
{
    // التأكد من إدخال offer_id أو room_id ولكن ليس كليهما
    if ((!$request->filled('offer_id') && !$request->filled('room_id')) || 
        ($request->filled('offer_id') && $request->filled('room_id'))) {
        return $this->returnError('E002', 'You must provide either offer_id or room_id, but not both.');
    }

    // قواعد التحقق الديناميكية
    $rules = [
        'fullname' => 'required',
        'phonenumber' => 'required',
        'specialrequest' => 'nullable',
        'room_id' => 'nullable',
        'numberofroom' => 'required',
        'offer_id' => 'nullable',
    ];

    $messages = [
        'fullname.required' => 'Full Name is required',
        'phonenumber.required' => 'Phone Number is required',
        'numberofroom.required' => 'Number of Room is required',
    ];

    // إذا تم توفير room_id، اجعل الحقول التالية إجبارية
    if ($request->filled('room_id')) {
        $rules['check_in_ar'] = 'required';
        $rules['check_in_en'] = 'required';
        $rules['check_out_ar'] = 'required';
        $rules['check_out_en'] = 'required';

        $messages['check_in_ar.required'] = 'Check-in (Arabic) is required when room_id is provided.';
        $messages['check_in_en.required'] = 'Check-in (English) is required when room_id is provided.';
        $messages['check_out_ar.required'] = 'Check-out (Arabic) is required when room_id is provided.';
        $messages['check_out_en.required'] = 'Check-out (English) is required when room_id is provided.';
    }

    $request->validate($rules, $messages);

    try {
        $booknow = new booknow();
        $booknow->fullname = $request->fullname;
        $booknow->phonenumber = $request->phonenumber;
        $booknow->specialrequest = $request->specialrequest;
        $booknow->check_in_ar = $request->check_in_ar;
        $booknow->check_in_en = $request->check_in_en;
        $booknow->check_out_ar = $request->check_out_ar;
        $booknow->check_out_en = $request->check_out_en;
        $booknow->numberofadult = $request->numberofadult;
        $booknow->numberofchild = $request->numberofchild;
        $booknow->age_child = json_encode($request->age_child);
        $booknow->totalprice = $request->totalprice;

        // حفظ الحقول حسب ما تم إدخاله
        $booknow->room_id = $request->room_id;
        $booknow->offer_id = $request->offer_id;
        $booknow->numberofroom = $request->numberofroom;
        $booknow->save();

        return $this->returnData('booknow', $booknow);
    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'errNum' => 'E001',
            'msg' => $e->getMessage(), // رسالة الخطأ الفعلية
            'trace' => $e->getTraceAsString() // مسار الخطأ، يمكن إزالته بعد التصحيح
        ]);
    }
}



    public function destroy($id)
    {
        $booknow = booknow::find($id);
        $booknow->delete();
        if(!$booknow){
            return $this->returnError('E001','data not found');
        }else{
            return $this->returnSuccessMessage('data deleted');
        }    }
}
