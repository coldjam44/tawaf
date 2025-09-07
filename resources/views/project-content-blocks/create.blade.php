@extends('admin.layouts.app')

@section('content')

<div class="row">
    <div class="col-xl">
        <div class="card mb-4">

            {{-- عنوان الصفحة --}}
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="text-center my-4">إضافة قسم محتوى جديد</h3>
                    <p class="text-muted mb-0">المشروع: {{ $project->getTitle() }}</p>
                </div>
                <div class="text-right mb-3">
                    <a href="{{ route('project-content-blocks.index', $project->id) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> العودة لأقسام المحتوى
                    </a>
                </div>
            </div>

            <div class="card-body">

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
                <form action="{{ route('project-content-blocks.store', $project->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- العنوان ثنائي اللغة --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="title_ar" class="form-label">عنوان القسم (عربي) *</label>
                                <input type="text" name="title_ar" id="title_ar" class="form-control @error('title_ar') is-invalid @enderror" value="{{ old('title_ar') }}" required>
                                @error('title_ar')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="title_en" class="form-label">عنوان القسم (إنجليزي) *</label>
                                <input type="text" name="title_en" id="title_en" class="form-control @error('title_en') is-invalid @enderror" value="{{ old('title_en') }}" required>
                                @error('title_en')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- المحتوى ثنائي اللغة --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="content_ar" class="form-label">محتوى القسم (عربي) *</label>
                                <textarea name="content_ar" id="content_ar" rows="6" class="form-control @error('content_ar') is-invalid @enderror" required>{{ old('content_ar') }}</textarea>
                                @error('content_ar')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="content_en" class="form-label">محتوى القسم (إنجليزي) *</label>
                                <textarea name="content_en" id="content_en" rows="6" class="form-control @error('content_en') is-invalid @enderror" required>{{ old('content_en') }}</textarea>
                                @error('content_en')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- الصور --}}
                    <div class="mb-3">
                        <label for="images" class="form-label">صور القسم (اختياري)</label>
                        <input type="file" name="images[]" id="images" class="form-control" multiple accept="image/*">
                        <small class="form-text text-muted">يمكنك رفع أي كمية من الصور</small>
                    </div>

                    {{-- الترتيب والحالة --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="order" class="form-label">ترتيب العرض</label>
                                <input type="number" name="order" id="order" class="form-control @error('order') is-invalid @enderror" value="{{ old('order', $project->contentBlocks()->max('order') + 1) }}" min="1">
                                <small class="form-text text-muted">سيتم ترتيب الأقسام حسب هذا الرقم (الأقل أولاً)</small>
                                @error('order')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-check">
                                    <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label for="is_active" class="form-check-label">تفعيل القسم</label>
                                </div>
                                <small class="form-text text-muted">الأقسام المفعلة فقط ستظهر في الموقع</small>
                            </div>
                        </div>
                    </div>

                    {{-- أمثلة على أنواع الأقسام --}}
                    <div class="mb-3">
                        <div class="alert alert-info">
                            <h6><i class="fas fa-info-circle"></i> أمثلة على أنواع أقسام المحتوى:</h6>
                            <ul class="mb-0">
                                <li><strong>وصف المشروع:</strong> تفاصيل شاملة عن المشروع ومميزاته</li>
                                <li><strong>سياسة الدفع:</strong> خطط الدفع والأقساط المتاحة</li>
                                <li><strong>المميزات:</strong> قائمة بمميزات المشروع والمرافق</li>
                                <li><strong>الموقع:</strong> تفاصيل الموقع والمنطقة المحيطة</li>
                                <li><strong>الشروط والأحكام:</strong> شروط الحجز والشراء</li>
                            </ul>
                        </div>
                    </div>

                    <div class="text-end">
                        <a href="{{ route('project-content-blocks.index', $project->id) }}" class="btn btn-secondary me-2">
                            <i class="fas fa-times"></i> إلغاء
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> حفظ القسم
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// معاينة الصور المختارة
document.getElementById('images').addEventListener('change', function(e) {
    const files = e.target.files;
    const previewContainer = document.createElement('div');
    previewContainer.className = 'mt-2';
    
    // إزالة المعاينة السابقة
    const existingPreview = document.querySelector('.image-preview');
    if (existingPreview) {
        existingPreview.remove();
    }
    
    if (files.length > 0) {
        previewContainer.innerHTML = '<h6>معاينة الصور المختارة:</h6>';
        
        for (let i = 0; i < Math.min(files.length, 5); i++) {
            const file = files[i];
            const reader = new FileReader();
            
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'img-thumbnail me-2 mb-2';
                img.style.width = '100px';
                img.style.height = '100px';
                img.style.objectFit = 'cover';
                previewContainer.appendChild(img);
            };
            
            reader.readAsDataURL(file);
        }
        
        previewContainer.className = 'mt-2 image-preview';
        document.getElementById('images').parentNode.appendChild(previewContainer);
    }
});
</script>
@endpush
