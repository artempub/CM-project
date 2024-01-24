@extends('backend.layouts.app')

@section('page-title', $role->name .' '. trans('app.tree'))
@section('page-heading', $role->name .' '. trans('app.tree'))

@section('content')

<section class="content-header">
    @include('backend.partials.messages')
</section>

<section class="content">
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">{{ $role->name }} @lang('app.tree')</h3>
        </div>
        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            @if( auth()->user()->hasRole(['admin','distributor']) )
                            <th>@lang('app.distributor')</th>
                            @endif
                            <th>@lang('app.shop')</th>
                            <th>@lang('app.agent')</th>
                            <th>@lang('app.user')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($agents))
                        @foreach ($agents as $agent)
                        <tr>
                            @if($agent['agent']->hasRole('distributor'))
                            @include('backend.agent.partials.distributor', ['distributor' => $agent['agent'], 'prev' => $agent['prev']])
                            @endif
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="6">@lang('app.no_data')</td>
                        </tr>
                        @endif
                    </tbody>
                    <thead>
                        <tr>
                            @if( auth()->user()->hasRole(['admin','distributor']) )
                            <th>@lang('app.distributor')</th>
                            @endif
                            <th>@lang('app.shop')</th>
                            <th>@lang('app.agent')</th>
                            <th>@lang('app.user')</th>
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