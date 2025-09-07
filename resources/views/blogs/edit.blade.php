@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">{{ trans('main_trans.edit_blog') }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('blogsection.update', $blog->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        {{-- Basic Information --}}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="title_ar" class="form-label">Title (Arabic) *</label>
                                    <input type="text" name="title_ar" id="title_ar" class="form-control" value="{{ old('title_ar', $blog->title_ar) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="title_en" class="form-label">Title (English) *</label>
                                    <input type="text" name="title_en" id="title_en" class="form-control" value="{{ old('title_en', $blog->title_en) }}" required>
                                </div>
                            </div>
                        </div>

                        {{-- Content --}}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="content_ar" class="form-label">Content (Arabic) *</label>
                                    <textarea name="content_ar" id="content_ar" class="form-control" rows="8" required>{{ old('content_ar', $blog->content_ar) }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="content_en" class="form-label">Content (English) *</label>
                                    <textarea name="content_en" id="content_en" class="form-control" rows="8" required>{{ old('content_en', $blog->content_en) }}</textarea>
                                </div>
                            </div>
                        </div>

                        {{-- Main Image --}}
                        <div class="mb-3">
                            <label for="main_image" class="form-label">Main Image</label>
                            
                            @if($blog->main_image)
                            <div class="mb-2">
                                <strong>Current Main Image:</strong><br>
                                <img src="{{ $blog->main_image_url }}" alt="Main Image" class="img-thumbnail" style="max-width: 150px; max-height: 150px;">
                            </div>
                            @endif
                            
                            <input type="file" name="main_image" id="main_image" class="form-control" accept="image/*">
                        </div>

                        {{-- Additional Images --}}
                        <div class="mb-3">
                            <label for="images" class="form-label">Additional Images</label>
                            
                            @if($blog->images->count() > 0)
                            <div class="mb-2">
                                <strong>Current Additional Images:</strong><br>
                                <div class="row">
                                    @foreach($blog->images as $image)
                                    <div class="col-md-2 mb-2">
                                        <img src="{{ $image->image_url }}" alt="Additional Image" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                            
                            <input type="file" name="images[]" id="images" class="form-control" accept="image/*" multiple>
                        </div>

                        {{-- Status --}}
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status" class="form-control">
                                <option value="draft" {{ old('status', $blog->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ old('status', $blog->status) == 'published' ? 'selected' : '' }}>Published</option>
                            </select>
                        </div>

                        {{-- Submit Button --}}
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">
                                Update Blog
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
