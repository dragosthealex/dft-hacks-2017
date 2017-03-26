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
    var processData = function(zone_id) {
        var a = day;
        var graphData = {
          "today": [],
          "control": []
        };
        var keys = []
        for(var k in a) {
            if(a.hasOwnProperty(k)) {
                keys.push(k);
            }
        }
        keys.sort();

        for(var i=0; i<keys.length; i++) {
            var k = keys[i];
            graphData["today"].push([i, a[k][zone_id]["density"]]);
        }
        return graphData;
    },
    makeThePlot = function(zone_id) {
        graphData = processData(zone_id);
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
                        x: 0.0,
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
            };
        doPlot();
    };
    // Add evt listener
    map.data.addListener("click", function(event) {
        makeThePlot(event.feature.getId());
        console.log(event.feature.getId());
    });
    makeThePlot(6058);
}

</script>
<script src="https://maps.googleapis.com/maps/api/js?key=<?=env('MAPS_API_KEY')?>&callback=initMap">
</script>
<script type="text/javascript"></script>
@endsection
