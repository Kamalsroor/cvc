<div class="item-tour {{$wrap_class ?? ''}}">
    @if($row->is_featured == "1")
        <div class="featured">
            {{__("Featured")}}
        </div>
    @endif
    <div class="thumb-image ">
        @if($row->discount_percent)
            <div class="sale_info">{{$row->discount_percent}}</div>
        @endif
        <a @if(!empty($blank)) target="_blank" @endif href="{{$row->getDetailUrl()}}">
            @if($row->image_url)
                @if(!empty($disable_lazyload))
                    <img src="{{$row->image_url}}" class="img-responsive" alt="">
                @else
                    {!! get_image_tag($row->image_id,'medium',['class'=>'img-responsive','alt'=>$row->title]) !!}
                @endif
            @endif
        </a>
    </div>
    <div class="location">
        @if(!empty($row->location->name))
            <i class="icofont-paper-plane"></i>
            {{$row->location->name ?? ''}}
        @endif
    </div>
    <div class="item-title">
        <a @if(!empty($blank)) target="_blank" @endif href="{{$row->getDetailUrl()}}">
            <h4>
                {{$row->title}}
            </h4>
        </a>
        {!! $row->short_desc !!}

    </div>

    <div class="info">
        <div class="duration">
            <i class="icofont-wall-clock"></i>
            {{$row->duration}} {{__("Day")}}
        </div>
        <div class="g-price">
            <div class="prefix">
                <i class="icofont-flash"></i>
                <span class="fr_text">{{__("from")}}</span>
            </div>
            <div class="price">
                <span class="onsale">{{ $row->display_sale_price }}</span>
                <span class="text-price">{{ $row->display_price }}</span>
            </div>
        </div>
    </div>
</div>
