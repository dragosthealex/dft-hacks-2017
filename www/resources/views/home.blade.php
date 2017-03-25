@extends('layouts.app')

@section('title', config('app.name') . ' | Dashboard')
@section('css')
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css">
<style type="text/css">
.padded {
    padding: 40px 0;
}
.dash-title {
    margin: 30px 30px 0;
}
</style>
@endsection

@section('content')
<div class="row">
    <h1 class="dash-title">Dashboard</h1>
    <div class="col-md-10 col-md-offset-1 padded">
            
    </div>
</div>
@endsection

@section('post-scripts')
<script type="text/javascript" src="//cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
$(document).ready(function () {
    $('.datatables').DataTable();
});     
</script>
@endsection