@extends('be.layouts.main') 
@section('specific_vendor_header')
<link rel="stylesheet" type="text/css" href="/be/app-assets/vendors/css/forms/selects/select2.min.css">
<link rel="stylesheet" type="text/css" href="/be/app-assets/vendors/css/extensions/sweetalert.css">
<link rel="stylesheet" type="text/css" href="/be/app-assets/vendors/css/forms/icheck/icheck.css">
@endsection
 
@section('local_styles')
@endsection
 
@section('content')
<div class="content-header row" style="padding: 15px">
    <div class="content-header-left col-md-6 col-12 mb-1">
    </div>
    <div class="content-header-right breadcrumbs-right breadcrumbs-top col-md-6 col-12">
        <div class="breadcrumb-wrapper col-12" style="padding-bottom: 15px">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="/admin/pages" target="blank">Pages list</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="/admin/add-page">Add Page</a>
                </li>
            </ol>
        </div>
    </div>
</div>
<div id="cms-module">
    <pages-create :editable_page="{{(isset($editable_page)) ? json_encode($editable_page) :  'null'}}" :host={{json_encode(env('APP_URL', 'https://properos.com'))}}></pages-create>
</div>
@endsection
 
@section('specific_vendor_footer')
<script src="/be/app-assets/vendors/js/forms/select/select2.full.min.js" type="text/javascript"></script>
<script src="/be/app-assets/vendors/js/extensions/sweetalert.min.js" type="text/javascript"></script>
<script src="/be/app-assets/vendors/js/forms/icheck/icheck.min.js" type="text/javascript"></script>
@endsection
 
@section('local_script')
<script>
    $(document).ready(function () {
        $(".nav-blog").removeClass("active");
        $('#pages').addClass('active')
    });
</script>
<script src="{{ asset('be/js/modules/cms.js') }}"></script>
@endsection