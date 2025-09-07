@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Edit About Us Section</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('about-us.update', $section->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <!-- Section Name -->
                        <div class="mb-3">
                            <label>Section Name</label>
                            <input type="text" name="section_name" class="form-control" value="{{ $section->section_name }}">
                        </div>

                        <!-- Order -->
                        <div class="mb-3">
                            <label>Order</label>
                            <input type="number" name="order_index" class="form-control" value="{{ $section->order_index }}">
                        </div>

                        <!-- Arabic Title -->
                        <div class="mb-3">
                            <label>Arabic Title</label>
                            <input type="text" name="title_ar" class="form-control" value="{{ $section->title_ar }}">
                        </div>

                        <!-- English Title -->
                        <div class="mb-3">
                            <label>English Title</label>
                            <input type="text" name="title_en" class="form-control" value="{{ $section->title_en }}">
                        </div>

                        <!-- Arabic Content -->
                        <div class="mb-3">
                            <label>Arabic Content</label>
                            <textarea name="content_ar" class="form-control" rows="4">{{ $section->content_ar }}</textarea>
                        </div>

                        <!-- English Content -->
                        <div class="mb-3">
                            <label>English Content</label>
                            <textarea name="content_en" class="form-control" rows="4">{{ $section->content_en }}</textarea>
                        </div>

                        <!-- Main Image -->
                        <div class="mb-3">
                            <label>Main Image</label>
                            @if($section->main_image)
                                <div class="mb-2">
                                    <strong>Current:</strong><br>
                                    <img src="{{ asset('about-us/main-images/' . $section->main_image) }}" alt="Main Image" class="img-thumbnail" style="max-width: 200px;">
                                </div>
                            @endif
                            <input type="file" name="main_image" class="form-control">
                        </div>

                        <!-- Current Images -->
                        @if($section->images->count() > 0)
                        <div class="mb-3">
                            <label>Current Images ({{ $section->images->count() }})</label>
                            <div class="row">
                                @foreach($section->images as $image)
                                <div class="col-md-3 mb-2">
                                    <img src="{{ asset('about-us/images/' . $image->image_path) }}" alt="{{ $image->alt_text_en }}" class="img-thumbnail" style="width: 100%; height: 120px; object-fit: cover;">
                                    <br>
                                    <small>{{ $image->caption_en ?: $image->alt_text_en }}</small>
                                    <br>
                                    <a href="{{ route('about-us.delete-image', [$section->id, $image->id]) }}" class="btn btn-sm btn-danger" onclick="return confirm('Delete?')">Delete</a>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Upload New Images -->
                        <div class="mb-3">
                            <label>Upload New Images</label>
                            <input type="file" name="images[]" class="form-control" multiple>
                        </div>

                        <!-- Active Status -->
                        <div class="mb-3">
                            <label>
                                <input type="checkbox" name="is_active" {{ $section->is_active ? 'checked' : '' }}>
                                Active
                            </label>
                        </div>

                        <!-- Buttons -->
                        <div class="mb-3">
                            <a href="{{ route('about-us.index') }}" class="btn btn-secondary">Back</a>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


