@extends('admin.layouts.app')

@section('content')

<div class="row">
    <div class="col-xl">
        <div class="card mb-4">

            {{-- عنوان الصفحة --}}
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="text-center my-4">{{ trans('main_trans.edit_project_description') }}</h3>
                    <h5 class="text-muted">{{ $project->getTitle() }}</h5>
                </div>
                <div class="text-right mb-3">
                    <a href="{{ route('project-descriptions.index', $project->id) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> {{ trans('main_trans.back') }}
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

                {{-- أخطاء الإدخال --}}
                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                {{-- فورم التعديل --}}
                <form action="{{ route('project-descriptions.update', [$project->id, $projectDescription->id]) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- نوع القسم --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="section_type" class="form-label">{{ trans('main_trans.section_type') }} *</label>
                                <select name="section_type" id="section_type" class="form-control @error('section_type') is-invalid @enderror" required>
                                    <option value="">{{ trans('main_trans.select_section_type') }}</option>
                                    @foreach($sectionTypes as $key => $label)
                                    <option value="{{ $key }}" {{ old('section_type', $projectDescription->section_type) == $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('section_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="order_index" class="form-label">{{ trans('main_trans.order') }}</label>
                                <input type="number" name="order_index" id="order_index" class="form-control @error('order_index') is-invalid @enderror" value="{{ old('order_index', $projectDescription->order_index) }}" min="0">
                                <small class="form-text text-muted">الترتيب الذي سيظهر به هذا القسم</small>
                                @error('order_index')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- عنوان القسم ثنائي اللغة --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="title_ar" class="form-label">{{ trans('main_trans.title_ar') }} *</label>
                                <input type="text" name="title_ar" id="title_ar" class="form-control @error('title_ar') is-invalid @enderror" value="{{ old('title_ar', $projectDescription->title_ar) }}" required>
                                @error('title_ar')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="title_en" class="form-label">{{ trans('main_trans.title_en') }} *</label>
                                <input type="text" name="title_en" id="title_en" class="form-control @error('title_en') is-invalid @enderror" value="{{ old('title_en', $projectDescription->title_en) }}" required>
                                @error('title_en')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- محتوى القسم ثنائي اللغة --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="content_ar" class="form-label">{{ trans('main_trans.content_ar') }} *</label>
                                <textarea name="content_ar" id="content_ar" class="form-control @error('content_ar') is-invalid @enderror" rows="10" required>{{ old('content_ar', $projectDescription->content_ar) }}</textarea>
                                <small class="form-text text-muted">يمكنك استخدام HTML tags للتصميم</small>
                                @error('content_ar')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="content_en" class="form-label">{{ trans('main_trans.content_en') }} *</label>
                                <textarea name="content_en" id="content_en" class="form-control @error('content_en') is-invalid @enderror" rows="10" required>{{ old('content_en', $projectDescription->content_en) }}</textarea>
                                <small class="form-text text-muted">You can use HTML tags for formatting</small>
                                @error('content_en')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- حقول الموقع (للنوع location_map) --}}
                    <div id="locationFields" style="display: none;">
                        <div class="card mb-3">
                            <div class="card-header">
                                <h6 class="mb-0">{{ trans('main_trans.location_information') }}</h6>
                            </div>
                            <div class="card-body">
                                {{-- صورة الموقع الحالية --}}
                                @if($projectDescription->location_image)
                                <div class="mb-3">
                                    <label class="form-label">{{ trans('main_trans.current_location_image') }}</label>
                                    <div class="mb-2">
                                        <img src="{{ $projectDescription->location_image_url }}" alt="Location Image" class="img-thumbnail" style="max-width: 200px;">
                                    </div>
                                </div>
                                @endif

                                {{-- صورة الموقع الجديدة --}}
                                <div class="mb-3">
                                    <label for="location_image" class="form-label">{{ trans('main_trans.location_image') }}</label>
                                    <input type="file" name="location_image" id="location_image" class="form-control @error('location_image') is-invalid @enderror" accept="image/*">
                                    <small class="form-text text-muted">{{ trans('main_trans.allowed_files') }}: JPG, JPEG, PNG, GIF ({{ trans('main_trans.max_size') }}: 5MB)</small>
                                    @error('location_image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- عنوان الموقع ثنائي اللغة --}}
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="location_address_ar" class="form-label">{{ trans('main_trans.location_address_ar') }}</label>
                                            <input type="text" name="location_address_ar" id="location_address_ar" class="form-control @error('location_address_ar') is-invalid @enderror" value="{{ old('location_address_ar', $projectDescription->location_address_ar) }}">
                                            @error('location_address_ar')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="location_address_en" class="form-label">{{ trans('main_trans.location_address_en') }}</label>
                                            <input type="text" name="location_address_en" id="location_address_en" class="form-control @error('location_address_en') is-invalid @enderror" value="{{ old('location_address_en', $projectDescription->location_address_en) }}">
                                            @error('location_address_en')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                {{-- رابط Google Location --}}
                                <div class="mb-3">
                                    <label for="google_location" class="form-label">{{ trans('main_trans.google_location') }}</label>
                                    <input type="url" name="google_location" id="google_location" class="form-control @error('google_location') is-invalid @enderror" value="{{ old('google_location', $projectDescription->google_location) }}" placeholder="https://maps.google.com/...">
                                    <small class="form-text text-muted">{{ trans('main_trans.google_location_help') }}</small>
                                    @error('google_location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- تفعيل القسم --}}
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $projectDescription->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                {{ trans('main_trans.is_active') }}
                            </label>
                        </div>
                        <small class="form-text text-muted">الأقسام غير المفعلة لن تظهر للجمهور</small>
                    </div>

                    <div class="text-end">
                        <a href="{{ route('project-descriptions.index', $project->id) }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> {{ trans('main_trans.cancel') }}
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> {{ trans('main_trans.update') }}
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const sectionTypeSelect = document.getElementById('section_type');
    const locationFields = document.getElementById('locationFields');
    
    // Function to toggle location fields visibility
    function toggleLocationFields() {
        if (sectionTypeSelect.value === 'location_map') {
            locationFields.style.display = 'block';
        } else {
            locationFields.style.display = 'none';
        }
    }
    
    // Initial check
    toggleLocationFields();
    
    // Listen for changes
    sectionTypeSelect.addEventListener('change', toggleLocationFields);
});
</script>
@endsection
