@extends('admin.layouts.app')

@section('content')
    <div class="row">
        <div class="col-xl">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="text-center my-4">{{ trans('Counters_trans.Edit_customerreview') }}</h3>
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

                    <form action="{{ route('customerreviews.update', $customerreview->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col">
                                <label for="name_ar" class="mr-sm-2">{{ trans('Counters_trans.name_ar') }}
                                    :</label>
                                <input id="name_ar" type="text" name="name_ar" class="form-control"
                                    value="{{ $customerreview->name_ar }}" required>
                                <input id="id" type="hidden" name="id" class="form-control"
                                    value="{{ $customerreview->id }}">
                            </div>
                            <div class="col">
                                <label for="name_en" class="mr-sm-2">{{ trans('Counters_trans.name_en') }}
                                    :</label>
                                <input type="text" class="form-control" value="{{ $customerreview->name_en }}"
                                    name="name_en" required>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col">
                                <label for="description_ar"
                                    class="mr-sm-2">{{ trans('Counters_trans.description_ar') }}:</label>
                                <textarea id="description_ar" name="description_ar" class="form-control" required>{{ $customerreview->description_ar }}</textarea>
                                <input id="id" type="hidden" name="id" class="form-control"
                                    value="{{ $customerreview->id }}">
                            </div>
                            <div class="col">
                                <label for="description_en"
                                    class="mr-sm-2">{{ trans('Counters_trans.description_en') }}:</label>
                                <textarea id="description_en" name="description_en" class="form-control" required>{{ $customerreview->description_en }}</textarea>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col">
                                <label for="comment_ar" class="mr-sm-2">{{ trans('Counters_trans.comment_ar') }}
                                    :</label>
                                <input id="comment_ar" type="text" name="comment_ar" class="form-control"
                                    value="{{ $customerreview->comment_ar }}" required>
                                <input id="id" type="hidden" name="id" class="form-control"
                                    value="{{ $customerreview->id }}">
                            </div>
                            <div class="col">
                                <label for="comment_en" class="mr-sm-2">{{ trans('Counters_trans.comment_en') }}
                                    :</label>
                                <input type="text" class="form-control" value="{{ $customerreview->comment_en }}"
                                    name="comment_en" required>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col">
                                <label for="possition_ar" class="mr-sm-2">{{ trans('Counters_trans.position_ar') }}
                                    :</label>
                                <input id="possition_ar" type="text" name="possition_ar" class="form-control"
                                    value="{{ $customerreview->possition_ar }}" required>
                                <input id="id" type="hidden" name="id" class="form-control"
                                    value="{{ $customerreview->id }}">
                            </div>
                            <div class="col">
                                <label for="possition_en" class="mr-sm-2">{{ trans('Counters_trans.position_en') }}
                                    :</label>
                                <input type="text" class="form-control" value="{{ $customerreview->possition_en }}"
                                    name="possition_en" required>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col">
                                <label for="rate" class="mr-sm-2">{{ trans('Counters_trans.rate') }}
                                    :</label>
                                <input id="rate" type="text" name="rate" class="form-control"
                                    value="{{ $customerreview->rate }}" required>
                                <input id="id" type="hidden" name="id" class="form-control"
                                    value="{{ $customerreview->id }}">
                            </div>
                        </div>


                        <div class="mt-4">
                            <button type="submit" class="btn btn-success">{{ trans('Counters_trans.Update') }}</button>

                            <a href="{{ route('customerreviews.index') }}"
                                class="btn btn-secondary">{{ trans('Counters_trans.Back') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
