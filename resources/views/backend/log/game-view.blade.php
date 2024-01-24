@extends('backend.layouts.app')

@section('page-title', 'Game Log')
@section('page-heading', 'Game Log')

@section('content')

    <section class="content-header">
        @include('backend.partials.messages')
    </section>

    <section class="content">

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">@lang('app.game_log')</h3>
            </div>
            <div class="box-body">
                <table class="table table-bordered table-striped" id="agents-table">
                    <thead>
                        <tr>
                            <th>@lang('app.username')</th>
                            <th>@lang('app.game_name')</th>
                            <th>@lang('app.bet')</th>
                            <th>@lang('app.win')</th>
                            <th>@lang('app.begin_money')</th>
                            <th>@lang('app.end_money')</th>
                            <th>@lang('app.date_time')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($gameLogs as $gameLog)
                            <tr>
                                <td>{{ $gameLog->user->username }}</td>
                                <td>{{ $gameLog->game }}</td>
                                <td>{{ number_format($gameLog->bet, 2, '.', '') }}</td>
                                <td>{{ number_format($gameLog->win, 2, '.', '') }}</td>
                                <td>{{ number_format($gameLog->balance + $gameLog->bet - $gameLog->win, 2, '.', '') }}</td>
                                <td>{{ number_format($gameLog->balance, 2, '.', '') }}</td>
                                <td>{{ $gameLog->date_time }}</td>
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
                            <th>@lang('app.game_name')</th>
                            <th>@lang('app.bet')</th>
                            <th>@lang('app.win')</th>
                            <th>@lang('app.begin_money')</th>
                            <th>@lang('app.end_money')</th>
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
