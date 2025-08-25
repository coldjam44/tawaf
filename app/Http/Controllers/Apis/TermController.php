<?php

namespace App\Http\Controllers\Apis;

use App\Models\term;
use Illuminate\Http\Request;
use App\Http\Trait\GeneralTrait;
use App\Http\Controllers\Controller;

class TermController extends Controller
{
use GeneralTrait;

    public function index()
    {
        $terms = term::all();
        return $this->returnData('terms', $terms);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title_ar' => 'required',
            'title_en' => 'required',
           
            'description_ar' => 'required',
            'description_en' => 'required',
            
        ],[
            'title_ar.required' => 'Title  Arabic is required',
            'title_en.required' => 'Title  English is required',
            'description_ar.required' => 'Description  Arabic is required',
            'description_en.required' => 'Description  English is required',
            
        ]);
        try{
        $term=new term();
        $term->title_ar=$request->title_ar;
        $term->title_en=$request->title_en;
       

        $term->description_ar=$request->description_ar;
        $term->description_en=$request->description_en;
      
        
        $term->save();
        return $this->returnData('term', $term);
        }catch(\Exception $ex){
            return $this->returnError('E001','error');

        }
    }

    public function update(Request $request)
    {
         $request->validate([
            'title_ar' => 'required',
            'title_en' => 'required',
           


            'description_ar' => 'required',
            'description_en' => 'required',
           
        ],[
            'title_ar.required' => 'Title  Arabic is required',
            'title_en.required' => 'Title  English is required',
            'description_ar.required' => 'Description  Arabic is required',
            'description_en.required' => 'Description  English is required',
           
        ]);
        try{

            $term = term::findOrFail($request->id);
            $term->title_ar=$request->title_ar;
            $term->title_en=$request->title_en;
            

            $term->description_ar=$request->description_ar;
            $term->description_en=$request->description_en;
          
            $term->save();
            return $this->returnData('term', $term);

            }catch(\Exception $e){
                return $this->returnError('E001','error');

            }
    }

    public function destroy($id)
    {
        $term = term::find($id);
        $term->delete();
        if(!$term){
            return $this->returnError('E001','data not found');
        }else{
            return $this->returnSuccessMessage('data deleted');
        }     }
}
