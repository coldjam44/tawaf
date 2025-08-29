@extends('admin.layouts.app')

@section('content')

<div class="row">
    <div class="col-xl">
        <div class="card mb-4">

            {{-- عنوان الصفحة وزر الإضافة --}}
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="text-center my-4">{{ trans('main_trans.project_images_management') }}</h3>
                    <h5 class="text-muted">{{ $project->getTitle() }}</h5>
                </div>
                <div class="text-right mb-3">
                    <a href="{{ route('projects.index') }}" class="btn btn-secondary me-2">
                        <i class="fas fa-arrow-left"></i> {{ trans('main_trans.back') }}
                    </a>
                    <a href="{{ route('project-images.create', $project->id) }}" class="btn btn-primary">
                        <i class="fas fa-plus-circle"></i> {{ trans('main_trans.add_project_image') }}
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
                                <h5>{{ $project->projectImages->where('type', 'interior')->count() }}</h5>
                                <small>{{ trans('main_trans.interior') }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white">
                            <div class="card-body text-center">
                                <h5>{{ $project->projectImages->where('type', 'exterior')->count() }}</h5>
                                <small>{{ trans('main_trans.exterior') }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-info text-white">
                            <div class="card-body text-center">
                                <h5>{{ $project->projectImages->where('type', 'floorplan')->count() }}</h5>
                                <small>{{ trans('main_trans.floorplan') }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body text-center">
                                <h5>{{ $project->projectImages->where('type', 'featured')->count() }}</h5>
                                <small>{{ trans('main_trans.featured') }}</small>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- عرض الصور حسب النوع --}}
                @php
                    $imageTypes = ['interior', 'exterior', 'floorplan', 'featured'];
                @endphp

                @foreach($imageTypes as $type)
                    @php
                        $images = $project->projectImages->where('type', $type);
                    @endphp
                    
                    @if($images->count() > 0)
                    <div class="mb-4">
                        <h5 class="mb-3">
                            <i class="fas fa-images"></i> 
                            {{ trans('main_trans.' . $type) }} 
                            <span class="badge bg-secondary">{{ $images->count() }}</span>
                        </h5>
                        
                        <div class="row">
                            @foreach($images as $image)
                            <div class="col-md-4 col-lg-3 mb-3">
                                <div class="card h-100">
                                    <img src="{{ $image->image_url }}" class="card-img-top" alt="{{ $image->getTitle() }}" style="height: 200px; object-fit: cover;">
                                    <div class="card-body">
                                        <h6 class="card-title">{{ $image->getTitle() ?: 'بدون عنوان' }}</h6>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">{{ $image->type_label }}</small>
                                            @if($image->is_featured)
                                            <span class="badge bg-warning">{{ trans('main_trans.is_featured') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <div class="btn-group w-100" role="group">
                                            <a href="{{ route('project-images.edit', [$project->id, $image->id]) }}" class="btn btn-info btn-sm" title="{{ trans('main_trans.edit') }}">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <form action="{{ route('project-images.destroy', [$project->id, $image->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ trans('main_trans.confirm_delete') }}')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" title="{{ trans('main_trans.delete') }}">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        لا توجد صور من نوع "{{ trans('main_trans.' . $type) }}" لهذا المشروع.
                        <a href="{{ route('project-images.create', $project->id) }}?type={{ $type }}" class="alert-link">إضافة صورة {{ trans('main_trans.' . $type) }}</a>
                    </div>
                    @endif
                @endforeach

                {{-- رسالة إذا لم توجد صور --}}
                @if($project->projectImages->count() == 0)
                <div class="text-center py-5">
                    <i class="fas fa-images fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">{{ trans('main_trans.no_project_images_found') }}</h5>
                    <p class="text-muted">لم يتم إضافة أي صور لهذا المشروع بعد.</p>
                    <a href="{{ route('project-images.create', $project->id) }}" class="btn btn-primary">
                        <i class="fas fa-plus-circle"></i> {{ trans('main_trans.add_project_image') }}
                    </a>
                </div>
                @endif

            </div>
        </div>
    </div>
</div>

@endsection
