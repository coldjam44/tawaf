@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ trans('main_trans.edit') }} - {{ trans('main_trans.expert_team') }}</h5>
                    <a href="{{ route('expert-team.index') }}" class="btn btn-secondary btn-sm">{{ trans('main_trans.back') }}</a>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('expert-team.update', $member->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name_ar" class="form-label">{{ trans('main_trans.name_ar') }} *</label>
                            <input type="text" name="name_ar" id="name_ar" class="form-control @error('name_ar') is-invalid @enderror" value="{{ old('name_ar', $member->name_ar) }}" required>
                            @error('name_ar')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="name_en" class="form-label">{{ trans('main_trans.name_en') }} *</label>
                            <input type="text" name="name_en" id="name_en" class="form-control @error('name_en') is-invalid @enderror" value="{{ old('name_en', $member->name_en) }}" required>
                            @error('name_en')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="position_ar" class="form-label">{{ trans('main_trans.position_ar') }} *</label>
                            <input type="text" name="position_ar" id="position_ar" class="form-control @error('position_ar') is-invalid @enderror" value="{{ old('position_ar', $member->position_ar) }}" required>
                            @error('position_ar')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="position_en" class="form-label">{{ trans('main_trans.position_en') }} *</label>
                            <input type="text" name="position_en" id="position_en" class="form-control @error('position_en') is-invalid @enderror" value="{{ old('position_en', $member->position_en) }}" required>
                            @error('position_en')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="order_index" class="form-label">{{ trans('main_trans.order') }}</label>
                            <input type="number" name="order_index" id="order_index" class="form-control @error('order_index') is-invalid @enderror" value="{{ old('order_index', $member->order_index) }}" min="0">
                            @error('order_index')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ trans('main_trans.image') }}</label>
                            <div class="d-flex align-items-center gap-3">
                                <div>
                                    @if($member->image)
                                        <img src="{{ $member->image_url }}" alt="" class="img-thumbnail" style="max-width: 80px; max-height: 80px;">
                                    @else
                                        <span class="text-muted small">{{ trans('main_trans.no_image') }}</span>
                                    @endif
                                </div>
                                <div class="flex-fill">
                                    <input type="file" name="image" id="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                                    <small class="form-text text-muted">{{ trans('main_trans.allowed_files') }}: JPG, JPEG, PNG, GIF</small>
                                    @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>
                                {{ trans('main_trans.update') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


