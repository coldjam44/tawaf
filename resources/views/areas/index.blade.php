@extends('admin.layouts.app')

@section('content')

<div class="row">
    <div class="col-xl">
        <div class="card mb-4">

            {{-- عنوان الصفحة وزر الإضافة --}}
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="text-center my-4">{{ trans('main_trans.areas_management') }}</h3>
                <div class="text-right mb-3">
                    <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#addAreaForm" aria-expanded="false" aria-controls="addAreaForm">
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

                {{-- فورم الإضافة --}}
                <div class="collapse" id="addAreaForm">
                    <div class="card card-body">
                        <form action="{{ route('areas.store') }}" method="POST">
                            @csrf

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="name_ar" class="form-label">{{ trans('main_trans.area_name_ar') }} *</label>
                                        <input type="text" name="name_ar" id="name_ar" class="form-control @error('name_ar') is-invalid @enderror" value="{{ old('name_ar') }}" required>
                                        @error('name_ar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="name_en" class="form-label">{{ trans('main_trans.area_name_en') }} *</label>
                                        <input type="text" name="name_en" id="name_en" class="form-control @error('name_en') is-invalid @enderror" value="{{ old('name_en') }}" required>
                                        @error('name_en')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="slug" class="form-label">{{ trans('main_trans.area_slug') }} *</label>
                                        <input type="text" name="slug" id="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug') }}" required>
                                        <small class="form-text text-muted">مثال: downtown-dubai, dubai-marina</small>
                                        @error('slug')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="about_community_overview_ar" class="form-label">About & Community Overview (العربية)</label>
                                        <textarea name="about_community_overview_ar" id="about_community_overview_ar" class="form-control" rows="4">{{ old('about_community_overview_ar') }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="about_community_overview_en" class="form-label">About & Community Overview (English)</label>
                                        <textarea name="about_community_overview_en" id="about_community_overview_en" class="form-control" rows="4">{{ old('about_community_overview_en') }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="rental_sales_trends_ar" class="form-label">Rental & Sales Trends (العربية)</label>
                                        <textarea name="rental_sales_trends_ar" id="rental_sales_trends_ar" class="form-control" rows="4">{{ old('rental_sales_trends_ar') }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="rental_sales_trends_en" class="form-label">Rental & Sales Trends (English)</label>
                                        <textarea name="rental_sales_trends_en" id="rental_sales_trends_en" class="form-control" rows="4">{{ old('rental_sales_trends_en') }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="roi_ar" class="form-label">ROI (العربية)</label>
                                        <textarea name="roi_ar" id="roi_ar" class="form-control" rows="4">{{ old('roi_ar') }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="roi_en" class="form-label">ROI (English)</label>
                                        <textarea name="roi_en" id="roi_en" class="form-control" rows="4">{{ old('roi_en') }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="things_to_do_perks_ar" class="form-label">Things to Do & Perks (العربية)</label>
                                        <textarea name="things_to_do_perks_ar" id="things_to_do_perks_ar" class="form-control" rows="4">{{ old('things_to_do_perks_ar') }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="things_to_do_perks_en" class="form-label">Things to Do & Perks (English)</label>
                                        <textarea name="things_to_do_perks_en" id="things_to_do_perks_en" class="form-control" rows="4">{{ old('things_to_do_perks_en') }}</textarea>
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

                {{-- جدول المناطق --}}
                <div class="table-responsive mb-3">
                    <table class="table table-bordered text-center">
                        <thead>
                            <tr>
                                <th>{{ trans('main_trans.id') }}</th>
                                <th>{{ trans('main_trans.area_name') }}</th>
                                <th>{{ trans('main_trans.area_slug') }}</th>
                                <th>{{ trans('main_trans.area_projects_count') }}</th>
                                <th>{{ trans('main_trans.created_at') }}</th>
                                <th>{{ trans('main_trans.processes') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($areas as $area)
                            <tr>
                                <td>{{ $area->id }}</td>
                                <td>
                                    <div class="text-start">
                                        <div class="fw-bold">{{ app()->getLocale() == 'ar' ? $area->name_ar : $area->name_en }}</div>
                                        <small class="text-muted">
                                            {{ $area->name_ar }} / {{ $area->name_en }}
                                        </small>
                                    </div>
                                </td>
                                <td>
                                    <code>{{ $area->slug }}</code>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $area->projects()->count() }}</span>
                                </td>
                                <td>{{ $area->created_at->format('Y-m-d') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('areas.show', $area->id) }}" class="btn btn-success btn-sm" title="{{ trans('main_trans.view') }}">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('areas.edit', $area->id) }}" class="btn btn-info btn-sm" title="{{ trans('main_trans.edit') }}">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        @if($area->projects()->count() > 0)
                                        <a href="{{ route('projects.index') }}?area={{ $area->id }}" class="btn btn-warning btn-sm" title="{{ trans('main_trans.view_projects') }}">
                                            <i class="fas fa-building"></i>
                                        </a>
                                        @endif
                                        <form action="{{ route('areas.destroy', $area->id) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ trans('main_trans.confirm_delete') }}')">
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
                                <td colspan="6" class="text-center">{{ trans('main_trans.no_areas_found') }}</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    {{ $areas->links('pagination::bootstrap-5') }}
                </div>

            </div>
        </div>
    </div>
</div>

@endsection
