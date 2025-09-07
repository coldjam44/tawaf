@extends('admin.layouts.app')

@section('content')

<div class="row">
    <div class="col-xl">
        <div class="card mb-4">
            {{-- عنوان الصفحة --}}
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="text-center my-4">{{ trans('main_trans.edit_property') }}</h3>
                    <h5 class="text-muted">{{ $property->project->getTitle() ?? 'N/A' }}</h5>
                </div>
                <div class="text-right mb-3">
                    <a href="{{ route('properties.index') }}" class="btn btn-secondary">
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
                <form action="{{ route('properties.update', $property->propertyid) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- معلومات المشروع والشركة --}}
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title text-primary">{{ trans('main_trans.project_info') }}</h6>
                                    @if($property->project)
                                        <p class="mb-1"><strong>{{ trans('main_trans.project') }}:</strong> {{ $property->project->id }} - {{ $property->project->getTitle() }}</p>
                                        @if($property->project->area)
                                        <p class="mb-1"><strong>{{ trans('main_trans.location') }}:</strong> {{ app()->getLocale() === 'ar' ? $property->project->area->name_ar : $property->project->area->name_en }}</p>
                                        @endif
                                        @if($property->project->company)
                                        <p class="mb-0"><strong>{{ trans('main_trans.company') }}:</strong> {{ app()->getLocale() === 'ar' ? $property->project->company->company_name_ar : $property->project->company->company_name_en }}</p>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- اختيار المشروع --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="propertyproject" class="form-label">{{ trans('main_trans.project') }} *</label>
                                <select name="propertyproject" id="propertyproject" class="form-control @error('propertyproject') is-invalid @enderror" required>
                                    <option value="">{{ trans('main_trans.select_project') }}</option>
                                    @foreach($projects as $project)
                                    <option value="{{ $project->id }}" {{ old('propertyproject', $property->propertyproject) == $project->id ? 'selected' : '' }}>
                                        {{ $project->id }} - {{ $project->getTitle() }} - {{ $project->area ? $project->area->name_ar : trans('main_trans.no_area') }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('propertyproject')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="propertypurpose" class="form-label">{{ trans('main_trans.purpose') }} *</label>
                                <select name="propertypurpose" id="propertypurpose" class="form-control @error('propertypurpose') is-invalid @enderror" required>
                                    <option value="">{{ trans('main_trans.select_purpose') }}</option>
                                    <option value="sale" {{ old('propertypurpose', $property->propertypurpose) == 'sale' ? 'selected' : '' }}>{{ trans('main_trans.sale') }}</option>
                                    <option value="rental" {{ old('propertypurpose', $property->propertypurpose) == 'rental' ? 'selected' : '' }}>{{ trans('main_trans.rental') }}</option>
                                    <option value="both" {{ old('propertypurpose', $property->propertypurpose) == 'both' ? 'selected' : '' }}>{{ trans('main_trans.both') }}</option>
                                    <option value="investment" {{ old('propertypurpose', $property->propertypurpose) == 'investment' ? 'selected' : '' }}>{{ trans('main_trans.investment') }}</option>
                                    <option value="vacation" {{ old('propertypurpose', $property->propertypurpose) == 'vacation' ? 'selected' : '' }}>{{ trans('main_trans.vacation') }}</option>
                                </select>
                                @error('propertypurpose')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- السعر والمساحة --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="propertyprice" class="form-label">{{ trans('main_trans.price') }} *</label>
                                <input type="number" name="propertyprice" id="propertyprice" class="form-control @error('propertyprice') is-invalid @enderror" value="{{ old('propertyprice', $property->propertyprice) }}" step="0.01" min="0" required>
                                @error('propertyprice')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="propertyarea" class="form-label">{{ trans('main_trans.area') }} *</label>
                                <input type="number" name="propertyarea" id="propertyarea" class="form-control @error('propertyarea') is-invalid @enderror" value="{{ old('propertyarea', $property->propertyarea) }}" step="0.01" min="0" required>
                                @error('propertyarea')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- الغرف والحمامات --}}
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="propertyrooms" class="form-label">{{ trans('main_trans.rooms') }} *</label>
                                <input type="number" name="propertyrooms" id="propertyrooms" class="form-control @error('propertyrooms') is-invalid @enderror" value="{{ old('propertyrooms', $property->propertyrooms) }}" min="0" required>
                                @error('propertyrooms')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="propertybathrooms" class="form-label">{{ trans('main_trans.bathrooms') }} *</label>
                                <input type="number" name="propertybathrooms" id="propertybathrooms" class="form-control @error('propertybathrooms') is-invalid @enderror" value="{{ old('propertybathrooms', $property->propertybathrooms) }}" min="0" required>
                                @error('propertybathrooms')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="propertyquantity" class="form-label">{{ trans('main_trans.quantity') }} *</label>
                                <input type="number" name="propertyquantity" id="propertyquantity" class="form-control @error('propertyquantity') is-invalid @enderror" value="{{ old('propertyquantity', $property->propertyquantity) }}" min="1" required>
                                <small class="form-text text-muted">{{ trans('main_trans.quantity_help') }}</small>
                                @error('propertyquantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- خطة الدفع --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="propertypaymentplan" class="form-label">{{ trans('main_trans.payment_plan') }} *</label>
                                <select name="propertypaymentplan" id="propertypaymentplan" class="form-control @error('propertypaymentplan') is-invalid @enderror" required>
                                    <option value="">{{ trans('main_trans.select_payment_plan') }}</option>
                                    @foreach($paymentPlans as $plan)
                                    <option value="{{ $plan->id }}" {{ old('propertypaymentplan', $property->propertypaymentplan) == $plan->id ? 'selected' : '' }}>
                                        {{ $plan->getLocalizedNameAttribute() }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('propertypaymentplan')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="propertyloaction" class="form-label">{{ trans('main_trans.location') }} *</label>
                                <input type="text" name="propertyloaction" id="propertyloaction" class="form-control @error('propertyloaction') is-invalid @enderror" value="{{ old('propertyloaction', $property->propertyloaction) }}" required>
                                @error('propertyloaction')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- تاريخ التسليم --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="propertyhandover" class="form-label">{{ trans('main_trans.handover_date') }}</label>
                                <input type="date" name="propertyhandover" id="propertyhandover" class="form-control @error('propertyhandover') is-invalid @enderror" value="{{ old('propertyhandover', $property->propertyhandover ? $property->propertyhandover->format('Y-m-d') : '') }}">
                                @error('propertyhandover')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- الموظف المسؤول --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="employee_id" class="form-label">{{ trans('main_trans.responsible_employee') }} *</label>
                                <select name="employee_id" id="employee_id" class="form-control @error('employee_id') is-invalid @enderror" required>
                                    <option value="">{{ trans('main_trans.select_employee') }}</option>
                                    @if($projectEmployees && $projectEmployees->count() > 0)
                                        @foreach($projectEmployees as $employee)
                                            <option value="{{ $employee->id }}" {{ old('employee_id', $property->employee_id) == $employee->id ? 'selected' : '' }}>
                                                {{ app()->getLocale() === 'ar' ? $employee->name_ar : $employee->name_en }} - {{ $employee->email }} ({{ $employee->phone }})
                                            </option>
                                        @endforeach
                                    @else
                                        <option value="" disabled>{{ trans('main_trans.no_employees_found') }}</option>
                                    @endif
                                </select>
                                <small class="form-text text-muted">{{ trans('main_trans.employee_help') }}</small>
                                @error('employee_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- التفاصيل الكاملة - العربية --}}
                    <div class="mb-3">
                        <label for="propertyfulldetils_ar" class="form-label">{{ trans('main_trans.full_details_ar') }}</label>
                        <textarea name="propertyfulldetils_ar" id="propertyfulldetils_ar" class="form-control @error('propertyfulldetils_ar') is-invalid @enderror" rows="5" placeholder="اكتب التفاصيل الكاملة للعقار باللغة العربية">{{ old('propertyfulldetils_ar', $property->propertyfulldetils_ar) }}</textarea>
                        @error('propertyfulldetils_ar')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- التفاصيل الكاملة - الإنجليزية --}}
                    <div class="mb-3">
                        <label for="propertyfulldetils_en" class="form-label">{{ trans('main_trans.full_details_en') }}</label>
                        <textarea name="propertyfulldetils_en" id="propertyfulldetils_en" class="form-control @error('propertyfulldetils_en') is-invalid @enderror" rows="5" placeholder="Write the full details of the property in English">{{ old('propertyfulldetils_en', $property->propertyfulldetils_en) }}</textarea>
                        @error('propertyfulldetils_en')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- الصور --}}
                    <div class="mb-3">
                        <label for="propertyimages" class="form-label">{{ trans('main_trans.images') }}</label>
                        <input type="file" name="propertyimages[]" id="propertyimages" class="form-control @error('propertyimages') is-invalid @enderror" multiple accept="image/*">
                        <small class="form-text text-muted">{{ trans('main_trans.max_images') }}: 5, {{ trans('main_trans.max_size') }}: 2MB, {{ trans('main_trans.allowed_files') }}: JPG, PNG, GIF, WebP</small>
                        @error('propertyimages')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- عرض الصور الحالية --}}
                    @if($property->propertyimages && count($property->propertyimages) > 0)
                    <div class="mb-3">
                        <label class="form-label">{{ trans('main_trans.current_images') }}</label>
                        <div class="row">
                            @foreach($property->propertyimages as $image)
                            <div class="col-md-2 mb-2">
                                <img src="{{ asset('storage/' . $image) }}" class="img-fluid rounded" alt="Property Image" style="max-height: 100px;">
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- أزرار الإرسال --}}
                    <div class="text-end">
                        <a href="{{ route('properties.index') }}" class="btn btn-secondary">
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

{{-- تم إزالة JavaScript - الموظفين يتم تحميلهم من الخادم مباشرة --}}
