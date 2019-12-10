<div class="g-header">
    <div class="left">
        <h2>{{$row->title}}</h2>
    </div>.


    <div class="right">
        @if($review_score = $row->review_data)
            <div class="review-score">
                <span class="head-rating">{{$review_score['score_text']}}</span>
                <div class="list-star">
                    <ul class="booking-item-rating-stars">
                        <li><i class="fa fa-star-o"></i></li>
                        <li><i class="fa fa-star-o"></i></li>
                        <li><i class="fa fa-star-o"></i></li>
                        <li><i class="fa fa-star-o"></i></li>
                        <li><i class="fa fa-star-o"></i></li>
                    </ul>
                    <div class="booking-item-rating-stars-active" style="width: {{  $review_score['score_total'] * 2 * 10 ?? 0  }}%">
                        <ul class="booking-item-rating-stars">
                            <li><i class="fa fa-star"></i></li>
                            <li><i class="fa fa-star"></i></li>
                            <li><i class="fa fa-star"></i></li>
                            <li><i class="fa fa-star"></i></li>
                            <li><i class="fa fa-star"></i></li>
                        </ul>
                    </div>
                </div>
                <span>
                    {{__("from :number reviews",['number'=>$review_score['total_review']])}}
                </span>
            </div>
        @endif
    </div>

    
</div>
<div class="g-tour-feature">


    <div class="row">
        @if($row->duration)
            <div class="col-xs-6 col-lg-3 col-md-6">
                <div class="item">
                    <div class="icon">
                        <i class="icofont-wall-clock"></i>
                    </div>
                    <div class="info">
                        <h4 class="name">{{__("Duration")}}</h4>
                        <p class="value">
                            @if($row->duration > 1)
                                {{ __(":number days",array('number'=>$row->duration)) }}
                            @else
                                {{ __(":number day",array('number'=>$row->duration)) }}
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        @endif
        @if(!empty($row->category_tour->name))
            <div class="col-xs-6 col-lg-3 col-md-6">
                <div class="item">
                    <div class="icon">
                        <i class="icofont-beach"></i>
                    </div>
                    <div class="info">
                        <h4 class="name">{{__("Tour Type")}}</h4>
                        <p class="value">
                            {{$row->category_tour->name ?? ''}}
                        </p>
                    </div>
                </div>
            </div>
        @endif
        @if($row->max_people)
            <div class="col-xs-6 col-lg-3 col-md-6">
                <div class="item">
                    <div class="icon">
                        <i class="icofont-travelling"></i>
                    </div>
                    <div class="info">
                        <h4 class="name">{{__("Group Size")}}</h4>
                        <p class="value">
                            {{$row->max_people}} {{__("people")}}
                        </p>
                    </div>
                </div>
            </div>
        @endif
        @if(!empty($row->location->name))
            <div class="col-xs-6 col-lg-3 col-md-6">
                <div class="item">
                    <div class="icon">
                        <i class="icofont-island-alt"></i>
                    </div>
                    <div class="info">
                        <h4 class="name">{{__("Location")}}</h4>
                        <p class="value">
                            {{$row->location->name ?? ''}}
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@if($row->getGallery())
    <div class="g-gallery">
        <div class="fotorama" data-width="100%" data-thumbwidth="135" data-thumbheight="135" data-thumbmargin="15" data-nav="thumbs" data-allowfullscreen="true">
            @foreach($row->getGallery() as $key=>$item)
                <a href="{{$item['large']}}"><img src="{{$item['thumb']}}"></a>
            @endforeach
        </div>
    </div>
@endif


