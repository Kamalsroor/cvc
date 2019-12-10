<?php
namespace Modules\Template\Blocks;
class Text extends BaseBlock
{
    public function __construct()
    {
        $this->setOptions([
            'settings' => [
                [
                    'id'    => 'content',
                    'type'  => 'textArea',
                    'label' => __('Content')
                ],
                [
                    'id'    => 'content2',
                    'type'  => 'editor',
                    'label' => __('Editor')
                ],
                [
                    'id'        => 'class',
                    'type'      => 'input',
                    'inputType' => 'text',
                    'label'     => __('Wrapper Class (opt)')
                ],
                [
                    'id'          => 'list_item',
                    'type'        => 'listItem',
                    'label'       => __('Wrapper Class (opt)'),
                    'title_field' => 'class',
                    'settings'    => [
                        [
                            'id'        => 'class',
                            'type'      => 'input',
                            'inputType' => 'text',
                            'label'     => "Input"
                        ],
                        [
                            'id'    => 'content',
                            'type'  => 'textArea',
                            'label' => __('Content')
                        ]
                    ]
                ],
                [
                    'id'    => 'bg',
                    'type'  => 'uploader',
                    'label' => __('Image Uploader')
                ],
            ]
        ]);
    }

    public function getName()
    {
        return __('Text');
    }


    // public function content($model = [])
    // {
    //     $data = [
    //         'bg_image_url'  => '',
    //     ];
    //     $data = array_merge($model, $data);
    //     if (!empty($model['bg_image'])) {
    //         $data['bg_image_url'] = FileHelper::url($model['bg_image'], 'full');
    //     }
    //     return view('Tour::frontend.blocks.bannar-text.index', $data);
    // }
}