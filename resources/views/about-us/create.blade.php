@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">{{ trans('main_trans.add_about_us_section') }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('about-us.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        {{-- معلومات القسم الأساسية --}}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="section_name" class="form-label">{{ trans('main_trans.section_name') }} *</label>
                                    <input type="text" name="section_name" id="section_name" class="form-control @error('section_name') is-invalid @enderror" value="{{ old('section_name') }}" required>
                                    <small class="form-text text-muted">{{ trans('main_trans.section_name_help') }}</small>
                                    @error('section_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="order_index" class="form-label">{{ trans('main_trans.order') }}</label>
                                    <input type="number" name="order_index" id="order_index" class="form-control @error('order_index') is-invalid @enderror" value="{{ old('order_index', 0) }}" min="0">
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

                        {{-- المحتوى ثنائي اللغة --}}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="content_ar" class="form-label">{{ trans('main_trans.content_ar') }} *</label>
                                    <textarea name="content_ar" id="content_ar" class="form-control @error('content_ar') is-invalid @enderror" rows="8" required>{{ old('content_ar') }}</textarea>
                                    <small class="form-text text-muted">يمكنك استخدام HTML tags للتصميم</small>
                                    @error('content_ar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="content_en" class="form-label">{{ trans('main_trans.content_en') }} *</label>
                                    <textarea name="content_en" id="content_en" class="form-control @error('content_en') is-invalid @enderror" rows="8" required>{{ old('content_en') }}</textarea>
                                    <small class="form-text text-muted">You can use HTML tags for formatting</small>
                                    @error('content_en')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- الصورة الرئيسية --}}
                        <div class="mb-3">
                            <label for="main_image" class="form-label">{{ trans('main_trans.main_image') }}</label>
                            <input type="file" name="main_image" id="main_image" class="form-control @error('main_image') is-invalid @enderror" accept="image/*">
                            <small class="form-text text-muted">{{ trans('main_trans.allowed_files') }}: JPG, JPEG, PNG, GIF ({{ trans('main_trans.max_size') }}: 5MB)</small>
                            @error('main_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- الصور الإضافية --}}
                        <div class="card mb-3">
                            <div class="card-header">
                                <h6 class="mb-0">{{ trans('main_trans.additional_images') }}</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="images" class="form-label">{{ trans('main_trans.select_images') }}</label>
                                    <input type="file" name="images[]" id="images" class="form-control" accept="image/*" multiple>
                                    <small class="form-text text-muted">{{ trans('main_trans.multiple_images_help') }}</small>
                                </div>
                            </div>
                        </div>

                        {{-- تفعيل القسم --}}
                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" name="is_active" id="is_active" class="form-check-input" {{ old('is_active') ? 'checked' : '' }}>
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


