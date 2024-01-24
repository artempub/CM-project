@extends('backend.layouts.app')

@section('page-title', trans('app.manage_usertree'))
@section('page-heading', trans('app.manage_usertree'))

@section('content')

    <section class="content-header">
        @include('backend.partials.messages')
    </section>

    <section class="content">


        {!! Form::open(['route' => 'backend.user.storeoperator', 'files' => true, 'id' => 'operator-form']) !!}

        <div class="box box-default">
            <div class="box-header with-border">
                <div class="panel-heading"><b>Users Hierarchy</b> </div>
            </div>
            
            <!-- <link href="/back/dist/css/treeview.css" rel="stylesheet"> -->
            <div class="box-body" style="margin:10px">
                <div class="row">   
                    <ul id="tree1">
                        @foreach($categories as $category)
                            <li > 
                                <a href="#"> {{ $category->username }} - {{jeremykenedy\LaravelRoles\Models\Role::find($category->role_id)->slug}}</a>  
                            </li>
                            @if(count($category->childs))
                                @include('backend.user.partials.manageChildCheckbox',['childs' => $category->childs])
                            @endif
                        @endforeach
                    </ul>

                </div>
            </div>
            <div class="box-footer">    
            </div>
        </div>

        {!! Form::close() !!}

    </section>
@stop

@section('scripts')

<script>

    $.fn.extend({
        custom_treed: function (o) {
        
        var circle_closedClass = 'glyphicon glyphicon-minus-sign';
        var circle_openedClass = 'glyphicon glyphicon-plus-sign';
        var openedClass = 'fa fa-fw fa-2xl fa-folder-open  text-orange';
        var closedClass = 'fa fa-fw fa-2xl fa-folder text-orange';

        var originClass = 'jstree-icon-origin';
        var clickedClass = 'jstree-icon-clicked';
        
        if (typeof o != 'undefined'){
            if (typeof o.openedClass != 'undefined'){
            openedClass = o.openedClass;
            }
            if (typeof o.closedClass != 'undefined'){
            closedClass = o.closedClass;
            }
        };
        
            /* initialize each of the top levels */
            var tree = $(this);
            tree.addClass("user-tree");
            var i = 0;
           
            tree.find('li').has("ul").each(function () {
                console.log(i)
                var branch = $(this);

                branch.prepend("<i class='"+closedClass+"'></i>");
                branch.prepend("<i class='"+circle_openedClass+"' style='left:-20px;background:white;'></i>");
                
                branch.addClass('branch');
                $(this).find('i').on('click', function (e) {
                    if (this == e.target) {
                        var icon_1;
                        var icon_2;
                        if ($(this).hasClass('glyphicon')){
                            icon_1 = $(this);
                            icon_2 = $(this).parent().children('.text-orange');
                        } else {
                            icon_2 = $(this);
                            icon_1 = $(this).parent().children('.glyphicon');
                        }
                        // var icon_1 = $(this).children('.glyphicon:first');
                        // var icon_2 = $(this).children('.text-orange:first');
                        icon_1.toggleClass(circle_openedClass + " " + circle_closedClass);
                        icon_2.toggleClass(openedClass + " " + closedClass);
                        branch.children().children().toggle();

                    }
                })
                i++;
                branch.children().children().toggle();
            });
            // /* fire event from the dynamically added icon */
            // tree.find('.branch .indicator').each(function(){
            //     $(this).on('click', function () {
            //         $(this).closest('li').click();
            //     });
            // });
            // /* fire event to open branch if the li contains an anchor instead of text */
            // tree.find('.branch>a').each(function () {
            //     $(this).on('click', function (e) {
            //         $(this).closest('li').click();
            //         e.preventDefault();
            //     });
            // });
            // /* fire event to open branch if the li contains a button instead of text */
            // tree.find('.branch>button').each(function () {
            //     $(this).on('click', function (e) {
            //         $(this).closest('li').click();
            //         e.preventDefault();
            //     });
            // });
        }
    });
    /* Initialization of treeviews */
    $('#tree1').custom_treed();
</script>
<style>
.fa-fw{
    margin-left: -10px!important;
}
 .jstree-icon-origin {
     width: 24px;
     height: 24px;
     line-height: 24px;
     background-position: -132px -4px;
     background-image: url('/back/dist/img/32px.png');
}


 .user-tree, .user-tree ul {
margin:0;
padding:0;
list-style:none
}

.panel-primary > .panel-heading {
color: #fff;
background-color: #606ec3;
border-color: #606ec3;
}

.panel-primary {
border-color: #606ec3;
margin: 3%;
}
.user-tree ul {
margin-left:1em;
position:relative
}

.user-tree ul ul {
margin-left:.5em
}

.user-tree ul:before {
content:"";
display:block;
width:0;
position:absolute;
top:0;
bottom:0;
left:0;
border-left:0.5px dotted;
}

.user-tree li {
margin:0;
padding:0 1em;
line-height:1.9em;
color:inherit;
font-weight:normal;
position:relative
}

.user-tree ul li:before {
content:"";
display:block;
width:15px;
height:0;
border-top:0.5px dotted;
margin-top:1px;
position:absolute;
top:0.8em;
left:0
}

.user-tree ul li:last-child:before {
background:#fff;
height:auto;
top:1em;
bottom:0
}

.indicator {
margin-right:5px;
}

.user-tree li a {
/* text-decoration: none; */
color:inherit!important;
}

.user-tree li button, .user-tree li button:active, .user-tree li button:focus {
text-decoration: none;
color:#369;
border:none;
background:transparent;
margin:0px 0px 0px 0px;
padding:0px 0px 0px 0px;
outline: 0;
}
</style>
@stop

