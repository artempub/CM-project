@extends('backend.layouts.app')

@section('page-title', 'cashback')
@section('page-heading', 'cashback')

@section('content')

	<section class="content-header">
		@include('backend.partials.messages')
	</section>

	<section class="content">
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title">Cashback</h3>
                <div class="pull-right box-tools">
                    @if($shop)
                        @if( $shop->cashback_active )
                            <a href="{{ route('backend.cashback.status', 'disable') }}" class="btn btn-danger btn-sm">@lang('app.disable')</a>
                        @else
                            <a href="{{ route('backend.cashback.status', 'activate') }}" class="btn btn-success btn-sm">@lang('app.active')</a>
                        @endif
                    @endif
                </div>
			</div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
					<thead>
					<tr>
						<th>@lang('app.pay')</th>
						<th>@lang('app.sum')</th>
						<th>@lang('app.type')</th>
						<th>@lang('app.bonus')</th>
					</tr>
					</thead>
					<tbody>
					@if (count($cashbacks))
						@foreach ($cashbacks as $cashback)
							@include('backend.cashback.partials.row')
						@endforeach
					@else
						<tr><td colspan="6">@lang('app.no_data')</td></tr>
					@endif
					</tbody>
					<thead>
					<tr>
						<th>@lang('app.pay')</th>
						<th>@lang('app.sum')</th>
						<th>@lang('app.type')</th>
						<th>@lang('app.bonus')</th>
					</tr>
					</thead>
                            </table>
                        </div>
                    </div>
		</div>
	</section>

@stop

@section('scripts')
	<script>
	</script>
@stop
