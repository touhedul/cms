@extends('themes.'.(isset($theme)?$theme:'default').'.layouts.main')
@section('local_styles')
<style>
    .tt-about__info div,
    .tt-about__info p {
        // max-width: 820px;
    }

    #theme .tt-about__info {
        background-color: #fff;
    }

    .header-image {
        background-image: url('/storage/banner_about_back.jpg');
        background-repeat: no-repeat;
        background-attachment: fixed;
        background-position: top;
        background-size: cover;
        height: 500px;
        width: 100% !important;
        opacity: 0.9
    }
    @media only screen and (min-width: 768px) {
        .on-mobile {
			display: none !important;
		}
        .on-desktop {
			desktop: block;
		}
    }
    @media only screen and (max-width: 767px) {
        .on-mobile {
			display: block;
		}
        .on-desktop {
			display: none !important;
		}
    }		
</style>
@endsection
@section('content')
    <div id="cms-module" class="content">
        <blog-index :posts="{{$posts}}" :account="{{isset($account)?$account:'null'}}"></blog-index>
    </div>
@endsection
@section('local_script')
<script src="{{ asset('fe/js/modules/cms.js') }}"></script>
@endsection