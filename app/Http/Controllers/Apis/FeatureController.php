<?php

namespace App\Http\Controllers\Apis;

use App\Models\hotel;
use App\Models\feature;
use Illuminate\Http\Request;
use App\Http\Trait\GeneralTrait;
use App\Http\Controllers\Controller;

class FeatureController extends Controller
{
    use GeneralTrait;

    public function index()
    {
        $features = feature::all();
        $hotels = hotel::all();
        return $this->returnData('features', $features);

    }

    public function store(Request $request)
    {
        $request->validate([
            'name_en' => 'required',
            'name_ar' => 'required',
            'hotel_id' => 'required',
        ],[
            'name_en.required' => 'English Name is required',
            'name_ar.required' => 'Arabic Name is required',
            'hotel_id.required' => 'Hotel is required',

        ]);
        try{
        $feature=new feature();
        $feature->name_en=$request->name_en;
        $feature->name_ar=$request->name_ar;
        $feature->hotel_id=$request->hotel_id;
        $feature->save();
        return $this->returnData('feature', $feature);
    }catch(\Exception $e){
        return $this->returnError('E001','error');
    }
    }

    public function update(Request $request)
    {
        $request->validate([
            'name_en' => 'required',
            'name_ar' => 'required',
            'hotel_id' => 'required',
        ],[
            'name_en.required' => 'English Name is required',
            'name_ar.required' => 'Arabic Name is required',
            'hotel_id.required' => 'Hotel is required',

        ]);
        try{
        $feature=feature::find($request->id);
        $feature->name_en=$request->name_en;
        $feature->name_ar=$request->name_ar;
        $feature->hotel_id=$request->hotel_id;
        $feature->save();
        return $this->returnData('feature', $feature);
            }catch(\Exception $e){
                return $this->returnError('E001','error');
            }
    }

    public function destroy($id)
    {
        $feature = feature::find($id);
        $feature->delete();
        if(!$feature){
            return $this->returnError('E001','data not found');
        }else{
            return $this->returnSuccessMessage('data deleted');
        }    }
}
