@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="align-items-center">
			<h1 class="h3">{{translate('All Products')}}</h1>
	</div>
</div>

<div class="row">
	<div class="col-md-7">
		<div class="card">
		    <div class="card-header row gutters-5">
				<div class="col text-center text-md-left">
					<h5 class="mb-md-0 h6">Products</h5>
				</div>
				<div class="col-md-4">
					<form class="" id="sort_brands" action="" method="GET">
						<div class="input-group input-group-sm">
					  		<input type="text" class="form-control" id="search" name="search"@isset($sort_search) value="{{ $sort_search }}" @endisset placeholder="{{ translate('Type name & Enter') }}">
						</div>
					</form>
				</div>
		    </div>
		    <div class="card-body">
		        <table class="table aiz-table mb-0">
		            <thead>
		                <tr>
		                    <th>#</th>
		                    <th>Name</th>
		                    <th>Price</th>
		                    <th>Point</th>
		                    {{-- <th class="text-right">{{translate('Options')}}</th> --}}
		                </tr>
		            </thead>
		            <tbody>
                        @foreach($products as $key => $product)
                        <tr>
                            <td>{{ ($key+1) + ($products->currentPage() - 1)*$products->perPage() }}</td>
                            <td>{{ $product->getTranslation('name') }}</td>
                            <td>{{ $product->purchase_price }}</td>
                            <td>{{ $product->club_point }}</td>

                            {{-- <td class="text-right">
                                <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{route('products.edit', ['id'=>$product->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}" title="{{ translate('Edit') }}">
                                    <i class="las la-edit"></i>
                                </a>
                                <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{route('products.destroy', $product->id)}}" title="{{ translate('Delete') }}">
                                    <i class="las la-trash"></i>
                                </a>
                            </td> --}}
                        </tr>
                    @endforeach

		            </tbody>
		        </table>
		        <div class="aiz-pagination">
                	{{ $products->appends(request()->input())->links() }}
            	</div>
		    </div>
		</div>
	</div>
	<div class="col-md-5">
		<div class="card">
			<div class="card-header">
				<h5 class="mb-0 h6">Set Point for all Products</h5>
			</div>
			<div class="card-body">
				<form action="{{ route('club_point_system.store') }}" method="POST">
					@csrf
					<div class="form-group mb-3">
						<label for="name">Set Point for $1.000</label>
						<input type="number" placeholder="Points" name="points" class="form-control" required>
					</div>


					<div class="form-group mb-3 text-right">
						<button type="submit" class="btn btn-primary">{{translate('Save')}}</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<div class="row">

	<div class="col-md-5 offset-md-7">
		<div class="card">
			<div class="card-header">
				<h5 class="mb-0 h6">Convert point to wallet</h5>
			</div>
			<div class="card-body">
				<form action="{{ route('point_convert_wallet.store') }}" method="POST">
					@csrf
					<div class="form-group mb-3">
						<label for="name">Set Point for $1.000</label>
						<input type="number" placeholder="Points" name="points" class="form-control" required>
					</div>


					<div class="form-group mb-3 text-right">
						<button type="submit" class="btn btn-primary">{{translate('Save')}}</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection

@section('script')
<script type="text/javascript">
    function sort_brands(el){
        $('#sort_brands').submit();
    }
</script>
@endsection
