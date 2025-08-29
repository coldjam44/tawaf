@extends('admin.layouts.app')

@section('content')

<div class="row">
    <div class="col-xl">
        <div class="card mb-4">

            {{-- عنوان الصفحة --}}
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="text-center my-4">{{ $project->getTitle() }}</h3>
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

                {{-- وصف المشروع الأساسي --}}
                @if($project->getDescription())
                <div class="mb-4">
                    <h6 class="text-muted">{{ trans('main_trans.prj_description') }}</h6>
                    <p>{{ $project->getDescription() }}</p>
                </div>
                @endif

                {{-- عرض تفاصيل المشروع --}}
                @if($project->descriptions->count() > 0)
                    @foreach($project->descriptions as $description)
                    <div class="mb-5">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="mb-0">
                                    <i class="fas fa-info-circle text-primary me-2"></i>
                                    {{ $description->getTitle() }}
                                </h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6 class="text-muted mb-3">{{ trans('main_trans.content_ar') }}</h6>
                                        <div class="border rounded p-3 bg-light">
                                            {!! $description->content_ar !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="text-muted mb-3">{{ trans('main_trans.content_en') }}</h6>
                                        <div class="border rounded p-3 bg-light">
                                            {!! $description->content_en !!}
                                        </div>
                                    </div>
                                </div>

                                {{-- عرض معلومات الموقع للنوع location_map --}}
                                @if($description->section_type === 'location_map')
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <h6 class="text-muted mb-3">{{ trans('main_trans.location_information') }}</h6>
                                        <div class="border rounded p-3 bg-light">
                                            <div class="row">
                                                @if($description->location_image)
                                                <div class="col-md-6 mb-3">
                                                    <h6 class="text-primary">{{ trans('main_trans.location_image') }}</h6>
                                                    <img src="{{ $description->location_image_url }}" alt="Location Image" class="img-fluid rounded" style="max-height: 200px;">
                                                </div>
                                                @endif
                                                
                                                <div class="col-md-6">
                                                    @if($description->getAddress())
                                                    <h6 class="text-primary">{{ trans('main_trans.location_address') }}</h6>
                                                    <p class="mb-2">{{ $description->getAddress() }}</p>
                                                    @endif
                                                    
                                                    @if($description->google_location)
                                                    <h6 class="text-primary">{{ trans('main_trans.google_location') }}</h6>
                                                    <a href="{{ $description->google_location }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-map-marker-alt me-1"></i>
                                                        {{ trans('main_trans.view_on_map') }}
                                                    </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                
                                <div class="mt-3">
                                    <small class="text-muted">
                                        <i class="fas fa-tag me-1"></i>
                                        {{ trans('main_trans.section_type') }}: 
                                        <span class="badge bg-secondary">{{ $description->section_type_label }}</span>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle fa-2x mb-3"></i>
                    <h5>{{ trans('main_trans.no_project_descriptions_found') }}</h5>
                    <p class="text-muted">لم يتم إضافة تفاصيل تفصيلية لهذا المشروع بعد.</p>
                </div>
                @endif

                {{-- معلومات إضافية --}}
                @if($project->prj_projectNumber || $project->prj_adm || $project->prj_cn || $project->prj_MadhmounPermitNumber)
                <div class="mt-4">
                    <h6 class="text-muted">{{ trans('main_trans.additional_information') }}</h6>
                    <div class="row">
                        @if($project->prj_projectNumber)
                        <div class="col-md-3">
                            <small class="text-muted">{{ trans('main_trans.prj_projectNumber') }}</small>
                            <p class="mb-1">{{ $project->prj_projectNumber }}</p>
                        </div>
                        @endif
                        @if($project->prj_adm)
                        <div class="col-md-3">
                            <small class="text-muted">{{ trans('main_trans.prj_adm') }}</small>
                            <p class="mb-1">{{ $project->prj_adm }}</p>
                        </div>
                        @endif
                        @if($project->prj_cn)
                        <div class="col-md-3">
                            <small class="text-muted">{{ trans('main_trans.prj_cn') }}</small>
                            <p class="mb-1">{{ $project->prj_cn }}</p>
                        </div>
                        @endif
                        @if($project->prj_MadhmounPermitNumber)
                        <div class="col-md-3">
                            <small class="text-muted">{{ trans('main_trans.prj_MadhmounPermitNumber') }}</small>
                            <p class="mb-1">{{ $project->prj_MadhmounPermitNumber }}</p>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>
</div>

@endsection
