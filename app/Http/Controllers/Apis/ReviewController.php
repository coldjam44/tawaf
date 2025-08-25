<?php

namespace App\Http\Controllers\Apis;

use App\Models\review;
use Illuminate\Http\Request;
use App\Http\Trait\GeneralTrait;
use App\Http\Controllers\Controller;

class ReviewController extends Controller
{
use GeneralTrait;
    public function index()
    {
        $customerreviews = review::all();
        return $this->returnData('customerreviews',$customerreviews);

    }

    public function store(Request $request)
    {
        $request->validate([
            'name_en' => 'required',
            'name_ar' => 'required',
            'possition_en' => 'required',
            'possition_ar' => 'required',
            'rate' => 'required|integer|between:1,5',
            'comment_en' => 'required',
            'comment_ar' => 'required',
            'description_en' => 'required',
            'description_ar' => 'required',
        ],[
        'name_en.required' => 'English Name is required',
        'name_ar.required' => 'Arabic Name is required',
        'possition_en.required' => 'English Position is required',
        'possition_ar.required' => 'Arabic Position is required',
        'rate.required' => 'Rate is required',
        'comment_en.required' => 'English Comment is required',
        'comment_ar.required' => 'Arabic Comment is required',
        'description_en.required' => 'English Description is required',
        'description_ar.required' => 'Arabic Description is required',
        ]);
        try{
            $customerreview = new review();
            $customerreview->name_en = $request->name_en;
            $customerreview->name_ar = $request->name_ar;
            $customerreview->possition_en = $request->possition_en;
            $customerreview->possition_ar = $request->possition_ar;
            $customerreview->rate = $request->rate;
            $customerreview->comment_en = $request->comment_en;
            $customerreview->comment_ar = $request->comment_ar;
            $customerreview->description_en = $request->description_en;
            $customerreview->description_ar = $request->description_ar;
            $customerreview->save();
            return $this->returnData('customerreview',$customerreview);
        } catch (\Throwable $th) {
            return $this->returnError('E001','error');
        }
    }

    public function update(Request $request)
    {
        $request->validate([
            'name_en' => 'required',
            'name_ar' => 'required',
            'possition_en' => 'required',
            'possition_ar' => 'required',
            'rate' => 'required|integer|between:1,5',
            'comment_en' => 'required',
            'comment_ar' => 'required',
            'description_en' => 'required',
            'description_ar' => 'required',
        ],[
        'name_en.required' => 'English Name is required',
        'name_ar.required' => 'Arabic Name is required',
        'possition_en.required' => 'English Position is required',
        'possition_ar.required' => 'Arabic Position is required',
        'rate.required' => 'Rate is required',
        'comment_en.required' => 'English Comment is required',
        'comment_ar.required' => 'Arabic Comment is required',
        'description_en.required' => 'English Description is required',
        'description_ar.required' => 'Arabic Description is required',
        ]);
        try{

$customerreview = review::find($request->id);
        $customerreview->name_en = $request->name_en;
        $customerreview->name_ar = $request->name_ar;
        $customerreview->possition_en = $request->possition_en;
        $customerreview->possition_ar = $request->possition_ar;
        $customerreview->rate = $request->rate;
        $customerreview->comment_en = $request->comment_en;
        $customerreview->comment_ar = $request->comment_ar;
        $customerreview->description_en = $request->description_en;
        $customerreview->description_ar = $request->description_ar;
        $customerreview->save();
        return $this->returnData('customerreview',$customerreview);
        } catch (\Throwable $th) {
            return $this->returnError('E001','error');
        }
    }

    public function destroy($id)
    {
        $customerreview = review::find($id);
        $customerreview->delete();
        if(!$customerreview){
            return $this->returnError('E001','data not found');
        }else{
            return $this->returnSuccessMessage('data deleted');
        }    }
}
