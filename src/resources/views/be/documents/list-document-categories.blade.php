@extends('be.layouts.main') 
@section('specific_vendor_header')
<link rel="stylesheet" type="text/css" href="/be/app-assets/vendors/css/extensions/sweetalert.css"> {{--
<link rel="stylesheet" type="text/css" href="/be/app-assets/vendors/css/pickers/datetime/bootstrap-datetimepicker.css"> --}}
<link rel="stylesheet" type="text/css" href="/be/app-assets/vendors/css/printjs/print.min.css">
@endsection
 
@section('local_styles')
<style>
    .breadcrumb {
        float: right;
    }
</style>
@endsection
 
@section('content')
<div id="cms-module">
    <div class="content-header row">
        <div class="content-header col-md-12 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/admin/documents">Documents</a>
                        </li>
                        <li class="breadcrumb-item"><a href="/admin/documents/create">New document</a>
                        </li>
                        <li class="breadcrumb-item">Categories
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('manager') || Auth::user()->hasRole('director'))
    <documents-categories-list></documents-categories-list>
    @endif
</div>
@endsection
 
@section('specific_vendor_footer')
<script src="/be/app-assets/vendors/js/extensions/sweetalert.min.js" type="text/javascript"></script>
{{--
<script src="/be/app-assets/vendors/js/pickers/dateTime/moment-with-locales.min.js" type="text/javascript"></script>
<script src="/be/app-assets/vendors/js/pickers/dateTime/bootstrap-datetimepicker.min.js" type="text/javascript"></script> --}}
<script src="/be/app-assets/vendors/js/printjs/print.min.js" type="text/javascript"></script>
@endsection
 
@section('local_script')
<script>
    $(document).ready(function () {
            $(".nav-item").removeClass("active");
            $('#documents').addClass('active')
        });

</script>
<script src="{{ asset('be/js/modules/cms.js') }}"></script>
@endsection