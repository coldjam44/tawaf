@extends('admin.layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>{{ trans('main_trans.hotels') }}</h2>
            <a href="{{ route('hotels.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle"></i> {{ trans('Counters_trans.add_Grade') }}
            </a>
        </div>

        <div class="card shadow-sm overflow-auto">
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <table class="table table-striped table-bordered">
                    <thead class="table-dark text-center">
                        <tr>
                            <th>#</th>
                            <th>{{ trans('Counters_trans.Name') }}</th>
                            <th>{{ trans('Counters_trans.possition') }}</th>
                            <th>{{ trans('Counters_trans.rate') }}</th>
                            <th>{{ trans('Counters_trans.image') }}</th>
                            <th>{{ trans('Counters_trans.overview') }}</th>
                                                      <th>{{ trans('Counters_trans.pricing') }}</th> <!-- Added pricing column -->

                            <th>{{ trans('Counters_trans.location_map') }}</th>
                            <th>{{ trans('Counters_trans.amenity') }}</th>
                            <th>{{ trans('Counters_trans.Processes') }}</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        @forelse ($hotels as $hotel)
                            <tr>
        <td>{{ ($hotels->currentPage() - 1) * $hotels->perPage() + $loop->iteration }}</td>
                                <td>{{ App::getLocale() == 'ar' ? $hotel->name_ar : $hotel->name_en }}</td>
                                <td>{{ App::getLocale() == 'ar' ? $hotel->possition_ar : $hotel->possition_en }}</td>
                                <td>{{ $hotel->rate }}</td>

                                <td>
                                    @if ($hotel->image)
                                        @php
                                            $images = json_decode($hotel->image);
                                            $totalImages = count($images);
                                        @endphp
                                        @foreach ($images as $index => $image)
                                            @if ($index < 2)
                                                <img src="{{ asset('hotel/' . $image) }}" width="50" height="50"
                                                    style="margin-right: 5px;">
                                            @endif
                                        @endforeach
                                        @if ($totalImages > 2)
                                            <span>+{{ $totalImages - 2 }} more</span>
                                        @endif
                                    @endif
                                </td>

                                <td>
                                    <div style="max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                        {{ App::getLocale() == 'ar' ? $hotel->overview_ar : $hotel->overview_en }}
                                    </div>
                                    <button class="btn btn-info btn-sm mt-2" onclick="showDetails('{{ App::getLocale() == 'ar' ? $hotel->overview_ar : $hotel->overview_en }}')">
                                        عرض المزيد
                                    </button>
                                </td>
                                
                               

                                <!-- Add the pricing loop here -->
                              <td>
    @if ($hotel->pricings && $hotel->pricings->isNotEmpty())
        @foreach ($hotel->pricings as $pricing)
            <div>
                <p>
                    <strong>{{ trans('main_trans.from') }}:</strong> {{ $pricing->start_date }} 
                    <strong>{{ trans('main_trans.to') }}:</strong> {{ $pricing->end_date }}
                </p>
                <p>
                    <strong>{{ trans('main_trans.price') }}:</strong> {{ $pricing->price }} 
                </p>
                @if ($pricing->discount_price)
                    <p class="text-success">
                        <strong>{{ trans('main_trans.discount') }}:</strong> {{ $pricing->discount_price }} 
                    </p>
                @else
                    <p class="text-muted">
                        {{ trans('main_trans.no_discount') }}
                    </p>
                @endif
                <hr>
            </div>
        @endforeach
    @else
        <p>{{ trans('main_trans.no_pricing') }}</p> <!-- يمكنك تخصيص الرسالة هنا -->
    @endif
</td>
                              
                               <td>{{ $hotel->location_map }}</td>

                                <td>
                                    <div class="dropdown position-relative">
                                        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                          All Amenities
                                        </button>
                                        <ul class="dropdown-menu">
                                          @foreach ($hotel->amenities as $amenity)
                                              <li><a class="dropdown-item">{{ App::getLocale() == 'ar' ? $amenity->name_ar : $amenity->name_en }}</a></li>
                                          @endforeach
                                        </ul>
                                      </div>
                                </td>



                                <td>
                                    <a href="{{ route('hotels.edit', $hotel->id) }}" class="btn btn-info btn-sm">
                                                    <i class="fas fa-edit"></i> {{ trans('Counters_trans.Edit') }}
                                                </a>
                                    <form action="{{ route('hotels.destroy', $hotel->id) }}" method="POST"
                                        style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"
                                            onclick="return confirm('{{ trans('Counters_trans.confirm_delete') }}')">
                                            <i class="fas fa-trash"></i> {{ trans('Counters_trans.Delete') }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="15">{{ trans('Counters_trans.no_data') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-3">
                    {{ $hotels->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>

    <script>
        function showDetails(content) {
            alert(content); // عرض كامل النص في نافذة منبثقة (يمكنك تخصيصها)
        }
    </script>
@endsection
