@extends('admin.layouts.app')

@section('content')

<div class="row">
    <div class="col-xl">
        <div class="card mb-4">

            {{-- عنوان الصفحة --}}
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="text-center my-4">{{ trans('main_trans.add_property') }}</h3>
                <div class="text-right mb-3">
                    <a href="{{ route('properties.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> {{ trans('main_trans.back') }}
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

                {{-- رسالة نجاح --}}
                @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif

                {{-- رسالة خطأ --}}
                @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
                @endif

                {{-- فورم اختيار المشروع --}}
                @if(!$selectedProjectLocation)
                <div class="alert alert-info">
                    <h6>{{ trans('main_trans.select_project_first') }}</h6>
                    <form action="{{ route('properties.select-project') }}" method="POST" class="mt-3">
                        @csrf
                        <div class="row">
                            <div class="col-md-8">
                                <select name="project_id" class="form-control" required>
                                    <option value="">{{ trans('main_trans.select_project') }}</option>
                                    @foreach($projects as $project)
                                        <option value="{{ $project->id }}">
                                            {{ $project->id }} - {{ $project->getTitle() }} - {{ $project->area ? (app()->getLocale() === 'ar' ? $project->area->name_ar : $project->area->name_en) : trans('main_trans.no_area') }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-check"></i> {{ trans('main_trans.confirm_project') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                @endif

                {{-- فورم إضافة العقار (يظهر فقط بعد اختيار المشروع) --}}
                @if($selectedProjectLocation)
                
                {{-- معلومات المشروع والشركة --}}
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-title text-primary">{{ trans('main_trans.project_info') }}</h6>
                                <p class="mb-1"><strong>{{ trans('main_trans.project') }}:</strong> {{ request('project_id') }} - {{ $projects->where('id', request('project_id'))->first()->getTitle() }}</p>
                                <p class="mb-1"><strong>{{ trans('main_trans.location') }}:</strong> {{ $selectedProjectLocation }}</p>
                                @if($selectedProjectCompany)
                                <p class="mb-0"><strong>{{ trans('main_trans.company') }}:</strong> {{ app()->getLocale() === 'ar' ? $selectedProjectCompany->company_name_ar : $selectedProjectCompany->company_name_en }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <form action="{{ route('properties.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- المشروع وخطة الدفع --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="propertyproject" class="form-label">{{ trans('main_trans.project') }} *</label>
                                <select id="propertyproject" class="form-control @error('propertyproject') is-invalid @enderror" disabled>
                                    <option value="">{{ trans('main_trans.select_project') }}</option>
                                    @foreach($projects as $project)
                                        <option value="{{ $project->id }}" 
                                                {{ old('propertyproject') == $project->id ? 'selected' : '' }}
                                                {{ request('project_id') == $project->id ? 'selected' : '' }}>
                                            {{ $project->id }} - {{ $project->getTitle() }} - {{ $project->area ? (app()->getLocale() === 'ar' ? $project->area->name_ar : $project->area->name_en) : trans('main_trans.no_area') }}
                                        </option>
                                    @endforeach
                                </select>
                                {{-- Hidden field to carry the project value --}}
                                <input type="hidden" name="propertyproject" value="{{ request('project_id') }}">
                                <small class="form-text text-muted">{{ trans('main_trans.project_already_selected') }}</small>
                                @error('propertyproject')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="propertypaymentplan" class="form-label">{{ trans('main_trans.payment_plan') }} *</label>
                                <select name="propertypaymentplan" id="propertypaymentplan" class="form-control @error('propertypaymentplan') is-invalid @enderror" required>
                                    <option value="">{{ trans('main_trans.select_payment_plan') }}</option>
                                    @foreach($paymentPlans as $plan)
                                        <option value="{{ $plan->id }}" {{ old('propertypaymentplan') == $plan->id ? 'selected' : '' }}>
                                            {{ $plan->getLocalizedNameAttribute() }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('propertypaymentplan')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- الغرض --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="propertypurpose" class="form-label">{{ trans('main_trans.purpose') }} *</label>
                                <select name="propertypurpose" id="propertypurpose" class="form-control @error('propertypurpose') is-invalid @enderror" required>
                                    <option value="">{{ trans('main_trans.select_purpose') }}</option>
                                    <option value="sale" {{ old('propertypurpose') == 'sale' ? 'selected' : '' }}>{{ trans('main_trans.sale') }}</option>
                                    <option value="rental" {{ old('propertypurpose') == 'rental' ? 'selected' : '' }}>{{ trans('main_trans.rental') }}</option>
                                    <option value="both" {{ old('propertypurpose') == 'both' ? 'selected' : '' }}>{{ trans('main_trans.both') }}</option>
                                    <option value="investment" {{ old('propertypurpose') == 'investment' ? 'selected' : '' }}>{{ trans('main_trans.investment') }}</option>
                                    <option value="vacation" {{ old('propertypurpose') == 'vacation' ? 'selected' : '' }}>{{ trans('main_trans.vacation') }}</option>
                                </select>
                                @error('propertypurpose')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- السعر والكمية --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="propertyprice" class="form-label">{{ trans('main_trans.price') }} (AED) *</label>
                                <input type="number" name="propertyprice" id="propertyprice" class="form-control @error('propertyprice') is-invalid @enderror" value="{{ old('propertyprice') }}" step="0.01" min="0" required>
                                @error('propertyprice')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="propertyquantity" class="form-label">{{ trans('main_trans.quantity') }} *</label>
                                <input type="number" name="propertyquantity" id="propertyquantity" class="form-control @error('propertyquantity') is-invalid @enderror" value="{{ old('propertyquantity', 1) }}" min="1" required>
                                <small class="form-text text-muted">{{ trans('main_trans.quantity_help') }}</small>
                                @error('propertyquantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- الغرف والحمامات والمساحة --}}
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="propertyrooms" class="form-label">{{ trans('main_trans.rooms') }} *</label>
                                <input type="number" name="propertyrooms" id="propertyrooms" class="form-control @error('propertyrooms') is-invalid @enderror" value="{{ old('propertyrooms') }}" min="0" required>
                                @error('propertyrooms')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="propertybathrooms" class="form-label">{{ trans('main_trans.bathrooms') }} *</label>
                                <input type="number" name="propertybathrooms" id="propertybathrooms" class="form-control @error('propertybathrooms') is-invalid @enderror" value="{{ old('propertybathrooms') }}" min="0" required>
                                @error('propertybathrooms')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="propertyarea" class="form-label">{{ trans('main_trans.area') }} (sqm) *</label>
                                <input type="number" name="propertyarea" id="propertyarea" class="form-control @error('propertyarea') is-invalid @enderror" value="{{ old('propertyarea') }}" step="0.01" min="0" required>
                                @error('propertyarea')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- الموقع وتاريخ التسليم --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="propertyloaction" class="form-label">{{ trans('main_trans.location') }} *</label>
                                <input type="text" name="propertyloaction" id="propertyloaction" class="form-control @error('propertyloaction') is-invalid @enderror" 
                                       value="{{ old('propertyloaction', $selectedProjectLocation ?? '') }}" 
                                       readonly required>
                                <small class="form-text text-muted">{{ trans('main_trans.location_auto_fill') }}</small>
                                @error('propertyloaction')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="propertyhandover" class="form-label">{{ trans('main_trans.handover_date') }}</label>
                                <input type="date" name="propertyhandover" id="propertyhandover" class="form-control @error('propertyhandover') is-invalid @enderror" value="{{ old('propertyhandover') }}">
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
                                    @if($selectedProjectEmployees && $selectedProjectEmployees->count() > 0)
                                        @foreach($selectedProjectEmployees as $employee)
                                            <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
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

                    {{-- المميزات --}}
                    <div class="mb-3">
                        <label class="form-label">{{ trans('main_trans.features') }}</label>
                        
                        {{-- المميزات الأساسية --}}
                        <div class="card mb-3">
                            <div class="card-header">
                                <h6 class="mb-0">{{ trans('main_trans.basic_features') }}</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="propertyfeatures[]" value="central_ac" id="central_ac" {{ in_array('central_ac', old('propertyfeatures', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="central_ac">{{ trans('main_trans.central_ac') }}</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="propertyfeatures[]" value="elevator" id="elevator" {{ in_array('elevator', old('propertyfeatures', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="elevator">{{ trans('main_trans.elevator') }}</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="propertyfeatures[]" value="parking" id="parking" {{ in_array('parking', old('propertyfeatures', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="parking">{{ trans('main_trans.parking') }}</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="propertyfeatures[]" value="garden" id="garden" {{ in_array('garden', old('propertyfeatures', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="garden">{{ trans('main_trans.garden') }}</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="propertyfeatures[]" value="balcony" id="balcony" {{ in_array('balcony', old('propertyfeatures', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="balcony">{{ trans('main_trans.balcony') }}</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="propertyfeatures[]" value="terrace" id="terrace" {{ in_array('terrace', old('propertyfeatures', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="terrace">{{ trans('main_trans.terrace') }}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- المميزات الترفيهية --}}
                        <div class="card mb-3">
                            <div class="card-header">
                                <h6 class="mb-0">{{ trans('main_trans.entertainment_features') }}</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="propertyfeatures[]" value="swimming_pool" id="swimming_pool" {{ in_array('swimming_pool', old('propertyfeatures', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="swimming_pool">{{ trans('main_trans.swimming_pool') }}</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="propertyfeatures[]" value="gym" id="gym" {{ in_array('gym', old('propertyfeatures', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="gym">{{ trans('main_trans.gym') }}</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="propertyfeatures[]" value="playground" id="playground" {{ in_array('playground', old('propertyfeatures', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="playground">{{ trans('main_trans.playground') }}</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="propertyfeatures[]" value="tennis_court" id="tennis_court" {{ in_array('tennis_court', old('propertyfeatures', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="tennis_court">{{ trans('main_trans.tennis_court') }}</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="propertyfeatures[]" value="cinema_room" id="cinema_room" {{ in_array('cinema_room', old('propertyfeatures', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="cinema_room">{{ trans('main_trans.cinema_room') }}</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="propertyfeatures[]" value="game_room" id="game_room" {{ in_array('game_room', old('propertyfeatures', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="game_room">{{ trans('main_trans.game_room') }}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- المميزات الأمنية --}}
                        <div class="card mb-3">
                            <div class="card-header">
                                <h6 class="mb-0">{{ trans('main_trans.security_features') }}</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="propertyfeatures[]" value="security" id="security" {{ in_array('security', old('propertyfeatures', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="security">{{ trans('main_trans.security') }}</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="propertyfeatures[]" value="cctv" id="cctv" {{ in_array('cctv', old('propertyfeatures', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="cctv">{{ trans('main_trans.cctv') }}</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="propertyfeatures[]" value="guard" id="guard" {{ in_array('guard', old('propertyfeatures', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="guard">{{ trans('main_trans.guard') }}</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="propertyfeatures[]" value="electronic_access" id="electronic_access" {{ in_array('electronic_access', old('propertyfeatures', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="electronic_access">{{ trans('main_trans.electronic_access') }}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- المميزات الخدمية --}}
                        <div class="card mb-3">
                            <div class="card-header">
                                <h6 class="mb-0">{{ trans('main_trans.service_features') }}</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="propertyfeatures[]" value="laundry" id="laundry" {{ in_array('laundry', old('propertyfeatures', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="laundry">{{ trans('main_trans.laundry') }}</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="propertyfeatures[]" value="cleaning" id="cleaning" {{ in_array('cleaning', old('propertyfeatures', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="cleaning">{{ trans('main_trans.cleaning') }}</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="propertyfeatures[]" value="maintenance" id="maintenance" {{ in_array('maintenance', old('propertyfeatures', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="maintenance">{{ trans('main_trans.maintenance') }}</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="propertyfeatures[]" value="concierge" id="concierge" {{ in_array('concierge', old('propertyfeatures', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="concierge">{{ trans('main_trans.concierge') }}</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="propertyfeatures[]" value="valet_parking" id="valet_parking" {{ in_array('valet_parking', old('propertyfeatures', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="valet_parking">{{ trans('main_trans.valet_parking') }}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- المميزات التقنية --}}
                        <div class="card mb-3">
                            <div class="card-header">
                                <h6 class="mb-0">{{ trans('main_trans.technical_features') }}</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="propertyfeatures[]" value="internet" id="internet" {{ in_array('internet', old('propertyfeatures', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="internet">{{ trans('main_trans.internet') }}</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="propertyfeatures[]" value="cable_tv" id="cable_tv" {{ in_array('cable_tv', old('propertyfeatures', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="cable_tv">{{ trans('main_trans.cable_tv') }}</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="propertyfeatures[]" value="smart_system" id="smart_system" {{ in_array('smart_system', old('propertyfeatures', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="smart_system">{{ trans('main_trans.smart_system') }}</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="propertyfeatures[]" value="solar_power" id="solar_power" {{ in_array('solar_power', old('propertyfeatures', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="solar_power">{{ trans('main_trans.solar_power') }}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @error('propertyfeatures')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- التفاصيل الكاملة - العربية --}}
                    <div class="mb-3">
                        <label for="propertyfulldetils_ar" class="form-label">{{ trans('main_trans.full_details_ar') }}</label>
                        <textarea name="propertyfulldetils_ar" id="propertyfulldetils_ar" class="form-control @error('propertyfulldetils_ar') is-invalid @enderror" rows="5" placeholder="اكتب التفاصيل الكاملة للعقار باللغة العربية">{{ old('propertyfulldetils_ar') }}</textarea>
                        @error('propertyfulldetils_ar')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- التفاصيل الكاملة - الإنجليزية --}}
                    <div class="mb-3">
                        <label for="propertyfulldetils_en" class="form-label">{{ trans('main_trans.full_details_en') }}</label>
                        <textarea name="propertyfulldetils_en" id="propertyfulldetils_en" class="form-control @error('propertyfulldetils_en') is-invalid @enderror" rows="5" placeholder="Write the full details of the property in English">{{ old('propertyfulldetils_en') }}</textarea>
                        @error('propertyfulldetils_en')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- الصور --}}
                    <div class="mb-3">
                        <label for="propertyimages" class="form-label">{{ trans('main_trans.images') }}</label>
                        <input type="file" name="propertyimages[]" id="propertyimages" class="form-control @error('propertyimages') is-invalid @enderror" multiple accept="image/*">
                        <small class="form-text text-muted">{{ trans('main_trans.max_images') }}: 5, {{ trans('main_trans.max_size') }}: 2MB</small>
                        @error('propertyimages')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- أزرار الإرسال --}}
                    <div class="text-end">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> {{ trans('main_trans.submit') }}
                        </button>
                    </div>
                </form>
                @endif

            </div>
        </div>
    </div>
</div>

@endsection

{{-- تم إزالة JavaScript - الموظفين يتم تحميلهم من الخادم مباشرة --}}
