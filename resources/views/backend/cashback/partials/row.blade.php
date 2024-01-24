<tr>
	<td><a href="{{ route('backend.cashback.edit', $cashback->id) }}">{{ mb_convert_case($cashback->pay, MB_CASE_TITLE) }}</a></td>
	<td>{{ $cashback->sum }}</td>
	<td>{{ \VanguardLTE\Cashback::$values['type'][$cashback->type] }}</td>
	<td>{{ $cashback->bonus }}</td>

</tr>
