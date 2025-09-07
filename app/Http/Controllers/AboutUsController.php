<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AboutUsSection;
use App\Models\AboutUsImage;
use Illuminate\Support\Facades\Validator;

class AboutUsController extends Controller
{
    /**
     * Display a listing of about us sections.
     */
    public function index()
    {
        $sections = AboutUsSection::with(['images' => function($query) {
            $query->orderBy('order_index', 'asc');
        }])->orderBy('order_index', 'asc')->get();
        
        return view('about-us.index', compact('sections'));
    }

    /**
     * Show the form for creating a new about us section.
     */
    public function create()
    {
        return view('about-us.create');
    }

    /**
     * Store a newly created about us section.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'section_name' => 'required|string|max:255|unique:about_us_sections',
            'title_ar' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'content_ar' => 'required|string',
            'content_en' => 'required|string',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'order_index' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            // Handle main image upload
            $mainImageName = null;
            if ($request->hasFile('main_image')) {
                $image = $request->file('main_image');
                $mainImageName = time() . '_main_' . $request->section_name . '.' . $image->getClientOriginalExtension();
                
                $uploadPath = public_path('about-us/main-images');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                
                $image->move($uploadPath, $mainImageName);
            }

            // Create section
            $section = AboutUsSection::create([
                'section_name' => $request->section_name,
                'title_ar' => $request->title_ar,
                'title_en' => $request->title_en,
                'content_ar' => $request->content_ar,
                'content_en' => $request->content_en,
                'main_image' => $mainImageName,
                'order_index' => $request->order_index ?: 0,
                'is_active' => $request->has('is_active')
            ]);

            // Handle additional images
            if ($request->hasFile('images')) {
                $uploadPath = public_path('about-us/images');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }

                foreach ($request->file('images') as $index => $image) {
                    if ($image->isValid()) {
                        $imageName = time() . '_' . $section->id . '_' . $index . '.' . $image->getClientOriginalExtension();
                        $image->move($uploadPath, $imageName);

                        AboutUsImage::create([
                            'about_us_section_id' => $section->id,
                            'image_path' => $imageName,
                            'alt_text_ar' => $image->getClientOriginalName(),
                            'alt_text_en' => $image->getClientOriginalName(),
                            'caption_ar' => null,
                            'caption_en' => null,
                            'order_index' => $index,
                            'is_active' => true
                        ]);
                    }
                }
            }

            return redirect()->route('about-us.index')
                           ->with('success', trans('main_trans.about_us_section_created_successfully'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while creating the about us section: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Show the form for editing the specified about us section.
     */
    public function edit($id)
    {
        $section = AboutUsSection::with('images')->findOrFail($id);
        return view('about-us.edit', compact('section'));
    }

    /**
     * Update the specified about us section.
     */
    public function update(Request $request, $id)
    {
        // No validation - basic form submission

        $section = AboutUsSection::findOrFail($id);
        
        // Handle main image upload
        $mainImageName = $section->main_image;
        if ($request->hasFile('main_image')) {
            $image = $request->file('main_image');
            $mainImageName = time() . '_main.' . $image->getClientOriginalExtension();
            
            $uploadPath = public_path('about-us/main-images');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            $image->move($uploadPath, $mainImageName);
        }

        // Update section
        $section->update([
            'section_name' => $request->section_name ?: $section->section_name,
            'title_ar' => $request->title_ar ?: $section->title_ar,
            'title_en' => $request->title_en ?: $section->title_en,
            'content_ar' => $request->content_ar ?: $section->content_ar,
            'content_en' => $request->content_en ?: $section->content_en,
            'main_image' => $mainImageName,
            'order_index' => $request->order_index ?: $section->order_index,
            'is_active' => $request->has('is_active')
        ]);

        // Handle additional images
        if ($request->hasFile('images')) {
            $uploadPath = public_path('about-us/images');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            foreach ($request->file('images') as $index => $image) {
                $imageName = time() . '_' . $section->id . '_' . $index . '.' . $image->getClientOriginalExtension();
                $image->move($uploadPath, $imageName);

                AboutUsImage::create([
                    'about_us_section_id' => $section->id,
                    'image_path' => $imageName,
                    'alt_text_ar' => $image->getClientOriginalName(),
                    'alt_text_en' => $image->getClientOriginalName(),
                    'caption_ar' => null,
                    'caption_en' => null,
                    'order_index' => $section->images()->count() + $index,
                    'is_active' => true
                ]);
            }
        }

        return redirect()->route('about-us.index')->with('success', 'Section updated successfully');
    }

    /**
     * Remove the specified about us section.
     */
    public function destroy($id)
    {
        try {
            $section = AboutUsSection::with('images')->findOrFail($id);
            
            // Delete main image
            if ($section->main_image) {
                $mainImagePath = public_path('about-us/main-images/' . $section->main_image);
                if (file_exists($mainImagePath)) {
                    unlink($mainImagePath);
                }
            }
            
            // Delete additional images
            foreach ($section->images as $image) {
                $imagePath = public_path('about-us/images/' . $image->image_path);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            
            $section->delete();
            
            return redirect()->route('about-us.index')
                           ->with('success', trans('main_trans.about_us_section_deleted_successfully'));
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while deleting the about us section: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified about us section.
     */
    public function show($id)
    {
        $section = AboutUsSection::with(['images' => function($query) {
            $query->orderBy('order_index', 'asc');
        }])->findOrFail($id);
        
        return view('about-us.show', compact('section'));
    }

    /**
     * Show about us sections in a public view.
     */
    public function showPublic()
    {
        $sections = AboutUsSection::with(['activeImages' => function($query) {
            $query->orderBy('order_index', 'asc');
        }])->active()->ordered()->get();
        
        return view('about-us.public-show', compact('sections'));
    }

    /**
     * Delete a specific image from a section.
     */
    public function deleteImage($sectionId, $imageId)
    {
        try {
            $image = AboutUsImage::where('about_us_section_id', $sectionId)->findOrFail($imageId);
            
            // Delete image file
            $imagePath = public_path('about-us/images/' . $image->image_path);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
            
            $image->delete();
            
            return redirect()->back()->with('success', trans('main_trans.image_deleted_successfully'));
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while deleting the image: ' . $e->getMessage());
        }
    }
}
