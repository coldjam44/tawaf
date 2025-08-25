@extends('admin.layouts.app')


@section('content')
<h3 class="text-center my-4"> {{ trans('main_trans.customerreview') }}</h3>

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

            <form action="{{ route('customerreviews.store') }}" method="POST">
                @csrf
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="name_ar" class="mr-sm-2">{{ trans('Counters_trans.name_ar') }}
                            :</label>
                            <input id="name_ar" type="text" name="name_ar" class="form-control">
                        </div>
                    <div class="col-md-6">
                        <label for="name_en" class="mr-sm-2">{{ trans('Counters_trans.name_en') }}
                            :</label>
                        <input id="name_en" type="text" class="form-control" name="name_en" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="description_ar" class="mr-sm-2">{{ trans('Counters_trans.description_ar') }}:</label>
                        <textarea id="description_ar" name="description_ar" class="form-control"></textarea>
                    </div>
                    <div class="col-md-6">
                        <label for="description_en" class="mr-sm-2">{{ trans('Counters_trans.description_en') }}:</label>
                        <textarea id="description_en" name="description_en" class="form-control" required></textarea>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="comment_ar" class="mr-sm-2">{{ trans('Counters_trans.comment_ar') }}
                            :</label>
                        <input id="comment_ar" type="text" name="comment_ar" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label for="comment_en" class="mr-sm-2">{{ trans('Counters_trans.comment_en') }}
                            :</label>
                        <input id="comment_en" type="text" class="form-control" name="comment_en" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="possition_ar" class="mr-sm-2">{{ trans('Counters_trans.position_ar') }}
                            :</label>
                        <input id="possition_ar" type="text" name="possition_ar" class="form-control">
                    </div>
                    <div class="col-md-6">

                    <label for="possition_en" class="mr-sm-2">{{ trans('Counters_trans.position_en') }}
                        :</label>
                    <input id="possition_en" type="text" class="form-control" name="possition_en" required>
                </div>
                </div>

                <div class="mb-3">
                    <label for="rate" class="mr-sm-2">{{ trans('Counters_trans.rate') }}
                        :</label>
                    <input id="rate" type="text" name="rate" class="form-control">
                    <br>
                    <span>ملاحظة: يجب أن يكون  التقييم بين 1 و 5.
                        .</span>
                </div>


                <div class="d-flex justify-content-end">
                    <a href="{{ route('customerreviews.index') }}" class="btn btn-secondary me-2">{{ trans('Counters_trans.Close') }}</a>
                    <button type="submit" class="btn btn-success">{{ trans('Counters_trans.Submit') }}</button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection
