@extends('admin.layouts.app')

@section('content')

<div class="row">
    <div class="col-xl">
        <div class="card mb-4">

            {{-- عنوان الصفحة وزر الإضافة --}}
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="text-center my-4">إدارة أقسام المحتوى</h3>
                    <p class="text-muted mb-0">المشروع: {{ $project->getTitle() }}</p>
                </div>
                <div class="text-right mb-3">
                    <a href="{{ route('projects.index') }}" class="btn btn-secondary me-2">
                        <i class="fas fa-arrow-left"></i> العودة للمشاريع
                    </a>
                    <a href="{{ route('project-content-blocks.create', $project->id) }}" class="btn btn-primary">
                        <i class="fas fa-plus-circle"></i> إضافة قسم جديد
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

                {{-- جدول أقسام المحتوى --}}
                <div class="table-responsive mb-3">
                    <table class="table table-bordered text-center">
                        <thead>
                            <tr>
                                <th>الترتيب</th>
                                <th>العنوان</th>
                                <th>المحتوى</th>
                                <th>الصور</th>
                                <th>الحالة</th>
                                <th>العمليات</th>
                            </tr>
                        </thead>
                        <tbody id="content-blocks-table">
                            @forelse ($contentBlocks as $block)
                            <tr data-id="{{ $block->id }}">
                                <td>
                                    <span class="badge bg-secondary">{{ $block->order }}</span>
                                </td>
                                <td>
                                    <div class="text-start">
                                        <div class="fw-bold">{{ $block->title_ar }}</div>
                                        <small class="text-muted">{{ $block->title_en }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-start">
                                        <div>{{ Str::limit($block->content_ar, 100) }}</div>
                                        <small class="text-muted">{{ Str::limit($block->content_en, 100) }}</small>
                                    </div>
                                </td>
                                <td>
                                    @if($block->images && $block->images->count() > 0)
                                        <div class="d-flex flex-wrap gap-1 justify-content-center">
                                            @foreach($block->images->take(3) as $image)
                                                <img src="{{ $image->image_url }}" 
                                                     alt="صورة" 
                                                     class="img-thumbnail" 
                                                     style="width: 40px; height: 40px; object-fit: cover;">
                                            @endforeach
                                            @if($block->images->count() > 3)
                                                <span class="badge bg-info">+{{ $block->images->count() - 3 }}</span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-muted">لا توجد صور</span>
                                    @endif
                                </td>
                                <td>
                                    @if($block->is_active)
                                        <span class="badge bg-success">مفعل</span>
                                    @else
                                        <span class="badge bg-danger">غير مفعل</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('project-content-blocks.edit', [$project->id, $block->id]) }}" 
                                           class="btn btn-info btn-sm" title="تعديل">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('project-content-blocks.toggle', [$project->id, $block->id]) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-warning btn-sm" title="تفعيل/إلغاء تفعيل">
                                                <i class="fas fa-toggle-on"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('project-content-blocks.destroy', [$project->id, $block->id]) }}" 
                                              method="POST" class="d-inline" 
                                              onsubmit="return confirm('هل أنت متأكد من حذف هذا القسم؟')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="حذف">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">
                                    <div class="py-4">
                                        <i class="fas fa-file-text fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">لا توجد أقسام محتوى لهذا المشروع</p>
                                        <a href="{{ route('project-content-blocks.create', $project->id) }}" class="btn btn-primary">
                                            <i class="fas fa-plus"></i> إضافة أول قسم
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// يمكن إضافة JavaScript هنا لتحديث الترتيب بالسحب والإفلات
$(document).ready(function() {
    // إمكانية إضافة drag & drop لتحديث الترتيب
    $('#content-blocks-table').sortable({
        handle: 'td:first',
        onDrop: function($item, container, _super) {
            _super($item, container);
            updateOrder();
        }
    });
});

function updateOrder() {
    const rows = $('#content-blocks-table tr');
    const blocks = [];
    
    rows.each(function(index) {
        const id = $(this).data('id');
        if (id) {
            blocks.push({
                id: id,
                order: index + 1
            });
        }
    });
    
    $.ajax({
        url: '{{ route("project-content-blocks.update-order", $project->id) }}',
        method: 'POST',
        data: {
            blocks: blocks,
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success) {
                location.reload();
            }
        },
        error: function() {
            alert('حدث خطأ أثناء تحديث الترتيب');
        }
    });
}
</script>
@endpush
