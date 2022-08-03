@extends('/themes.'.(isset($theme)?$theme:'default').'.layouts.main') 
@section('specific_header')
<style>
  .site-navbar {
    bottom: auto;
    width: 100%;
    left: 0;
    top: 0;
    right: 0;
  }

  section.contact-section {
    padding: 10em 0;
    padding-top: 100px;
    min-height: 48vh
  }
</style>
@endsection
 
@section('content')
<div id="cms-module">
  {{--
  <section class="contact-section" data-aos="fade-up"> --}}
    <section class="contact-section">
      <div class="container">
        <documents :category="{{(isset($category_id) && $category_id > 0) ? $category_id : json_encode('all')}}" :units="{{isset($units) ? $units : json_encode('')}}"
          :owner_type="{{isset($owner_type) ? json_encode($owner_type) : json_encode('')}}" :visibility="{{isset($visibility) ? json_encode($visibility) : json_encode('')}}"></documents>
      </div>
    </section>
</div>
@endsection
 
@section('specific_footer') {{--
<script>
  jQuery(document).ready(function($) {
    $('html, body').animate({
     scrollTop: $("#top_menu").offset().top - 200
      }, 500);
});

</script> --}}
<script src="{{ asset('fe/js/modules/cms.js') }}"></script>
@endsection