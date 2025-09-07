@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-robot me-2"></i>
                        {{ trans('main_trans.bot_offers_management') }}
                    </h4>
                    <div class="d-flex align-items-center">
                        <span class="badge bg-info me-2">{{ $projects->count() }} {{ trans('main_trans.projects') }}</span>
                        <a href="{{ route('projects.index') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i>
                            {{ trans('main_trans.add_project') }}
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="mb-3">
                        <p class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            {{ trans('main_trans.bot_offers_description') }}
                        </p>
                    </div>

                    @if($projects->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th style="width: 60px;">#</th>
                                        <th style="width: 80px;">{{ trans('main_trans.image') }}</th>
                                        <th>{{ trans('main_trans.project_title') }}</th>
                                        <th style="width: 150px;">{{ trans('main_trans.developer') }}</th>
                                        <th style="width: 120px;">{{ trans('main_trans.area') }}</th>
                                        <th style="width: 100px;">{{ trans('main_trans.status') }}</th>
                                        <th style="width: 150px;">{{ trans('main_trans.bot_toggle') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($projects as $project)
                                    <tr>
                                        <td class="text-center">{{ $project->id }}</td>
                                        <td class="text-center">
                                            @if($project->projectImages->count() > 0)
                                                @php
                                                    $featuredImage = $project->projectImages->where('is_featured', true)->first();
                                                    $firstImage = $featuredImage ?: $project->projectImages->first();
                                                @endphp
                                                <img src="{{ asset('projects/images/' . $firstImage->image_path) }}" alt="Project Image" class="img-thumbnail" style="max-width: 60px; max-height: 60px; object-fit: cover;">
                                            @else
                                                <span class="text-muted small">{{ trans('main_trans.no_image') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="fw-bold">{{ $project->getTitle() }}</div>
                                            <div class="text-muted small">{{ Str::limit($project->getDescription(), 80) }}</div>
                                        </td>
                                        <td>
                                            @if($project->developer)
                                                <span class="badge bg-primary">{{ app()->getLocale() === 'ar' ? $project->developer->name_ar : $project->developer->name_en }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($project->area)
                                                <span class="badge bg-info">{{ app()->getLocale() === 'ar' ? $project->area->name_ar : $project->area->name_en }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-success">{{ trans('main_trans.active') }}</span>
                                        </td>
                                        <td class="text-center">
                                            <form action="{{ route('bot-offers.toggle', $project->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn {{ $project->is_sent_to_bot ? 'btn-danger' : 'btn-success' }} btn-sm">
                                                    <i class="fas fa-robot"></i>
                                                    {{ $project->is_sent_to_bot ? trans('main_trans.stop_sending') : trans('main_trans.send_to_bot') }}
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-robot fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">{{ trans('main_trans.no_projects_found') }}</h5>
                            <p class="text-muted">{{ trans('main_trans.bot_offers_not_configured') }}</p>
                            <a href="{{ route('projects.index') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i>
                                {{ trans('main_trans.add_project') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
