@extends('backend.layouts.app')

@section('content')

    <div class="col-lg-6  mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">Wallet System Activation</h5>
            </div>
            <div class="card-body">
                <div class="custom-control custom-switch">
                    @if (Session::has('wallet')=="on")
                    <input type="checkbox" class="custom-control-input redirectToUrlDe"  checked data-redirect-url="{{ route("walletDeactivation") }}" id="customSwitch1">
                    <label class="custom-control-label" for="customSwitch1"></label>
                    @else
                    <input type="checkbox" class="custom-control-input redirectToUrl"   data-redirect-url="{{ route("walletActivation") }}" id="customSwitch1">
                    <label class="custom-control-label" for="customSwitch1"></label>
                    @endif

                  </div>

            </div>
        </div>
    </div>

@endsection
