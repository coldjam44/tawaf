@extends('admin.layouts.app')

@section('content')

<div class="row">
    <div class="col-xl">
        <div class="card mb-4">

            {{-- عنوان الصفحة --}}
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="text-center my-4">{{ trans('main_trans.edit') }} {{ trans('main_trans.contact_us') }}</h3>
                <div class="text-right mb-3">
                    <a href="{{ route('contact-us.index') }}" class="btn btn-secondary">
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

                <form action="{{ route('contact-us.update', $contactInfo->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                                
                                {{-- اسم الشركة ثنائي اللغة --}}
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="company_name_ar" class="form-label">{{ trans('main_trans.company_name_ar') }} *</label>
                                            <input type="text" name="company_name_ar" id="company_name_ar" class="form-control @error('company_name_ar') is-invalid @enderror" value="{{ old('company_name_ar', $contactInfo->company_name_ar) }}" required>
                                            @error('company_name_ar')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="company_name_en" class="form-label">{{ trans('main_trans.company_name_en') }} *</label>
                                            <input type="text" name="company_name_en" id="company_name_en" class="form-control @error('company_name_en') is-invalid @enderror" value="{{ old('company_name_en', $contactInfo->company_name_en) }}" required>
                                            @error('company_name_en')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                {{-- رقم تسجيل الوسيط --}}
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="broker_registration_number" class="form-label">{{ trans('main_trans.broker_registration_number') }}</label>
                                            <input type="text" name="broker_registration_number" id="broker_registration_number" class="form-control @error('broker_registration_number') is-invalid @enderror" value="{{ old('broker_registration_number', $contactInfo->broker_registration_number) }}">
                                            @error('broker_registration_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                {{-- أرقام الهاتف --}}
                                <div class="row">
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label class="form-label">{{ trans('main_trans.phone_numbers') }} *</label>
                                            <div id="phone_numbers_container">
                                                @if($contactInfo->phone_numbers)
                                                    @foreach($contactInfo->phone_numbers as $index => $phone)
                                                    <div class="phone-number-item border rounded p-3 mb-2">
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <input type="text" name="phone_numbers[{{ $index }}][number]" class="form-control" placeholder="{{ trans('main_trans.phone') }}" value="{{ $phone['number'] }}" required>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <select name="phone_numbers[{{ $index }}][type]" class="form-control">
                                                                    <option value="">{{ trans('main_trans.phone_type') }}</option>
                                                                    <option value="mobile" {{ $phone['type'] == 'mobile' ? 'selected' : '' }}>{{ trans('main_trans.mobile') }}</option>
                                                                    <option value="landline" {{ $phone['type'] == 'landline' ? 'selected' : '' }}>{{ trans('main_trans.landline') }}</option>
                                                                    <option value="fax" {{ $phone['type'] == 'fax' ? 'selected' : '' }}>{{ trans('main_trans.fax') }}</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <button type="button" class="btn btn-danger btn-sm remove-phone" style="display: {{ count($contactInfo->phone_numbers) > 1 ? 'block' : 'none' }};">
                                                                    <i class="fas fa-trash"></i> {{ trans('main_trans.remove_phone') }}
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                @else
                                                    <div class="phone-number-item border rounded p-3 mb-2">
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <input type="text" name="phone_numbers[0][number]" class="form-control" placeholder="{{ trans('main_trans.phone') }}" required>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <select name="phone_numbers[0][type]" class="form-control">
                                                                    <option value="">{{ trans('main_trans.phone_type') }}</option>
                                                                    <option value="mobile">{{ trans('main_trans.mobile') }}</option>
                                                                    <option value="landline">{{ trans('main_trans.landline') }}</option>
                                                                    <option value="fax">{{ trans('main_trans.fax') }}</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <button type="button" class="btn btn-danger btn-sm remove-phone" style="display: none;">
                                                                    <i class="fas fa-trash"></i> {{ trans('main_trans.remove_phone') }}
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                            <button type="button" class="btn btn-success btn-sm" id="add_phone_btn">
                                                <i class="fas fa-plus"></i> {{ trans('main_trans.add_phone') }}
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                {{-- عناوين البريد الإلكتروني --}}
                                <div class="row">
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label class="form-label">{{ trans('main_trans.email_addresses') }} *</label>
                                            <div id="email_addresses_container">
                                                @if($contactInfo->email_addresses)
                                                    @foreach($contactInfo->email_addresses as $index => $email)
                                                    <div class="email-address-item border rounded p-3 mb-2">
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <input type="email" name="email_addresses[{{ $index }}][email]" class="form-control" placeholder="{{ trans('main_trans.email') }}" value="{{ $email['email'] }}" required>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <select name="email_addresses[{{ $index }}][type]" class="form-control">
                                                                    <option value="">{{ trans('main_trans.email_type') }}</option>
                                                                    <option value="general" {{ $email['type'] == 'general' ? 'selected' : '' }}>{{ trans('main_trans.general') }}</option>
                                                                    <option value="support" {{ $email['type'] == 'support' ? 'selected' : '' }}>{{ trans('main_trans.support') }}</option>
                                                                    <option value="sales" {{ $email['type'] == 'sales' ? 'selected' : '' }}>{{ trans('main_trans.sales') }}</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <button type="button" class="btn btn-danger btn-sm remove-email" style="display: {{ count($contactInfo->email_addresses) > 1 ? 'block' : 'none' }};">
                                                                    <i class="fas fa-trash"></i> {{ trans('main_trans.remove_email') }}
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                @else
                                                    <div class="email-address-item border rounded p-3 mb-2">
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <input type="email" name="email_addresses[0][email]" class="form-control" placeholder="{{ trans('main_trans.email') }}" required>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <select name="email_addresses[0][type]" class="form-control">
                                                                    <option value="">{{ trans('main_trans.email_type') }}</option>
                                                                    <option value="general">{{ trans('main_trans.general') }}</option>
                                                                    <option value="support">{{ trans('main_trans.support') }}</option>
                                                                    <option value="sales">{{ trans('main_trans.sales') }}</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <button type="button" class="btn btn-danger btn-sm remove-email" style="display: none;">
                                                                    <i class="fas fa-trash"></i> {{ trans('main_trans.remove_email') }}
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                            <button type="button" class="btn btn-success btn-sm" id="add_email_btn">
                                                <i class="fas fa-plus"></i> {{ trans('main_trans.add_email') }}
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                {{-- المواقع --}}
                                <div class="row">
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label class="form-label">{{ trans('main_trans.locations') }} *</label>
                                            <div id="locations_container">
                                                @if($contactInfo->locations)
                                                    @foreach($contactInfo->locations as $index => $location)
                                                    <div class="location-item border rounded p-3 mb-2">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <label class="form-label">{{ trans('main_trans.address_ar') }} *</label>
                                                                <textarea name="locations[{{ $index }}][address_ar]" class="form-control" rows="2" required>{{ $location['address_ar'] }}</textarea>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label">{{ trans('main_trans.address_en') }} *</label>
                                                                <textarea name="locations[{{ $index }}][address_en]" class="form-control" rows="2" required>{{ $location['address_en'] }}</textarea>
                                                            </div>
                                                        </div>
                                                        <div class="row mt-2">
                                                            <div class="col-md-8">
                                                                <label class="form-label">{{ trans('main_trans.map') }} (Embed Code)</label>
                                                                <textarea name="locations[{{ $index }}][map_embed]" class="form-control" rows="2" placeholder="أضف كود الخريطة من Google Maps">{{ $location['map_embed'] ?? '' }}</textarea>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <button type="button" class="btn btn-danger btn-sm remove-location" style="display: {{ count($contactInfo->locations) > 1 ? 'block' : 'none' }};">
                                                                    <i class="fas fa-trash"></i> {{ trans('main_trans.remove_location') }}
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                @else
                                                    <div class="location-item border rounded p-3 mb-2">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <label class="form-label">{{ trans('main_trans.address_ar') }} *</label>
                                                                <textarea name="locations[0][address_ar]" class="form-control" rows="2" required></textarea>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label">{{ trans('main_trans.address_en') }} *</label>
                                                                <textarea name="locations[0][address_en]" class="form-control" rows="2" required></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="row mt-2">
                                                            <div class="col-md-8">
                                                                <label class="form-label">{{ trans('main_trans.map') }} (Embed Code)</label>
                                                                <textarea name="locations[0][map_embed]" class="form-control" rows="2" placeholder="أضف كود الخريطة من Google Maps"></textarea>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <button type="button" class="btn btn-danger btn-sm remove-location" style="display: none;">
                                                                    <i class="fas fa-trash"></i> {{ trans('main_trans.remove_location') }}
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                            <button type="button" class="btn btn-success btn-sm" id="add_location_btn">
                                                <i class="fas fa-plus"></i> {{ trans('main_trans.add_location') }}
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                </div>

                                <div class="text-center mt-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> {{ trans('main_trans.submit') }}
                                    </button>
                                    <a href="{{ route('contact-us.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> {{ trans('main_trans.cancel') }}
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let phoneIndex = {{ $contactInfo->phone_numbers ? count($contactInfo->phone_numbers) : 1 }};
    let emailIndex = {{ $contactInfo->email_addresses ? count($contactInfo->email_addresses) : 1 }};
    let locationIndex = {{ $contactInfo->locations ? count($contactInfo->locations) : 1 }};

    // إضافة رقم هاتف جديد
    document.getElementById('add_phone_btn').addEventListener('click', function() {
        const container = document.getElementById('phone_numbers_container');
        const newItem = document.createElement('div');
        newItem.className = 'phone-number-item border rounded p-3 mb-2';
        newItem.innerHTML = `
            <div class="row">
                <div class="col-md-4">
                    <input type="text" name="phone_numbers[${phoneIndex}][number]" class="form-control" placeholder="{{ trans('main_trans.phone') }}" required>
                </div>
                <div class="col-md-4">
                    <select name="phone_numbers[${phoneIndex}][type]" class="form-control">
                        <option value="">{{ trans('main_trans.phone_type') }}</option>
                        <option value="mobile">{{ trans('main_trans.mobile') }}</option>
                        <option value="landline">{{ trans('main_trans.landline') }}</option>
                        <option value="fax">{{ trans('main_trans.fax') }}</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="button" class="btn btn-danger btn-sm remove-phone">
                        <i class="fas fa-trash"></i> {{ trans('main_trans.remove_phone') }}
                    </button>
                </div>
            </div>
        `;
        container.appendChild(newItem);
        phoneIndex++;
        updateRemoveButtons();
    });

    // إضافة بريد إلكتروني جديد
    document.getElementById('add_email_btn').addEventListener('click', function() {
        const container = document.getElementById('email_addresses_container');
        const newItem = document.createElement('div');
        newItem.className = 'email-address-item border rounded p-3 mb-2';
        newItem.innerHTML = `
            <div class="row">
                <div class="col-md-4">
                    <input type="email" name="email_addresses[${emailIndex}][email]" class="form-control" placeholder="{{ trans('main_trans.email') }}" required>
                </div>
                <div class="col-md-4">
                    <select name="email_addresses[${emailIndex}][type]" class="form-control">
                        <option value="">{{ trans('main_trans.email_type') }}</option>
                        <option value="general">{{ trans('main_trans.general') }}</option>
                        <option value="support">{{ trans('main_trans.support') }}</option>
                        <option value="sales">{{ trans('main_trans.sales') }}</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="button" class="btn btn-danger btn-sm remove-email">
                        <i class="fas fa-trash"></i> {{ trans('main_trans.remove_email') }}
                    </button>
                </div>
            </div>
        `;
        container.appendChild(newItem);
        emailIndex++;
        updateRemoveButtons();
    });

    // إضافة موقع جديد
    document.getElementById('add_location_btn').addEventListener('click', function() {
        const container = document.getElementById('locations_container');
        const newItem = document.createElement('div');
        newItem.className = 'location-item border rounded p-3 mb-2';
        newItem.innerHTML = `
            <div class="row">
                <div class="col-md-6">
                    <label class="form-label">{{ trans('main_trans.address_ar') }} *</label>
                    <textarea name="locations[${locationIndex}][address_ar]" class="form-control" rows="2" required></textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label">{{ trans('main_trans.address_en') }} *</label>
                    <textarea name="locations[${locationIndex}][address_en]" class="form-control" rows="2" required></textarea>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-8">
                    <label class="form-label">{{ trans('main_trans.map') }} (Embed Code)</label>
                    <textarea name="locations[${locationIndex}][map_embed]" class="form-control" rows="2" placeholder="أضف كود الخريطة من Google Maps"></textarea>
                </div>
                <div class="col-md-4">
                    <button type="button" class="btn btn-danger btn-sm remove-location">
                        <i class="fas fa-trash"></i> {{ trans('main_trans.remove_location') }}
                    </button>
                </div>
            </div>
        `;
        container.appendChild(newItem);
        locationIndex++;
        updateRemoveButtons();
    });

    // حذف العناصر
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-phone') || e.target.closest('.remove-phone')) {
            const item = e.target.closest('.phone-number-item');
            if (document.querySelectorAll('.phone-number-item').length > 1) {
                item.remove();
                updateRemoveButtons();
            }
        }
        
        if (e.target.classList.contains('remove-email') || e.target.closest('.remove-email')) {
            const item = e.target.closest('.email-address-item');
            if (document.querySelectorAll('.email-address-item').length > 1) {
                item.remove();
                updateRemoveButtons();
            }
        }
        
        if (e.target.classList.contains('remove-location') || e.target.closest('.remove-location')) {
            const item = e.target.closest('.location-item');
            if (document.querySelectorAll('.location-item').length > 1) {
                item.remove();
                updateRemoveButtons();
            }
        }
    });

    // تحديث أزرار الحذف
    function updateRemoveButtons() {
        const phoneItems = document.querySelectorAll('.phone-number-item');
        const emailItems = document.querySelectorAll('.email-address-item');
        const locationItems = document.querySelectorAll('.location-item');

        // إظهار/إخفاء أزرار حذف أرقام الهاتف
        phoneItems.forEach((item, index) => {
            const removeBtn = item.querySelector('.remove-phone');
            if (phoneItems.length > 1) {
                removeBtn.style.display = 'block';
            } else {
                removeBtn.style.display = 'none';
            }
        });

        // إظهار/إخفاء أزرار حذف البريد الإلكتروني
        emailItems.forEach((item, index) => {
            const removeBtn = item.querySelector('.remove-email');
            if (emailItems.length > 1) {
                removeBtn.style.display = 'block';
            } else {
                removeBtn.style.display = 'none';
            }
        });

        // إظهار/إخفاء أزرار حذف المواقع
        locationItems.forEach((item, index) => {
            const removeBtn = item.querySelector('.remove-location');
            if (locationItems.length > 1) {
                removeBtn.style.display = 'block';
            } else {
                removeBtn.style.display = 'none';
            }
        });
    }

    // تحديث الأزرار عند تحميل الصفحة
    updateRemoveButtons();
});
</script>
@endsection
