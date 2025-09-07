@extends('admin.layouts.app')

@section('content')

<div class="row">
    <div class="col-xl">
        <div class="card mb-4">
            {{-- عنوان الصفحة --}}
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="text-center my-4">{{ trans('main_trans.property_details') }}</h3>
                    <h5 class="text-muted">{{ $property->project->id }} - {{ $property->project->getTitle() ?? 'N/A' }}</h5>
                </div>
                <div class="text-right mb-3">
                    <a href="{{ route('properties.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> {{ trans('main_trans.back') }}
                    </a>
                    <a href="{{ route('properties.edit', $property->propertyid) }}" class="btn btn-primary">
                        <i class="fas fa-edit"></i> {{ trans('main_trans.edit') }}
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

                {{-- معلومات العقار الأساسية --}}
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5 class="text-primary">{{ trans('main_trans.basic_information') }}</h5>
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>{{ trans('main_trans.project') }}:</strong></td>
                                <td>{{ $property->project->id }} - {{ $property->project->getTitle() ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td><strong>{{ trans('main_trans.company') }}:</strong></td>
                                <td>
                                    @if($property->project && $property->project->company)
                                        {{ app()->getLocale() === 'ar' ? $property->project->company->company_name_ar : $property->project->company->company_name_en }}
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>{{ trans('main_trans.purpose') }}:</strong></td>
                                <td>
                                    @switch($property->propertypurpose)
                                        @case('sale')
                                            <span class="badge bg-success">{{ trans('main_trans.sale') }}</span>
                                            @break
                                        @case('rental')
                                            <span class="badge bg-info">{{ trans('main_trans.rental') }}</span>
                                            @break
                                        @case('both')
                                            <span class="badge bg-warning">{{ trans('main_trans.both') }}</span>
                                            @break
                                        @case('investment')
                                            <span class="badge bg-primary">{{ trans('main_trans.investment') }}</span>
                                            @break
                                        @case('vacation')
                                            <span class="badge bg-secondary">{{ trans('main_trans.vacation') }}</span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary">{{ $property->propertypurpose }}</span>
                                    @endswitch
                                </td>
                            </tr>
                            <tr>
                                <td><strong>{{ trans('main_trans.price') }}:</strong></td>
                                <td>{{ $property->getFormattedPriceAttribute() }}</td>
                            </tr>
                            <tr>
                                <td><strong>{{ trans('main_trans.quantity') }}:</strong></td>
                                <td>{{ $property->getFormattedQuantityAttribute() }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h5 class="text-primary">{{ trans('main_trans.property_specifications') }}</h5>
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>{{ trans('main_trans.rooms') }}:</strong></td>
                                <td>{{ $property->propertyrooms }}</td>
                            </tr>
                            <tr>
                                <td><strong>{{ trans('main_trans.bathrooms') }}:</strong></td>
                                <td>{{ $property->propertybathrooms }}</td>
                            </tr>
                            <tr>
                                <td><strong>{{ trans('main_trans.area') }}:</strong></td>
                                <td>{{ $property->getFormattedAreaAttribute() }}</td>
                            </tr>
                            <tr>
                                <td><strong>{{ trans('main_trans.location') }}:</strong></td>
                                <td>{{ $property->propertyloaction }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                {{-- خطة الدفع --}}
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5 class="text-primary">{{ trans('main_trans.payment_plan') }}</h5>
                        @if($property->paymentPlan)
                        <div class="card">
                            <div class="card-body">
                                <h6>{{ $property->paymentPlan->getLocalizedNameAttribute() }}</h6>
                                <p class="text-muted mb-0">{{ $property->paymentPlan->getLocalizedDescriptionAttribute() }}</p>
                            </div>
                        </div>
                        @else
                        <p class="text-muted">{{ trans('main_trans.no_payment_plan') }}</p>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <h5 class="text-primary">{{ trans('main_trans.handover_date') }}</h5>
                        @if($property->propertyhandover)
                        <p class="mb-0">{{ $property->propertyhandover->format('Y-m-d') }}</p>
                        @else
                        <p class="text-muted mb-0">{{ trans('main_trans.no_handover_date') }}</p>
                        @endif
                    </div>
                </div>

                {{-- الموظف المسؤول --}}
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5 class="text-primary">{{ trans('main_trans.responsible_employee') }}</h5>
                        @if($property->employee)
                            <p class="text-muted mb-0">
                                {{ app()->getLocale() === 'ar' ? $property->employee->name_en : $property->employee->name_en }}
                            </p>
                            <p class="text-muted mb-0">
                                <small>{{ $property->employee->email }} | {{ $property->employee->phone }}</small>
                            </p>
                        @else
                            <p class="text-muted mb-0">{{ trans('main_trans.no_employee_assigned') }}</p>
                        @endif
                    </div>
                </div>

                {{-- المميزات --}}
                @if($property->propertyfeatures && count($property->propertyfeatures) > 0)
                <div class="mb-4">
                    <h5 class="text-primary">{{ trans('main_trans.features') }}</h5>
                    <div class="row">
                        @foreach($property->propertyfeatures as $feature)
                        <div class="col-md-3 mb-2">
                            <span class="badge bg-light text-dark">{{ trans('main_trans.' . $feature) }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- التفاصيل الكاملة - العربية --}}
                @if($property->propertyfulldetils_ar)
                <div class="mb-4">
                    <h5 class="text-primary">{{ trans('main_trans.full_details_ar') }}</h5>
                    <div class="card">
                        <div class="card-body">
                            <p class="mb-0">{{ $property->propertyfulldetils_ar }}</p>
                        </div>
                    </div>
                </div>
                @endif

                {{-- التفاصيل الكاملة - الإنجليزية --}}
                @if($property->propertyfulldetils_en)
                <div class="mb-4">
                    <h5 class="text-primary">{{ trans('main_trans.full_details_en') }}</h5>
                    <div class="card">
                        <div class="card-body">
                            <p class="mb-0">{{ $property->propertyfulldetils_en }}</p>
                        </div>
                    </div>
                </div>
                @endif

                {{-- الصور --}}
                @if($property->propertyimages && count($property->propertyimages) > 0)
                <div class="mb-4">
                    <h5 class="text-primary">{{ trans('main_trans.images') }}</h5>
                    <div class="row">
                        @foreach($property->propertyimages as $image)
                        <div class="col-md-3 mb-3">
                            <img src="{{ asset('storage/' . $image) }}" class="img-fluid rounded" alt="Property Image" style="max-height: 200px;">
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- معلومات إضافية --}}
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5 class="text-primary">{{ trans('main_trans.created_at') }}</h5>
                        <p class="mb-0">{{ $property->created_at->format('Y-m-d H:i:s') }}</p>
                    </div>
                    <div class="col-md-6">
                        <h5 class="text-primary">{{ trans('main_trans.updated_at') }}</h5>
                        <p class="mb-0">{{ $property->updated_at->format('Y-m-d H:i:s') }}</p>
                    </div>
                </div>

                {{-- أزرار الإجراءات --}}
                <div class="text-end">
                    <a href="{{ route('properties.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> {{ trans('main_trans.back_to_list') }}
                    </a>
                    <a href="{{ route('properties.edit', $property->propertyid) }}" class="btn btn-primary">
                        <i class="fas fa-edit"></i> {{ trans('main_trans.edit') }}
                    </a>
                    <form action="{{ route('properties.destroy', $property->propertyid) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ trans('main_trans.confirm_delete') }}')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash"></i> {{ trans('main_trans.delete') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
