@extends('admin.layouts.app')

@section('content')

<div class="row">
    <div class="col-xl">
        <div class="card mb-4">

            {{-- عنوان الصفحة --}}
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="text-center my-4">{{ trans('main_trans.project_amenities_management') }}</h3>
                    <h5 class="text-muted">{{ $project->getTitle() }}</h5>
                </div>
                <div class="text-right mb-3">
                    <a href="{{ route('projects.index') }}" class="btn btn-secondary me-2">
                        <i class="fas fa-arrow-left"></i> {{ trans('main_trans.back') }}
                    </a>
                    <a href="{{ route('project-amenities.show', $project->id) }}" class="btn btn-success" target="_blank">
                        <i class="fas fa-eye"></i> {{ trans('main_trans.view_project_amenities') }}
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

                {{-- إحصائيات سريعة --}}
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body text-center">
                                <h5>{{ $project->amenities->where('is_active', true)->count() }}</h5>
                                <small>{{ trans('main_trans.active_amenities') }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body text-center">
                                <h5>{{ $project->amenities->where('is_active', false)->count() }}</h5>
                                <small>{{ trans('main_trans.inactive_amenities') }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white">
                            <div class="card-body text-center">
                                <h5>{{ $project->amenities->count() }}</h5>
                                <small>{{ trans('main_trans.total_amenities') }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-info text-white">
                            <div class="card-body text-center">
                                <h5>{{ count($allAmenities) - $project->amenities->count() }}</h5>
                                <small>{{ trans('main_trans.available_amenities') }}</small>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- فورم التحديث الجماعي --}}
                <form action="{{ route('project-amenities.bulk-update', $project->id) }}" method="POST">
                    @csrf
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-cogs text-primary me-2"></i>
                                {{ trans('main_trans.bulk_update_amenities') }}
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach($allAmenities as $type => $amenity)
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="card h-100 {{ $amenity['is_active'] ? 'border-success' : 'border-secondary' }}">
                                        <div class="card-body text-center">
                                            <div class="mb-2">
                                                <i class="{{ $amenity['data']['icon'] }} fa-2x {{ $amenity['is_active'] ? 'text-success' : 'text-muted' }}"></i>
                                            </div>
                                            <h6 class="card-title">{{ app()->getLocale() == 'ar' ? $amenity['data']['ar'] : $amenity['data']['en'] }}</h6>
                                            <div class="form-check form-switch d-flex justify-content-center">
                                                <input class="form-check-input" type="checkbox" name="amenities[]" 
                                                       value="{{ $type }}" id="amenity_{{ $type }}"
                                                       {{ $amenity['is_active'] ? 'checked' : '' }}>
                                                <label class="form-check-label ms-2" for="amenity_{{ $type }}">
                                                    {{ $amenity['is_active'] ? trans('main_trans.active') : trans('main_trans.inactive') }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <div class="text-center mt-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> {{ trans('main_trans.update_all_amenities') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

                {{-- جدول تفاصيل المرافق --}}
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-list text-primary me-2"></i>
                            {{ trans('main_trans.amenities_details') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered text-center">
                                <thead>
                                    <tr>
                                        <th>{{ trans('main_trans.amenity') }}</th>
                                        <th>{{ trans('main_trans.icon') }}</th>
                                        <th>{{ trans('main_trans.status') }}</th>
                                        <th>{{ trans('main_trans.processes') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($allAmenities as $type => $amenity)
                                    <tr>
                                        <td>
                                            <div class="text-start">
                                                <div class="fw-bold">{{ app()->getLocale() == 'ar' ? $amenity['data']['ar'] : $amenity['data']['en'] }}</div>
                                                <small class="text-muted">{{ $type }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <i class="{{ $amenity['data']['icon'] }} fa-2x {{ $amenity['is_active'] ? 'text-success' : 'text-muted' }}"></i>
                                        </td>
                                        <td>
                                            @if($amenity['exists'])
                                                @if($amenity['is_active'])
                                                    <span class="badge bg-success">{{ trans('main_trans.active') }}</span>
                                                @else
                                                    <span class="badge bg-warning">{{ trans('main_trans.inactive') }}</span>
                                                @endif
                                            @else
                                                <span class="badge bg-secondary">{{ trans('main_trans.not_added') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($amenity['exists'])
                                                <div class="btn-group" role="group">
                                                    <form action="{{ route('project-amenities.toggle', [$project->id, $amenity['id']]) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm {{ $amenity['is_active'] ? 'btn-warning' : 'btn-success' }}" 
                                                                title="{{ $amenity['is_active'] ? trans('main_trans.deactivate') : trans('main_trans.activate') }}">
                                                            <i class="fas {{ $amenity['is_active'] ? 'fa-toggle-off' : 'fa-toggle-on' }}"></i>
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('project-amenities.destroy', [$project->id, $amenity['id']]) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ trans('main_trans.confirm_delete') }}')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm" title="{{ trans('main_trans.delete') }}">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            @else
                                                <form action="{{ route('project-amenities.store', $project->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="amenity_type" value="{{ $type }}">
                                                    <input type="hidden" name="is_active" value="1">
                                                    <button type="submit" class="btn btn-success btn-sm" title="{{ trans('main_trans.add_amenity') }}">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center">{{ trans('main_trans.no_amenities_found') }}</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection
