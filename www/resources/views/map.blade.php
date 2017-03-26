@extends('layouts.app')

@section('title', config('app.name') . ' | Map')

@section('css')
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css">
<style type="text/css">
.padded {
    padding: 40px 0;
}
.dash-title {
    margin: 30px 30px 0;
}
#map {
    height: 70vh;
    width: 100%;
}
#graph {
    width: 100%;
    padding: 5px;
    height: 30vh;
    background: #151515;
}
.demo-placeholder {
-khtml-user-select: none;
-o-user-select: none;
-moz-user-select: none;
-webkit-user-select: none;
user-select: none;
}
</style>
@endsection

@section('pre-scripts')
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">

  <div id="map"></div>
  <div id="graph" class="video-placeholder demo-placeholder"></div>

</div>
</div>
@endsection

@section('post-scripts')
<script type="text/javascript" src="//cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
var map;
var thePlot;
var stations = JSON.parse("<?=addcslashes(file_get_contents(url('json/stations.json')), '"')?>");
var day = JSON.parse("<?=addcslashes(file_get_contents(url('json/m_' . $day . '.json')), '"')?>");
var control = JSON.parse("<?=addcslashes(file_get_contents(url('json/m_1205.json')), '"')?>");
console.log(control);
console.log(day);
console.log(stations);
var initMap = function() {
    var uluru = {lat: 45.483640, lng: 9.186667};
    map = new google.maps.Map(document.getElementById('map'), {
      zoom: 13,
      clickableIcons: false,
      keyboardShortcuts: false,
      maxZoom: 17,
      minZoom: 13,
      rotateControl: false,
      streetView: false,
      streetViewControl: false,
      mapTypeControl: false,
      center: uluru,
      styles:
      [
    {
        "featureType": "all",
        "elementType": "labels.text.fill",
        "stylers": [
            {
                "saturation": 36
            },
            {
                "color": "#000000"
            },
            {
                "lightness": 40
            }
        ]
    },
    {
        "featureType": "all",
        "elementType": "labels.text.stroke",
        "stylers": [
            {
                "visibility": "off"
            },
            {
                "color": "#000000"
            },
            {
                "lightness": 16
            }
        ]
    },
    {
        "featureType": "all",
        "elementType": "labels.icon",
        "stylers": [
            {
                "visibility": "off"
            }
        ]
    },
    {
        "featureType": "administrative",
        "elementType": "geometry.fill",
        "stylers": [
            {
                "color": "#000000"
            },
            {
                "lightness": 20
            }
        ]
    },
    {
        "featureType": "administrative",
        "elementType": "geometry.stroke",
        "stylers": [
            {
                "color": "#000000"
            },
            {
                "lightness": 17
            },
            {
                "weight": 1.2
            },
            {
                "visibility": "off"
            }
        ]
    },
    {
        "featureType": "landscape",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#000000"
            },
            {
                "lightness": "-51"
            }
        ]
    },
    {
        "featureType": "poi",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#000000"
            },
            {
                "lightness": 21
            },
            {
                "visibility": "off"
            }
        ]
    },
    {
        "featureType": "road.highway",
        "elementType": "geometry.fill",
        "stylers": [
            {
                "color": "#000000"
            },
            {
                "lightness": 17
            },
            {
                "gamma": "1"
            }
        ]
    },
    {
        "featureType": "road.highway",
        "elementType": "geometry.stroke",
        "stylers": [
            {
                "color": "#000000"
            },
            {
                "lightness": 29
            },
            {
                "weight": 0.2
            },
            {
                "visibility": "off"
            }
        ]
    },
    {
        "featureType": "road.arterial",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#000000"
            },
            {
                "lightness": 18
            },
            {
                "gamma": "1"
            }
        ]
    },
    {
        "featureType": "road.local",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#000000"
            },
            {
                "lightness": 16
            },
            {
                "gamma": "1"
            }
        ]
    },
    {
        "featureType": "transit",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#000000"
            },
            {
                "lightness": 19
            },
            {
                "visibility": "off"
            }
        ]
    },
    {
        "featureType": "water",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#000000"
            },
            {
                "lightness": 17
            }
        ]
    }
]
    });
    map.data.loadGeoJson(
      '{{ url("/milan-grid.geojson") }}');


    var first_unix = 0,
    heatmap = {
        "8": "rgba(74,20,134, 0.6)",
        "7": "rgba(106,81,163, 0.6)",
        "6": "rgba(128,125,186, 0.6)",
        "5": "rgba(158,154,200, 0.6)",
        "4": "rgba(188,189,220, 0.6)",
        "3": "rgba(218,218,235, 0.4)",
        "2": "rgba(239,237,245, 0.3)",
        "1": "rgba(252,251,253, 0.2)",
    },
    get_sorted_keys_event = function() {
        var a = day;
        var keys = []
        for(var k in a) {
            if(a.hasOwnProperty(k)) {
                keys.push(k);
            }
        }
        keys.sort();
        first_unix = keys[0];
        return keys;
    },
    get_sorted_keys_control = function() {
        var a = control;
        var keys = []
        for(var k in a) {
            if(a.hasOwnProperty(k)) {
                keys.push(k);
            }
        }
        keys.sort();
        return keys;
    },
    set_style = function(timestamp) {
        var zones = day[timestamp];
        map.data.setStyle(function(feature) {
            the_id = feature.getId();
            // Get stations
            current_stations = stations[the_id]
            if(current_stations) {
                stroke = "rgba(121,221,255, 0.6)";
                sWeight = "1";
            } else {
                stroke = "#333";
                sWeight = "1;"
            }
            // Get the zone
            zn = zones[the_id]
            if(zn) {
                // Color heatmap
                return {
                    fillColor: heatmap[zn["level"]],
                    fillOpacity: '1',
                    strokeColor: stroke,
                    strokeWeight: sWeight
                }
            } else {
                return {
                    fillColor: "transparent",
                    strokeColor: stroke,
                    strokeWeight: sWeight
                }
            }
        });
    },
    processData = function(zone_id, keys1, keys2) {
        var a = day;
        var b = control;
        var graphData = {
          "today": [],
          "control": []
        };
        for(var i=0; i<keys1.length; i++) {
            var k1 = keys1[i];
            var k2 = keys2[i];
            graphData["today"].push([i, a[k1][zone_id]["density"]]);
            graphData["control"].push([i, b[k2][zone_id]["density"]]);
        }
        return graphData;
    },
    last_x = 0,
    makeThePlot = function(zone_id, keys1, keys2) {
        graphData = processData(zone_id, keys1, keys2);
        // Create plot after video meta is loaded
        var options = {
                series: {

                },
                cursors: [{
                    name: 'Player',
                    color: '#00BEFF',
                    mode: 'x',
                    showIntersections: false,
                    symbol: 'triangle',
                    showValuesRelativeToSeries: 0,
                    position: {
                        x: last_x,
                        y: 0.5
                    },
                    snapToPlot: 0
                }],
                xaxis: {
                    min: 0,
                },
                yaxis: {
                    min: 0,
                    max: 100,
                    backgroundColor: '#151515',
                },
                shadowSize: 0,
                clickable: false,
                hoverable: false,
                grid: {
                    backgroundColor: '#151515',
                    borderWidth: 0,
                    margin: 20,
                },
                margin: {
                    top: 10,
                    left: 10,
                    bottom: 10,
                    right: 10
                }
            },
            doPlot = function() {
                // keep reference
                thePlot = $.plot($("#graph"), [
                {
                    data: graphData["today"],
                    lines: { show: true, lineWidth: 1},
                    curvedLines: {apply: false},
                    color: "#f9f9f9"
                },
                {
                    data:graphData["control"],
                    lines: { show: true, lineWidth: 1},
                    curvedLines: {apply: false},
                    color: "#aaa"
                },
                {
                    data: graphData["today"],
                    color: 'transparent',
                    points: {show: false},
                }], options);
                $("#graph").bind("cursorupdates", function(event, cursordata) {
                    var index = Math.floor(cursordata[0].x),
                        unix = parseInt(first_unix) + 600 * index;
                    if (last_x != index) {
                        last_x = index;
                        set_style(unix);
                    }
                });
                setTimeout(function() {
                    $("#play-button").click(function() {
                        if($(this).attr("data-status") == "playing") {
                            // Stop
                            console.log("stopping...")
                            clearInterval($(this).attr("data-interval-id"));
                            $(this).attr("data-status", "stopped");
                            $(this).html("PLAY");
                        } else {
                            // Play
                            console.log("playing...")
                            intervalId = setInterval(function() {
                                thePlot.setCursor(thePlot.getCursors()[0], {
                                    position: {
                                        x: last_x + 1,
                                        y: 0.5
                                    }
                                });
                            }, 200);
                            $(this).attr("data-status", "playing");
                            $(this).attr("data-interval-id", intervalId);
                            $(this).html("STOP");
                        }
                    });
                }, 1000);
            };
        doPlot();
    };
    var keys1 = get_sorted_keys_event();
    var keys2 = get_sorted_keys_control();
    map.data.addListener("click", function(event) {
        $("#stations").html("");
        // Append html stations
        the_id = event.feature.getId();
        current_stations = stations[the_id]
        if(current_stations) {
            for(var i=0; i<current_stations.length; i++) {
                s_li = $('<ul class="station"></ul>');
                s = current_stations[i];
                s_li.append($("<li>Station: " + s['name'] + "</li>"));
                s_li.append($("<li>Lines: " + s['lines'] + "</li>"));
                $("#stations").append(s_li);
            }
        }
        makeThePlot(event.feature.getId(), keys1, keys2);
        console.log(event.feature.getId());
    });
    makeThePlot(6058, keys1, keys2);
    set_style(first_unix);
}
function pad (str, max) {
  str = str.toString();
  return str.length < max ? pad("0" + str, max) : str;
}
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=<?=env('MAPS_API_KEY')?>&callback=initMap">
</script>
<script type="text/javascript"></script>
@endsection
