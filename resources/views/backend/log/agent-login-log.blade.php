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
                <h3 class="box-title">@lang('app.agent_login_logo')</h3>
            </div>
            <div class="box-body">
                <table class="table table-bordered table-striped" id="agents-table">
                    <thead>
                        <tr>
                            <th>@lang('app.username')</th>
                            <th>@lang('app.ip_address')</th>
                            <th>@lang('app.action')</th>
                            <th>@lang('app.log_time')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($agentLogs as $agentLog)
                            <tr>
                               <td><a href="{{route('backend.user.edit', $agentLog->userdata->id)}}">{{$agentLog->userdata->username}}</a></td>
                               <td>{{$agentLog->ip_address}}</td>
                               <td>{{$agentLog->description}}</td>
                               <td>{{$agentLog->created_at}}</td>
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
                            <th>@lang('app.ip_address')</th>
                            <th>@lang('app.action')</th>
                            <th>@lang('app.log_time')</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </section>

@stop

@section('scripts')
<script>
 
</script>
@stop
