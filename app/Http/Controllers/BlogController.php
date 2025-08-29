<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Blog;
use App\Models\BlogImage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    /**
     * Display a listing of blogs.
     */
    public function index()
    {
        $blogs = Blog::with(['images' => function($query) {
            $query->orderBy('order_index', 'asc');
        }])->orderBy('order_index', 'asc')->orderBy('created_at', 'desc')->get();
        
        return view('blogs.index', compact('blogs'));
    }

    /**
     * Show the form for creating a new blog.
     */
    public function create()
    {
        return view('blogs.create');
    }

    /**
     * Store a newly created blog.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title_ar' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'content_ar' => 'required|string',
            'content_en' => 'required|string',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'status' => 'nullable|in:draft,published',
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
                $mainImageName = time() . '_main_' . Str::slug($request->title_en) . '.' . $image->getClientOriginalExtension();
                
                $uploadPath = public_path('blog/main-images');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                
                $image->move($uploadPath, $mainImageName);
            }

            // Create blog
            $blog = Blog::create([
                'title_ar' => $request->title_ar,
                'title_en' => $request->title_en,
                'content_ar' => $request->content_ar,
                'content_en' => $request->content_en,
                'main_image' => $mainImageName,
                'status' => $request->status ?: 'draft',
                'order_index' => $request->order_index ?: 0,
                'is_active' => $request->has('is_active'),
                'published_at' => $request->status === 'published' ? now() : null
            ]);

            // Handle additional images
            if ($request->hasFile('images')) {
                $uploadPath = public_path('blog/images');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }

                foreach ($request->file('images') as $index => $image) {
                    if ($image->isValid()) {
                        $imageName = time() . '_' . $blog->id . '_' . $index . '.' . $image->getClientOriginalExtension();
                        $image->move($uploadPath, $imageName);

                        BlogImage::create([
                            'blog_id' => $blog->id,
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

            return redirect()->route('blogs.index')
                           ->with('success', trans('main_trans.blog_created_successfully'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while creating the blog: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified blog.
     */
    public function show($id)
    {
        $blog = Blog::with('images')->findOrFail($id);
        return view('blogs.show', compact('blog'));
    }

    /**
     * Show the form for editing the specified blog.
     */
    public function edit($id)
    {
        try {
            $blog = Blog::with(['images' => function($query) {
                $query->orderBy('order_index', 'asc');
            }])->findOrFail($id);
            
            \Log::info('Blog found for editing: ' . $blog->id . ' - ' . $blog->title_ar);
            
            return view('blogs.edit', compact('blog'));
        } catch (\Exception $e) {
            \Log::error('Blog Edit Error: ' . $e->getMessage());
            return redirect()->route('blogs.index')->with('error', 'Blog not found');
        }
    }

    /**
     * Update the specified blog.
     */
    public function update(Request $request, $id)
    {
        \Log::info('Blog Update Request: ', $request->all());
        
        $validator = Validator::make($request->all(), [
            'title_ar' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'content_ar' => 'required|string',
            'content_en' => 'required|string',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'status' => 'nullable|in:draft,published',
            'order_index' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean'
        ]);

        if ($validator->fails()) {
            \Log::error('Blog Update Validation Failed: ' . json_encode($validator->errors()));
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'يرجى التحقق من البيانات المدخلة - ' . json_encode($validator->errors()));
        }

        try {
            $blog = Blog::findOrFail($id);
            
            // Handle main image upload
            $mainImageName = $blog->main_image; // Keep existing image if no new one uploaded
            if ($request->hasFile('main_image')) {
                // Delete old main image
                if ($blog->main_image) {
                    $oldImagePath = public_path('blog/main-images/' . $blog->main_image);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }

                $image = $request->file('main_image');
                $mainImageName = time() . '_main_' . Str::slug($request->title_en) . '.' . $image->getClientOriginalExtension();
                
                $uploadPath = public_path('blog/main-images');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                
                $image->move($uploadPath, $mainImageName);
            }

            // Update blog
            $updateData = [
                'title_ar' => $request->title_ar,
                'title_en' => $request->title_en,
                'content_ar' => $request->content_ar,
                'content_en' => $request->content_en,
                'main_image' => $mainImageName,
                'status' => $request->status ?: 'draft',
                'order_index' => $request->order_index ?: 0,
                'is_active' => $request->has('is_active')
            ];

            // Handle published_at
            if ($request->status === 'published' && $blog->status !== 'published') {
                $updateData['published_at'] = now();
            } elseif ($request->status === 'draft') {
                $updateData['published_at'] = null;
            }

            $blog->update($updateData);
            
            \Log::info('Blog Updated Successfully: ' . $blog->id . ' - ' . $blog->title_ar);

            // Handle additional images
            if ($request->hasFile('images')) {
                $uploadPath = public_path('blog/images');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }

                foreach ($request->file('images') as $index => $image) {
                    if ($image->isValid()) {
                        $imageName = time() . '_' . $blog->id . '_' . $index . '.' . $image->getClientOriginalExtension();
                        $image->move($uploadPath, $imageName);

                        BlogImage::create([
                            'blog_id' => $blog->id,
                            'image_path' => $imageName,
                            'alt_text_ar' => $image->getClientOriginalName(),
                            'alt_text_en' => $image->getClientOriginalName(),
                            'caption_ar' => null,
                            'caption_en' => null,
                            'order_index' => $blog->images()->count() + $index,
                            'is_active' => true
                        ]);
                    }
                }
            }

            return redirect()->route('blogs.index')
                           ->with('success', trans('main_trans.blog_updated_successfully'));

        } catch (\Exception $e) {
            \Log::error('Blog Update Error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', 'An error occurred while updating the blog: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified blog.
     */
    public function destroy($id)
    {
        try {
            $blog = Blog::with('images')->findOrFail($id);
            
            // Delete main image
            if ($blog->main_image) {
                $mainImagePath = public_path('blog/main-images/' . $blog->main_image);
                if (file_exists($mainImagePath)) {
                    unlink($mainImagePath);
                }
            }
            
            // Delete additional images
            foreach ($blog->images as $image) {
                $imagePath = public_path('blog/images/' . $image->image_path);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            
            $blog->delete();
            
            return redirect()->route('blogs.index')
                           ->with('success', trans('main_trans.blog_deleted_successfully'));
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while deleting the blog: ' . $e->getMessage());
        }
    }

    /**
     * Delete a specific blog image
     */
    public function deleteImage($blogId, $imageId)
    {
        try {
            $image = BlogImage::where('blog_id', $blogId)->findOrFail($imageId);
            
            // Delete image file
            $imagePath = public_path('blog/images/' . $image->image_path);
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
