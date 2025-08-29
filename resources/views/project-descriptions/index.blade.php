@extends('admin.layouts.app')

@section('content')

<div class="row">
    <div class="col-xl">
        <div class="card mb-4">

            {{-- عنوان الصفحة وزر الإضافة --}}
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="text-center my-4">{{ trans('main_trans.project_descriptions_management') }}</h3>
                    <h5 class="text-muted">{{ $project->getTitle() }}</h5>
                </div>
                <div class="text-right mb-3">
                    <a href="{{ route('projects.index') }}" class="btn btn-secondary me-2">
                        <i class="fas fa-arrow-left"></i> {{ trans('main_trans.back') }}
                    </a>
                    <a href="{{ route('project-descriptions.create', $project->id) }}" class="btn btn-primary">
                        <i class="fas fa-plus-circle"></i> {{ trans('main_trans.add_project_description') }}
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
                    <div class="col-md-2">
                        <div class="card bg-primary text-white">
                            <div class="card-body text-center">
                                <h5>{{ $project->descriptions->where('section_type', 'about')->count() }}</h5>
                                <small>{{ trans('main_trans.about') }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card bg-success text-white">
                            <div class="card-body text-center">
                                <h5>{{ $project->descriptions->where('section_type', 'architecture')->count() }}</h5>
                                <small>{{ trans('main_trans.architecture') }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card bg-info text-white">
                            <div class="card-body text-center">
                                <h5>{{ $project->descriptions->where('section_type', 'why_choose')->count() }}</h5>
                                <small>{{ trans('main_trans.why_choose') }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card bg-warning text-white">
                            <div class="card-body text-center">
                                <h5>{{ $project->descriptions->where('section_type', 'location')->count() }}</h5>
                                <small>{{ trans('main_trans.location') }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card bg-danger text-white">
                            <div class="card-body text-center">
                                <h5>{{ $project->descriptions->where('section_type', 'investment')->count() }}</h5>
                                <small>{{ trans('main_trans.investment') }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card bg-dark text-white">
                            <div class="card-body text-center">
                                <h5>{{ $project->descriptions->where('is_active', true)->count() }}</h5>
                                <small>{{ trans('main_trans.active') }}</small>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- جدول تفاصيل المشروع --}}
                <div class="table-responsive mb-3">
                    <table class="table table-bordered text-center">
                        <thead>
                            <tr>
                                <th>{{ trans('main_trans.order') }}</th>
                                <th>{{ trans('main_trans.section_type') }}</th>
                                <th>{{ trans('main_trans.title') }}</th>
                                <th>{{ trans('main_trans.content_preview') }}</th>
                                <th>{{ trans('main_trans.status') }}</th>
                                <th>{{ trans('main_trans.processes') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($project->descriptions as $description)
                            <tr>
                                <td>{{ $description->order_index }}</td>
                                <td>
                                    <span class="badge bg-secondary">{{ $description->section_type_label }}</span>
                                </td>
                                <td>
                                    <div class="text-start">
                                        <div class="fw-bold">{{ $description->getTitle() }}</div>
                                        <small class="text-muted">
                                            {{ $description->title_ar }} / {{ $description->title_en }}
                                        </small>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-start">
                                        <div>{{ Str::limit($description->getContent(), 100) }}</div>
                                    </div>
                                </td>
                                <td>
                                    @if($description->is_active)
                                        <span class="badge bg-success">{{ trans('main_trans.active') }}</span>
                                    @else
                                        <span class="badge bg-danger">{{ trans('main_trans.inactive') }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('project-descriptions.edit', [$project->id, $description->id]) }}" class="btn btn-info btn-sm" title="{{ trans('main_trans.edit') }}">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <form action="{{ route('project-descriptions.destroy', [$project->id, $description->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ trans('main_trans.confirm_delete') }}')">
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
                                <td colspan="6" class="text-center">{{ trans('main_trans.no_project_descriptions_found') }}</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- عرض تفاصيل المشروع للجمهور --}}
                <div class="mt-4">
                    <h5>{{ trans('main_trans.view_project_descriptions') }}</h5>
                    <a href="{{ route('project-descriptions.show', $project->id) }}" class="btn btn-success" target="_blank">
                        <i class="fas fa-eye"></i> {{ trans('main_trans.view_project_descriptions') }}
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection
