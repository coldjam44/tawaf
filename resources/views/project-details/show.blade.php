@extends('admin.layouts.app')

@section('content')

<div class="row">
    <div class="col-xl">
        <div class="card mb-4">

            {{-- عنوان الصفحة --}}
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="text-center my-4">{{ trans('main_trans.project_details') }}</h3>
                    <h5 class="text-muted">{{ $project->getTitle() }}</h5>
                </div>
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

                {{-- وصف المشروع --}}
                @if($project->getDescription())
                <div class="mb-4">
                    <h6 class="text-muted">{{ trans('main_trans.prj_description') }}</h6>
                    <p>{{ $project->getDescription() }}</p>
                </div>
                @endif

                {{-- جدول تفاصيل المشروع --}}
                @if($project->projectDetails->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tbody>
                            @foreach($project->projectDetails as $detail)
                            <tr>
                                <td class="fw-bold" style="width: 30%;">{{ $detail->getDetail() }}</td>
                                <td>{{ $detail->getDetailValue() }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="alert alert-info text-center">
                    {{ trans('main_trans.no_project_details_found') }}
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
