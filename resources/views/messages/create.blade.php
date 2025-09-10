@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">{{ trans('main_trans.add_message') }}</h4>
                    <div>
                        <a href="{{ route('messages.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>
                            {{ trans('main_trans.actions_back') }}
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('messages.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <!-- Image Upload Section -->
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0">{{ trans('main_trans.image') }}</h5>
                                    </div>
                                    <div class="card-body text-center">
                                        <div class="mb-3">
                                            <img id="imagePreview" src="{{ asset('admin/img/no-image.png') }}" 
                                                 alt="Preview" class="img-thumbnail" 
                                                 style="width: 200px; height: 250px; object-fit: cover;">
                                        </div>
                                        <div class="form-group">
                                            <input type="file" name="image" id="image" class="form-control" 
                                                   accept="image/*" onchange="previewImage(this)">
                                            <small class="form-text text-muted">{{ trans('main_trans.allowed_files') }}: JPG, JPEG, PNG, GIF ({{ trans('main_trans.max_size') }}: 5MB)</small>
                                            @error('image')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Form Fields Section -->
                            <div class="col-md-8">
                                <div class="row">
                                    <!-- Name Fields -->
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="name_en" class="form-label">{{ trans('main_trans.person_name') }} ({{ trans('main_trans.english') }})</label>
                                            <input type="text" name="name_en" id="name_en" class="form-control @error('name_en') is-invalid @enderror" 
                                                   value="{{ old('name_en') }}" required>
                                            @error('name_en')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="name_ar" class="form-label">{{ trans('main_trans.person_name') }} ({{ trans('main_trans.arabic') }})</label>
                                            <input type="text" name="name_ar" id="name_ar" class="form-control @error('name_ar') is-invalid @enderror" 
                                                   value="{{ old('name_ar') }}" required>
                                            @error('name_ar')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Position Fields -->
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="position_en" class="form-label">{{ trans('main_trans.person_position') }} ({{ trans('main_trans.english') }})</label>
                                            <input type="text" name="position_en" id="position_en" class="form-control @error('position_en') is-invalid @enderror" 
                                                   value="{{ old('position_en') }}" required>
                                            @error('position_en')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="position_ar" class="form-label">{{ trans('main_trans.person_position') }} ({{ trans('main_trans.arabic') }})</label>
                                            <input type="text" name="position_ar" id="position_ar" class="form-control @error('position_ar') is-invalid @enderror" 
                                                   value="{{ old('position_ar') }}" required>
                                            @error('position_ar')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Message Fields -->
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="message_en" class="form-label">{{ trans('main_trans.message_content') }} ({{ trans('main_trans.english') }})</label>
                                            <textarea name="message_en" id="message_en" rows="8" class="form-control @error('message_en') is-invalid @enderror" required>{{ old('message_en') }}</textarea>
                                            @error('message_en')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="message_ar" class="form-label">{{ trans('main_trans.message_content') }} ({{ trans('main_trans.arabic') }})</label>
                                            <textarea name="message_ar" id="message_ar" rows="8" class="form-control @error('message_ar') is-invalid @enderror" required>{{ old('message_ar') }}</textarea>
                                            @error('message_ar')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i>
                                        {{ trans('main_trans.actions_save') }}
                                    </button>
                                    <a href="{{ route('messages.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times me-1"></i>
                                        {{ trans('main_trans.actions_cancel') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('imagePreview').src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
