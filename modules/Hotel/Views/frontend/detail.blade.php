@extends('layouts.app')
@section('head')
    <link href="{{ asset('module/tour/css/tour.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset("libs/ion_rangeslider/css/ion.rangeSlider.min.css") }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset("libs/fotorama/fotorama.css") }}"/>
@endsection
@section('content')
    <div class="bravo_detail_tour">
        @include('Tour::frontend.layouts.details.tour-banner')
        <div class="bravo_content">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        @include('Hotel::frontend.details.tour-detail')
                    </div>
                </div>
               
            </div>
        </div>
        <div class="bravo-more-book-mobile">
            <div class="container">
                <div class="left">
                    <div class="g-price">
                        <div class="prefix">
                            <span class="fr_text">{{__("from")}}</span>
                        </div>
                        <div class="price">
                            <span class="onsale">{{ $row->display_sale_price }}</span>
                            <span class="text-price">{{ $row->display_price }}</span>
                        </div>
                    </div>
        
                </div>
                <div class="right">
                    <a class="btn btn-primary bravo-button-book-mobile">{{__("Book Now")}}</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer')
    {!! App\Helpers\MapEngine::scripts() !!}
    <script>
        jQuery(function ($) {
            @if($row->map_lat && $row->map_lng)
            new BravoMapEngine('map_content', {
                disableScripts: true,
                fitBounds: true,
                center: [{{$row->map_lat}}, {{$row->map_lng}}],
                zoom:{{$row->map_zoom ?? "8"}},
                ready: function (engineMap) {
                    engineMap.addMarker([{{$row->map_lat}}, {{$row->map_lng}}], {
                        icon_options: {}
                    });
                }
            });
            @endif
        })
    </script>

    <script type="text/javascript" src="{{ asset("libs/ion_rangeslider/js/ion.rangeSlider.min.js") }}"></script>
    <script type="text/javascript" src="{{ asset("libs/fotorama/fotorama.js") }}"></script>
    <script type="text/javascript" src="{{ asset("libs/sticky/jquery.sticky.js") }}"></script>
    <script type="text/javascript" src="{{ asset('module/tour/js/single-tour.js') }}"></script>
@endsection
