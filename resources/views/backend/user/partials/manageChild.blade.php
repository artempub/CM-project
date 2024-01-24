
<ul>
   @foreach($childs as $child)
   <li>
      <!-- <input type="radio"  id="{{ $child->id }}" name="parent_id" value="{{ $child->id }}"> -->
      <label for="vehicle1"> {{ $child->username }} - {{jeremykenedy\LaravelRoles\Models\Role::find($child->role_id)->slug}}</label><br>
      @if(count($child->childs))
      @include('backend.user.partials.manageChildCheckbox',['childs' => $child->childs])
      @endif
   </li>
   @endforeach
</ul>