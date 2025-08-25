@extends('admin.layouts.app')


@section('content')
<h3 class="text-center my-4"> {{ trans('main_trans.findstays') }}</h3>

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

            <form action="{{ route('findstays.store') }}" method="POST">
                @csrf
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="check_in_ar" class="form-label">{{ trans('Counters_trans.check_in_ar') }}</label>
                        <input type="text" name="check_in_ar" class="form-control" id="check_in_ar" placeholder="{{ trans('Counters_trans.check_in_ar') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="check_in_en" class="form-label">{{ trans('Counters_trans.check_in_en') }}</label>
                        <input type="text" name="check_in_en" class="form-control" id="check_in_en" placeholder="{{ trans('Counters_trans.check_in_en') }}" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="check_out_ar" class="form-label">{{ trans('Counters_trans.check_out_ar') }}</label>
                        <input type="text" name="check_out_ar" class="form-control" id="check_out_ar" placeholder="{{ trans('Counters_trans.check_out_ar') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="check_out_en" class="form-label">{{ trans('Counters_trans.check_out_en') }}</label>
                        <input type="text" name="check_out_en" class="form-control" id="check_out_en" placeholder="{{ trans('Counters_trans.check_out_en') }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="place_id">{{ trans('Counters_trans.place') }}</label>
                    <select name="place_id" id="place_id" class="form-control form-select">
                        <option value="">{{ trans('Counters_trans.place') }}</option>
                        @foreach ($places as $category)
                            <option value="{{ $category->id }}"
                                {{ isset($selectedPlaceId) && $selectedPlaceId == $category->id ? 'selected' : '' }}>
                                {{ App::getLocale() === 'ar' ? $category->name_ar : $category->name_en }}
                            </option>
                        @endforeach
                    </select>
                </div>


                {{-- <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="place_ar" class="form-label">{{ trans('Counters_trans.place_ar') }}</label>
                        <input type="text" name="place_ar" class="form-control" id="place_ar" placeholder="{{ trans('Counters_trans.place_ar') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="place_en" class="form-label">{{ trans('Counters_trans.place_en') }}</label>
                        <input type="text" name="place_en" class="form-control" id="place_en" placeholder="{{ trans('Counters_trans.place_en') }}" required>
                    </div>
                </div> --}}

                <div class="mb-3">
                    <label for="numberofroom" class="form-label">{{ trans('Counters_trans.numberofrooms') }}</label>
                    <input type="text" name="numberofroom" class="form-control" id="numberofroom" placeholder="{{ trans('Counters_trans.numberofrooms') }}">
                </div>
                <div class="mb-3">
                    <label for="numberofadult" class="form-label">{{ trans('Counters_trans.numberofadults') }}</label>
                    <input type="text" name="numberofadult" class="form-control" id="numberofadult" placeholder="{{ trans('Counters_trans.numberofadults') }}">
                </div>
                <div class="mb-3">
                    <label for="numberofchild" class="form-label">{{ trans('Counters_trans.numberofchilds') }}</label>
                    <input type="text" name="numberofchild" class="form-control" id="numberofchild" placeholder="{{ trans('Counters_trans.numberofchilds') }}">
                </div>
              
               <div class="row">
                    <div class="col">
                        <label for="age_child" class="mr-sm-2">{{ trans('Counters_trans.age_child') }} :</label>
                        <input id="age_child" type="text" name="age_child[]" class="form-control" placeholder="{{ trans('Counters_trans.age_child') }}">
                    </div>

                </div>


                <div class="d-flex justify-content-end">
                    <a href="{{ route('findstays.index') }}" class="btn btn-secondary me-2">{{ trans('Counters_trans.Close') }}</a>
                    <button type="submit" class="btn btn-success">{{ trans('Counters_trans.Submit') }}</button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection
