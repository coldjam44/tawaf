@extends('admin.layouts.app')

@section('title', 'تعديل شركة التطوير العقاري')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">تعديل شركة التطوير العقاري</h5>
                    <a href="{{ route('real-estate-company.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> رجوع
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('real-estate-company.update', $company->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="company_logo" class="form-label">شعار الشركة</label>
                                    @if($company->company_logo)
                                        <div class="mb-2">
                                            <img src="{{ $company->logo_url }}" alt="Current Logo" style="max-width: 100px; max-height: 100px;">
                                        </div>
                                    @endif
                                    <input type="file" class="form-control" id="company_logo" name="company_logo">
                                    <small class="form-text text-muted">اتركه فارغاً للاحتفاظ بالشعار الحالي</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="contact_number" class="form-label">رقم التواصل</label>
                                    <input type="text" class="form-control" id="contact_number" name="contact_number" value="{{ $company->contact_number }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="company_name_ar" class="form-label">اسم الشركة (العربية) *</label>
                                    <input type="text" class="form-control" id="company_name_ar" name="company_name_ar" value="{{ $company->company_name_ar }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="company_name_en" class="form-label">اسم الشركة (English) *</label>
                                    <input type="text" class="form-control" id="company_name_en" name="company_name_en" value="{{ $company->company_name_en }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="short_description_ar" class="form-label">الوصف المختصر (العربية)</label>
                                    <textarea class="form-control" id="short_description_ar" name="short_description_ar" rows="3">{{ $company->short_description_ar }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="short_description_en" class="form-label">الوصف المختصر (English)</label>
                                    <textarea class="form-control" id="short_description_en" name="short_description_en" rows="3">{{ $company->short_description_en }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="about_company_ar" class="form-label">عن الشركة (العربية)</label>
                                    <textarea class="form-control" id="about_company_ar" name="about_company_ar" rows="4">{{ $company->about_company_ar }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="about_company_en" class="form-label">عن الشركة (English)</label>
                                    <textarea class="form-control" id="about_company_en" name="about_company_en" rows="4">{{ $company->about_company_en }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="company_images" class="form-label">صور الشركة</label>
                            @if($company->company_images)
                                <div class="mb-2">
                                    <strong>الصور الحالية:</strong>
                                    <div class="row">
                                        @php
                                            $images = is_string($company->company_images) ? json_decode($company->company_images, true) : $company->company_images;
                                        @endphp
                                        @if($images && is_array($images))
                                            @foreach($images as $image)
                                                <div class="col-md-3 mb-2">
                                                    <img src="{{ asset('real-estate-companies/' . $image) }}" alt="Company Image" style="max-width: 100%; height: auto;">
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            @endif
                            <input type="file" class="form-control" id="company_images" name="company_images[]" multiple>
                            <small class="form-text text-muted">اتركه فارغاً للاحتفاظ بالصور الحالية</small>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="features_ar" class="form-label">المميزات (العربية)</label>
                                    <textarea class="form-control" id="features_ar" name="features_ar" rows="4">{{ $company->features_ar }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="features_en" class="form-label">المميزات (English)</label>
                                    <textarea class="form-control" id="features_en" name="features_en" rows="4">{{ $company->features_en }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="properties_communities_ar" class="form-label">المناطق والمجتمعات (العربية)</label>
                                    <textarea class="form-control" id="properties_communities_ar" name="properties_communities_ar" rows="4">{{ $company->properties_communities_ar }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="properties_communities_en" class="form-label">المناطق والمجتمعات (English)</label>
                                    <textarea class="form-control" id="properties_communities_en" name="properties_communities_en" rows="4">{{ $company->properties_communities_en }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="adm_number" class="form-label">رقم ADM</label>
                                    <input type="text" class="form-control" id="adm_number" name="adm_number" value="{{ $company->adm_number }}" placeholder="مثال: 20230000095124">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="cn_number" class="form-label">رقم CN</label>
                                    <input type="text" class="form-control" id="cn_number" name="cn_number" value="{{ $company->cn_number }}" placeholder="مثال: 2301096">
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">حفظ التعديلات</button>
                            <a href="{{ route('real-estate-company.index') }}" class="btn btn-secondary">إلغاء</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
