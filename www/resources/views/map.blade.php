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
    height: 500px;
    width: 100%;
}
</style>
@endsection

@section('pre-scripts')

@endsection

@section('content')
<div class="row">
    <h1 class="dash-title">Map</h1>
    <div class="col-md-10 col-md-offset-1 padded">
        <p>Data stuff</p>
        <div id="map-container">
            <div id="map"></div>
        </div>
    </div>
</div>
@endsection

@section('post-scripts')
<script type="text/javascript" src="//cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
var map;
var initMap = function() {
    var uluru = {lat: 45.466565, lng: 9.185970};
    map = new google.maps.Map(document.getElementById('map'), {
      zoom: 12,
      center: uluru
    });
    map.data.loadGeoJson(
      '{{ url("/milan-grid.geojson") }}');
}
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=<?=env('MAPS_API_KEY')?>&callback=initMap">
</script>
<script type="text/javascript"></script>
$(document).ready(function () {

    $('.datatables').DataTable();
});     
</script>
@endsection