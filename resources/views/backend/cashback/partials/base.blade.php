<div class="col-md-6">
    <div class="form-group">
        <label>@lang('app.sum')</label>
        <input type="number" step="0.0000001" class="form-control" name="sum" value="{{ $edit ? $cashback->sum : old('sum') }}" required >
    </div>
</div>
<div class="col-md-6">
    <div class="form-group">
        <label>@lang('app.type')</label>
        {!! Form::select('type', \VanguardLTE\Cashback::$values['type'], $edit ? $cashback->type : old('type'), ['class' => 'form-control', 'disabled' => true]) !!}
    </div>
</div>
<div class="col-md-6">
    <div class="form-group">
        <label>@lang('app.bonus')</label>
        @php
            $bonuses = array_combine(\VanguardLTE\Cashback::$values['bonus'], \VanguardLTE\Cashback::$values['bonus']);
        @endphp
        {!! Form::select('bonus', $bonuses, $edit ? $cashback->bonus : old('bonus'), ['class' => 'form-control']) !!}
    </div>
</div>

