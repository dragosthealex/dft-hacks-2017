<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>
    <link href="{{ asset('/vendors/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/build/css/custom.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/global.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Roboto+Mono" rel="stylesheet">

    @yield('css')
    @yield('pre-scripts')
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>
  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <div class="col-md-3 left_col">
          <div class="left_col scroll-view">
            <div class="navbar nav_title" style="border: 0;">
              <!--<a href="{{ url('/') }}" class="site_title"><img class="logo" width="120" src="{{ asset('img/streamflow-logo.svg') }}"></a>-->
            </div>

            <div class="clearfix"></div>
            <br />
            <!-- sidebar menu -->
            <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
              <div class="menu_section">
                <ul class="nav side-menu">

                  <li>
                      <div class="station_icon">
                    <img class="station_logo" src='{{ asset('img/m1.svg') }}' />
                </div>
                <h3> Incident #1 – 07/12/2013</h3>
                <h3> <img class="play_icon" src='https://icon.now.sh/play_circle_outline/20/ffffff' alt='chevron icon' />Control <h3 class="time">00:00:00</h3></h3>
                <h3> <img class="play_icon" src='https://icon.now.sh/play_circle_outline/20/ffffff' alt='chevron icon' />Incident <h3 class="time">00:00:00</h3></h3>
                <div class="sparks"><span  class="spark controlparkline"></span>
                <span  class="spark incidentparkline"></span>
            </div>

                  </li>
                  <li>
                      <div class="station_icon">
                    <img class="station_logo" src='{{ asset('img/m2.svg') }}' />
                </div>
                <h3> Incident #2 – 07/12/2013</h3>
                <h3 class="time"> 00:00:00</h3>
                  </li>
                </ul>
              </div>
            </div>
            <!-- /sidebar menu -->
          </div>
        </div>


        <!-- page content -->
        <div class="right_col" role="main" height="100%">

        @yield('content')

        </div>
<!-- /page content -->

<!-- footer content -->

<!-- /footer content -->
</div>
</div>

<!-- jQuery -->
<script src="{{ asset('/vendors/jquery/dist/jquery.min.js') }}"></script>
<!-- Bootstrap -->
<script src="{{ asset('/vendors/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<!-- FastClick -->
<script src="{{ asset('/vendors/fastclick/lib/fastclick.js') }}"></script>
<!-- NProgress -->
<script src="{{ asset('/vendors/nprogress/nprogress.js') }}"></script>
<!-- Chart.js -->
<script src="{{ asset('/vendors/Chart.js/dist/Chart.min.js') }}"></script>
<!-- gauge.js -->
<script src="{{ asset('/vendors/gauge.js/dist/gauge.min.js') }}"></script>
<!-- bootstrap-progressbar -->
<script src="{{ asset('/vendors/bootstrap-progressbar/bootstrap-progressbar.min.js') }}"></script>
<!-- iCheck -->
<script src="{{ asset('/vendors/iCheck/icheck.min.js') }}"></script>
<!-- Skycons -->
<script src="{{ asset('/vendors/skycons/skycons.js') }}"></script>
<!-- Flot -->
<script src="{{ asset('/vendors/Flot/jquery.flot.js') }}"></script>
<script src="{{ asset('/vendors/Flot/jquery.flot.pie.js') }}"></script>
<script src="{{ asset('/vendors/Flot/jquery.flot.time.js') }}"></script>
<script src="{{ asset('/vendors/Flot/jquery.flot.stack.js') }}"></script>
<script src="{{ asset('/vendors/Flot/jquery.flot.resize.js') }}"></script>
<!-- Flot plugins -->
<script src="{{ asset('/vendors/flot.orderbars/js/jquery.flot.orderBars.js') }}"></script>
<script src="{{ asset('/vendors/flot-spline/js/jquery.flot.spline.min.js') }}"></script>
<script src="{{ asset('/vendors/flot.curvedlines/curvedLines.js') }}"></script>
<script src="{{ asset('/vendors/flot.cursors/cursors.js') }}"></script>

