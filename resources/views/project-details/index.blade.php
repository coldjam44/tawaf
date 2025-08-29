@extends('admin.layouts.app')

@section('content')

<div class="row">
    <div class="col-xl">
        <div class="card mb-4">

            {{-- عنوان الصفحة وزر الإضافة --}}
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="text-center my-4">{{ trans('main_trans.project_details_management') }}</h3>
                    <h5 class="text-muted">{{ $project->getTitle() }}</h5>
                </div>
                <div class="text-right mb-3">
                    <a href="{{ route('projects.index') }}" class="btn btn-secondary me-2">
                        <i class="fas fa-arrow-left"></i> {{ trans('main_trans.back') }}
                    </a>
                    <a href="{{ route('project-details.create', $project->id) }}" class="btn btn-primary">
                        <i class="fas fa-plus-circle"></i> {{ trans('main_trans.add_project_detail') }}
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

                {{-- جدول تفاصيل المشروع --}}
                <div class="table-responsive mb-3">
                    <table class="table table-bordered text-center">
                        <thead>
                            <tr>
                                <th>{{ trans('main_trans.order') }}</th>
                                <th>{{ trans('main_trans.detail') }}</th>
                                <th>{{ trans('main_trans.detail_value') }}</th>
                                <th>{{ trans('main_trans.processes') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($project->projectDetails as $detail)
                            <tr>
                                <td>{{ $detail->order }}</td>
                                <td>
                                    <div class="text-start">
                                        <div class="fw-bold">{{ $detail->getDetail() }}</div>
                                        @if($detail->detail_ar && $detail->detail_en)
                                        <small class="text-muted">
                                            {{ $detail->detail_ar }} / {{ $detail->detail_en }}
                                        </small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="text-start">
                                        <div>{{ $detail->getDetailValue() }}</div>
                                        @if($detail->detail_value_ar && $detail->detail_value_en)
                                        <small class="text-muted">
                                            {{ $detail->detail_value_ar }} / {{ $detail->detail_value_en }}
                                        </small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <a href="{{ route('project-details.edit', [$project->id, $detail->id]) }}" class="btn btn-info btn-sm" title="{{ trans('main_trans.edit') }}">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <form action="{{ route('project-details.destroy', [$project->id, $detail->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ trans('main_trans.confirm_delete') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="{{ trans('main_trans.delete') }}">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">{{ trans('main_trans.no_project_details_found') }}</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- عرض تفاصيل المشروع للجمهور --}}
                <div class="mt-4">
                    <h5>{{ trans('main_trans.view_project_details') }}</h5>
                    <a href="{{ route('project-details.show', $project->id) }}" class="btn btn-success" target="_blank">
                        <i class="fas fa-eye"></i> {{ trans('main_trans.view_project_details') }}
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection
