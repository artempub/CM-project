@extends('backend.layouts.app')

@section('page-title', 'Edit Cashback')
@section('page-heading', $cashback->title)

@section('content')

<section class="content-header">
  @include('backend.partials.messages')
</section>

<section class="content">

  <div class="box box-default">
    {!! Form::open(['route' => array('backend.cashback.update', $cashback->id), 'files' => true, 'id' => 'user-form']) !!}
    <div class="box-header with-border">
      <h3 class="box-title">Edit Cashback</h3>
    </div>

    <div class="box-body">
      <div class="row">
        @include('backend.cashback.partials.base', ['edit' => true])
      </div>
    </div>

    <div class="box-footer">
      <button type="submit" class="btn btn-primary">
        Edit Cashback
      </button>
      {{-- <a href="{{ route('backend.cashback.delete', $cashback->id) }}" class="btn btn-danger" data-method="DELETE" data-confirm-title="@lang('app.please_confirm')" data-confirm-text="Are you sure you want to delete?" data-confirm-delete="@lang('app.yes_delete_him')">
        delete
      </a> --}}
    </div>
    {!! Form::close() !!}
  </div>
</section>

@stop