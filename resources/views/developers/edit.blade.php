@extends('admin.layouts.app')

@section('content')
<div class="row">
    <div class="col-xl">
        <div class="card mb-4">
            <div class="card-header">
                <h3>{{ trans('main_trans.edit_developer') }}</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('developers.update', $developer->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label>{{ trans('main_trans.name_en') }}</label>
                        <input type="text" name="name_en" class="form-control" value="{{ $developer->name_en }}" required>
                    </div>
                    <div class="mb-3">
                        <label>{{ trans('main_trans.name_ar') }}</label>
                        <input type="text" name="name_ar" class="form-control" value="{{ $developer->name_ar }}" required>
                    </div>
                    <div class="mb-3">
                        <label>{{ trans('main_trans.email') }}</label>
                        <input type="email" name="email" class="form-control" value="{{ $developer->email }}" required>
                    </div>
                    <div class="mb-3">
                        <label>{{ trans('main_trans.phone') }}</label>
                        <input type="text" name="phone" class="form-control" value="{{ $developer->phone }}" required>
                    </div>

                    <div class="mb-3">
                        <label>الشركة</label>
                        <select name="company_id" class="form-control">
                            <option value="">اختر الشركة</option>
                            @foreach(\App\Models\RealEstateCompany::all() as $realEstateCompany)
                                <option value="{{ $realEstateCompany->id }}" {{ $developer->company_id == $realEstateCompany->id ? 'selected' : '' }}>
                                    {{ $realEstateCompany->company_name_ar }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn btn-success">{{ trans('main_trans.update') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
