@extends('admin.layouts.app')

@section('content')

<div class="row">
    <div class="col-xl">
        <div class="card mb-4">

            {{-- عنوان الصفحة --}}
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="text-center my-4">{{ trans('main_trans.edit_project_image') }}</h3>
                    <h5 class="text-muted">{{ $project->getTitle() }}</h5>
                </div>
                <div class="text-right mb-3">
                    <a href="{{ route('project-images.index', $project->id) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> {{ trans('main_trans.back') }}
                    </a>
                </div>
            </div>

            <div class="card-body">

                {{-- رسائل النجاح --}}
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                {{-- رسائل الخطأ --}}
                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                {{-- أخطاء الإدخال --}}
                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                {{-- عرض الصورة الحالية --}}
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6>{{ trans('main_trans.current_image') }}</h6>
                        <img src="{{ $projectImage->image_url }}" class="img-fluid rounded" alt="{{ $projectImage->getTitle() }}" style="max-height: 300px;">
                    </div>
                    <div class="col-md-6">
                        <h6>{{ trans('main_trans.image_info') }}</h6>
                        <ul class="list-unstyled">
                            <li><strong>{{ trans('main_trans.image_type') }}:</strong> {{ $projectImage->type_label }}</li>
                            <li><strong>{{ trans('main_trans.order') }}:</strong> {{ $projectImage->order }}</li>
                            <li><strong>{{ trans('main_trans.is_featured') }}:</strong> {{ $projectImage->is_featured ? 'نعم' : 'لا' }}</li>
                            <li><strong>{{ trans('main_trans.created_at') }}:</strong> {{ $projectImage->created_at->format('Y-m-d H:i:s') }}</li>
                        </ul>
                    </div>
                </div>

                {{-- فورم التعديل --}}
                <form action="{{ route('project-images.update', [$project->id, $projectImage->id]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- نوع الصورة --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="type" class="form-label">{{ trans('main_trans.image_type') }} *</label>
                                <select name="type" id="type" class="form-control @error('type') is-invalid @enderror" required>
                                    <option value="">{{ trans('main_trans.select_image_type') }}</option>
                                    <option value="interior" {{ old('type', $projectImage->type) == 'interior' ? 'selected' : '' }}>{{ trans('main_trans.interior') }}</option>
                                    <option value="exterior" {{ old('type', $projectImage->type) == 'exterior' ? 'selected' : '' }}>{{ trans('main_trans.exterior') }}</option>
                                    <option value="floorplan" {{ old('type', $projectImage->type) == 'floorplan' ? 'selected' : '' }}>{{ trans('main_trans.floorplan') }}</option>
                                </select>
                                @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="order" class="form-label">{{ trans('main_trans.order') }}</label>
                                <input type="number" name="order" id="order" class="form-control @error('order') is-invalid @enderror" value="{{ old('order', $projectImage->order) }}" min="0">
                                <small class="form-text text-muted">الترتيب الذي سيظهر به هذا التفصيل في القائمة</small>
                                @error('order')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- رفع صورة جديدة (اختياري) --}}
                    <div class="mb-3">
                        <label for="image" class="form-label">{{ trans('main_trans.new_image') }} ({{ trans('main_trans.optional') }})</label>
                        <input type="file" name="image" id="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                        <small class="form-text text-muted">{{ trans('main_trans.allowed_files') }}: JPG, JPEG, PNG, GIF ({{ trans('main_trans.max_size') }}: 5MB)</small>
                        @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- عنوان الصورة ثنائي اللغة --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="title_ar" class="form-label">{{ trans('main_trans.image_title_ar') }}</label>
                                <input type="text" name="title_ar" id="title_ar" class="form-control @error('title_ar') is-invalid @enderror" value="{{ old('title_ar', $projectImage->title_ar) }}">
                                @error('title_ar')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="title_en" class="form-label">{{ trans('main_trans.image_title_en') }}</label>
                                <input type="text" name="title_en" id="title_en" class="form-control @error('title_en') is-invalid @enderror" value="{{ old('title_en', $projectImage->title_en) }}">
                                @error('title_en')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- وصف الصورة ثنائي اللغة --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="description_ar" class="form-label">{{ trans('main_trans.image_description_ar') }}</label>
                                <textarea name="description_ar" id="description_ar" class="form-control @error('description_ar') is-invalid @enderror" rows="3">{{ old('description_ar', $projectImage->description_ar) }}</textarea>
                                @error('description_ar')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="description_en" class="form-label">{{ trans('main_trans.image_description_en') }}</label>
                                <textarea name="description_en" id="description_en" class="form-control @error('description_en') is-invalid @enderror" rows="3">{{ old('description_en', $projectImage->description_en) }}</textarea>
                                @error('description_en')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- صورة مميزة --}}
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured', $projectImage->is_featured) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_featured">
                                {{ trans('main_trans.is_featured') }}
                            </label>
                        </div>
                        <small class="form-text text-muted">الصور المميزة ستظهر في المعرض الرئيسي للمشروع</small>
                    </div>

                    <div class="text-end">
                        <a href="{{ route('project-images.index', $project->id) }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> {{ trans('main_trans.cancel') }}
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> {{ trans('main_trans.update') }}
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

@endsection
