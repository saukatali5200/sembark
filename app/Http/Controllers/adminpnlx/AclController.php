<?php

namespace App\Http\Controllers\adminpnlx;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Config;
use App\Models\Acl;

use Illuminate\Support\Facades\{DB, Session, Validator, Redirect, Auth, Hash, Str, Notification};
use Illuminate\Support\Facades\{URL, View, Response, Cookie, File, Mail, Blade, Cache, Http};
use Yajra\DataTables\Facades\DataTables;

class AclController extends Controller
{

    public $modelName   = 'Acl';
    public function __construct(Request $request)
    {
        View()->Share('sectionName', $this->modelName);
        View()->Share('modelName', $this->modelName);
        $this->request = $request;
    }

    public function index()
    {
        if (!hasPermission('Acl', 'listing_permission')) {
            return redirect()->route('Admin.dashboard');
        }
        $results = Acl::get();
         return View("adminpnlx.$this->modelName.index", compact('results'));
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = Acl::where('is_active', 1)
                    ->select(['id', 'name', 'description', 'icon', 'created_at', 'is_active'])
                    ->get();

            return DataTables::of($data)

                ->addColumn('status', function ($row) {
                    $class = $row->is_active ? 'bg-success' : 'bg-danger';
                    $text = $row->is_active == 1 ? 'Active' : 'Inactive';
                    return '<span class="badge badge-pill badge-status '.$class.'">'.$text.'</span>';
                })

                ->addColumn('created_at', function ($row) {
                    return $row->created_at->format('d-m-Y H:i');
                })
            ->addColumn('action', function ($row) {
                return '
                    <a href="'.route("Acl.edit", base64_encode($row->id)).'" class="btn btn-warning btn-sm">Edit</a>
                    <a href="'.route("Acl.destroy", base64_encode($row->id)).'" class="btn btn-danger btn-sm">Delete</a>
                ';
            })
            ->rawColumns(['status','action'])
                ->make(true);
        }
    }
    

    
    public function create(){
        return view('adminpnlx.' . $this->modelName . '.add');
    }


    public function store(Request $request)
    {
        $formData = $request->all();
        $validator = Validator::make(
        $request->all(),
            array(
                'name'                             =>  'required',
                // 'email'                            =>  'required|email|unique:users',
            ),
            array(
                // 'name.required'                  => 'The name field must be required',
            )
        );
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {

                $obj                            = new Acl;
                $obj->name                      = $request->input('name');
                $obj->description                     = $request->input('description');
            $obj->save();
           

            Session::flash('success', "Staff has been Added successfully");
            return redirect()->route($this->modelName . '.index');
        }
    }

    public function show($modelId)
    {
        $modelId = base64_decode($modelId);
        if ($modelId) {
            $modelDetails = Acl::where('id', $modelId)->first();
            return view('adminpnlx.' . $this->modelName . '.view', compact('modelDetails'));
        } else {
            return redirect::back();
        }
    }

    public function edit($id)
    {
        $modelId = base64_decode($id);
        if ($modelId) {
            $modelDetails = Acl::where('id', $modelId)->first();
            return view("adminpnlx." . $this->modelName . ".edit", compact('modelDetails'));
        } else {
            return Redirect::back();
        }
    }

    public function update(Request $request, $id)
    {
        $modelId                = base64_decode($id);
        $model                  = Acl::findorFail($modelId);

        if (empty($model)) {
            return Redirect::back();
        }
        $formData                =    $request->all();
        if (!empty($formData)) {
            $validator = Validator::make(
                $request->all(),
                array(
                    'name'                             =>  'required',
                ),
                array(
                    'name.required'                  => 'The name field must be required',
                )
            );
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            } else {
                $obj                            = $model;
                $obj->name                      = $request->input('name');
                $obj->description               = $request->input('description');
                $obj->save();
                Session::flash('success', "Staff has been updated successfully");
                return Redirect::route($this->modelName . ".index");

            }
        }

    }

    public function destroy($id)
    {

        $modelId = base64_decode($id);
        if ($modelId) {
            Acl::where('id', $modelId)->delete();
            $statusMessage   =   "Staff has been deleted successfully";
            Session()->flash('flash_notice', $statusMessage);
        }
        return redirect::back();
    }
}