<!-- DateJS -->
<script src="{{ asset('/vendors/DateJS/build/date.js') }}"></script>
<!-- JQVMap -->
<script src="{{ asset('/vendors/jqvmap/dist/jquery.vmap.js') }}"></script>
<script src="{{ asset('/vendors/jqvmap/dist/maps/jquery.vmap.world.js') }}"></script>
<script src="{{ asset('/vendors/jqvmap/examples/js/jquery.vmap.sampledata.js') }}"></script>
<!-- bootstrap-daterangepicker -->
<script src="{{ asset('/vendors/moment/min/moment.min.js') }}"></script>
<script src="{{ asset('/vendors/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
<!-- bootstrap-wysiwyg -->
<script src="{{ asset('/vendors/bootstrap-wysiwyg/js/bootstrap-wysiwyg.min.js') }}"></script>
<script src="{{ asset('/vendors/jquery.hotkeys/jquery.hotkeys.js') }}"></script>
<script src="{{ asset('/vendors/google-code-prettify/src/prettify.js') }}"></script>
<!-- jQuery Tags Input -->
<script src="{{ asset('/vendors/jquery.tagsinput/src/jquery.tagsinput.js') }}"></script>
<!-- Switchery -->
<script src="{{ asset('/vendors/switchery/dist/switchery.min.js') }}"></script>
<!-- Select2 -->
<script src="{{ asset('/vendors/select2/dist/js/select2.full.min.js') }}"></script>
<!-- Parsley -->
<script src="{{ asset('/vendors/parsleyjs/dist/parsley.min.js') }}"></script>
<!-- Autosize -->
<script src="{{ asset('/vendors/autosize/dist/autosize.min.js') }}"></script>
<!-- jQuery autocomplete -->
<script src="{{ asset('/vendors/devbridge-autocomplete/dist/jquery.autocomplete.min.js') }}"></script>
<!-- starrr -->
<script src="{{ asset('/vendors/starrr/dist/starrr.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-sparklines/2.1.2/jquery.sparkline.js"></script>
<script type="text/javascript">
    $(function() {
        /** This code runs when everything has been loaded on the page */
        /* Inline sparklines take their values from the contents of the tag */
        var control = [477.1491934,
499.2626612,
371.4108399,
414.7833893,
532.2165529,
308.1831178,
349.6902502,
344.1497873,
327.2159311,
339.0518441,
368.153234,
338.5719322,
338.8704302,
306.4412079,
237.0908348,
295.1058852,
269.9558872,
260.1930865,
273.3433532,
270.3638867,
238.8164052,
259.8504069,
248.9336652,
221.7488278,
276.7063788,
253.4116473,
260.8711896,
309.7150116,
293.5663431,
260.2663074,
306.4446086,
274.3178802,
297.1048953,
285.5523029,
463.2908412,
286.8184209,
261.1536449,
352.0980221,
373.7368509,
472.060594,
485.7833354,
421.767169,
519.2858863,
576.4838643,
658.5440125,
754.381223,
1093.738689,
1191.470635,
1218.882516,
1303.655688,
1536.184282,
1825.484793,
1770.76026,
1870.097674,
1813.908445,
1530.691885,
1611.694467,
2182.331551,
1802.03181,
1803.428378,
1536.480545,
1546.041821,
1980.487146,
1699.677661,
1892.493875,
1731.371726,
1511.218413,
1486.518644,
1562.568717,
1583.799216,
1352.287215,
1489.44842,
1431.039473,
1668.680437,
1585.809273,
1714.74991,
1764.163172,
1865.441323,
1869.974407,
1707.997936,
1865.956898,
1990.033115,
1862.623973,
1621.798233,
1575.992663,
1593.893501,
1991.526221,
1835.990783,
1667.401636,
1532.469612,
1689.776963,
1662.725892,
1628.377327,
1778.143732,
1563.318351,
1659.398389,
1786.387497,
1695.799977,
1867.494399,
2026.736908,
1561.493776,
1601.048453,
1669.990965,
1991.508169,
2211.965006,
2444.487604,
2067.078365,
1885.066233,
1995.532008,
2210.003642,
2500.23006,
2029.621827,
1906.673047,
1784.591145,
1900.856937,
1772.001554,
1722.044791,
1489.615202,
1248.085076,
1395.222171,
1497.17748,
1077.995357,
1068.078766,
988.4389919,
868.8005771,
776.9472498,
615.0017081,
682.2932654,
651.566423,
798.2665263,
625.6664172,
545.4683074,
585.6070016,
601.3439858,
571.9738596,
605.7030406,
566.7078205,
586.4251842,
534.5966726,
536.3804652,
482.9488872,
502.8370043,
504.0872005,
486.2008538];
        $('.controlparkline').sparkline(control, {type: 'line', width: '220px', height: '80px', lineColor: '#00beff',
    fillColor: 'transparent',
    spotColor: 'transparent',
    minSpotColor: 'transparent',
    maxSpotColor: 'transparent'} );


    var incident = [306.9158422,
358.9458432,
333.3178937,
323.5522412,
333.0776314,
364.7561822,
299.3110936,
305.4679336,
299.795067,
246.1301634,
288.5522687,
281.8398777,
270.1463371,
257.9471772,
234.7714745,
220.9135747,
228.1819212,
225.9276491,
212.4644065,
213.2553882,
224.1672683,
197.773599,
194.3378879,
258.0370352,
214.0745036,
247.3828376,
229.2042529,
221.9569736,
214.6855962,
199.0218306,
200.52778,
354.820549,
202.3659192,
222.7208815,
208.9284667,
239.3083397,
196.5634504,
228.9198197,
253.0503554,
271.6684551,
265.2165085,
314.9740525,
345.9042197,
336.4679891,
303.5550061,
309.8371575,
335.1019427,
342.0036665,
374.3229521,
386.9551137,
451.2276064,
654.6235223,
735.9157168,
587.4252602,
729.4281894,
674.6053655,
710.2430697,
638.769924,
519.5122188,
514.2169173,
549.446157,
565.9025756,
632.9945033,
695.964941,
537.0637721,
668.3540629,
1011.030658,
800.3980768,
1096.620533,
899.361228,
642.1932418,
595.6993185,
674.1388455,
589.495683,
665.5951528,
687.5776154,
735.6195319,
645.7955407,
613.2481215,
599.9002401,
630.8916512,
739.991723,
568.6773599,
610.0095659,
608.4081537,
645.2544801,
660.0503645,
752.1771489,
782.8114142,
730.2314163,
784.7377821,
860.6425156,
851.7716334,
970.2403541,
836.8310853,
676.6130337,
710.6997436,
786.8335212,
846.6991786,
875.3795048,
852.9060635,
799.1461844,
780.9024048,
1141.343972,
909.912705,
1077.902902,
1026.069326,
825.5609541,
885.4801915,
864.1789345,
1316.360823,
1290.492057,
1036.344587,
992.6617412,
913.2866971,
1285.077262,
1528.708157,
959.2155595,
860.2251798,
816.8506418,
1066.087461,
777.0167837,
683.0498692,
717.8859532,
617.1273901,
609.7630617,
652.3694098,
619.0301249,
597.5043852,
913.1354423,
571.340132,
512.7012389,
592.177154,
702.9509616,
547.9145248,
531.3339248,
469.7654637,
504.1528341,
637.1643393,
781.6984437,
730.5727133,
474.9773841,
448.4992247,
403.2127641];
    $('.incidentparkline').sparkline(incident, {type: 'line', width: '220px', height: '80px', lineColor: '#BD0FE1',
fillColor: 'transparent',
spotColor: 'transparent',
minSpotColor: 'transparent',
maxSpotColor: 'transparent'} );


    });
    </script>

@yield('post-scripts')
</body>
</html>
