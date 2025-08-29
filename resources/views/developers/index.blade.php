@extends('admin.layouts.app')

@section('content')

<div class="row">
    <div class="col-xl">
        <div class="card mb-4">

            {{-- عنوان الصفحة وزر الإضافة --}}
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="text-center my-4">
                    {{ trans('main_trans.developers') }}
                    @if($company)
                        - {{ $company->company_name_ar }}
                    @endif
                </h3>
                <div class="text-right mb-3">
                    <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#addDeveloperForm" aria-expanded="false" aria-controls="addDeveloperForm">
                        <i class="fas fa-plus-circle"></i> {{ trans('main_trans.add') }}
                    </button>
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

                {{-- فورم الإضافة --}}
                <div class="collapse" id="addDeveloperForm">
                    <div class="card card-body">
                        <form action="{{ route('developers.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="row mb-3">
                                <div class="col">
                                    <label>{{ trans('main_trans.name_en') }}</label>
                                    <input type="text" name="name_en" class="form-control" required>
                                </div>
                                <div class="col">
                                    <label>{{ trans('main_trans.name_ar') }}</label>
                                    <input type="text" name="name_ar" class="form-control" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label>{{ trans('main_trans.email') }}</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label>{{ trans('main_trans.phone') }}</label>
                                <input type="text" name="phone" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label>{{ trans('main_trans.image') }}</label>
                                <input type="file" name="image" class="form-control" accept="image/*">
                            </div>

                            <div class="mb-3">
                                <label>الشركة</label>
                                <select name="company_id" class="form-control">
                                    <option value="">اختر الشركة</option>
                                    @if($company)
                                        <option value="{{ $company->id }}" selected>{{ $company->company_name_ar }}</option>
                                    @else
                                        @foreach(\App\Models\RealEstateCompany::all() as $realEstateCompany)
                                            <option value="{{ $realEstateCompany->id }}">{{ $realEstateCompany->company_name_ar }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <button type="submit" class="btn btn-success">{{ trans('main_trans.submit') }}</button>
                        </form>
                    </div>
                </div>

                {{-- جدول المطورين --}}
                <div class="table-responsive mb-3">
                    <table class="table table-bordered text-center">
                        <thead>
                            <tr>
                                <th>{{ trans('main_trans.id') }}</th>
                                <th>{{ trans('main_trans.image') }}</th>
                                <th>{{ trans('main_trans.name') }}</th>
                                <th>{{ trans('main_trans.email') }}</th>
                                <th>{{ trans('main_trans.phone') }}</th>
                                <th>الشركة</th>
                                <th>{{ trans('main_trans.processes') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($developers as $developer)
                            <tr>
                                <td>{{ $developer->id }}</td>
                                <td>
                                    @if($developer->image)
                                        <img src="{{ asset('storage/developers/' . $developer->image) }}" alt="image" width="60" height="60" class="rounded-circle">
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>{{ $developer->name_en }} / {{ $developer->name_ar }}</td>
                                <td>{{ $developer->email }}</td>
                                <td>{{ $developer->phone }}</td>
                                <td>
                                    @if($developer->company)
                                        {{ $developer->company->company_name_ar }}
                                    @else
                                        <span class="text-muted">غير محدد</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('developers.edit', $developer->id) }}" class="btn btn-info btn-sm">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <form action="{{ route('developers.destroy', $developer->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $developers->links('pagination::bootstrap-5') }}
                </div>

            </div>
        </div>
    </div>
</div>

@endsection
