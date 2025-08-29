@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">{{ trans('main_trans.about_us_sections') }}</h4>
                    <div>
                        <a href="{{ route('about-us.create') }}" class="btn btn-primary me-2">
                            <i class="fas fa-plus me-1"></i>
                            {{ trans('main_trans.add_about_us_section') }}
                        </a>
                        <a href="{{ route('awards.create') }}" class="btn btn-success me-2">
                            <i class="fas fa-trophy me-1"></i>
                            {{ trans('main_trans.add_our_awards') }}
                        </a>
                        <a href="{{ route('services.index') }}" class="btn btn-secondary me-2">
                            <i class="fas fa-concierge-bell me-1"></i>
                            {{ trans('main_trans.our_services') }}
                        </a>
                        <a href="{{ route('achievements.index') }}" class="btn btn-warning me-2">
                            <i class="fas fa-award me-1"></i>
                            {{ trans('main_trans.our_achievement') }}
                        </a>
                        <a href="{{ route('expert-team.index') }}" class="btn btn-info">
                            <i class="fas fa-users me-1"></i>
                            {{ trans('main_trans.expert_team') }}
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

                    @if($sections->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>{{ trans('main_trans.order') }}</th>
                                        <th>{{ trans('main_trans.section_name') }}</th>
                                        <th>{{ trans('main_trans.title') }}</th>
                                        <th>{{ trans('main_trans.main_image') }}</th>
                                        <th>{{ trans('main_trans.additional_images') }}</th>
                                        <th>{{ trans('main_trans.status') }}</th>
                                        <th>{{ trans('main_trans.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($sections as $section)
                                    <tr>
                                        <td>{{ $section->order_index }}</td>
                                        <td>
                                            <strong>{{ $section->section_name }}</strong>
                                        </td>
                                        <td>
                                            <div>
                                                <strong>{{ trans('main_trans.title_ar') }}:</strong> {{ $section->title_ar }}
                                            </div>
                                            <div>
                                                <strong>{{ trans('main_trans.title_en') }}:</strong> {{ $section->title_en }}
                                            </div>
                                        </td>
                                        <td>
                                            @if($section->main_image)
                                                <img src="{{ $section->main_image_url }}" alt="Main Image" class="img-thumbnail" style="max-width: 80px;">
                                            @else
                                                <span class="text-muted">{{ trans('main_trans.no_image') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $section->images->count() }} {{ trans('main_trans.images') }}</span>
                                            @if($section->images->count() > 0)
                                                <br><small class="text-muted">{{ trans('main_trans.click_to_view') }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            @if($section->is_active)
                                                <span class="badge bg-success">{{ trans('main_trans.active') }}</span>
                                            @else
                                                <span class="badge bg-secondary">{{ trans('main_trans.inactive') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('about-us.edit', $section->id) }}" class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('about-us.destroy', $section->id) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ trans('main_trans.confirm_delete') }}')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-info-circle fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">{{ trans('main_trans.no_about_us_sections_found') }}</h5>
                            <a href="{{ route('about-us.create') }}" class="btn btn-primary mt-2">
                                <i class="fas fa-plus me-1"></i>
                                {{ trans('main_trans.add_about_us_section') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
