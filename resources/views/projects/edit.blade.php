@extends('admin.layouts.app')

@section('content')

<div class="row">
    <div class="col-xl">
        <div class="card mb-4">

            {{-- عنوان الصفحة --}}
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="text-center my-4">{{ trans('main_trans.edit_project') }}</h3>
                <div class="text-right mb-3">
                    <a href="{{ route('projects.index') }}" class="btn btn-secondary">
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
                <form action="{{ route('projects.update', $project->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- عنوان المشروع ثنائي اللغة --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="prj_title_ar" class="form-label">{{ trans('main_trans.prj_title_ar') }} *</label>
                                <input type="text" name="prj_title_ar" id="prj_title_ar" class="form-control @error('prj_title_ar') is-invalid @enderror" value="{{ old('prj_title_ar', $project->prj_title_ar) }}" required>
                                @error('prj_title_ar')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="prj_title_en" class="form-label">{{ trans('main_trans.prj_title_en') }} *</label>
                                <input type="text" name="prj_title_en" id="prj_title_en" class="form-control @error('prj_title_en') is-invalid @enderror" value="{{ old('prj_title_en', $project->prj_title_en) }}" required>
                                @error('prj_title_en')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- المنطقة وشركة التطوير العقاري --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="prj_areaId" class="form-label">{{ trans('main_trans.area') }} *</label>
                                <select name="prj_areaId" id="prj_areaId" class="form-control @error('prj_areaId') is-invalid @enderror" required>
                                    <option value="">{{ trans('main_trans.select_area') }}</option>
                                    @foreach($areas as $area)
                                    <option value="{{ $area->id }}" {{ old('prj_areaId', $project->prj_areaId) == $area->id ? 'selected' : '' }}>
                                        {{ app()->getLocale() == 'ar' ? $area->name_ar : $area->name_en }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('prj_areaId')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="company_id" class="form-label">شركة التطوير العقاري *</label>
                                <select name="company_id" id="company_id" class="form-control @error('company_id') is-invalid @enderror" required>
                                    <option value="">اختر شركة التطوير العقاري</option>
                                    @foreach(\App\Models\RealEstateCompany::all() as $company)
                                    <option value="{{ $company->id }}" {{ old('company_id', $project->company_id) == $company->id ? 'selected' : '' }}>
                                        {{ $company->company_name_ar }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('company_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- وصف المشروع ثنائي اللغة --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="prj_description_ar" class="form-label">{{ trans('main_trans.prj_description_ar') }}</label>
                                <textarea name="prj_description_ar" id="prj_description_ar" class="form-control @error('prj_description_ar') is-invalid @enderror" rows="4">{{ old('prj_description_ar', $project->prj_description_ar) }}</textarea>
                                @error('prj_description_ar')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="prj_description_en" class="form-label">{{ trans('main_trans.prj_description_en') }}</label>
                                <textarea name="prj_description_en" id="prj_description_en" class="form-control @error('prj_description_en') is-invalid @enderror" rows="4">{{ old('prj_description_en', $project->prj_description_en) }}</textarea>
                                @error('prj_description_en')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- معلومات إضافية --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="prj_projectNumber" class="form-label">{{ trans('main_trans.prj_projectNumber') }}</label>
                                <input type="text" name="prj_projectNumber" id="prj_projectNumber" class="form-control @error('prj_projectNumber') is-invalid @enderror" value="{{ old('prj_projectNumber', $project->prj_projectNumber) }}">
                                @error('prj_projectNumber')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="prj_MadhmounPermitNumber" class="form-label">{{ trans('main_trans.prj_MadhmounPermitNumber') }}</label>
                                <input type="text" name="prj_MadhmounPermitNumber" id="prj_MadhmounPermitNumber" class="form-control @error('prj_MadhmounPermitNumber') is-invalid @enderror" value="{{ old('prj_MadhmounPermitNumber', $project->prj_MadhmounPermitNumber) }}">
                                @error('prj_MadhmounPermitNumber')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="prj_adm" class="form-label">{{ trans('main_trans.prj_adm') }}</label>
                                <input type="text" name="prj_adm" id="prj_adm" class="form-control @error('prj_adm') is-invalid @enderror" value="{{ old('prj_adm', $project->prj_adm) }}">
                                @error('prj_adm')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="prj_cn" class="form-label">{{ trans('main_trans.prj_cn') }}</label>
                                <input type="text" name="prj_cn" id="prj_cn" class="form-control @error('prj_cn') is-invalid @enderror" value="{{ old('prj_cn', $project->prj_cn) }}">
                                @error('prj_cn')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- رفع الملفات --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="prj_brochurefile" class="form-label">{{ trans('main_trans.prj_brochurefile') }}</label>
                                <input type="file" name="prj_brochurefile" id="prj_brochurefile" class="form-control @error('prj_brochurefile') is-invalid @enderror" accept=".pdf,.doc,.docx">
                                <small class="form-text text-muted">{{ trans('main_trans.allowed_files') }}: PDF, DOC, DOCX ({{ trans('main_trans.max_size') }}: 5MB)</small>
                                @error('prj_brochurefile')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                
                                @if($project->prj_brochurefile)
                                <div class="mt-2">
                                    <small class="text-muted">{{ trans('main_trans.current_brochure') }}: {{ $project->prj_brochurefile }}</small>
                                    <br>
                                    <a href="{{ asset('projects/brochures/' . $project->prj_brochurefile) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-download"></i> {{ trans('main_trans.download_brochure') }}
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="prj_floorplan" class="form-label">{{ trans('main_trans.prj_floorplan') }}</label>
                                <input type="file" name="prj_floorplan" id="prj_floorplan" class="form-control @error('prj_floorplan') is-invalid @enderror" accept=".pdf,.jpg,.jpeg,.png">
                                <small class="form-text text-muted">{{ trans('main_trans.allowed_files') }}: PDF, JPG, JPEG, PNG ({{ trans('main_trans.max_size') }}: 5MB)</small>
                                @error('prj_floorplan')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                
                                @if($project->prj_floorplan)
                                <div class="mt-2">
                                    <small class="text-muted">{{ trans('main_trans.current_floorplan') }}: {{ $project->prj_floorplan }}</small>
                                    <br>
                                    <a href="{{ asset('projects/floorplans/' . $project->prj_floorplan) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-download"></i> {{ trans('main_trans.download_floorplan') }}
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="text-end">
                        <a href="{{ route('projects.index') }}" class="btn btn-secondary">
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
