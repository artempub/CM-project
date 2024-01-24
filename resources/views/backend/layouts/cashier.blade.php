<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('page-title') - {{ settings('app_name') }}</title>

    <link rel="stylesheet" href="/back/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/back/bower_components/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="/back/bower_components/Ionicons/css/ionicons.min.css">
    <link rel="stylesheet" href="/back/dist/css/AdminLTE.min.css">

    <link rel="stylesheet" href="/back/dist/css/skins/_all-skins.min.css">
    <link rel="stylesheet" href="/cashier/timepicki.css">
    <link rel="stylesheet" href="/back/bower_components/morris.js/morris.css">
    <link rel="stylesheet" href="/back/bower_components/jvectormap/jquery-jvectormap.css">
    <link rel="stylesheet" href="/back/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
    <link rel="stylesheet"
        href="/back/bower_components/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" href="/back/bower_components/bootstrap-daterangepicker/daterangepicker.css">
    <link href="/cashier/jquery.minical.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="/back/bower_components/bootstrap-tagsinput/bootstrap-tagsinput.css">
    <link rel="stylesheet" href="/back/bower_components/croppie/croppie.css">
    <link rel="stylesheet" href="/back/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
    <link rel="stylesheet" href="/back/bower_components/select2/dist/css/select2.css">
    <link rel="stylesheet" href="/back/bower_components/select2/dist/css/select2-bootstrap.min.css">
    <link rel="stylesheet" href="/back/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
    {{--
    <link rel="stylesheet" href="/cashier/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/cashier/tailwind.min.css"> --}}
    {{--
    <link rel="stylesheet" href="/back/plugins/iCheck/all.css">

    <link rel="stylesheet" href="/back/dist/css/new.css">
    <link rel="stylesheet" href="/back/dist/css/custom.css"> --}}


    <link rel="stylesheet" href="/cashier/custom.css">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    <script src="/back/bower_components/jquery/dist/jquery.min.js"></script>
</head>

<body>
    <div>

        @include('backend.cashier.navbar')

        <!-- Content Wrapper. Contains page content -->


        @yield('content')

        @include('backend.cashier.sidebar')

        @include('backend.cashier.footer')

    </div>
    <!-- ./wrapper -->

    {{-- <script src="/cashier/jquery-3.3.0.min.js"></script>
    <script src="/cashier/bootstrap.min.js"></script> --}}
    <script src="/back/js/timepicki.js"></script>
    <script src="/back/js/jquery.minical.js"></script>
    <script src="/back/bower_components/sweetalert2/sweetalert2.js"></script>
    <script src="/back/bower_components/jquery-ui/jquery-ui.min.js"></script>
    <script>
        var timezon = '{{ date_default_timezone_get() }}';
    $.widget.bridge('uibutton', $.ui.button);
    </script>
    <script src="/back/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="/back/bower_components/raphael/raphael.min.js"></script>
    <script src="/back/bower_components/morris.js/morris.min.js"></script>
    <script src="/back/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
    <script src="/back/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
    <script src="/back/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
    <script src="/back/bower_components/jquery-knob/dist/jquery.knob.min.js"></script>
    <script src="/back/bower_components/moment/min/moment.min.js"></script>
    <script src="/back/bower_components/moment/min/moment-timezone-with-data-1970-2030.min.js"></script>

    <script src="/back/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
    <script src="/back/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
    <script src="/back/bower_components/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
    <script src="/back/bower_components/bootstrap-tagsinput/bootstrap-tagsinput.min.js"></script>
    <script src="/back/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
    <script src="/back/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
    <script src="/back/bower_components/fastclick/lib/fastclick.js"></script>
    <script src="/back/bower_components/croppie/croppie.js"></script>
    <script src="/back/bower_components/select2/dist/js/select2.js"></script>
    <script src="/back/dist/js/adminlte.js"></script>
    <!--<script src="/back/js/sweetalert.min.js"></script>-->
    <script src="/back/js/delete.handler.js"></script>
    <script src="/back/bower_components/jquery-validation/jquery.validate.min.js"></script>
    <script src="/back/bower_components/jquery-validation/additional-methods.min.js"></script>
    <script src="/back/plugins/jquery-cookie/jquery.cookie.min.js"></script>

    <script src="/back/bower_components/ckeditor5/ckeditor.js"></script>
    <script src="/back/bower_components/ckeditor5/sample.js"></script>

    <link href="/back/bower_components/sweetalert2/bootstrap-4.css" rel="stylesheet">
    <link rel="stylesheet" href="/back/dist/css/additional.css">
    <script src="/back/bower_components/sweetalert2/sweetalert2.js"></script>

    <!-- DataTables -->
    <script src="/back/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="/back/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>

    <!-- iCheck 1.0.1 -->
    <script src="/back/plugins/iCheck/icheck.min.js"></script>

    <!-- InputMask -->
    <script src="/back/plugins/input-mask/jquery.inputmask.js"></script>
    <script src="/back/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
    <script src="/back/plugins/input-mask/jquery.inputmask.extensions.js"></script>

    <script src="/back/dist/js/demo.js"></script>

<script>
    if(!localStorage.getItem("sidebar_state")){
      document.getElementById("mySidenav").style.right="0";
      localStorage.setItem("sidebar_state",true);
    }

function openRightSide() {
  document.getElementById("mySidenav").style.right = "0";
}

function closeRightSide() {
  document.getElementById('mySidenav').style.right="-260px";
  document.getElementById('btn_save').className = "btn";
  var clsName = document.getElementById('btn_cancel').className;
  clsName = clsName.replace(" btn-primary","");
  document.getElementById('btn_cancel').className += " btn-primary";
}
var coll = document.getElementsByClassName("sidebar_collapsible");
    coll[0].addEventListener("click", function() {
        this.classList.toggle("sidebar_active");
        var content = this.nextElementSibling;
        if (content.style.display === "block") {
        content.style.display = "none";
        } else {
        content.style.display = "block";
        }
    });
</script>

    <script type="text/javascript">
        $(function() {
            setInterval(function(){
                $.get('/refresh-csrf').done(function(data){
                    $('[name="csrf-token"]').attr('content', data);
                    $('[name="_token"]').val(data);
                });
            }, 5000);

            
            $("#nav-search").on('input', function (e) {
                //  console.log(e);
                let search = e.target.value.toLowerCase();
                let cells = $(".side-list-cell");
                for(let cell of cells){
                    if($('.side-cell-data', cell).text().toLowerCase().includes(search)){
                        cell.style.display = 'block';
                    } else{
                        cell.style.display = 'none';
                    };
                }
            });

        });

    
    </script>

    @yield('scripts')

</body>

</html>