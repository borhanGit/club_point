@extends('frontend.layouts.user_panel')

@section('panel_content')
    <div class="aiz-titlebar mt-2 mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('Support Ticket') }}</h1>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="bg-grad-3 text-white rounded-lg mb-4 overflow-hidden">
                <div class="px-3 pt-3">
                    <div class="h3 fw-700" style="text-align: center">{{$points->points}} Points = ${{$points->amount}} Wallet Money</div>
                    <div class="opacity-50"></div>
                </div>

            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">Earning Point History </h5>
        </div>
          <div class="card-body">
              <table class="table aiz-table mb-0">
                  <thead>
                      <tr>
                          <th data-breakpoints="lg">Order Code</th>
                          <th data-breakpoints="lg">Points</th>
                          <th>Converted</th>
                          <th>Date</th>
                          <th data-breakpoints="lg">Action</th>
                      </tr>
                  </thead>
                  <tbody>

                      @foreach ($getData as $key => $data)
                        @if ($data->order->user_id == Auth::user()->id)
                        {{-- @php
                            $check = \App\Models\ConvertWallet::where('user_id', $data->order->user_id)->whereNull('product_id')->get();
                        @endphp
                        {{$check}} --}}
                        <tr>
                            <td>{{ $data->order->code }}</td>
                            <td>{{ $data->order->created_at->format('Y-m-d') }}</td>

                            @if ($data->convert == 1)
                              <td><h1 class="badge bg-success">YES</h1></td>
                              <td>0 pts</td>
                              <td>
                                <a class="btn btn-success btn-sm" style="pointer-events: none">
                                    Done
                                </a>
                              </td>
                            @else
                                <td><h1 class="badge bg-primary">NO</h1></td>
                                <td>{{ $data->product->club_point }}</td>
                                <td>
                                    <a class="btn btn-danger btn-sm" href="{{route('point_convert_wallet',['points'=>$data->product->club_point,'product_id'=>$data->product_id,'orderDId'=>$data->id])}}">
                                        Convert Now
                                    </a>
                                </td>
                            @endif




                        </tr>

                        @else

                        @endif

                      @endforeach
                  </tbody>
              </table>
              <div class="aiz-pagination">
                  {{-- {{ $getData->links() }} --}}
              </div>
          </div>
    </div>
@endsection

