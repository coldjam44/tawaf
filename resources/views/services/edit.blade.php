@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ trans('main_trans.our_services') }}</h5>
                    <a href="{{ route('services.index') }}" class="btn btn-secondary btn-sm">{{ trans('main_trans.back') }}</a>
                </div>
                <div class="card-body">
                    <form action="{{ route('services.update', $service->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">{{ trans('main_trans.title_ar') }} *</label>
                            <input type="text" name="title_ar" value="{{ old('title_ar', $service->title_ar) }}" class="form-control @error('title_ar') is-invalid @enderror" required>
                            @error('title_ar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ trans('main_trans.title_en') }} *</label>
                            <input type="text" name="title_en" value="{{ old('title_en', $service->title_en) }}" class="form-control @error('title_en') is-invalid @enderror" required>
                            @error('title_en')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ trans('main_trans.description_ar') }}</label>
                            <textarea name="description_ar" class="form-control @error('description_ar') is-invalid @enderror" rows="3">{{ old('description_ar', $service->description_ar) }}</textarea>
                            @error('description_ar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ trans('main_trans.description_en') }}</label>
                            <textarea name="description_en" class="form-control @error('description_en') is-invalid @enderror" rows="3">{{ old('description_en', $service->description_en) }}</textarea>
                            @error('description_en')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ trans('main_trans.phone') }}</label>
                            <input type="text" name="contact_phone" value="{{ old('contact_phone', $service->contact_phone) }}" class="form-control @error('contact_phone') is-invalid @enderror">
                            @error('contact_phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ trans('main_trans.image') }}</label>
                            <div class="d-flex align-items-center gap-3">
                                <div>
                                    @if($service->image_url)
                                        <img src="{{ $service->image_url }}" alt="" class="img-thumbnail" style="max-width:80px; max-height:80px;">
                                    @else
                                        <span class="text-muted small">—</span>
                                    @endif
                                </div>
                                <div class="flex-fill">
                                    <input type="file" name="image" class="form-control @error('image') is-invalid @enderror">
                                    @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">{{ trans('main_trans.update') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
