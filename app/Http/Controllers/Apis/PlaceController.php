<?php

namespace App\Http\Controllers\Apis;

use App\Models\place;
use Illuminate\Http\Request;
use App\Http\Trait\GeneralTrait;
use App\Http\Controllers\Controller;

class PlaceController extends Controller
{
use GeneralTrait;
    public function index()
    {
        $places = place::all();
        return $this->returnData('places',$places);
    }

    public function store(Request $request)
    {
            $request->validate([
                'name_en' => 'required',
                'name_ar' => 'required',
            ],[
            'name_en.required' => 'English Name is required',
            'name_ar.required' => 'Arabic Name is required',
            ]);
            try {

            $place = new place();
            $place->name_en = $request->name_en;
            $place->name_ar = $request->name_ar;
            $place->save();
            return $this->returnData('place',$place);
        } catch (\Throwable $th) {
            return $this->returnError('E001','error');
        }
    }

    public function update(Request $request)
    {
        $request->validate([
            'name_en' => 'required',
            'name_ar' => 'required',
        ],[
        'name_en.required' => 'English Name is required',
        'name_ar.required' => 'Arabic Name is required',
        ]);
        try {

            $place = place::find($request->id);
            $place->name_en = $request->name_en;
            $place->name_ar = $request->name_ar;
            $place->save();
            return $this->returnData('place',$place);
        } catch (\Throwable $th) {
            return $this->returnError('E001','error');
        }
    }

    public function destroy($id)
    {
        $place = place::find($id);
        $place->delete();
        if(!$place){
            return $this->returnError('E001','data not found');
        }else{
            return $this->returnSuccessMessage('data deleted');
        }
}
}
