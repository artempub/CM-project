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
                <h3 class="box-title">@lang('app.user_login_logo')</h3>
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
                        @forelse($userLogs as $userLog)
                            <tr>
                               <td><a href="{{route('backend.user.edit', $userLog->userdata->id)}}">{{$userLog->userdata->username}}</a></td>
                               <td>{{$userLog->ip_address}}</td>
                               <td>{{$userLog->description}}</td>
                               <td>{{$userLog->created_at}}</td>
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
