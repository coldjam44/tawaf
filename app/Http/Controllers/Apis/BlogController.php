<?php

namespace App\Http\Controllers\Apis;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogImage;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    /**
     * Display a listing of blogs.
     */
    public function index(): JsonResponse
    {
        try {
            $blogs = Blog::with(['images' => function($query) {
                $query->orderBy('order_index', 'asc');
            }])->orderBy('order_index', 'asc')->orderBy('created_at', 'desc')->get();
            
            // Transform main_image to full URL
            $blogs->each(function ($blog) {
                if ($blog->main_image) {
                    $blog->main_image = asset('uploads/blog/main-images/' . $blog->main_image);
                }
            });
            
            return response()->json([
                'status' => 'success',
                'message' => 'Blogs retrieved successfully',
                'data' => $blogs
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve blogs',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created blog.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'title_ar' => 'required|string|max:255',
                'title_en' => 'required|string|max:255',
                'content_ar' => 'required|string',
                'content_en' => 'required|string',
                'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
                'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
                'alt_text_ar.*' => 'nullable|string|max:255',
                'alt_text_en.*' => 'nullable|string|max:255',
                'caption_ar.*' => 'nullable|string|max:500',
                'caption_en.*' => 'nullable|string|max:500',
                'slug' => 'nullable|string|max:255|unique:blogs,slug',
                'status' => 'nullable|in:draft,published',
                'order_index' => 'nullable|integer|min:0',
                'is_active' => 'nullable|boolean',
                'published_at' => 'nullable|date'
            ]);

            // Handle main image upload
            $mainImageName = null;
            if ($request->hasFile('main_image')) {
                $mainImage = $request->file('main_image');
                $mainImageName = time() . '_main_' . Str::slug($request->title_en ?: $request->title_ar) . '.' . $mainImage->getClientOriginalExtension();
                
                $uploadPath = public_path('uploads/blog/main-images');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                
                $mainImage->move($uploadPath, $mainImageName);
            }

            // Create blog
            $blog = Blog::create([
                'title_ar' => $request->title_ar,
                'title_en' => $request->title_en,
                'content_ar' => $request->content_ar,
                'content_en' => $request->content_en,
                'main_image' => $mainImageName,
                'slug' => $request->slug ?: Str::slug($request->title_en ?: $request->title_ar),
                'status' => $request->status ?: 'draft',
                'order_index' => $request->order_index ?: 0,
                'is_active' => $request->has('is_active'),
                'published_at' => $request->published_at
            ]);

            // Handle additional images upload
            if ($request->hasFile('images')) {
                $images = $request->file('images');
                $altTextsAr = $request->input('alt_text_ar', []);
                $altTextsEn = $request->input('alt_text_en', []);
                $captionsAr = $request->input('caption_ar', []);
                $captionsEn = $request->input('caption_en', []);

                $uploadPath = public_path('uploads/blog/images');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }

                foreach ($images as $index => $image) {
                    $imageName = time() . '_' . $blog->id . '_' . $index . '.' . $image->getClientOriginalExtension();
                    $image->move($uploadPath, $imageName);

                    BlogImage::create([
                        'blog_id' => $blog->id,
                        'image_path' => $imageName,
                        'alt_text_ar' => $altTextsAr[$index] ?? null,
                        'alt_text_en' => $altTextsEn[$index] ?? null,
                        'caption_ar' => $captionsAr[$index] ?? null,
                        'caption_en' => $captionsEn[$index] ?? null,
                        'order_index' => $index,
                        'is_active' => true
                    ]);
                }
            }

            // Load images for response
            $blog->load(['images' => function($query) {
                $query->orderBy('order_index', 'asc');
            }]);

            // Transform main_image to full URL
            if ($blog->main_image) {
                $blog->main_image = asset('uploads/blog/main-images/' . $blog->main_image);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Blog created successfully',
                'data' => $blog
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create blog',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified blog.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $blog = Blog::with(['images' => function($query) {
                $query->orderBy('order_index', 'asc');
            }])->findOrFail($id);
            
            // Transform main_image to full URL
            if ($blog->main_image) {
                $blog->main_image = asset('uploads/blog/main-images/' . $blog->main_image);
            }
            
            return response()->json([
                'status' => 'success',
                'message' => 'Blog retrieved successfully',
                'data' => $blog
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Blog not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update the specified blog.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $blog = Blog::findOrFail($id);
            
            $request->validate([
                'title_ar' => 'sometimes|required|string|max:255',
                'title_en' => 'sometimes|required|string|max:255',
                'content_ar' => 'sometimes|required|string',
                'content_en' => 'sometimes|required|string',
                'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
                'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
                'alt_text_ar.*' => 'nullable|string|max:255',
                'alt_text_en.*' => 'nullable|string|max:255',
                'caption_ar.*' => 'nullable|string|max:500',
                'caption_en.*' => 'nullable|string|max:500',
                'slug' => 'nullable|string|max:255|unique:blogs,slug,' . $id,
                'status' => 'nullable|in:draft,published',
                'order_index' => 'nullable|integer|min:0',
                'is_active' => 'nullable|boolean',
                'published_at' => 'nullable|date'
            ]);

            // Handle main image upload
            if ($request->hasFile('main_image')) {
                // Delete old main image if exists
                if ($blog->main_image && file_exists(public_path('uploads/blog/main-images/' . $blog->main_image))) {
                    unlink(public_path('uploads/blog/main-images/' . $blog->main_image));
                }

                $mainImage = $request->file('main_image');
                $mainImageName = time() . '_main_' . Str::slug($request->title_en ?: $request->title_ar) . '.' . $mainImage->getClientOriginalExtension();
                
                $uploadPath = public_path('uploads/blog/main-images');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                
                $mainImage->move($uploadPath, $mainImageName);
                $blog->main_image = $mainImageName;
            }

            // Update blog
            $blog->update([
                'title_ar' => $request->title_ar ?: $blog->title_ar,
                'title_en' => $request->title_en ?: $blog->title_en,
                'content_ar' => $request->content_ar ?: $blog->content_ar,
                'content_en' => $request->content_en ?: $blog->content_en,
                'slug' => $request->slug ?: $blog->slug,
                'status' => $request->status ?: $blog->status,
                'order_index' => $request->order_index !== null ? $request->order_index : $blog->order_index,
                'is_active' => $request->has('is_active') ? $request->boolean('is_active') : $blog->is_active,
                'published_at' => $request->published_at ?: $blog->published_at
            ]);

            // Handle additional images upload
            if ($request->hasFile('images')) {
                $images = $request->file('images');
                $altTextsAr = $request->input('alt_text_ar', []);
                $altTextsEn = $request->input('alt_text_en', []);
                $captionsAr = $request->input('caption_ar', []);
                $captionsEn = $request->input('caption_en', []);

                $uploadPath = public_path('uploads/blog/images');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }

                foreach ($images as $index => $image) {
                    $imageName = time() . '_' . $blog->id . '_' . $index . '.' . $image->getClientOriginalExtension();
                    $image->move($uploadPath, $imageName);

                    BlogImage::create([
                        'blog_id' => $blog->id,
                        'image_path' => $imageName,
                        'alt_text_ar' => $altTextsAr[$index] ?? null,
                        'alt_text_en' => $altTextsEn[$index] ?? null,
                        'caption_ar' => $captionsAr[$index] ?? null,
                        'caption_en' => $captionsEn[$index] ?? null,
                        'order_index' => $blog->images()->count() + $index,
                        'is_active' => true
                    ]);
                }
            }

            // Load images for response
            $blog->load(['images' => function($query) {
                $query->orderBy('order_index', 'asc');
            }]);

            // Transform main_image to full URL
            if ($blog->main_image) {
                $blog->main_image = asset('uploads/blog/main-images/' . $blog->main_image);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Blog updated successfully',
                'data' => $blog
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update blog',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified blog.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $blog = Blog::with('images')->findOrFail($id);
            
            // Delete main image if exists
            if ($blog->main_image && file_exists(public_path('uploads/blog/main-images/' . $blog->main_image))) {
                unlink(public_path('uploads/blog/main-images/' . $blog->main_image));
            }

            // Delete additional images
            foreach ($blog->images as $image) {
                if (file_exists(public_path('uploads/blog/images/' . $image->image_path))) {
                    unlink(public_path('uploads/blog/images/' . $image->image_path));
                }
            }

            // Delete blog (this will cascade delete images)
            $blog->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Blog deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete blog',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a specific blog image.
     */
    public function deleteImage(string $blogId, string $imageId): JsonResponse
    {
        try {
            $blog = Blog::findOrFail($blogId);
            $image = BlogImage::where('blog_id', $blogId)->findOrFail($imageId);
            
            // Delete image file
            if (file_exists(public_path('uploads/blog/images/' . $image->image_path))) {
                unlink(public_path('uploads/blog/images/' . $image->image_path));
            }

            $image->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Blog image deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete blog image',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get blog by slug.
     */
    public function showBySlug(string $slug): JsonResponse
    {
        try {
            $blog = Blog::with(['images' => function($query) {
                $query->orderBy('order_index', 'asc');
            }])->where('slug', $slug)->firstOrFail();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Blog retrieved successfully',
                'data' => $blog
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Blog not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }
}
