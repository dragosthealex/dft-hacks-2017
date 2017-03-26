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
    height: 80vh;
    width: 100%;
}
#graph {
    width: 100%;
    padding: 5px;
    height: 20vh;
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
var stations = JSON.parse("<?=addcslashes(file_get_contents(url('json/stations.json')), '"')?>");
var day = JSON.parse("<?=addcslashes(file_get_contents(url('json/m_' . $day . '.json')), '"')?>");
console.log(day)
console.log(stations)
var initMap = function() {
    var uluru = {lat: 45.466565, lng: 9.185970};
    map = new google.maps.Map(document.getElementById('map'), {
      zoom: 15,
      clickableIcons: false,
      keyboardShortcuts: false,
      maxZoom: 17,
      minZoom: 15,
      rotateControl: false,
      streetView: false,
      streetViewControl: false,
      mapTypeControl: false,
      center: uluru,
      styles:
      [
    {
        "featureType": "administrative",
        "elementType": "labels.text.fill",
        "stylers": [
            {
                "color": "#444444"
            }
        ]
    },
    {
        "featureType": "landscape",
        "elementType": "all",
        "stylers": [
            {
                "color": "#f2f2f2"
            }
        ]
    },
    {
        "featureType": "poi",
        "elementType": "all",
        "stylers": [
            {
                "visibility": "off"
            }
        ]
    },
    {
        "featureType": "road",
        "elementType": "all",
        "stylers": [
            {
                "saturation": -100
            },
            {
                "lightness": 45
            }
        ]
    },
    {
        "featureType": "road.highway",
        "elementType": "all",
        "stylers": [
            {
                "visibility": "simplified"
            }
        ]
    },
    {
        "featureType": "road.arterial",
        "elementType": "labels.icon",
        "stylers": [
            {
                "visibility": "off"
            }
        ]
    },
    {
        "featureType": "transit",
        "elementType": "all",
        "stylers": [
            {
                "visibility": "off"
            }
        ]
    },
    {
        "featureType": "water",
        "elementType": "all",
        "stylers": [
            {
                "color": "#46bcec"
            },
            {
                "visibility": "on"
            }
        ]
    }
]
    });
    map.data.loadGeoJson(
      '{{ url("/milan-grid.geojson") }}');


    map.data.setStyle(function(feature) {
        if(feature.getId() == "6058") {
            return {
                fillColor: "red",
                clickable: true,
            }
        }
    });
    var first_unix = 0,
    heatmap = {
        "0": "#9ABF00",
        "1": "#C2AD00",
        "2": "#C67400",
        "3": "#CA3A00",
        "4": "#D20041",
        "5": "#D900C6",
        "6": "#6D00E1",
        "7": "#2A00E5",
    },
    get_sorted_keys = function() {
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
    set_style = function(timestamp) {
        var zones = day[timestamp];
        map.data.setStyle(function(feature) {
            zn = zones[feature.getId()]
            if(zn) {
                // Color heatmap
                return {
                    fillColor: heatmap[zn["level"]],
                }
            } else {
                fillColor: "transparent"
            }
        });
    },
    processData = function(zone_id, keys) {
        var a = day;
        var graphData = {
          "today": [],
          "control": []
        };
        for(var i=0; i<keys.length; i++) {
            var k = keys[i];
            graphData["today"].push([i, a[k][zone_id]["density"]]);
        }
        return graphData;
    },
    last_x = 0,
    makeThePlot = function(zone_id, keys) {
        graphData = processData(zone_id, keys);
        // Create plot after video meta is loaded
        var options = {
                series: {
                    curvedLines: {active: true}
                },
                cursors: [{
                    name: 'Player',
                    color: 'red',
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
                    min: 0
                },
                clickable: false,
                hoverable: false,
                grid: {}
            },
            doPlot = function() {
                // keep reference
                $.plot($("#graph"), [
                {
                    data: graphData["today"],
                    lines: { show: true, lineWidth: 2},
                    curvedLines: {apply: true, tension: 0.5},
                    color: "red"
                },
                {
                    data: graphData["today"],
                    color: '#f03b20',
                    points: {show: true},
                }], options);
                $("#graph").bind("cursorupdates", function(event, cursordata) {
                    var index = Math.floor(cursordata[0].x),
                        unix = parseInt(first_unix) + 600 * index;
                    last_x = index;
                    set_style(unix);
                });
            };
        doPlot();
    };
    var keys = get_sorted_keys();
    map.data.addListener("click", function(event) {
        makeThePlot(event.feature.getId(), keys);
        console.log(event.feature.getId());
    });
    makeThePlot(6058, keys);
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
