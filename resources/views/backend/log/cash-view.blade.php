@extends('backend.layouts.app')

@section('page-title', trans('app.pincodes'))
@section('page-heading', trans('app.pincodes'))

@section('content')

    <section class="content-header">
        @include('backend.partials.messages')
    </section>

    <section class="content">

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">@lang('app.cash_in_out_logs')</h3>
            </div>
            <div class="box-body">
                <table class="table table-bordered table-striped" id="agents-table">
                    <thead>
                        <tr>
                            <th>@lang('app.username')</th>
                            <th>@lang('app.in')</th>
                            <th>@lang('app.out')</th>
                            <th>@lang('app.date_time')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cashLogs as $cashLog)
                            <tr>
                                <td><a
                                        href="{{ route('backend.user.edit', $cashLog->user->id) }}">{{ $cashLog->user->username }}</a>
                                </td>
                                <td class="text-green">
                                    {{ $cashLog->type == 'add' ? number_format($cashLog->summ, 2, '.', '') : 0 }}</td>
                                <td class="text-red">
                                    {{ $cashLog->type == 'out' ? number_format($cashLog->summ, 2, '.', '') : 0 }}</td>
                                <td>{{ $cashLog->created_at }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">@lang('app.no_data')</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <thead>
                        <tr>
                            <th>@lang('app.username')</th>
                            <th>@lang('app.in')</th>
                            <th>@lang('app.out')</th>
                            <th>@lang('app.date_time')</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </section>

@stop

@section('scripts')
    <script></script>
@stop
