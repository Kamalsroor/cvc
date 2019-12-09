<div class="form-group">
    <label>{{__("Name")}}</label>
    <input type="text" value="{{$row->name}}" placeholder="{{__("Location name")}}" name="name" class="form-control">
</div>

<div class="form-group">
    <label class="control-label">{{__("Description")}}</label>
    <div class="">
        <textarea name="content" class="d-none has-ckeditor" cols="30" rows="10">{{$row->content}}</textarea>
    </div>
</div>
<div class="form-group">
    <label class="control-label">{{__("Location")}}</label>
    <div class="">
        <select name="location_id" class="form-control">
            <option value="">{{__("-- Please Select --")}}</option>
            <?php
            $traverse = function ($locations, $prefix = '') use (&$traverse, $row) {
                foreach ($locations as $location) {
                    $selected = '';
                    if ($row->location_id == $location->id)
                        $selected = 'selected';
                    printf("<option value='%s' %s>%s</option>", $location->id, $selected, $prefix . ' ' . $location->name);
                    $traverse($location->children, $prefix . '-');
                }
            };
            $traverse($hotel_location);
            ?>
        </select>
    </div>
</div>
<div class="form-group">
    <label class="control-label">{{__("Gallery")}}</label>
    {!! \Modules\Media\Helpers\FileHelper::fieldGalleryUpload('gallery',$row->gallery) !!}
</div>


<div class="form-group form-index-hide">
    <label class="control-label">{{__("Map Engine")}}</label>
    <div class="control-map-group">
        <div id="map_content"></div>
        <div class="g-control">
            <div class="form-group">
                <label>{{__("Map Lat")}}:</label>
                <input type="text" name="map_lat" class="form-control" value="{{$row->map_lat}}">
            </div>
            <div class="form-group">
                <label>{{__("Map Lng")}}:</label>
                <input type="text" name="map_lng" class="form-control" value="{{$row->map_lng}}">
            </div>
            <div class="form-group">
                <label>{{__("Map Zoom")}}:</label>
                <input type="text" name="map_zoom" class="form-control" value="{{$row->map_zoom ?? "8"}}">
            </div>
        </div>
    </div>
</div>