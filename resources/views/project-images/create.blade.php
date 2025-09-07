@extends('admin.layouts.app')

@section('content')

<div class="row">
    <div class="col-xl">
        <div class="card mb-4">

            {{-- عنوان الصفحة --}}
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="text-center my-4">{{ trans('main_trans.add_project_image') }}</h3>
                    <h5 class="text-muted">{{ $project->getTitle() }}</h5>
                </div>
                <div class="text-right mb-3">
                    <a href="{{ route('project-images.index', $project->id) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> {{ trans('main_trans.back') }}
                    </a>
                </div>
            </div>

            <div class="card-body">

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

                {{-- فورم الإضافة --}}
                <form action="{{ route('project-images.store', $project->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- نوع الصورة --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="type" class="form-label">{{ trans('main_trans.image_type') }} *</label>
                                                                       <select name="type" id="type" class="form-control @error('type') is-invalid @enderror" required>
                                           <option value="">{{ trans('main_trans.select_image_type') }}</option>
                                           <option value="interior" {{ old('type') == 'interior' ? 'selected' : '' }}>{{ trans('main_trans.interior') }}</option>
                                           <option value="exterior" {{ old('type') == 'exterior' ? 'selected' : '' }}>{{ trans('main_trans.exterior') }}</option>
                                           <option value="floorplan" {{ old('type') == 'floorplan' ? 'selected' : '' }}>{{ trans('main_trans.floorplan') }}</option>
                                           <option value="featured" {{ old('type') == 'featured' ? 'selected' : '' }}>{{ trans('main_trans.featured') }}</option>
                                       </select>
                                @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>


                    </div>

                    {{-- رفع الصور المتعدد --}}
                    <div class="mb-3">
                        <label for="images" class="form-label">{{ trans('main_trans.images') }} *</label>
                        <input type="file" name="images[]" id="images" class="form-control @error('images') is-invalid @enderror" accept="image/*" multiple required>
                        <small class="form-text text-muted">{{ trans('main_trans.allowed_files') }}: JPG, JPEG, PNG, GIF, WebP ({{ trans('main_trans.max_size') }}: 5MB لكل صورة)</small>
                        <small class="form-text text-muted d-block">{{ trans('main_trans.max_images') }}: 10 صور في المرة الواحدة</small>
                        @error('images')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- معاينة الصور المختارة --}}
                    <div class="mb-3" id="imagePreview" style="display: none;">
                        <label class="form-label">{{ trans('main_trans.selected_images') }}</label>
                        <div class="row" id="previewContainer">
                            <!-- الصور المختارة ستظهر هنا -->
                        </div>
                    </div>

                    {{-- الحقول المخفية للعناوين والأوصاف --}}
                    <input type="hidden" name="title_ar" id="title_ar" value="">
                    <input type="hidden" name="title_en" id="title_en" value="">
                    <input type="hidden" name="description_ar" id="description_ar" value="">
                    <input type="hidden" name="description_en" id="description_en" value="">
                    <input type="hidden" name="is_featured" id="is_featured" value="1">
                    <input type="hidden" name="order" id="order" value="0">

                    <div class="text-end">
                        <a href="{{ route('project-images.index', $project->id) }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> {{ trans('main_trans.cancel') }}
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> {{ trans('main_trans.submit') }}
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
    const imageInput = document.getElementById('images');
    const previewContainer = document.getElementById('previewContainer');
    const imagePreview = document.getElementById('imagePreview');
    const typeSelect = document.getElementById('type');
    const titleArInput = document.getElementById('title_ar');
    const titleEnInput = document.getElementById('title_en');
    const descArInput = document.getElementById('description_ar');
    const descEnInput = document.getElementById('description_en');
    
    // Project details for auto-fill
    const projectTitleAr = '{{ $project->prj_title_ar }}';
    const projectTitleEn = '{{ $project->prj_title_en }}';
    
    // Type labels
    const typeLabels = {
        'interior': { ar: 'صور داخلية', en: 'Interior Images' },
        'exterior': { ar: 'صور خارجية', en: 'Exterior Images' },
        'floorplan': { ar: 'مخططات الطوابق', en: 'Floor Plans' },
        'featured': { ar: 'صور مميزة', en: 'Featured Images' }
    };
    
    // Auto-fill titles and descriptions when type changes
    typeSelect.addEventListener('change', function() {
        const selectedType = this.value;
        if (selectedType && typeLabels[selectedType]) {
            const label = typeLabels[selectedType];
            
            // Auto-fill titles (hidden fields)
            titleArInput.value = label.ar + ' ' + projectTitleAr;
            titleEnInput.value = label.en + ' ' + projectTitleEn;
            
            // Auto-fill descriptions (hidden fields)
            descArInput.value = 'مجموعة من ' + label.ar + ' لمشروع ' + projectTitleAr;
            descEnInput.value = 'Collection of ' + label.en + ' for ' + projectTitleEn + ' project';
        }
    });
    
    imageInput.addEventListener('change', function(e) {
        const files = e.target.files;
        previewContainer.innerHTML = '';
        
        if (files.length > 0) {
            imagePreview.style.display = 'block';
            
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    const col = document.createElement('div');
                    col.className = 'col-md-3 col-lg-2 mb-2';
                    
                    col.innerHTML = `
                        <div class="card">
                            <img src="${e.target.result}" class="card-img-top" alt="Preview" style="height: 100px; object-fit: cover;">
                            <div class="card-body p-2">
                                <small class="text-muted">${file.name}</small>
                                <br>
                                <small class="text-muted">${(file.size / 1024 / 1024).toFixed(2)} MB</small>
                            </div>
                        </div>
                    `;
                    
                    previewContainer.appendChild(col);
                };
                
                reader.readAsDataURL(file);
            }
        } else {
            imagePreview.style.display = 'none';
        }
    });
});
</script>
@endsection
