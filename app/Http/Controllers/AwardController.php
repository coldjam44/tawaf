<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Award;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AwardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $awards = Award::orderBy('order_index', 'asc')->get();
        return view('awards.index', compact('awards'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('awards.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title_ar' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'description_ar' => 'required|string',
            'description_en' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'year' => 'nullable|string|max:4',
            'category' => 'nullable|string|max:255',
            'order_index' => 'nullable|integer|min:0'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $award = new Award();
            $award->title_ar = $request->title_ar;
            $award->title_en = $request->title_en;
            $award->description_ar = $request->description_ar;
            $award->description_en = $request->description_en;
            $award->year = $request->year;
            $award->category = $request->category;
            $award->order_index = $request->order_index ?: 0;
            $award->is_active = true;

            // Handle image upload
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_award.' . $image->getClientOriginalExtension();
                
                $uploadPath = public_path('awards');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                
                $image->move($uploadPath, $imageName);
                $award->image = $imageName;
            }

            $award->save();

            return redirect()->route('awards.index')
                           ->with('success', trans('main_trans.award_created_successfully'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while creating the award: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $award = Award::findOrFail($id);
        return view('awards.show', compact('award'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $award = Award::findOrFail($id);
        return view('awards.edit', compact('award'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'title_ar' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'description_ar' => 'required|string',
            'description_en' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'year' => 'nullable|string|max:4',
            'category' => 'nullable|string|max:255',
            'order_index' => 'nullable|integer|min:0'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $award = Award::findOrFail($id);
            
            // Handle image upload
            $imageName = $award->image; // Keep existing image if no new one uploaded
            if ($request->hasFile('image')) {
                // Delete old image
                if ($award->image) {
                    $oldImagePath = public_path('awards/' . $award->image);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }

                $image = $request->file('image');
                $imageName = time() . '_award.' . $image->getClientOriginalExtension();
                
                $uploadPath = public_path('awards');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                
                $image->move($uploadPath, $imageName);
            }

            $award->update([
                'title_ar' => $request->title_ar,
                'title_en' => $request->title_en,
                'description_ar' => $request->description_ar,
                'description_en' => $request->description_en,
                'image' => $imageName,
                'year' => $request->year,
                'category' => $request->category,
                'order_index' => $request->order_index ?: 0,
                'is_active' => true
            ]);

            return redirect()->route('awards.index')
                           ->with('success', trans('main_trans.award_updated_successfully'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while updating the award: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Toggle the active status of the specified award.
     */
    public function toggle(string $id)
    {
        try {
            $award = Award::findOrFail($id);
            $oldStatus = $award->is_active;
            $award->is_active = !$award->is_active;
            $award->save();
            
            $message = $award->is_active ? 
                trans('main_trans.award_activated_successfully') : 
                trans('main_trans.award_deactivated_successfully');
            
            return redirect()->back()->with('success', $message);
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while toggling the award status: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $award = Award::findOrFail($id);
            
            // Delete image
            if ($award->image) {
                $imagePath = public_path('awards/' . $award->image);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            
            $award->delete();
            
            return redirect()->route('awards.index')
                           ->with('success', trans('main_trans.award_deleted_successfully'));
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while deleting the award: ' . $e->getMessage());
        }
    }
}
