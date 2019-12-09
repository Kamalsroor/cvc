<?php
namespace Modules\Hotel\Models;

use App\BaseModel;
use Kalnoy\Nestedset\NodeTrait;
use Modules\Media\Helpers\FileHelper;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Core\Models\SEO;

class Hotel extends BaseModel
{
    use SoftDeletes;
    use NodeTrait;
    protected $table = 'bravo_hotels';
    protected $fillable = [
        'name',
        'content',
        'gallery',
        'location_id',
        'image_id',
        'map_lat',
        'map_lng',
        'map_zoom',
        'status',
        'parent_id'
    ];
    protected $slugField     = 'slug';
    protected $slugFromField = 'name';

    public static function getModelName()
    {
        return __("Hotel");
    }

    public static function searchForMenu($q = false)
    {
        $query = static::select('id', 'name');
        if (strlen($q)) {

            $query->where('name', 'like', "%" . $q . "%");
        }
        $a = $query->limit(10)->get();
        return $a;
    }


    
    public function getGallery($featuredIncluded = false)
    {
        if (empty($this->gallery))
            return $this->gallery;
        $list_item = [];
        if ($featuredIncluded and $this->image_id) {
            $list_item[] = [
                'large' => FileHelper::url($this->image_id, 'full'),
                'thumb' => FileHelper::url($this->image_id, 'thumb')
            ];
        }
        $items = explode(",", $this->gallery);
        foreach ($items as $k => $item) {
            $large = FileHelper::url($item, 'full');
            $thumb = FileHelper::url($item, 'thumb');
            $list_item[] = [
                'large' => $large,
                'thumb' => $thumb
            ];
        }
        return $list_item;
    }
    
    public function Location()
    {
        return $this->belongsTo('Modules\Location\Models\Location', 'location_id');
    }

    public function getNumberServiceInLocation($location)
    {
        if(!empty($location)) {
            $number = parent::join('bravo_locations', function ($join) use ($location) {
                $join->on('bravo_locations.id', '=', 'bravo_hotels.location_id')->where('bravo_locations._lft', '>=', $location->_lft)->where('bravo_locations._rgt', '<=', $location->_rgt);
            })->where("bravo_hotels.status", "publish")->count("bravo_hotels.id");
        }
        if ($number > 1) {
            return __(":number Hotels", ['number' => $number]);
        }
        return __(":number Hotel", ['number' => $number]);
    }
    
    public function getImageUrl($size = "medium")
    {
        $url = FileHelper::url($this->image_id, $size);
        return $url ? $url : '';
    }

    public function getDisplayNumberServiceInHotel($service_type)
    {
        $allServices = config('booking.services');
        $module = new $allServices[$service_type];
        return $module->getNumberServiceInHotel($this);
    }

    public function saveSEO(\Illuminate\Http\Request $request)
    {
        $meta = SEO::where('object_id', $this->id)->where('object_model', 'hotel')->first();
        if (!$meta) {
            $meta = new SEO();
            $meta->object_id = $this->id;
            $meta->object_model = "hotel";
        }
        $meta->fill($request->input());
        return $meta->save();
    }

    public function getSeoMeta()
    {
        $meta = SEO::where('object_id', $this->id)->where('object_model', 'hotel')->first();
        if(!empty($meta)){
            $meta = $meta->toArray();
        }
        $meta['slug'] = $this->slug;
        $meta['full_url'] = $this->getDetailUrl();
        $meta['service_title'] = $this->name;
        return $meta;
    }

    public function getDetailUrl()
    {
        return url(config('hotel.hotel_route_prefix')."/".$this->slug);
    }
}