<?php
namespace Modules\Media\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Modules\AdminController;
use Modules\Media\Helpers\FileHelper;
use Modules\Media\Models\MediaFile;
use Intervention\Image\ImageManagerStatic as Image;
use Spatie\LaravelImageOptimizer\Facades\ImageOptimizer;

class MediaController extends AdminController
{
    public function browser()
    {
        echo FileHelper::url();
    }

    public function sendError($message, $data = [])
    {
        $data['uploaded'] = 0;
        $data['error'] = [
            "message"=>$message
        ];

        parent::sendError($message,$data);
    }

    public function sendSuccess($data = [], $message = '')
    {
        $data['uploaded'] = 1;

        if(!empty($data['data']->file_name))
        {
            $data['fileName'] = $data['data']->file_name;
            $data['url'] = FileHelper::url($data['data']->id,'full');
        }
        parent::sendSuccess($data, $message); // TODO: Change the autogenerated stub
    }

    public function compressAllImages(){
        $files = MediaFile::get();

        if(!empty($files))
        {
            foreach ($files as $file)
            {
                if(FileHelper::isImage($file))
                {
                    if(Storage::disk('uploads')->exists('public/'.$file->file_path))
                    ImageOptimizer::optimize(public_path('app/public/'.$file->file_path));
                }
            }
        }

        echo "Processed: ".count($files);
    }

    public function store(Request $request)
    {

        $ckEditor = $request->query('ckeditor');

        if (!$this->hasPermissionMedia()) {
            $this->sendError('There is no permission upload');
        }
        $fileName = 'file';
        if($ckEditor) $fileName = 'upload';

        $file = $request->file($fileName);
        $file_type = $request->file('type');
        if (empty($file)) {
            $this->sendError(__("Please select file"));
        }
        try {
            static::validateFile($file, $file_type);
        } catch (\Exception $exception) {
            $this->sendError($exception->getMessage());
        }
        $folder = '';
        $id = Auth::id();
        if ($id) {
            $folder .= sprintf('%04d', (int)$id / 1000) . '/' . $id . '/';
        }
        $folder = $folder . date('Y/m/d');
        $newFileName = Str::slug(substr($file->getClientOriginalName(), 0, strrpos($file->getClientOriginalName(), '.')));
        $i = 0;
        do {
            $newFileName2 = $newFileName . ($i ? $i : '');
            $testPath = $folder . '/' . $newFileName2 . '.' . $file->getClientOriginalExtension();
            $i++;
        } while (Storage::disk('uploads')->exists($testPath));
        // $file->
        // $file->insert(public_path('images/final logo-03.png'), 'bottom-right', 10, 10);
        
        $check = $file->storeAs( $folder, $newFileName2 . '.' . $file->getClientOriginalExtension(),'uploads');

        // Try to compress Images
        ImageOptimizer::optimize(public_path("uploads/".$check));

        if ($check) {
            try {
                $fileObj = new MediaFile();
                $fileObj->file_name = $newFileName2;
                $fileObj->file_path = $check;
                $fileObj->file_size = $file->getSize();
                $fileObj->file_type = $file->getMimeType();
                $fileObj->file_extension = $file->getClientOriginalExtension();
                if (FileHelper::checkMimeIsImage($file->getMimeType())) {
                    list($width, $height, $type, $attr) = getimagesize(public_path("uploads/".$check));
                    $fileObj->file_width = $width;
                    $fileObj->file_height = $height;
                }
                $fileObj->save();
                // Sizes use for uploaderAdapter:
                // https://ckeditor.com/docs/ckeditor5/latest/framework/guides/deep-dive/upload-adapter.html#the-anatomy-of-the-adapter
                $fileObj->sizes = [
                    'default' => asset('uploads/' . $fileObj->file_path),
                    '150'     => FileHelper::url($fileObj->id, 'thumb'),
                    '600'     => FileHelper::url($fileObj->id, 'medium'),
                    '1024'    => FileHelper::url($fileObj->id, 'large'),
                ];
                $this->sendSuccess(['data' => $fileObj]);
            } catch (\Exception $exception) {
                Storage::disk('uploads')->delete($check);
                $this->sendError($exception->getMessage());
            }
        }
        $this->sendError(__("Can not store the file"));
    }

    /**
     * @param $file UploadedFile
     * @param $group string
     *
     * @return bool
     *
     * @throws \Exception
     */
    public static function validateFile($file, $group = "default")
    {
        $allowedExts = [
            'jpg',
            'jpeg',
            'bmp',
            'png',
            'gif',
            'zip',
            'rar',
            'pdf',
            'xls',
            'xlsx',
            'txt',
            'doc',
            'docx',
            'ppt',
            'pptx',
            'webm',
            'mp4',
            'mp3',
            'flv',
            'vob',
            'avi',
            'mov',
            'wmv',
            'svg'
        ];
        $allowedExtsImage = [
            'jpg',
            'jpeg',
            'bmp',
            'png',
            'gif',
            'svg'
        ];
        $uploadConfigs = [
            'default' => [
                'types'    => $allowedExts,
                "max_size" => 20000000
                // 20MB
            ],
            'image'   => [
                'types'    => $allowedExtsImage,
                "max_size" => 20000000
                // 20MB
            ],
        ];
        $config = isset($uploadConfigs[$group]) ? $uploadConfigs[$group] : $uploadConfigs['default'];

        if (!in_array(strtolower($file->getClientOriginalExtension()), $config['types'])) {
            throw new \Exception(__("File type are not allowed"));
        }
        if ($file->getSize() > $config['max_size']) {
            throw new \Exception(__("Maximum upload file size is :max_size B", ['max_size' => $config['max_size']]));
        }
        return true;
    }

    public function getLists(Request $request)
    {
        if (!$this->hasPermissionMedia()) {
            $this->sendError('There is no permission upload');
        }
        $file_type = $request->input('file_type', 'image');
        $page = $request->input('page', 1);
        $s = $request->input('s');
        $offset = ($page - 1) * 32;
        $model = MediaFile::query();
        $model2 = MediaFile::query();
        if (!Auth::user()->hasPermissionTo("media_manage")) {
             $model->where('create_user', Auth::id());
             $model2->where('create_user', Auth::id());
        }
        switch ($file_type) {
            case "image":
                $model->whereIn('file_extension', [
                    'png',
                    'jpg',
                    'jpeg',
                    'gif',
                    'bmp',
                    'svg'
                ]);
                $model2->whereIn('file_extension', [
                    'png',
                    'jpg',
                    'jpeg',
                    'gif',
                    'bmp'
                ]);
                break;
        }
        if ($s) {
            $model->where('file_name', 'like', '%' . ($s) . '%');
            $model2->where('file_name', 'like', '%' . ($s) . '%');
        }
        $files = $model->limit(32)->offset($offset)->orderBy('id', 'desc')->get();
        // Count
        $total = $model2->count();
        $totalPage = ceil($total / 32);
        if (!empty($files)) {
            foreach ($files as $file) {
                $file->medium_size = FileHelper::url($file);
                $file->thumb_size = FileHelper::url($file, 'thumb');
            }
        }
        $this->sendSuccess([
            'data'      => $files,
            'total'     => $total,
            'totalPage' => $totalPage
        ]);
    }

    /**
     * Check Permission Media
     *
     * @return bool
     */
    private function hasPermissionMedia()
    {
        if (Auth::user()->hasPermissionTo("media_upload")) {
            return true;
        }
        if (Auth::user()->hasPermissionTo("media_manage")) {
            return true;
        }
        return false;
    }

    public function ckeditorBrowser(){
        return view('Media::ckeditor');
    }
}
