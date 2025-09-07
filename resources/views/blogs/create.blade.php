@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">{{ trans('main_trans.add_blog') }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('blogsection.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        {{-- Basic Information --}}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="title_ar" class="form-label">{{ trans('main_trans.title_ar') }} *</label>
                                    <input type="text" name="title_ar" id="title_ar" class="form-control @error('title_ar') is-invalid @enderror" value="{{ old('title_ar') }}" required>
                                    @error('title_ar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="title_en" class="form-label">{{ trans('main_trans.title_en') }} *</label>
                                    <input type="text" name="title_en" id="title_en" class="form-control @error('title_en') is-invalid @enderror" value="{{ old('title_en') }}" required>
                                    @error('title_en')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Content --}}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="content_ar" class="form-label">{{ trans('main_trans.content_ar') }} *</label>
                                    <textarea name="content_ar" id="content_ar" class="form-control @error('content_ar') is-invalid @enderror" rows="8" required>{{ old('content_ar') }}</textarea>
                                    @error('content_ar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="content_en" class="form-label">{{ trans('main_trans.content_en') }} *</label>
                                    <textarea name="content_en" id="content_en" class="form-control @error('content_en') is-invalid @enderror" rows="8" required>{{ old('content_en') }}</textarea>
                                    @error('content_en')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Main Image (Optional) --}}
                        <div class="mb-3">
                            <label for="main_image" class="form-label">{{ trans('main_trans.main_image') }} ({{ trans('main_trans.optional') }})</label>
                            <input type="file" name="main_image" id="main_image" class="form-control @error('main_image') is-invalid @enderror" accept="image/*">
                            <small class="form-text text-muted">JPG, JPEG, PNG, GIF (Max: 5MB) - {{ trans('main_trans.optional') }}</small>
                            @error('main_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Additional Images (Optional) --}}
                        <div class="mb-3">
                            <label for="images" class="form-label">{{ trans('main_trans.additional_images') }} ({{ trans('main_trans.optional') }})</label>
                            <input type="file" name="images[]" id="images" class="form-control" accept="image/*" multiple>
                            <small class="form-text text-muted">You can select multiple images - {{ trans('main_trans.optional') }}</small>
                        </div>

                        {{-- Status and Settings --}}
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="status" class="form-label">{{ trans('main_trans.blog_status') }}</label>
                                    <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                                        <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>{{ trans('main_trans.draft') }}</option>
                                        <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>{{ trans('main_trans.published') }}</option>
                                    </select>
                                    @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="order_index" class="form-label">{{ trans('main_trans.order') }}</label>
                                    <input type="number" name="order_index" id="order_index" class="form-control @error('order_index') is-invalid @enderror" value="{{ old('order_index', 0) }}" min="0">
                                    @error('order_index')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <div class="form-check mt-4">
                                        <input type="checkbox" name="is_active" id="is_active" class="form-check-input" {{ old('is_active') ? 'checked' : '' }}>
                                        <label for="is_active" class="form-check-label">{{ trans('main_trans.is_active') }}</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Submit Buttons --}}
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('blogsection.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i>
                                {{ trans('main_trans.back') }}
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>
                                {{ trans('main_trans.save') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
