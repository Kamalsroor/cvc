<?php
namespace Modules\Hotel\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\AdminController;
use Modules\Hotel\Models\Hotel;
use Modules\Location\Models\Location;

class HotelController extends AdminController
{
    public function __construct()
    {
        $this->setActiveMenu('admin/module/hotel');
        parent::__construct();
    }

    public function index(Request $request)
    {
        $this->checkPermission('hotel_view');
        if ($request->isMethod('post') and !empty($request->input())) {
            $request->validate([
                'name' => 'required',
            ]);
            $row = new Hotel($request->input());
            $row->status = 'publish';
            if ($row->save()) {
                $row->saveSEO($request);
                return redirect('admin/module/hotel')->with('success', __("Hotel created!"));
            }
        }
        $listLocation = Hotel::query() ;
        if (!empty($search = $request->query('s'))) {
            $listLocation->where('name', 'LIKE', '%' . $search . '%');
        }
        $listLocation->orderBy('created_at', 'asc');
        $data = [
            'rows'        => $listLocation->get()->toTree(),
            'row'         => new Hotel(),
            'hotel_location' => Location::where('status', 'publish')->get()->toTree(),
            'breadcrumbs' => [
                [
                    'name' => __('Location'),
                    'url'  => 'admin/module/location'
                ],
                [
                    'name'  => __('All'),
                    'class' => 'active'
                ],
            ]
        ];
        return view('Hotel::admin.index', $data);
    }

    public function edit(Request $request, $id)
    {
        // $this->checkPermission('hotel_update');
        $row = Hotel::find($id);
        if (empty($row)) {
            return redirect('admin/module/hotel');
        }
        if (!empty($request->input())) {
            $row->fill($request->input());
            if ($row->save()) {
                $row->saveSEO($request);
                return redirect('admin/module/hotel')->with('success', __('Hotel updated'));
            }
        }
        $data = [
            'row'         => $row,
            'parents'     => Hotel::get()->toTree(),
            'hotel_location' => Location::where('status', 'publish')->get()->toTree(),
            'breadcrumbs' => [
                [
                    'name' => __('Location'),
                    'url'  => 'admin/module/hotel'
                ],
                [
                    'name'  => __('Edit'),
                    'class' => 'active'
                ],
            ]
        ];
        return view('Hotel::admin.detail', $data);
    }

    public function getForSelect2(Request $request)
    {
        $pre_selected = $request->query('pre_selected');
        $selected = $request->query('selected');

        if($pre_selected && $selected){
            $item = Hotel::find($selected);
            if(empty($item)){
                return response()->json([
                    'text'=>''
                ]);
            }else{
                return response()->json([
                    'text'=>$item->name
                ]);
            }
        }

        $q = $request->query('q');
        $query = Hotel::select('id', 'name as text')->where("status","publish");
        if ($q) {
            $query->where('name', 'like', '%' . $q . '%');
        }
        $res = $query->orderBy('id', 'desc')->limit(20)->get();
        return response()->json([
            'results' => $res
        ]);
    }

    public function editBulk(Request $request)
    {
        $ids = $request->input('ids');
        $action = $request->input('action');
        if (empty($ids) or !is_array($ids)) {
            return redirect()->back()->with('error', __("Select at least 1 item!"));
        }
        if (empty($action)) {
            return redirect()->back()->with('error', __('Select an Action!'));
        }
        if ($action == "delete") {
            foreach ($ids as $id) {
                $query = Hotel::where("id", $id);
                if (!$this->hasPermission('hotel_manage_others')) {
                    $query->where("create_user", Auth::id());
                    $this->checkPermission('hotel_delete');
                }
                $query->first()->delete();
            }
        } else {
            foreach ($ids as $id) {
                $query = Hotel::where("id", $id);
                if (!$this->hasPermission('hotel_manage_others')) {
                    $query->where("create_user", Auth::id());
                    $this->checkPermission('hotel_update');
                }
                $query->update(['status' => $action]);
            }
        }
        return redirect()->back()->with('success', __('Updated success!'));
    }
}
