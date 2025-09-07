@extends('admin.layouts.app')

@section('content')

<div class="row">
    <div class="col-xl">
        <div class="card mb-4">

            {{-- عنوان الصفحة --}}
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="text-center my-4">تعديل قسم المحتوى</h3>
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

                {{-- فورم التعديل --}}
                <form action="{{ route('project-content-blocks.update', [$project->id, $contentBlock->id]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- العنوان ثنائي اللغة --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="title_ar" class="form-label">عنوان القسم (عربي) *</label>
                                <input type="text" name="title_ar" id="title_ar" class="form-control @error('title_ar') is-invalid @enderror" value="{{ old('title_ar', $contentBlock->title_ar) }}" required>
                                @error('title_ar')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="title_en" class="form-label">عنوان القسم (إنجليزي) *</label>
                                <input type="text" name="title_en" id="title_en" class="form-control @error('title_en') is-invalid @enderror" value="{{ old('title_en', $contentBlock->title_en) }}" required>
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
                                <textarea name="content_ar" id="content_ar" rows="6" class="form-control @error('content_ar') is-invalid @enderror" required>{{ old('content_ar', $contentBlock->content_ar) }}</textarea>
                                @error('content_ar')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="content_en" class="form-label">محتوى القسم (إنجليزي) *</label>
                                <textarea name="content_en" id="content_en" rows="6" class="form-control @error('content_en') is-invalid @enderror" required>{{ old('content_en', $contentBlock->content_en) }}</textarea>
                                @error('content_en')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- الصور الحالية --}}
                    @if($contentBlock->images && count($contentBlock->images) > 0)
                    <div class="mb-3">
                        <label class="form-label">الصور الحالية</label>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($contentBlock->images as $image)
                            <div class="position-relative">
                                <img src="{{ $image->image_url }}" 
                                     alt="صورة" 
                                     class="img-thumbnail" 
                                     style="width: 120px; height: 120px; object-fit: cover;">
                                <div class="position-absolute top-0 end-0">
                                    <form action="{{ route('project-content-blocks.images.destroy', [$project->id, $contentBlock->id, $image->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذه الصورة؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="حذف الصورة">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <small class="form-text text-muted">اختر صور جديدة لإضافتها للصور الحالية</small>
                    </div>
                    @endif

                    {{-- إضافة صور جديدة --}}
                    <div class="mb-3">
                        <label class="form-label">إضافة صور جديدة</label>
                        <form action="{{ route('project-content-blocks.images.store', [$project->id, $contentBlock->id]) }}" method="POST" enctype="multipart/form-data" class="d-inline">
                            @csrf
                            <div class="input-group">
                                <input type="file" name="images[]" class="form-control" multiple accept="image/*" required>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> إضافة الصور
                                </button>
                            </div>
                            <small class="form-text text-muted">الملفات المسموحة: JPEG, PNG, JPG, GIF</small>
                        </form>
                    </div>

                    {{-- الترتيب والحالة --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="order" class="form-label">ترتيب العرض</label>
                                <input type="number" name="order" id="order" class="form-control @error('order') is-invalid @enderror" value="{{ old('order', $contentBlock->order) }}" min="1">
                                <small class="form-text text-muted">سيتم ترتيب الأقسام حسب هذا الرقم (الأقل أولاً)</small>
                                @error('order')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-check">
                                    <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1" {{ old('is_active', $contentBlock->is_active) ? 'checked' : '' }}>
                                    <label for="is_active" class="form-check-label">تفعيل القسم</label>
                                </div>
                                <small class="form-text text-muted">الأقسام المفعلة فقط ستظهر في الموقع</small>
                            </div>
                        </div>
                    </div>

                    <div class="text-end">
                        <a href="{{ route('project-content-blocks.index', $project->id) }}" class="btn btn-secondary me-2">
                            <i class="fas fa-times"></i> إلغاء
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> حفظ التعديلات
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

@endsection


