@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">{{ trans('main_trans.blog_details') }}</h4>
                </div>
                <div class="card-body">
                    {{-- Basic Information --}}
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>{{ trans('main_trans.title_ar') }}</h5>
                            <p class="text-muted">{{ $blog->title_ar }}</p>
                        </div>
                        <div class="col-md-6">
                            <h5>{{ trans('main_trans.title_en') }}</h5>
                            <p class="text-muted">{{ $blog->title_en }}</p>
                        </div>
                    </div>

                    {{-- Content --}}
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>{{ trans('main_trans.content_ar') }}</h5>
                            <div class="border p-3 bg-light">
                                {!! $blog->content_ar !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h5>{{ trans('main_trans.content_en') }}</h5>
                            <div class="border p-3 bg-light">
                                {!! $blog->content_en !!}
                            </div>
                        </div>
                    </div>

                    {{-- Main Image --}}
                    @if($blog->main_image)
                    <div class="mb-4">
                        <h5>{{ trans('main_trans.main_image') }}</h5>
                        <div class="text-center">
                            <img src="{{ $blog->main_image_url }}" alt="Main Image" class="img-fluid" style="max-width: 500px; max-height: 300px; object-fit: cover;">
                        </div>
                    </div>
                    @else
                    <div class="mb-4">
                        <h5>{{ trans('main_trans.main_image') }}</h5>
                        <p class="text-muted">{{ trans('main_trans.no_image') }}</p>
                    </div>
                    @endif

                    {{-- Additional Images --}}
                    @if($blog->images->count() > 0)
                    <div class="mb-4">
                        <h5>{{ trans('main_trans.additional_images') }} ({{ $blog->images->count() }})</h5>
                        <div class="row">
                            @foreach($blog->images as $image)
                            <div class="col-md-3 mb-3">
                                <div class="card">
                                    <img src="{{ $image->image_url }}" alt="{{ $image->getAltText() }}" class="card-img-top" style="height: 150px; object-fit: cover;">
                                    <div class="card-body p-2">
                                        <small class="text-muted">{{ $image->getAltText() ?: 'No Alt Text' }}</small>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @else
                    <div class="mb-4">
                        <h5>{{ trans('main_trans.additional_images') }}</h5>
                        <p class="text-muted">{{ trans('main_trans.no_images') }}</p>
                    </div>
                    @endif

                    {{-- Status and Settings --}}
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <h5>{{ trans('main_trans.blog_status') }}</h5>
                            @if($blog->status === 'published')
                                <span class="badge bg-success">{{ trans('main_trans.published') }}</span>
                            @else
                                <span class="badge bg-warning">{{ trans('main_trans.draft') }}</span>
                            @endif
                        </div>
                        <div class="col-md-3">
                            <h5>{{ trans('main_trans.order') }}</h5>
                            <p class="text-muted">{{ $blog->order_index }}</p>
                        </div>
                        <div class="col-md-3">
                            <h5>{{ trans('main_trans.is_active') }}</h5>
                            @if($blog->is_active)
                                <span class="badge bg-success">{{ trans('main_trans.active') }}</span>
                            @else
                                <span class="badge bg-secondary">{{ trans('main_trans.inactive') }}</span>
                            @endif
                        </div>
                        <div class="col-md-3">
                            <h5>{{ trans('main_trans.created_at') }}</h5>
                            <p class="text-muted">{{ $blog->created_at->format('Y-m-d H:i:s') }}</p>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('blogsection.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>
                            {{ trans('main_trans.back') }}
                        </a>
                        <div>
                            <a href="{{ route('blogsection.edit', $blog->id) }}" class="btn btn-warning">
                                <i class="fas fa-edit me-1"></i>
                                {{ trans('main_trans.edit') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
