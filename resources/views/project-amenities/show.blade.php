@extends('admin.layouts.app')

@section('content')

<div class="row">
    <div class="col-xl">
        <div class="card mb-4">

            {{-- عنوان الصفحة --}}
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="text-center my-4">{{ trans('main_trans.project_amenities') }} - {{ $project->getTitle() }}</h3>
                <div class="text-right mb-3">
                    <a href="{{ route('projects.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> {{ trans('main_trans.back') }}
                    </a>
                </div>
            </div>

            <div class="card-body">

                {{-- معلومات المشروع الأساسية --}}
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted">{{ trans('main_trans.developer') }}</h6>
                        <p class="fw-bold">{{ $project->developer ? (app()->getLocale() == 'ar' ? $project->developer->name_ar : $project->developer->name_en) : 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">{{ trans('main_trans.area') }}</h6>
                        <p class="fw-bold">{{ $project->area ? (app()->getLocale() == 'ar' ? $project->area->name_ar : $project->area->name_en) : 'N/A' }}</p>
                    </div>
                </div>

                {{-- عرض المرافق --}}
                @if($project->amenities->count() > 0)
                    <div class="row">
                        @foreach($project->amenities as $amenity)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100 border-success">
                                <div class="card-body text-center">
                                    <div class="mb-3">
                                        <i class="{{ $amenity->amenity_icon }} fa-3x text-success"></i>
                                    </div>
                                    <h5 class="card-title">{{ $amenity->getAmenityName() }}</h5>
                                    <p class="card-text text-muted">
                                        {{ app()->getLocale() == 'ar' ? 'مرافق متاحة' : 'Available Facility' }}
                                    </p>
                                    <div class="mt-2">
                                        <span class="badge bg-success">
                                            <i class="fas fa-check me-1"></i>
                                            {{ trans('main_trans.available') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    {{-- إحصائيات المرافق --}}
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="alert alert-info text-center">
                                <i class="fas fa-info-circle fa-2x mb-3"></i>
                                <h5>{{ trans('main_trans.amenities_summary') }}</h5>
                                <p class="mb-0">
                                    {{ trans('main_trans.total_amenities_available') }}: 
                                    <strong>{{ $project->amenities->count() }}</strong> 
                                    {{ trans('main_trans.amenities') }}
                                </p>
                            </div>
                        </div>
                    </div>

                @else
                    <div class="alert alert-warning text-center">
                        <i class="fas fa-exclamation-triangle fa-2x mb-3"></i>
                        <h5>{{ trans('main_trans.no_amenities_available') }}</h5>
                        <p class="text-muted">لم يتم إضافة مرافق لهذا المشروع بعد.</p>
                    </div>
                @endif

                {{-- قائمة المرافق المتاحة --}}
                <div class="mt-4">
                    <h5 class="text-muted mb-3">
                        <i class="fas fa-list me-2"></i>
                        {{ trans('main_trans.available_amenity_types') }}
                    </h5>
                    <div class="row">
                        @php
                            $amenityTypes = \App\Models\ProjectAmenity::getAmenityTypesData();
                        @endphp
                        @foreach($amenityTypes as $type => $data)
                        <div class="col-md-6 col-lg-4 mb-2">
                            <div class="d-flex align-items-center p-2 border rounded">
                                <i class="{{ $data['icon'] }} fa-lg text-primary me-3"></i>
                                <span class="small">{{ app()->getLocale() == 'ar' ? $data['ar'] : $data['en'] }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection
