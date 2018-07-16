<!DOCTYPE html>
    <html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Manuri Pharmacy</title>
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
        <link href="{{ asset('css/boostrap.css') }}" rel="stylesheet">
        <link href="{{ asset('css/datepicker.css') }}" rel="stylesheet">
        <link href="{{ asset('css/libries/material-checkbox.css') }}" rel="stylesheet"  >
        <link href="{{ asset('css/libries/bootstrap-select.min.css') }}" rel="stylesheet">
        <link href="{{ asset('css/libries/datatables.min.css') }}" rel="stylesheet">
        <link href="{{ asset('css/libries/select2.min.css') }}" rel="stylesheet">

        <link href="{{ asset('css/libries/cell-corner-button.css') }}" rel="stylesheet">
        <link href="{{ asset('css/libries/fullcalendar.min.css') }}" rel="stylesheet">
        <link href="{{ asset('css/libries/formValidation.min.css') }}" rel="stylesheet">
        <link href="{{ asset('css/libries/bootstrap-toggle.min.css') }}" rel="stylesheet">
        <script src="{{ asset('js/libries/jquery-2.2.4.min.js') }}"></script>
        <script src="{{ asset('js/libries/bootstrap.min.js') }}"></script>
        <script src="{{ asset('js/libries/bootstrap-select.min.js') }}"></script>
        <script src="{{ asset('js/libries/bootstrap-toggle.min.js') }}"></script>
        <script src="{{ asset('js/libries/dataTables.min.js') }}"></script>
        <script src="{{ asset('js/libries/select2.min.js') }}"></script>
    </head>
    <body>
        <div id="throbber" style="display:none; min-height:120px;"></div>
        <div id="noty-holder"></div>
        <div id="wrapper">
            <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
                <div class="navbar-header">
                    <a href="/" class="navbar-brand">Manuri Pharmacy</a>
                </div>
                
                <ul class="nav navbar-right top-nav" style="margin-top: 5px;">
                    @if (Auth::guest())
                        <li><a href="{{ route('login') }}">Login</a></li>
                        <li><a href="{{ route('register') }}">Register</a></li>
                    @else
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu" role="menu">
                                <li>
                                    <a href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                        Logout
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endif
                </ul>

                <div class="collapse navbar-collapse navbar-ex1-collapse">
                    <ul class="nav navbar-nav side-nav">
                        <li>
                            <a data-toggle="collapse" data-target="#submenu-1"><i class="fa fa-user" aria-hidden="true"></i>&nbsp &nbsp &nbsp  Admin<i class="fa fa-fw fa-angle-down pull-right"></i></a>
                            <ul id="submenu-1" class="collapse">
                                <li><a href="/category"><i class="fa fa-angle-double-right"></i>&nbsp Category</a></li>
                                <li><a href="/subCategory"><i class="fa fa-angle-double-right"></i>&nbsp Sub Category</a></li>
                                <li><a href="/item"><i class="fa fa-angle-double-right"></i>&nbsp Item </a></li>
                                <li><a href="/supplier"><i class="fa fa-angle-double-right"></i>&nbsp Supplier</a></li>
                            </ul>
                        </li>
                        <li>
                            <a data-toggle="collapse" data-target="#submenu-2"><i class="fa fa-fw fa-star"></i>  Transaction <i class="fa fa-fw fa-angle-down pull-right"></i></a>
                            <ul id="submenu-2" class="collapse">
                                <li><a href="/purchase_order"><i class="fa fa-angle-double-right"></i> &nbsp Purchase Order </a></li>
                                <li><a href="/purchase_return"><i class="fa fa-angle-double-right"></i> &nbsp Purchase Return </a></li>
                                <li><a href="/grn"><i class="fa fa-angle-double-right"></i> &nbsp GRN </a></li>
                                <li><a href="/sales_invoice"><i class="fa fa-angle-double-right"></i> &nbsp Sales Invoice</a></li>
                                <li><a href="/sales_return"><i class="fa fa-angle-double-right"></i> &nbsp Sales Return</a></li>
                                <li><a href="/stock"><i class="fa fa-angle-double-right"></i> &nbsp Stock </a></li>
                            </ul>
                        </li>

                         <li>
                            <a data-toggle="collapse" data-target="#submenu-3"><i class="fa fa-fw fa-star"></i>  Reports <i class="fa fa-fw fa-angle-down pull-right"></i></a>
                            <ul id="submenu-3" class="collapse">
                                <li><a href="/purchase_order_report"><i class="fa fa-angle-double-right"></i> &nbsp Purchase Order Report </a></li>
                                <li><a href="/purchase_return_report"><i class="fa fa-angle-double-right"></i> &nbsp Purchase Return Report </a></li>
                                <li><a href="/grn_report"><i class="fa fa-angle-double-right"></i> &nbsp GRN report</a></li>
                                <li><a href="/sales_invoice_report"><i class="fa fa-angle-double-right"></i> &nbsp Sales invoice Report</a></li>
                                <li><a href="/sales_return_report"><i class="fa fa-angle-double-right"></i> &nbsp Sales Return Report</a></li>
                                <li><a href="/stock_report"><i class="fa fa-angle-double-right"></i>&nbsp Stock </a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
            <div id="page-wrapper" style="margin-top:40px;">
                <div class="container-fluid">
                    <div class="row" id="main" >
                        <div class="col-sm-12 col-md-12 well" id="content">
                             @yield('content')
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="{{ asset('js/libries/formValidation.min.js') }}"></script>
        <script src="{{ asset('js/libries/new_bootstrap.min.js') }}"></script>
        <script src="{{ asset('js/ajax.js') }}"></script>
        <script src="{{ asset('js/datepicker.js') }}"></script>
        <script src="{{ asset('js/tables.js') }}"></script>
        @yield('script')


    </body>
</html>
<script type="text/javascript">
$(document).ready(function()
{

    $('.datepicker').datepicker({
      format:'yyyy-mm-dd',
      todatbtn:'linked',
      keyboardNaigation:false,
      forceParse:false,
      calendarWeeks:true,
      autoclose:true
    });

    $('.js-example-basic-single').select2();


    $(function()
    {
        $('[data-toggle="tooltip"]').tooltip();
        $(".side-nav .collapse").on("hide.bs.collapse", function() {                   
            $(this).prev().find(".fa").eq(1).removeClass("fa-angle-right").addClass("fa-angle-down");
        });
        $('.side-nav .collapse').on("show.bs.collapse", function() {                        
            $(this).prev().find(".fa").eq(1).removeClass("fa-angle-down").addClass("fa-angle-right");        
        });
    });
});

function numberOnly(evt)
{
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
         return false;
    return true;
}

</script>