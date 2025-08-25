@extends('admin.layouts.app')
@section('content')
<h3 class="text-center my-4"> {{ trans('main_trans.hotels') }}</h3>
<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header text-center" style="background: #fff; color: #333; border-radius: 10px 10px 0 0; border-bottom: 2px solid #ddd; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
            <h3 style="font-weight: bold; letter-spacing: 1px;">{{ trans('Counters_trans.add_Grade') }}</h3>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('hotels.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="name_ar" class="form-label">{{ trans('Counters_trans.name_ar') }}</label>
                        <input type="text" name="name_ar" class="form-control" id="name_ar" placeholder="{{ trans('Counters_trans.name_ar') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="name_en" class="form-label">{{ trans('Counters_trans.name_en') }}</label>
                        <input type="text" name="name_en" class="form-control" id="name_en" placeholder="{{ trans('Counters_trans.name_en') }}" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="possition_ar" class="form-label">{{ trans('Counters_trans.possition_ar') }}</label>
                        <input type="text" name="possition_ar" class="form-control" id="possition_ar" placeholder="{{ trans('Counters_trans.possition_ar') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="possition_en" class="form-label">{{ trans('Counters_trans.possition_en') }}</label>
                        <input type="text" name="possition_en" class="form-control" id="possition_en" placeholder="{{ trans('Counters_trans.possition_en') }}" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="rate" class="form-label">{{ trans('Counters_trans.rate') }}</label>
                    <input type="number" name="rate" class="form-control" id="rate" placeholder="{{ trans('Counters_trans.rate') }}">
                </div>
                

                <div class="mb-3">
                    <label for="image">Images:</label>
                    <input type="file" name="image[]" multiple required>
                  </div>

                <div class="row mb-3">
    <div class="col-md-6">
        <label for="overview_ar" class="form-label">{{ trans('Counters_trans.overview_ar') }}</label>
        <textarea name="overview_ar" class="form-control" id="overview_ar" placeholder="{{ trans('Counters_trans.overview_ar') }}" required></textarea>
    </div>
    <div class="col-md-6">
        <label for="overview_en" class="form-label">{{ trans('Counters_trans.overview_en') }}</label>
        <textarea name="overview_en" class="form-control" id="overview_en" placeholder="{{ trans('Counters_trans.overview_en') }}" required></textarea>
    </div>
</div>

                
                <div class="mb-3">
                        <label for="location_map" class="form-label">{{ trans('Counters_trans.location_map') }}</label>
                        <input type="text" name="location_map" class="form-control" id="location_map" placeholder="{{ trans('Counters_trans.location_map') }}" required>
                    </div>




               

               <div class="form-group">
    <label for="amenity_ids[]">{{ trans('Counters_trans.amenity') }}</label>
    <select name="amenity_ids[]" class="form-control form-select" id="amenity-select" multiple>
        @foreach ($amenities as $category)
            <option value="{{ $category->id }}" {{ in_array($category->id, old('amenity_ids', [])) ? 'selected' : '' }}>
                {{ App::getLocale() === 'ar' ? $category->name_ar : $category->name_en }}
            </option>
        @endforeach
    </select>
</div>

<script>
    $(document).ready(function() {
        // تهيئة Select2
        $('#amenity-select').select2({
            placeholder: '{{ trans('Counters_trans.amenity') }}',
            allowClear: true,
            closeOnSelect: false // إبقاء القائمة مفتوحة بعد الاختيار
        });

        // إعادة فتح القائمة عند الاختيار
        $('#amenity-select').on('select2:select', function() {
            $(this).select2('open');
        });

        // لا حاجة لإعادة فتح القائمة عند إزالة الاختيار
        $('#amenity-select').on('select2:unselect', function() {
            // هنا يمكن إضافة أي منطق إضافي إذا لزم الأمر
        });
    });
</script>


                

                <h4 class="mt-4">{{ trans('main_trans.pricing_periods') }}</h4>
        <div id="pricing-container">
            <div class="pricing-item mb-4 border p-3 rounded">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="start_date" class="form-label">{{ trans('main_trans.start_date') }}</label>
                        <input type="date" name="pricing[0][start_date]" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label for="end_date" class="form-label">{{ trans('main_trans.end_date') }}</label>
                        <input type="date" name="pricing[0][end_date]" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label for="price" class="form-label">{{ trans('main_trans.price') }}</label>
                        <input type="number" name="pricing[0][price]" class="form-control" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="discount_price" class="form-label">{{ trans('main_trans.discount_price') }}</label>
                        <input type="number" name="pricing[0][discount_price]" class="form-control">
                    </div>
                </div>
                <button type="button" class="btn btn-danger remove-pricing" style="display: none;">{{ trans('main_trans.remove') }}</button>
            </div>
        </div>

        <button type="button" id="add-pricing" class="btn btn-primary mb-4">{{ trans('main_trans.add_period') }}</button>

        <div class="d-flex justify-content-end">
            <a href="{{ route('hotels.index') }}" class="btn btn-secondary me-2">{{ trans('Counters_trans.Close') }}</a>
            <button type="submit" class="btn btn-success">{{ trans('Counters_trans.Submit') }}</button>
        </div>
    </form>
</div>

<script>
    let pricingIndex = 1;

    document.getElementById('add-pricing').addEventListener('click', function () {
        const container = document.getElementById('pricing-container');
        const firstPricingItem = document.querySelector('.pricing-item');
        const newPricing = firstPricingItem.cloneNode(true);

        newPricing.querySelectorAll('input').forEach(input => {
            const name = input.getAttribute('name').replace(/\d+/, pricingIndex);
            input.setAttribute('name', name);
            input.value = '';
        });

        newPricing.querySelector('.remove-pricing').style.display = 'inline-block';

        container.appendChild(newPricing);

        pricingIndex++;
    });

    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-pricing')) {
            e.target.closest('.pricing-item').remove();
        }
    });
</script>
@endsection
