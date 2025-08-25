<?php

namespace App\Http\Controllers;

use App\Models\term;
use Illuminate\Http\Request;

class TermController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $terms = term::paginate(5);
        return view('pages.terms.terms', compact('terms'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
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

        return redirect()->route('terms.index')->with('success', 'Term Created successfully');
        }catch(\Exception $e){
            return redirect()->route('terms.index')->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(term $term)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(term $term)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
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

            return redirect()->route('terms.index')->with('success', 'Term Updated successfully');
            }catch(\Exception $e){
                return redirect()->route('terms.index')->with('error', $e->getMessage());
            }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $term = term::find($id);
        $term->delete();
        return redirect()->back()->with('success', 'Term Deleted Successfully');
    }
}
