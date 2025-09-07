@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">{{ trans('main_trans.about_us_section_details') }}</h4>
                    <div>
                        <a href="{{ route('about-us.index') }}" class="btn btn-secondary me-2">
                            <i class="fas fa-arrow-left me-1"></i>
                            {{ trans('main_trans.actions_back') }}
                        </a>
                        <a href="{{ route('about-us.edit', $section->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-1"></i>
                            {{ trans('main_trans.actions_edit') }}
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="row mb-4">
                                <div class="col-sm-3">
                                    <strong>{{ trans('main_trans.section_name') }}:</strong>
                                </div>
                                <div class="col-sm-9">
                                    <span class="badge bg-primary">{{ $section->section_name }}</span>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-sm-3">
                                    <strong>{{ trans('main_trans.order') }}:</strong>
                                </div>
                                <div class="col-sm-9">
                                    {{ $section->order_index }}
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-sm-3">
                                    <strong>{{ trans('main_trans.status') }}:</strong>
                                </div>
                                <div class="col-sm-9">
                                    @if($section->is_active)
                                        <span class="badge bg-success">{{ trans('main_trans.active') }}</span>
                                    @else
                                        <span class="badge bg-secondary">{{ trans('main_trans.inactive') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-sm-3">
                                    <strong>{{ trans('main_trans.title_ar') }}:</strong>
                                </div>
                                <div class="col-sm-9">
                                    {{ $section->title_ar }}
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-sm-3">
                                    <strong>{{ trans('main_trans.title_en') }}:</strong>
                                </div>
                                <div class="col-sm-9">
                                    {{ $section->title_en }}
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-sm-3">
                                    <strong>{{ trans('main_trans.content_ar') }}:</strong>
                                </div>
                                <div class="col-sm-9">
                                    <div class="border p-3 rounded bg-light">
                                        {!! $section->content_ar !!}
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-sm-3">
                                    <strong>{{ trans('main_trans.content_en') }}:</strong>
                                </div>
                                <div class="col-sm-9">
                                    <div class="border p-3 rounded bg-light">
                                        {!! $section->content_en !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">{{ trans('main_trans.main_image') }}</h5>
                                </div>
                                <div class="card-body text-center">
                                    @if($section->main_image)
                                        <img src="{{ $section->main_image_url }}" alt="Main Image" class="img-fluid rounded">
                                    @else
                                        <div class="text-muted py-4">
                                            <i class="fas fa-image fa-3x mb-2"></i>
                                            <p>{{ trans('main_trans.no_image') }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="card mt-3">
                                <div class="card-header">
                                    <h5 class="mb-0">{{ trans('main_trans.additional_images') }} ({{ $section->images->count() }})</h5>
                                </div>
                                <div class="card-body">
                                    @if($section->images->count() > 0)
                                        <div class="row">
                                            @foreach($section->images as $image)
                                                <div class="col-6 mb-3">
                                                    <div class="position-relative">
                                                        <img src="{{ asset('about-us/images/' . $image->image_path) }}" 
                                                             alt="{{ $image->alt_text_en }}" 
                                                             class="img-fluid rounded">
                                                        <div class="position-absolute top-0 end-0">
                                                            <span class="badge bg-dark">{{ $image->order_index }}</span>
                                                        </div>
                                                    </div>
                                                    <small class="text-muted d-block mt-1">
                                                        {{ $image->caption_en ?: $image->alt_text_en }}
                                                    </small>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="text-muted text-center py-3">
                                            <i class="fas fa-images fa-2x mb-2"></i>
                                            <p>{{ trans('main_trans.no_additional_images') }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