<div class="container">
    <div class="row">
        <div class="col-xs-12 ">
            <nav class="tour_detailes">
                <div class="nav nav-tabs nav-fill " id="nav-tab" role="tablist">
                <a class="nav-item nav-link active" id="nav-overview-tab" data-toggle="tab" href="#nav-overview" role="tab" aria-controls="nav-overview" aria-selected="true">{{__("Overview")}}</a>
                <a class="nav-item nav-link" id="nav-Itinerary-tab" data-toggle="tab" href="#nav-Itinerary" role="tab" aria-controls="nav-Itinerary" aria-selected="true">{{__("Itinerary")}}</a>
                <a class="nav-item nav-link" id="nav-tour_map-tab" data-toggle="tab" href="#nav-tour_map" role="tab" aria-controls="nav-tour_map"  aria-selected="true">{{__("Tour Map")}}</a>
                <a class="nav-item nav-link" id="nav-package_includes-tab" data-toggle="tab" href="#nav-package_includes" role="tab" aria-controls="nav-package_includes" aria-selected="true">{{__("Package Includes")}}</a>
                <a class="nav-item nav-link" id="nav-package_excludes-tab" data-toggle="tab" href="#nav-package_excludes" role="tab" aria-controls="nav-package_excludes" aria-selected="true">{{__("Package Excludes")}}</a>
                <a class="nav-item nav-link" id="nav-dates_rates-tab" data-toggle="tab" href="#nav-dates_rates" role="tab" aria-controls="nav-dates_rates"  aria-selected="true">{{__("dates&rates")}}</a>
                </div>
                
            </nav>
            <div class="tab-content-tour py-3 px-3 px-sm-0" id="nav-tabContent">
                <div class="tab-pane fade show active" id="nav-overview" role="tabpanel" aria-labelledby="nav-overview-tab">
                    {!!  $row->content !!}
                </div>
                @if($row->itinerary)
                    <div class="tab-pane fade" id="nav-Itinerary" role="tabpanel" aria-labelledby="nav-Itinerary-tab">
                        {{-- {!!  $row->itinerary !!} --}}
                        <div class="g-faq">
                            @foreach($row->itinerary as $item)
                                <div class="item">
                                    <div class="header">
                                        <i class="field-icon icofont-support-itinerary"></i>
                                        <h5>{{$item['title']}}</h5>
                                        <span class="arrow"><i class="fa fa-angle-down"></i></span>
                                    </div>
                                    <div class="body">
                                        {{$item['content']}}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
                <div class="tab-pane fade" id="nav-tour_map" role="tabpanel" aria-labelledby="nav-tour_map-tab">
                    {!!  $row->tour_map !!}
                </div>
                
                <div class="tab-pane fade" id="nav-package_includes" role="tabpanel" aria-labelledby="nav-package_includes-tab">
                    {!!  $row->package_includes !!}
                </div>
                <div class="tab-pane fade" id="nav-package_excludes" role="tabpanel" aria-labelledby="nav-package_excludes-tab">
                    {!!  $row->package_excludes !!}
                </div>
                <div class="tab-pane fade" id="nav-dates_rates" role="tabpanel" aria-labelledby="nav-dates_rates-tab">
                    {!!  $row->dates_rates !!}
                </div>
       
            </div>
        </div>
    </div>
</div>
{{-- @if($row->content)
    <div class="g-overview">
        <h3>{{__("Overview")}}</h3>
        <div class="description">
            
        </div>
    </div>
@endif --}}


@if($row->faqs)
<div class="g-faq">
    <h3> {{__("FAQs")}} </h3>
    @foreach($row->faqs as $item)
        <div class="item">
            <div class="header">
                <i class="field-icon icofont-support-faq"></i>
                <h5>{{$item['title']}}</h5>
                <span class="arrow"><i class="fa fa-angle-down"></i></span>
            </div>
            <div class="body">
                {{$item['content']}}
            </div>
        </div>
    @endforeach
</div>
@endif
{{-- @if($row->map_lat && $row->map_lng)
<div class="g-location">
    <h3>{{__("Tour Location")}}</h3>
    <div class="location-map">
        <div id="map_content"></div>
    </div>
</div>
@endif --}}