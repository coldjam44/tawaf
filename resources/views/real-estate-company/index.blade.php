@extends('admin.layouts.app')

@section('title', 'شركة التطوير العقاري')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">شركة التطوير العقاري</h5>
                </div>
                <div class="card-body">
                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>الشعار</th>
                                    <th>اسم الشركة</th>
                                    <th>الوصف المختصر</th>
                                    <th>رقم التواصل</th>
                                    <th>رقم ADM</th>
                                    <th>رقم CN</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($companies as $company)
                                <tr>
                                    <td>
                                        @if($company->company_logo)
                                            <img src="{{ $company->logo_url }}" alt="Company Logo" style="max-width: 50px; max-height: 50px;">
                                        @else
                                            <span class="text-muted">لا يوجد شعار</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div><strong>العربية:</strong> {{ $company->company_name_ar }}</div>
                                        <div><strong>English:</strong> {{ $company->company_name_en }}</div>
                                    </td>
                                    <td>
                                        <div><strong>العربية:</strong> {{ Str::limit($company->short_description_ar, 50) }}</div>
                                        <div><strong>English:</strong> {{ Str::limit($company->short_description_en, 50) }}</div>
                                    </td>
                                    <td>{{ $company->contact_number ?? 'غير محدد' }}</td>
                                    <td>{{ $company->adm_number ?? 'غير محدد' }}</td>
                                    <td>{{ $company->cn_number ?? 'غير محدد' }}</td>
                                    <td>
                                        <a href="{{ route('real-estate-company.edit', $company->id) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i> تعديل
                                        </a>
                                        <a href="{{ route('developers.index', ['company_id' => $company->id]) }}" class="btn btn-sm btn-success">
                                            <i class="fas fa-user-plus"></i> إضافة موظف
                                        </a>
                                        <form action="{{ route('real-estate-company.destroy', $company->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('هل أنت متأكد من الحذف؟')">
                                                <i class="fas fa-trash"></i> حذف
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">لا توجد شركات عقارية مضافة</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Form -->
                    <div class="mt-4">
                        <h6>إضافة شركة عقارية جديدة</h6>
                        <form action="{{ route('real-estate-company.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="company_logo" class="form-label">شعار الشركة</label>
                                        <input type="file" class="form-control" id="company_logo" name="company_logo">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="contact_number" class="form-label">رقم التواصل</label>
                                        <input type="text" class="form-control" id="contact_number" name="contact_number">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="company_name_ar" class="form-label">اسم الشركة (العربية) *</label>
                                        <input type="text" class="form-control" id="company_name_ar" name="company_name_ar" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="company_name_en" class="form-label">اسم الشركة (English) *</label>
                                        <input type="text" class="form-control" id="company_name_en" name="company_name_en" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="short_description_ar" class="form-label">الوصف المختصر (العربية)</label>
                                        <textarea class="form-control" id="short_description_ar" name="short_description_ar" rows="3"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="short_description_en" class="form-label">الوصف المختصر (English)</label>
                                        <textarea class="form-control" id="short_description_en" name="short_description_en" rows="3"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="about_company_ar" class="form-label">عن الشركة (العربية)</label>
                                        <textarea class="form-control" id="about_company_ar" name="about_company_ar" rows="4"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="about_company_en" class="form-label">عن الشركة (English)</label>
                                        <textarea class="form-control" id="about_company_en" name="about_company_en" rows="4"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="company_images" class="form-label">صور الشركة (3 صور على الأقل)</label>
                                <input type="file" class="form-control" id="company_images" name="company_images[]" multiple>
                                <small class="form-text text-muted">يمكنك اختيار أكثر من صورة</small>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="features_ar" class="form-label">المميزات (العربية)</label>
                                        <textarea class="form-control" id="features_ar" name="features_ar" rows="4"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="features_en" class="form-label">المميزات (English)</label>
                                        <textarea class="form-control" id="features_en" name="features_en" rows="4"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="properties_communities_ar" class="form-label">المناطق والمجتمعات (العربية)</label>
                                        <textarea class="form-control" id="properties_communities_ar" name="properties_communities_ar" rows="4"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="properties_communities_en" class="form-label">المناطق والمجتمعات (English)</label>
                                        <textarea class="form-control" id="properties_communities_en" name="properties_communities_en" rows="4"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="adm_number" class="form-label">رقم ADM</label>
                                        <input type="text" class="form-control" id="adm_number" name="adm_number" placeholder="مثال: 20230000095124">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="cn_number" class="form-label">رقم CN</label>
                                        <input type="text" class="form-control" id="cn_number" name="cn_number" placeholder="مثال: 2301096">
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">إضافة الشركة</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
