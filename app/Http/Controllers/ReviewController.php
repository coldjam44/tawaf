<?php

namespace App\Http\Controllers;

use App\Models\review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customerreviews = review::paginate(5);
        return view('pages.customerreviews.customerreviews', compact('customerreviews'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.customerreviews.create');

    }

    /**
     * Store a newly created resource in storage.
     */
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
            return redirect()->route('customerreviews.index')->with('success', 'customerreview Added Successfully');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(review $review)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(review $customerreview)
    {
        return view('pages.customerreviews.edit', compact('customerreview'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, review $customerreview)
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
        return redirect()->route('customerreviews.index')->with('success', 'customerreviews Added Successfully');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $customerreview = review::find($id);
        $customerreview->delete();
        return redirect()->back()->with('success', 'Review Deleted Successfully');
    }
}
