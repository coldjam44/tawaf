@extends('admin.layouts.app')

@section('content')

<div class="row">
    <div class="col-xl">
        <div class="card mb-4">

            {{-- عنوان الصفحة وزر الإضافة --}}
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="text-center my-4">{{ trans('main_trans.contact_us') }}</h3>
                @if(!$contactInfo)
                    <div class="text-right mb-3">
                        <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#addContactForm" aria-expanded="false" aria-controls="addContactForm">
                            <i class="fas fa-plus-circle"></i> {{ trans('main_trans.add') }}
                        </button>
                    </div>
                @endif
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

                {{-- فورم الإضافة --}}
                @if(!$contactInfo)
                <div class="collapse" id="addContactForm">
                    <div class="card card-body">
                        <form action="{{ route('contact-us.store') }}" method="POST">
                            @csrf

                            {{-- اسم الشركة ثنائي اللغة --}}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="company_name_ar" class="form-label">{{ trans('main_trans.company_name_ar') }} *</label>
                                        <input type="text" name="company_name_ar" id="company_name_ar" class="form-control @error('company_name_ar') is-invalid @enderror" value="{{ old('company_name_ar') }}" required>
                                        @error('company_name_ar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="company_name_en" class="form-label">{{ trans('main_trans.company_name_en') }} *</label>
                                        <input type="text" name="company_name_en" id="company_name_en" class="form-control @error('company_name_en') is-invalid @enderror" value="{{ old('company_name_en') }}" required>
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
                                        <input type="text" name="broker_registration_number" id="broker_registration_number" class="form-control @error('broker_registration_number') is-invalid @enderror" value="{{ old('broker_registration_number') }}">
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
                                        </div>
                                        <button type="button" class="btn btn-success btn-sm" id="add_location_btn">
                                            <i class="fas fa-plus"></i> {{ trans('main_trans.add_location') }}
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> {{ trans('main_trans.submit') }}
                                </button>
                                <button type="button" class="btn btn-secondary" data-bs-toggle="collapse" data-bs-target="#addContactForm">
                                    <i class="fas fa-times"></i> {{ trans('main_trans.cancel') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                @endif

                {{-- عرض معلومات التواصل --}}
                @if($contactInfo)
                    {{-- معلومات الشركة --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h6 class="mb-0">
                                        <i class="fas fa-building text-primary"></i>
                                        {{ trans('main_trans.company_name_ar') }}
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <p class="mb-0">{{ $contactInfo->company_name_ar }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h6 class="mb-0">
                                        <i class="fas fa-building text-primary"></i>
                                        {{ trans('main_trans.company_name_en') }}
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <p class="mb-0">{{ $contactInfo->company_name_en }}</p>
                                </div>
                            </div>
                        </div>

                        @if($contactInfo->broker_registration_number)
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h6 class="mb-0">
                                        <i class="fas fa-id-card text-primary"></i>
                                        {{ trans('main_trans.broker_registration_number') }}
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <p class="mb-0">{{ $contactInfo->broker_registration_number }}</p>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                    {{-- أرقام الهاتف --}}
                    @if($contactInfo->phone_numbers)
                    <div class="row">
                        <div class="col-12">
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h6 class="mb-0">
                                        <i class="fas fa-phone text-primary"></i>
                                        {{ trans('main_trans.phone_numbers') }}
                                    </h6>
                                </div>
                                <div class="card-body">
                                    @foreach($contactInfo->phone_numbers as $phone)
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div>
                                            <strong>{{ $phone['number'] }}</strong>
                                            @if($phone['type'])
                                                <span class="badge bg-secondary ms-2">{{ trans('main_trans.' . $phone['type']) }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- عناوين البريد الإلكتروني --}}
                    @if($contactInfo->email_addresses)
                    <div class="row">
                        <div class="col-12">
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h6 class="mb-0">
                                        <i class="fas fa-envelope text-primary"></i>
                                        {{ trans('main_trans.email_addresses') }}
                                    </h6>
                                </div>
                                <div class="card-body">
                                    @foreach($contactInfo->email_addresses as $email)
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div>
                                            <strong>{{ $email['email'] }}</strong>
                                            @if($email['type'])
                                                <span class="badge bg-secondary ms-2">{{ trans('main_trans.' . $email['type']) }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- المواقع --}}
                    @if($contactInfo->locations)
                    <div class="row">
                        @foreach($contactInfo->locations as $index => $location)
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h6 class="mb-0">
                                        <i class="fas fa-map-marker-alt text-primary"></i>
                                        {{ trans('main_trans.locations') }} #{{ $index + 1 }}
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-2">
                                        <strong>{{ trans('main_trans.address_ar') }}:</strong>
                                        <p class="mb-1">{{ $location['address_ar'] }}</p>
                                    </div>
                                    <div class="mb-2">
                                        <strong>{{ trans('main_trans.address_en') }}:</strong>
                                        <p class="mb-1">{{ $location['address_en'] }}</p>
                                    </div>
                                    @if($location['map_embed'])
                                    <div class="mt-3">
                                        <div class="ratio ratio-16x9">
                                            {!! $location['map_embed'] !!}
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif

                    <div class="text-center mt-4">
                        <a href="{{ route('contact-us.edit', $contactInfo->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> {{ trans('main_trans.edit') }}
                        </a>
                        <form action="{{ route('contact-us.destroy', $contactInfo->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('{{ trans('main_trans.confirm_delete') }}')">
                                <i class="fas fa-trash"></i> {{ trans('main_trans.delete') }}
                            </button>
                        </form>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-phone-slash text-muted" style="font-size: 4rem;"></i>
                        <h5 class="mt-3 text-muted">{{ trans('main_trans.no_contact_info') }}</h5>
                        <p class="text-muted">{{ trans('main_trans.add_contact_info_first') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let phoneIndex = 1;
    let emailIndex = 1;
    let locationIndex = 1;

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
