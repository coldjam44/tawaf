@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">{{ trans('main_trans.edit_about_us_section') }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('about-us.update', $section->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        {{-- معلومات القسم الأساسية --}}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="section_name" class="form-label">{{ trans('main_trans.section_name') }} *</label>
                                    <input type="text" name="section_name" id="section_name" class="form-control @error('section_name') is-invalid @enderror" value="{{ old('section_name', $section->section_name) }}" required>
                                    <small class="form-text text-muted">{{ trans('main_trans.section_name_help') }}</small>
                                    @error('section_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="order_index" class="form-label">{{ trans('main_trans.order') }}</label>
                                    <input type="number" name="order_index" id="order_index" class="form-control @error('order_index') is-invalid @enderror" value="{{ old('order_index', $section->order_index) }}" min="0">
                                    @error('order_index')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- العناوين ثنائية اللغة --}}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="title_ar" class="form-label">{{ trans('main_trans.title_ar') }} *</label>
                                    <input type="text" name="title_ar" id="title_ar" class="form-control @error('title_ar') is-invalid @enderror" value="{{ old('title_ar', $section->title_ar) }}" required>
                                    @error('title_ar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="title_en" class="form-label">{{ trans('main_trans.title_en') }} *</label>
                                    <input type="text" name="title_en" id="title_en" class="form-control @error('title_en') is-invalid @enderror" value="{{ old('title_en', $section->title_en) }}" required>
                                    @error('title_en')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- المحتوى ثنائي اللغة --}}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="content_ar" class="form-label">{{ trans('main_trans.content_ar') }} *</label>
                                    <textarea name="content_ar" id="content_ar" class="form-control @error('content_ar') is-invalid @enderror" rows="8" required>{{ old('content_ar', $section->content_ar) }}</textarea>
                                    <small class="form-text text-muted">يمكنك استخدام HTML tags للتصميم</small>
                                    @error('content_ar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="content_en" class="form-label">{{ trans('main_trans.content_en') }} *</label>
                                    <textarea name="content_en" id="content_en" class="form-control @error('content_en') is-invalid @enderror" rows="8" required>{{ old('content_en', $section->content_en) }}</textarea>
                                    <small class="form-text text-muted">You can use HTML tags for formatting</small>
                                    @error('content_en')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- الصورة الرئيسية --}}
                        <div class="mb-3">
                            @if($section->main_image)
                            <div class="mb-2">
                                <label class="form-label">{{ trans('main_trans.current_main_image') }}</label>
                                <div>
                                    <img src="{{ $section->main_image_url }}" alt="Main Image" class="img-thumbnail" style="max-width: 200px;">
                                </div>
                            </div>
                            @endif
                            <label for="main_image" class="form-label">{{ trans('main_trans.main_image') }}</label>
                            <input type="file" name="main_image" id="main_image" class="form-control @error('main_image') is-invalid @enderror" accept="image/*">
                            <small class="form-text text-muted">{{ trans('main_trans.allowed_files') }}: JPG, JPEG, PNG, GIF ({{ trans('main_trans.max_size') }}: 5MB)</small>
                            @error('main_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- الصور الحالية --}}
                        @if($section->images->count() > 0)
                        <div class="card mb-3">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">{{ trans('main_trans.current_images') }}</h6>
                                <span class="badge bg-success">
                                    <i class="fas fa-check-circle me-1"></i>
                                    {{ $section->images->count() }} {{ trans('main_trans.images_uploaded') }}
                                </span>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @foreach($section->images as $image)
                                    <div class="col-md-3 mb-3">
                                        <div class="card">
                                            <img src="{{ $image->image_url }}" alt="{{ $image->getAltText() }}" class="card-img-top" style="height: 150px; object-fit: cover;">
                                            <div class="card-body">
                                                <h6 class="card-title">{{ $image->getCaption() ?: 'No Caption' }}</h6>
                                                <p class="card-text small">{{ $image->getAltText() ?: 'No Alt Text' }}</p>
                                                <form action="{{ route('about-us.delete-image', [$section->id, $image->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ trans('main_trans.confirm_delete') }}')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="fas fa-trash"></i> {{ trans('main_trans.remove_image') }}
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endif

                        {{-- الصور الإضافية --}}
                        <div class="card mb-3">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">{{ trans('main_trans.upload_new_images') }}</h6>
                                @if($section->images->count() > 0)
                                    <span class="badge bg-info">
                                        <i class="fas fa-images me-1"></i>
                                        {{ $section->images->count() }} {{ trans('main_trans.current_images_count') }}
                                    </span>
                                @endif
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="images" class="form-label">
                                        {{ trans('main_trans.select_images') }}
                                        @if($section->images->count() > 0)
                                            <span class="text-muted">({{ trans('main_trans.adding_to') }} {{ $section->images->count() }} {{ trans('main_trans.existing_images') }})</span>
                                        @endif
                                    </label>
                                    <input type="file" name="images[]" id="images" class="form-control" accept="image/*" multiple>
                                    <small class="form-text text-muted">{{ trans('main_trans.multiple_images_help') }}</small>
                                </div>
                            </div>
                        </div>

                        {{-- تفعيل القسم --}}
                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" name="is_active" id="is_active" class="form-check-input" {{ old('is_active', $section->is_active) ? 'checked' : '' }}>
                                <label for="is_active" class="form-check-label">{{ trans('main_trans.is_active') }}</label>
                            </div>
                        </div>

                        {{-- أزرار الحفظ --}}
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('about-us.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i>
                                {{ trans('main_trans.back') }}
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>
                                {{ trans('main_trans.update') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


