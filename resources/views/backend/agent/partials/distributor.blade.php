<td rowspan="{{ $distributor->getRowspan() }}">
    <a href="{{ route('backend.user.edit', $distributor->id) }}">
        @for($i=0;$i<$prev;$i++)
            {{'-'}}
        @endfor
        {{ $distributor->username ?: trans('app.n_a') }}
    </a>
</td>
@if( $distributor->shops() && count($distributor->shops()) )
    @if($shops = $distributor->rel_shops)
        @foreach($shops AS $shop)
            @if($shop = $shop->shop)
                <td rowspan="{{ $shop->getRowspan($distributor->id) }}">
                    <a href="{{ route('backend.shop.edit', $shop->id) }}">{{ $shop->name }}</a>
                </td>

                @if( $agents = $shop->getUsersByRoleAndParentId('agent', $distributor->id))
                    @if(count($agents))
                        @foreach($agents AS $agent)
                            {{-- @if( $agent->parent_id == $distributor->id ) --}}
                            <td>
                                <a href="{{ route('backend.agent.edit', $agent->id) }}">
                                    {{ $agent->username ?: trans('app.n_a') }}
                                </a>
                                @if( $innerAgents = $agent->getInnerUsers() )
                                    @if(count($innerAgents))
                                    <a href="{{ route('backend.agent.edit', $agent->id) }}" class="pull-right">
                                        >> @lang('app.more')
                                    </a>
                                    @endif
                                @endif
                            </td>
                            <td>
                                <a href="{{  route('backend.profile.setshop', ['shop_id' => $shop->id, 'to' => route('backend.user.list', ['role' => 1])])  }}">
                                    >> @lang('app.users')
                                </a>
                            </td></tr><tr>
                            {{-- @endif --}}
                        @endforeach
                    @else
                        <td colspan="2"></td></tr><tr>
                    @endif
                @else
                    <td colspan="2"></td></tr><tr>
                @endif
            @else
                <td colspan="3"></td></tr><tr>
            @endif
        @endforeach
    @endif
@else
    <td colspan="4"></td></tr><tr>
@endif