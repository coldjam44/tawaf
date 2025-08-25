<?php

namespace App\Http\Controllers\Apis;

use App\Models\findstay;
use Illuminate\Http\Request;
use App\Http\Trait\GeneralTrait;
use App\Http\Controllers\Controller;

class FindstayController extends Controller
{
use GeneralTrait;
    public function index()
{
    $findstays = findstay::all()->map(function ($findstay) {
        // فك تشفير الحقل age_child إذا كان يحتوي على بيانات
        $findstay->age_child = $findstay->age_child ? json_decode($findstay->age_child, true) : [];
        return $findstay;
    });

    return $this->returnData('findstays', $findstays);
}

    public function store(Request $request)
    {
        $request->validate([
            'check_in_ar' => 'required',
            'check_in_en' => 'required',
            'check_out_ar' => 'required',
            'check_out_en' => 'required',
            'place_id' => 'required',

            'numberofroom' => 'required',

        ],[
        'check_in_ar.required' => 'English Check In Date is required',
        'check_in_en.required' => 'Arabic Check In Date is required',
        'check_out_ar.required' => 'English Check Out Date is required',
        'check_out_en.required' => 'Arabic Check Out Date is required',
        //'place_en.required' => 'English Place is required',
        //'place_ar.required' => 'Arabic Place is required',
        'numberofroom.required' => 'Number of Room is required',
        'place_id.required' => 'place is required',

        ]);
        try {

        $findstay = new findstay();
        $findstay->check_in_ar = $request->check_in_ar;
        $findstay->check_in_en = $request->check_in_en;
        $findstay->check_out_ar = $request->check_out_ar;
        $findstay->check_out_en = $request->check_out_en;
        $findstay->place_id = $request->place_id;
        $findstay->numberofadult = $request->numberofadult;
        $findstay->numberofchild = $request->numberofchild;
                  $findstay->age_child = json_encode($request->age_child);

        $findstay->numberofroom = $request->numberofroom;

        $findstay->save();
        return $this->returnData('findstay',$findstay);
    }
    catch (\Throwable $th) {
        return $this->returnError('E001','error');
    }
}

public function update(Request $request)
{
    $request->validate([
        'check_in_ar' => 'required',
        'check_in_en' => 'required',
        'check_out_ar' => 'required',
        'check_out_en' => 'required',
        'place_id' => 'required',

        'numberofroom' => 'required',

    ],[
    'check_in_ar.required' => 'English Check In Date is required',
    'check_in_en.required' => 'Arabic Check In Date is required',
    'check_out_ar.required' => 'English Check Out Date is required',
    'check_out_en.required' => 'Arabic Check Out Date is required',
    //'place_en.required' => 'English Place is required',
    //'place_ar.required' => 'Arabic Place is required',
    'numberofroom.required' => 'Number of Room is required',
    'place_id.required' => 'place is required',

    ]);
    try {
$findstay = findstay::find($request->id);
        $findstay->update($request->all());

        return $this->returnData('findstay',$findstay);
    }
catch (\Throwable $th) {
    return $this->returnError('E001','error');
}
    }

    public function destroy($id)
    {
        $findstay = findstay::find($id);
        $findstay->delete();
        if(!$findstay){
            return $this->returnError('E001','data not found');
        }else{
            return $this->returnSuccessMessage('data deleted');
        }

    }
}
