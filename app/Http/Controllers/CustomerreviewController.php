<?php

namespace App\Http\Controllers;

use App\Models\hotel;
use Illuminate\Http\Request;
use App\Models\customerreview;

class CustomerreviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customerreviews = customerreview::paginate(5);
        $hotels = hotel::all();
        return view('pages.reviews.reviews', compact('customerreviews','hotels'));
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
            'name' => 'required',
            'description' => 'required',
            'image' => 'nullable',
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

            // Save the uploaded image
            $image = $request->image;
            $imagename = time() . '.' . $image->getClientOriginalExtension();
            $request->image->move(public_path('customerreview'), $imagename);

            $customerreview->image = $imagename;

            // Save the review
            $customerreview->save();

            // Calculate the new average rate for the hotel
            //$averageRate = customerreview::where('hotel_id', $request->hotel_id)->avg('rate');
            $averageRate = CustomerReview::where('hotel_id', $request->hotel_id)->avg('rate');

            // Update the average_rate column for this review
            $customerreview->average_rate = $averageRate;
            $customerreview->save();

            return redirect()->route('reviews.index')->with('success', 'Review added successfully. Average rate updated.');
        } catch (\Exception $e) {
            return redirect()->route('reviews.index')->with('error', $e->getMessage());
        }
    }



    /**
     * Display the specified resource.
     */
    public function show(customerreview $customerreview)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(customerreview $customerreview)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, customerreview $customerreview)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $customerreview = customerreview::find($id);
        $customerreview->delete();
        return redirect()->route('reviews.index')->with('success', 'Review deleted successfully');
    }
}
