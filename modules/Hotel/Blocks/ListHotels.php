<?php
namespace Modules\Hotel\Blocks;

use Modules\Template\Blocks\BaseBlock;
use Modules\Hotel\Models\Hotel;
// use Modules\Tour\Models\TourCategory;
use Modules\Location\Models\Location;

class ListHotels extends BaseBlock
{
    function __construct()
    {
        $this->setOptions([
            'settings' => [
                [
                    'id'        => 'title',
                    'type'      => 'input',
                    'inputType' => 'text',
                    'label'     => __('Title')
                ],
                [
                    'id'        => 'number',
                    'type'      => 'input',
                    'inputType' => 'number',
                    'label'     => __('Number Item')
                ],
                [
                    'id'            => 'style',
                    'type'          => 'select',
                    'label'         => __('Style'),
                    'values'        => [
                        [
                            'id'   => 'normal',
                            'name' => __("Normal")
                        ],
                        [
                            'id'   => 'carousel',
                            'name' => __("Slider Carousel")
                        ]
                    ],
                    'selectOptions' => [
                        'noneSelectedText' => __('-- Select --')
                    ]
                ],
                [
                    'id'      => 'location_id',
                    'type'    => 'select2',
                    'label'   => __('Filter by Location'),
                    'select2' => [
                        'ajax'  => [
                            'url'      => url('/admin/module/location/getForSelect2'),
                            'dataType' => 'json'
                        ],
                        'width' => '100%'
                    ],
                    'pre_selected'=>url('/admin/module/location/getForSelect2?pre_selected=1')
                ],
                [
                    'id'            => 'order',
                    'type'          => 'select',
                    'label'         => __('Order'),
                    'values'        => [
                        [
                            'id'   => 'id',
                            'name' => __("Date Create")
                        ],
                        // [
                        //     'id'   => 'order_num',
                        //     'name' => __("Order Num")
                        // ],
                        /*[
                            'id'   => 'review_score',
                            'name' => __("Review Score")
                        ],*/
                    ],
                    'selectOptions' => [
                        'noneSelectedText' => __('-- Select --')
                    ]
                ],
                [
                    'id'            => 'order_by',
                    'type'          => 'select',
                    'label'         => __('Order By'),
                    'values'        => [
                        [
                            'id'   => 'asc',
                            'name' => __("ASC")
                        ],
                        [
                            'id'   => 'desc',
                            'name' => __("DESC")
                        ],
                    ],
                    'selectOptions' => [
                        'noneSelectedText' => __('-- Select --')
                    ]
                ],
            ]
        ]);
    }

    public function getName()
    {
        return __('List Hotels');
    }

    public function content($model = [])
    {
        $model_Tour = Hotel::query();
        if(empty($model['order'])) $model['order'] = "bravo_hotels.id";
        if(empty($model['order_by'])) $model['order_by'] = "desc";
        if(empty($model['number'])) $model['number'] = 5;
        if (!empty($model['location_id'])) {
            $location = Location::where('id', $model['location_id'])->where("status","publish")->first();
            if(!empty($location)){
                $model_Tour->join('bravo_locations', function ($join) use ($location) {
                    $join->on('bravo_locations.id', '=', 'bravo_hotels.location_id')
                        ->where('bravo_locations._lft', '>=', $location->_lft)
                        ->where('bravo_locations._rgt', '<=', $location->_rgt);
                });
            }
        }
        $model_Tour->orderBy("bravo_hotels.".$model['order'], $model['order_by']);
        $model_Tour->where("bravo_hotels.status", "publish");
        $model_Tour->with('location');
        $model_Tour->groupBy("bravo_hotels.id");
        $list = $model_Tour->limit($model['number'])->get();
        $data = [
            'rows'       => $list,
            'style_list' => $model['style'],
            'title'      => $model['title'],
        ];
        return view('Hotel::frontend.blocks.list-hotel.index', $data);
    }
}
