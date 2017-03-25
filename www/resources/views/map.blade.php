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
    height: 100vh;
    width: 100%;
}
</style>
@endsection

@section('pre-scripts')

@endsection

@section('content')
<div class="row">
    <div class="col-md-12">

  <div id="map"></div>

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
      zoom: 14,
      center: uluru
    });
    map.data.setStyle({
        fillColor: 'transparent',
        strokeColor: '#ffffff',
  strokeWeight: '0.5'
});
    map.data.loadGeoJson(
      '{{ url("/milan-grid.geojson") }}');
}
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=<?=env('MAPS_API_KEY')?>&callback=initMap">
</script>
<script type="text/javascript"></script>
@endsection
