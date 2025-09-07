@extends('admin.layouts.app')

@section('content')

<div class="row">
    <div class="col-xl">
        <div class="card mb-4">

            {{-- عنوان الصفحة وزر الإضافة --}}
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="text-center my-4">{{ trans('main_trans.projects') }}</h3>
                <div class="text-right mb-3">
                    <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#addProjectForm" aria-expanded="false" aria-controls="addProjectForm">
                        <i class="fas fa-plus-circle"></i> {{ trans('main_trans.add') }}
                    </button>
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

                {{-- فلتر المنطقة --}}
                @if(request('area'))
                    @php
                        $filteredArea = \App\Models\Area::find(request('area'));
                    @endphp
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <i class="fas fa-filter"></i>
                        <strong>{{ trans('main_trans.filtered_by_area') }}:</strong> 
                        {{ $filteredArea ? (app()->getLocale() == 'ar' ? $filteredArea->name_ar : $filteredArea->name_en) : 'Unknown' }}
                        <a href="{{ route('projects.index') }}" class="btn btn-sm btn-outline-info ms-2">
                            <i class="fas fa-times"></i> {{ trans('main_trans.clear_filter') }}
                        </a>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                {{-- رسالة البحث --}}
                @if(request('search'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-search"></i>
                        <strong>{{ trans('main_trans.search_results_for') }}:</strong> 
                        "{{ request('search') }}"
                        <a href="{{ route('projects.index') }}" class="btn btn-sm btn-outline-success ms-2">
                            <i class="fas fa-times"></i> {{ trans('main_trans.clear_search') }}
                        </a>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                {{-- فورم الإضافة --}}
                <div class="collapse" id="addProjectForm">
                    <div class="card card-body">
                        <form action="{{ route('projects.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            {{-- عنوان المشروع ثنائي اللغة --}}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="prj_title_ar" class="form-label">{{ trans('main_trans.prj_title_ar') }} *</label>
                                        <input type="text" name="prj_title_ar" id="prj_title_ar" class="form-control @error('prj_title_ar') is-invalid @enderror" value="{{ old('prj_title_ar') }}" required>
                                        @error('prj_title_ar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="prj_title_en" class="form-label">{{ trans('main_trans.prj_title_en') }} *</label>
                                        <input type="text" name="prj_title_en" id="prj_title_en" class="form-control @error('prj_title_en') is-invalid @enderror" value="{{ old('prj_title_en') }}" required>
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
                                            <option value="{{ $area->id }}" {{ old('prj_areaId') == $area->id ? 'selected' : '' }}>
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
                                            <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>
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
                                        <textarea name="prj_description_ar" id="prj_description_ar" class="form-control @error('prj_description_ar') is-invalid @enderror" rows="4">{{ old('prj_description_ar') }}</textarea>
                                        @error('prj_description_ar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="prj_description_en" class="form-label">{{ trans('main_trans.prj_description_en') }}</label>
                                        <textarea name="prj_description_en" id="prj_description_en" class="form-control @error('prj_description_en') is-invalid @enderror" rows="4">{{ old('prj_description_en') }}</textarea>
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
                                        <input type="text" name="prj_projectNumber" id="prj_projectNumber" class="form-control @error('prj_projectNumber') is-invalid @enderror" value="{{ old('prj_projectNumber') }}">
                                        @error('prj_projectNumber')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="prj_MadhmounPermitNumber" class="form-label">{{ trans('main_trans.prj_MadhmounPermitNumber') }}</label>
                                        <input type="text" name="prj_MadhmounPermitNumber" id="prj_MadhmounPermitNumber" class="form-control @error('prj_MadhmounPermitNumber') is-invalid @enderror" value="{{ old('prj_MadhmounPermitNumber') }}">
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
                                        <input type="text" name="prj_adm" id="prj_adm" class="form-control @error('prj_adm') is-invalid @enderror" value="{{ old('prj_adm') }}">
                                        @error('prj_adm')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="prj_cn" class="form-label">{{ trans('main_trans.prj_cn') }}</label>
                                        <input type="text" name="prj_cn" id="prj_cn" class="form-control @error('prj_cn') is-invalid @enderror" value="{{ old('prj_cn') }}">
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
                                    </div>
                                </div>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save"></i> {{ trans('main_trans.submit') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- جدول المشاريع --}}
                <div class="table-responsive mb-3">
                    <table class="table table-bordered text-center">
                        <thead>
                            <tr>
                                <th>{{ trans('main_trans.id') }}</th>
                                <th>{{ trans('main_trans.prj_title') }}</th>
                                <th>{{ trans('main_trans.area') }}</th>
                                <th>شركة التطوير العقاري</th>
                                <th>{{ trans('main_trans.prj_description') }}</th>
                                <th>{{ trans('main_trans.processes') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($projects as $project)
                            <tr data-project-name="{{ $project->getTitle() }}" data-project-description="{{ $project->getDescription() }}" data-project-number="{{ $project->prj_projectNumber }}" data-project-permit="{{ $project->prj_MadhmounPermitNumber }}">
                                <td>{{ $project->id }}</td>
                                <td>
                                    <div class="text-start">
                                        <div class="fw-bold">{{ $project->getTitle() }}</div>
                                        @if($project->prj_title_ar && $project->prj_title_en)
                                        <small class="text-muted">
                                            {{ $project->prj_title_ar }} / {{ $project->prj_title_en }}
                                        </small>
                                        @endif
                                    </div>
                                </td>
                                <td>{{ $project->area ? (app()->getLocale() == 'ar' ? $project->area->name_ar : $project->area->name_en) : 'N/A' }}</td>
                                <td>{{ $project->company ? $project->company->company_name_ar : 'غير محدد' }}</td>
                                <td>
                                    @if($project->getDescription())
                                        {{ Str::limit($project->getDescription(), 50) }}
                                    @else
                                        <span class="text-muted">{{ trans('main_trans.no_description') }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('project-details.index', $project->id) }}" class="btn btn-warning btn-sm" title="{{ trans('main_trans.manage_project_details') }}">
                                            <i class="fas fa-list"></i>
                                        </a>
                                        {{-- <a href="{{ route('project-descriptions.index', $project->id) }}" class="btn btn-secondary btn-sm" title="{{ trans('main_trans.project_descriptions_management') }}">
                                            <i class="fas fa-file-alt"></i>
                                        </a> --}}
                                        <a href="{{ route('project-amenities.index', $project->id) }}" class="btn btn-warning btn-sm" title="{{ trans('main_trans.project_amenities_management') }}">
                                            <i class="fas fa-concierge-bell"></i>
                                        </a>
                                        <a href="{{ route('project-images.index', $project->id) }}" class="btn btn-primary btn-sm" title="{{ trans('main_trans.manage_project_images') }}">
                                            <i class="fas fa-images"></i>
                                        </a>
                                        <a href="{{ route('project-content-blocks.index', $project->id) }}" class="btn btn-success btn-sm" title="إدارة أقسام المحتوى">
                                            <i class="fas fa-file-text"></i>
                                        </a>
                                        <a href="{{ route('projects.edit', $project->id) }}" class="btn btn-info btn-sm" title="{{ trans('main_trans.edit') }}">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <form action="{{ route('projects.destroy', $project->id) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ trans('main_trans.confirm_delete') }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="{{ trans('main_trans.delete') }}">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">{{ trans('main_trans.no_projects_found') }}</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    {{ $projects->links('pagination::bootstrap-5') }}
                </div>

            </div>
        </div>
    </div>
</div>

@endsection
