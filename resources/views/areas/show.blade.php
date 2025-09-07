@extends('admin.layouts.app')

@section('content')

<div class="row">
    <div class="col-xl">
        <div class="card mb-4">

            {{-- عنوان الصفحة --}}
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="text-center my-4">{{ trans('main_trans.area_name') }}</h3>
                <div class="text-right mb-3">
                    <a href="{{ route('areas.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> {{ trans('main_trans.back') }}
                    </a>
                </div>
            </div>

            <div class="card-body">

                {{-- معلومات المنطقة --}}
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted">{{ trans('main_trans.area_name_ar') }}</h6>
                        <p class="fw-bold">{{ $area->name_ar }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">{{ trans('main_trans.area_name_en') }}</h6>
                        <p class="fw-bold">{{ $area->name_en }}</p>
                    </div>
                </div>

                {{-- الصورة الرئيسية --}}
                @if($area->main_image)
                <div class="row mb-4">
                    <div class="col-12">
                        <h6 class="text-muted">{{ trans('main_trans.main_image') }}</h6>
                        <div class="text-center">
                            <img src="{{ $area->main_image_url }}" alt="{{ $area->name_ar }}" class="img-fluid rounded shadow" style="max-width: 400px; max-height: 300px; object-fit: cover;">
                        </div>
                    </div>
                </div>
                @endif

                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted">{{ trans('main_trans.area_slug') }}</h6>
                        <p><code>{{ $area->slug }}</code></p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">{{ trans('main_trans.area_projects_count') }}</h6>
                        <p><span class="badge bg-info">{{ $area->projects()->count() }}</span></p>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted">{{ trans('main_trans.created_at') }}</h6>
                        <p>{{ $area->created_at->format('Y-m-d H:i:s') }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">{{ trans('main_trans.updated_at') }}</h6>
                        <p>{{ $area->updated_at->format('Y-m-d H:i:s') }}</p>
                    </div>
                </div>

                {{-- About & Community Overview --}}
                @if($area->about_community_overview_ar || $area->about_community_overview_en)
                <div class="row mb-4">
                    <div class="col-12">
                        <h5 class="text-primary mb-3">About & Community Overview</h5>
                    </div>
                    @if($area->about_community_overview_ar)
                    <div class="col-md-6">
                        <h6 class="text-muted">العربية</h6>
                        <div class="card">
                            <div class="card-body">
                                <p class="mb-0">{{ $area->about_community_overview_ar }}</p>
                            </div>
                        </div>
                    </div>
                    @endif
                    @if($area->about_community_overview_en)
                    <div class="col-md-6">
                        <h6 class="text-muted">English</h6>
                        <div class="card">
                            <div class="card-body">
                                <p class="mb-0">{{ $area->about_community_overview_en }}</p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                @endif

                {{-- Rental & Sales Trends --}}
                @if($area->rental_sales_trends_ar || $area->rental_sales_trends_en)
                <div class="row mb-4">
                    <div class="col-12">
                        <h5 class="text-success mb-3">Rental & Sales Trends</h5>
                    </div>
                    @if($area->rental_sales_trends_ar)
                    <div class="col-md-6">
                        <h6 class="text-muted">العربية</h6>
                        <div class="card">
                            <div class="card-body">
                                <p class="mb-0">{{ $area->rental_sales_trends_ar }}</p>
                            </div>
                        </div>
                    </div>
                    @endif
                    @if($area->rental_sales_trends_en)
                    <div class="col-md-6">
                        <h6 class="text-muted">English</h6>
                        <div class="card">
                            <div class="card-body">
                                <p class="mb-0">{{ $area->rental_sales_trends_en }}</p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                @endif

                {{-- ROI --}}
                @if($area->roi_ar || $area->roi_en)
                <div class="row mb-4">
                    <div class="col-12">
                        <h5 class="text-warning mb-3">ROI (Return on Investment)</h5>
                    </div>
                    @if($area->roi_ar)
                    <div class="col-md-6">
                        <h6 class="text-muted">العربية</h6>
                        <div class="card">
                            <div class="card-body">
                                <p class="mb-0">{{ $area->roi_ar }}</p>
                            </div>
                        </div>
                    </div>
                    @endif
                    @if($area->roi_en)
                    <div class="col-md-6">
                        <h6 class="text-muted">English</h6>
                        <div class="card">
                            <div class="card-body">
                                <p class="mb-0">{{ $area->roi_en }}</p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                @endif

                {{-- Things to Do & Perks --}}
                @if($area->things_to_do_perks_ar || $area->things_to_do_perks_en)
                <div class="row mb-4">
                    <div class="col-12">
                        <h5 class="text-info mb-3">Things to Do & Perks</h5>
                    </div>
                    @if($area->things_to_do_perks_ar)
                    <div class="col-md-6">
                        <h6 class="text-muted">العربية</h6>
                        <div class="card">
                            <div class="card-body">
                                <p class="mb-0">{{ $area->things_to_do_perks_ar }}</p>
                            </div>
                        </div>
                    </div>
                    @endif
                    @if($area->things_to_do_perks_en)
                    <div class="col-md-6">
                        <h6 class="text-muted">English</h6>
                        <div class="card">
                            <div class="card-body">
                                <p class="mb-0">{{ $area->things_to_do_perks_en }}</p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                @endif

                {{-- مشاريع المنطقة --}}
                @if($area->projects->count() > 0)
                <div class="mt-4">
                    <h5>{{ trans('main_trans.projects') }} ({{ $area->projects->count() }})</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>{{ trans('main_trans.id') }}</th>
                                    <th>{{ trans('main_trans.prj_title') }}</th>
                                    <th>{{ trans('main_trans.developer') }}</th>
                                    <th>{{ trans('main_trans.created_at') }}</th>
                                    <th>{{ trans('main_trans.processes') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($area->projects as $project)
                                <tr>
                                    <td>{{ $project->id }}</td>
                                    <td>
                                        <div class="text-start">
                                            <div class="fw-bold">{{ $project->getTitle() }}</div>
                                            <small class="text-muted">
                                                {{ $project->prj_title_ar }} / {{ $project->prj_title_en }}
                                            </small>
                                        </div>
                                    </td>
                                    <td>{{ $project->developer ? (app()->getLocale() == 'ar' ? $project->developer->name_ar : $project->developer->name_en) : 'N/A' }}</td>
                                    <td>{{ $project->created_at->format('Y-m-d') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('project-details.index', $project->id) }}" class="btn btn-warning btn-sm" title="{{ trans('main_trans.manage_project_details') }}">
                                                <i class="fas fa-list"></i>
                                            </a>
                                            <a href="{{ route('projects.edit', $project->id) }}" class="btn btn-info btn-sm" title="{{ trans('main_trans.edit') }}">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @else
                <div class="alert alert-info text-center">
                    {{ trans('main_trans.no_projects_found') }}
                </div>
                @endif

            </div>
        </div>
    </div>
</div>

@endsection
