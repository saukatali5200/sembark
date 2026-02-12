<?php

namespace App\Http\Controllers\adminpnlx;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Config;
use App\Models\Admin;
use App\Models\Acl;
use App\Models\Role;
use App\Models\StaffPermission;

use Illuminate\Support\Facades\{DB, Session, Validator, Redirect, Auth, Hash, Str, Notification};
use Illuminate\Support\Facades\{URL, View, Response, Cookie, File, Mail, Blade, Cache, Http};
use Yajra\DataTables\Facades\DataTables;

class StaffController extends Controller
{

    public $modelName   = 'Staff';
    public function __construct(Request $request)
    {
        View()->Share('sectionName', $this->modelName);
        View()->Share('modelName', $this->modelName);
        $this->request = $request;
    }

    public function index()
    {
        if (!hasPermission('Staff', 'listing_permission')) {
            return redirect()->route('Admin.dashboard');
        }
        $results = Admin::get();
         return View("adminpnlx.$this->modelName.index", compact('results'));
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = Admin::where('id', '!=', 1)
                    ->select(['id', 'name', 'email', 'phone_number', 'created_at', 'is_active'])
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
                    <a href="'.route("Staff.edit", base64_encode($row->id)).'" class="btn btn-warning btn-sm">Edit</a>
                    <a href="'.route("Staff.destroy", base64_encode($row->id)).'" class="btn btn-danger btn-sm">Delete</a>
                ';
            })
            ->rawColumns(['status','action'])
                ->make(true);
        }
    }
    

    
    public function create(){

        $roles = Role::where('id', '!=', 1)->get();
        return view('adminpnlx.' . $this->modelName . '.add', compact('roles'));
    }


    public function store(Request $request)
    {
        $formData = $request->all();
        $validator = Validator::make(
        $request->all(),
            array(
                'name'                             =>  'required',
                'email'                            =>  'required|email|unique:users',
                'phone_number'                     =>  'required',
                'password'                         =>  'required',
                'image'                            =>  'nullable|mimes:jpeg,png,jpg',
            ),
            array(
                'name.required'                  => 'The name field must be required',
                'email.required'                 => 'The email field must be required',
                'phone_number.required'          => 'The phone number field must be required',
                'password.required'              => 'The password field must be required',
                'image.required'                 => 'The image field must be required',
                'image.mimes'                    => 'The image field must be type .png .jpeg .jpg',
            )
        );
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {

                $obj                            = new Admin;
                $obj->role                      = $request->input('role_id');
                $obj->name                      = $request->input('name');
                $obj->email                     = $request->input('email');
                $obj->phone_number              = $request->input('phone_number');
                $obj->password                  = Hash::make($request->input('password'));
            $obj->save();
           

            Session::flash('success', "Staff has been Added successfully");
            return redirect()->route($this->modelName . '.index');
        }
    }

    public function show($modelId)
    {
        $modelId = base64_decode($modelId);
        if ($modelId) {
            $modelDetails = Admin::where('id', $modelId)->first();
            return view('adminpnlx.' . $this->modelName . '.view', compact('modelDetails'));
        } else {
            return redirect::back();
        }
    }

    public function edit($id)
    {
        $modelId = base64_decode($id);
        if ($modelId) {
            $modelDetails = Admin::where('id', $modelId)->first();
            $roles = Role::where('id', '!=', 1)->get();
            return view("adminpnlx." . $this->modelName . ".edit", compact('modelDetails', 'roles'));
        } else {
            return Redirect::back();
        }
    }

    public function update(Request $request, $id)
    {
        $modelId                = base64_decode($id);
        $model                  = Admin::findorFail($modelId);

        if (empty($model)) {
            return Redirect::back();
        }
        $formData                =    $request->all();
        if (!empty($formData)) {
            $validator = Validator::make(
                $request->all(),
                array(
                    'name'                             =>  'required',
                    'email'                            =>  'required',
                    'phone_number'                     =>  'required',
                    'image'                            =>  'nullable|mimes:jpeg,png,jpg',
                ),
                array(
                    'name.required'                  => 'The name field must be required',
                    'email.required'                 => 'The email field must be required',
                    'phone_number.required'          => 'The phone number field must be required',
                    'image.mimes'                    => 'The image field must be type .png .jpeg .jpg',
                )
            );
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            } else {
                $obj                            = $model;
                $obj->role                      = $request->input('role_id');
                $obj->name                      = $request->input('name');
                $obj->email                     = $request->input('email');
                $obj->phone_number              = $request->input('phone_number');
                $obj->password                  = Hash::make($request->input('password'));
                // $obj->gender                    = $request->input('gender');
                // $obj->description               = $request->input('description');
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
            Admin::where('id', $modelId)->delete();
            $statusMessage   =   "Staff has been deleted successfully";
            Session()->flash('flash_notice', $statusMessage);
        }
        return redirect::back();
    }

    
}