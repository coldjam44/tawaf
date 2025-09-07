@extends('admin.layouts.app')

@section('content')

<div class="row">
    <div class="col-xl">
        <div class="card mb-4">

            {{-- عنوان الصفحة --}}
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="text-center my-4">{{ trans('main_trans.edit_area') }}</h3>
                <div class="text-right mb-3">
                    <a href="{{ route('areas.index') }}" class="btn btn-secondary">
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

                {{-- فورم التعديل --}}
                <form action="{{ route('areas.update', $area->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="name_ar" class="form-label">{{ trans('main_trans.area_name_ar') }} *</label>
                                <input type="text" name="name_ar" id="name_ar" class="form-control @error('name_ar') is-invalid @enderror" value="{{ old('name_ar', $area->name_ar) }}" required>
                                @error('name_ar')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="name_en" class="form-label">{{ trans('main_trans.area_name_en') }} *</label>
                                <input type="text" name="name_en" id="name_en" class="form-control @error('name_en') is-invalid @enderror" value="{{ old('name_en', $area->name_en) }}" required>
                                @error('name_en')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="slug" class="form-label">{{ trans('main_trans.area_slug') }} *</label>
                                <input type="text" name="slug" id="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug', $area->slug) }}" required>
                                <small class="form-text text-muted">مثال: downtown-dubai, dubai-marina</small>
                                @error('slug')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="main_image" class="form-label">{{ trans('main_trans.main_image') }}</label>
                                <input type="file" name="main_image" id="main_image" class="form-control @error('main_image') is-invalid @enderror" accept="image/*">
                                <small class="form-text text-muted">الصور المسموحة: JPG, PNG, GIF (حد أقصى 2MB)</small>
                                @error('main_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                
                                @if($area->main_image)
                                    <div class="mt-2">
                                        <label class="form-label">الصورة الحالية:</label>
                                        <div>
                                            <img src="{{ $area->main_image_url }}" alt="{{ $area->name_ar }}" class="img-thumbnail" style="width: 150px; height: 150px; object-fit: cover;">
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="about_community_overview_ar" class="form-label">About & Community Overview (العربية)</label>
                                <textarea name="about_community_overview_ar" id="about_community_overview_ar" class="form-control" rows="4">{{ old('about_community_overview_ar', $area->about_community_overview_ar) }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="about_community_overview_en" class="form-label">About & Community Overview (English)</label>
                                <textarea name="about_community_overview_en" id="about_community_overview_en" class="form-control" rows="4">{{ old('about_community_overview_en', $area->about_community_overview_en) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="rental_sales_trends_ar" class="form-label">Rental & Sales Trends (العربية)</label>
                                <textarea name="rental_sales_trends_ar" id="rental_sales_trends_ar" class="form-control" rows="4">{{ old('rental_sales_trends_ar', $area->rental_sales_trends_ar) }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="rental_sales_trends_en" class="form-label">Rental & Sales Trends (English)</label>
                                <textarea name="rental_sales_trends_en" id="rental_sales_trends_en" class="form-control" rows="4">{{ old('rental_sales_trends_en', $area->rental_sales_trends_en) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="roi_ar" class="form-label">ROI (العربية)</label>
                                <textarea name="roi_ar" id="roi_ar" class="form-control" rows="4">{{ old('roi_ar', $area->roi_ar) }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="roi_en" class="form-label">ROI (English)</label>
                                <textarea name="roi_en" id="roi_en" class="form-control" rows="4">{{ old('roi_en', $area->roi_en) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="things_to_do_perks_ar" class="form-label">Things to Do & Perks (العربية)</label>
                                <textarea name="things_to_do_perks_ar" id="things_to_do_perks_ar" class="form-control" rows="4">{{ old('things_to_do_perks_ar', $area->things_to_do_perks_ar) }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="things_to_do_perks_en" class="form-label">Things to Do & Perks (English)</label>
                                <textarea name="things_to_do_perks_en" id="things_to_do_perks_en" class="form-control" rows="4">{{ old('things_to_do_perks_en', $area->things_to_do_perks_en) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="text-end">
                        <a href="{{ route('areas.index') }}" class="btn btn-secondary">
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
