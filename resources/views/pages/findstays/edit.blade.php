@extends('admin.layouts.app')

@section('content')
<div class="row">
    <div class="col-xl">
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="text-center my-4">{{ trans('Counters_trans.Edit_FindStay') }}</h3>
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

                <form action="{{ route('findstays.update', $findstay->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col">
                            <label for="check_in_ar" class="mr-sm-2">{{ trans('Counters_trans.check_in_ar') }}:</label>
                            <input id="check_in_ar" type="text" name="check_in_ar" class="form-control" value="{{ $findstay->check_in_ar }}" required>
                        </div>
                        <div class="col">
                            <label for="check_in_en" class="mr-sm-2">{{ trans('Counters_trans.check_in_en') }}:</label>
                            <input id="check_in_en" type="text" name="check_in_en" class="form-control" value="{{ $findstay->check_in_en }}" required>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col">
                            <label for="check_out_ar" class="mr-sm-2">{{ trans('Counters_trans.check_out_ar') }}:</label>
                            <input id="check_out_ar" type="text" name="check_out_ar" class="form-control" value="{{ $findstay->check_out_ar }}" required>
                        </div>
                        <div class="col">
                            <label for="check_out_en" class="mr-sm-2">{{ trans('Counters_trans.check_out_en') }}:</label>
                            <input id="check_out_en" type="text" name="check_out_en" class="form-control" value="{{ $findstay->check_out_en }}" required>
                        </div>
                    </div>


                    <div class="row mt-3">
                        <div class="col">
                            <label for="numberofroom" class="mr-sm-2">{{ trans('Counters_trans.numberofroom') }}:</label>
                            <input id="numberofroom" type="text" name="numberofroom" class="form-control" value="{{ $findstay->numberofroom }}">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col">
                            <label for="numberofchild" class="mr-sm-2">{{ trans('Counters_trans.numberofchild') }}:</label>
                            <input id="numberofchild" type="text" name="numberofchild" class="form-control" value="{{ $findstay->numberofchild }}">
                        </div>
                    </div>
                  
                   <div class="row mt-3">
                        <div class="col">
                            <label for="age_child" class="mr-sm-2">{{ trans('Counters_trans.age_child') }}:</label>
                            <input id="age_child" type="text" name="age_child[]" class="form-control" value="{{ $findstay->age_child }}">
                        </div>
                    </div>
                  
                    <div class="row mt-3">
                        <div class="col">
                            <label for="numberofadult" class="mr-sm-2">{{ trans('Counters_trans.numberofadult') }}:</label>
                            <input id="numberofadult" type="text" name="numberofadult" class="form-control" value="{{ $findstay->numberofadult }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="place_id">{{ trans('Counters_trans.place') }}</label>
                        <select name="place_id" id="place_id" class="form-control form-select">
                            <option value="">{{ trans('Counters_trans.place') }}</option>
                            @foreach ($places as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('place_id', $findstay->place_id ?? '') == $category->id ? 'selected' : '' }}>
                                    {{ App::getLocale() === 'ar' ? $category->name_ar : $category->name_en }}
                                </option>
                            @endforeach
                        </select>
                    </div>





                   

                    <div class="mt-4">
                        <button type="submit" class="btn btn-success">{{ trans('Counters_trans.Update') }}</button>

                                                <a href="{{ route('findstays.index') }}" class="btn btn-secondary">{{ trans('Counters_trans.Back') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
