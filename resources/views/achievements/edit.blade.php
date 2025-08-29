@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ trans('main_trans.our_achievement') }}</h5>
                    <a href="{{ route('achievements.index') }}" class="btn btn-secondary btn-sm">{{ trans('main_trans.back') }}</a>
                </div>
                <div class="card-body">
                    <form action="{{ route('achievements.update', $achievement->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">{{ trans('main_trans.name_ar') }} *</label>
                            <input type="text" name="name_ar" value="{{ old('name_ar', $achievement->name_ar) }}" class="form-control @error('name_ar') is-invalid @enderror" required>
                            @error('name_ar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ trans('main_trans.name_en') }} *</label>
                            <input type="text" name="name_en" value="{{ old('name_en', $achievement->name_en) }}" class="form-control @error('name_en') is-invalid @enderror" required>
                            @error('name_en')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ trans('main_trans.image') }}</label>
                            <div class="d-flex align-items-center gap-3">
                                <div>
                                    @if($achievement->image_url)
                                        <img src="{{ $achievement->image_url }}" alt="" class="img-thumbnail" style="max-width:80px; max-height:80px;">
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


