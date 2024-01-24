<ul>
   @foreach($childs as $child)
   <li >
      <a href="#"> {{ $child->username }} - {{jeremykenedy\LaravelRoles\Models\Role::find($child->role_id)->slug}}</a>
      @if(count($child->childs))
      @include('backend.user.partials.manageChildCheckbox',['childs' => $child->childs])
      @endif
   </li>
   @endforeach
</ul>