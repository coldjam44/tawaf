@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">{{ trans('main_trans.edit') }} {{ trans('main_trans.award') }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('awards.update', $award->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        {{-- العناوين ثنائية اللغة --}}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="title_ar" class="form-label">{{ trans('main_trans.title_ar') }} *</label>
                                    <input type="text" name="title_ar" id="title_ar" class="form-control @error('title_ar') is-invalid @enderror" value="{{ old('title_ar', $award->title_ar) }}" required>
                                    @error('title_ar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="title_en" class="form-label">{{ trans('main_trans.title_en') }} *</label>
                                    <input type="text" name="title_en" id="title_en" class="form-control @error('title_en') is-invalid @enderror" value="{{ old('title_en', $award->title_en) }}" required>
                                    @error('title_en')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- الوصف ثنائي اللغة --}}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="description_ar" class="form-label">{{ trans('main_trans.description_ar') }} *</label>
                                    <textarea name="description_ar" id="description_ar" class="form-control @error('description_ar') is-invalid @enderror" rows="6" required>{{ old('description_ar', $award->description_ar) }}</textarea>
                                    @error('description_ar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="description_en" class="form-label">{{ trans('main_trans.description_en') }} *</label>
                                    <textarea name="description_en" id="description_en" class="form-control @error('description_en') is-invalid @enderror" rows="6" required>{{ old('description_en', $award->description_en) }}</textarea>
                                    @error('description_en')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- الصورة الحالية --}}
                        @if($award->image)
                        <div class="mb-3">
                            <label class="form-label">{{ trans('main_trans.current_image') }}</label>
                            <div>
                                <img src="{{ $award->image_url }}" alt="Current Image" class="img-thumbnail" style="max-width: 200px;">
                            </div>
                        </div>
                        @endif

                        {{-- الصورة والسنة والفئة --}}
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="image" class="form-label">{{ trans('main_trans.image') }}</label>
                                    <input type="file" name="image" id="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                                    <small class="form-text text-muted">{{ trans('main_trans.allowed_files') }}: JPG, JPEG, PNG, GIF ({{ trans('main_trans.max_size') }}: 5MB)</small>
                                    @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="year" class="form-label">{{ trans('main_trans.year') }}</label>
                                    <input type="text" name="year" id="year" class="form-control @error('year') is-invalid @enderror" value="{{ old('year', $award->year) }}" placeholder="2024">
                                    @error('year')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="category" class="form-label">{{ trans('main_trans.category') }}</label>
                                    <input type="text" name="category" id="category" class="form-control @error('category') is-invalid @enderror" value="{{ old('category', $award->category) }}" placeholder="أفضل شركة عقارية">
                                    @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- الترتيب --}}
                        <div class="mb-3">
                            <label for="order_index" class="form-label">{{ trans('main_trans.order') }}</label>
                            <input type="number" name="order_index" id="order_index" class="form-control @error('order_index') is-invalid @enderror" value="{{ old('order_index', $award->order_index) }}" min="0">
                            @error('order_index')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- أزرار الحفظ --}}
                        <div class="d-flex justify-content-between">
                            <div>
                                <a href="{{ route('awards.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-1"></i>
                                    {{ trans('main_trans.back') }}
                                </a>
                                <form action="{{ route('awards.toggle', $award->id) }}" method="POST" class="d-inline ms-2">
                                    @csrf
                                    <button type="submit" class="btn {{ $award->is_active ? 'btn-warning' : 'btn-success' }}" 
                                            onclick="return confirm('{{ $award->is_active ? trans('main_trans.confirm_deactivate') : trans('main_trans.confirm_activate') }}')">
                                        <i class="fas {{ $award->is_active ? 'fa-eye-slash' : 'fa-eye' }} me-1"></i>
                                        {{ $award->is_active ? trans('main_trans.deactivate') : trans('main_trans.activate') }}
                                    </button>
                                </form>
                            </div>
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
