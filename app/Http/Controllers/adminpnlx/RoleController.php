<?php

namespace App\Http\Controllers\adminpnlx;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Config;
use App\Models\Role;
use App\Models\Acl;
use App\Models\StaffPermission;

use Illuminate\Support\Facades\{DB, Session, Validator, Redirect, Auth, Hash, Str, Notification};
use Illuminate\Support\Facades\{URL, View, Response, Cookie, File, Mail, Blade, Cache, Http};
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    public $modelName   = 'Role';
    public function __construct(Request $request)
    {
        View()->Share('sectionName', $this->modelName);
        View()->Share('modelName', $this->modelName);
        $this->request = $request;
    }

    public function index()
    {
        if (!hasPermission('Role', 'listing_permission')) {
            return redirect()->route('Admin.dashboard');
        }
        $results = Role::where('id', '!=', 1)->get();
         return View("adminpnlx.$this->modelName.index", compact('results'));
    }

    public function list(Request $request)
    {
        $roles = Role::select('id','name','description','created_at','is_active')->where('id', '!=', 1);

        return datatables()->of($roles)
            ->addColumn('status', function ($row) {
                return $row->is_active
                    ? '<span class="badge bg-success">Active</span>'
                    : '<span class="badge bg-danger">Inactive</span>';
            })
            ->addColumn('action', function ($row) {
                return '
                    <a href="'.route("Role.permissions", base64_encode($row->id)).'" class="btn btn-warning btn-sm">permissions</a>
                    <a href="'.route("Role.edit", base64_encode($row->id)).'" class="btn btn-warning btn-sm">Edit</a>
                    <a href="'.route("Role.destroy", base64_encode($row->id)).'" class="btn btn-danger btn-sm">Delete</a>
                ';
            })
            ->rawColumns(['status','action'])
            ->make(true);
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
                'name'                       =>  'required',
            ),
            array(
                'name.required'            => 'The name field must be required',
            )
        );
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $obj                      = new Role;
            $obj->name                = $request->input('name');
            $obj->description         = $request->input('description');
            $obj->save();

            Session::flash('success', "User has been Added successfully");
            return redirect()->route($this->modelName . '.index');
        }
    }

    public function show($modelId)
    {
        $modelId = base64_decode($modelId);
        if ($modelId) {
            $modelDetails = Role::where('id', $modelId)->first();
            return view('adminpnlx.' . $this->modelName . '.view', compact('modelDetails'));
        } else {
            return redirect::back();
        }
    }

    public function edit($id)
    {
        $modelId = base64_decode($id); 
        if ($modelId) {
            $modelDetails = Role::where('id', $modelId)->first();
            return view("adminpnlx." . $this->modelName . ".edit", compact('modelDetails'));
        } else {
            return Redirect::back();
        }
    }

    public function update(Request $request, $id)
    {
        $modelId                = base64_decode($id);
        $model                  = Role::findorFail($modelId);

        if (empty($model)) {
            return Redirect::back();
        }
        $formData                =    $request->all();
        if (!empty($formData)) {
            $validator = Validator::make(
                $request->all(),
                array(
                    'name'                       =>  'required',
                ),
                array(
                    'name.required'            => 'The first name field must be required',
                )
            );
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            } else {
                $obj                            = $model;
                $obj->name                = $request->input('name');
                $obj->description         = $request->input('description');
                $obj->save();
                Session::flash('success', "User has been updated successfully");
                return Redirect::route($this->modelName . ".index");
            }
        }

    }

    public function destroy($id)
    {
        $modelId = base64_decode($id);
        if ($modelId) {
            Role::where('id', $modelId)->delete();
            $statusMessage   =   "User has been deleted successfully";
            Session()->flash('flash_notice', $statusMessage);
        }
        return redirect::back();
    }

    // public function permissions($role_id)
    // {
    //     $roleID = base64_decode($role_id);
    //     if ($roleID) {
    //         $acl_modules = Acl::get();
    //         // dd($acl_modules);
    //         $staffPermissions = StaffPermission::where('role_id', $roleID)->get();
    //         // dd($staffPermissions);
    //         return view("adminpnlx." . $this->modelName . ".permission", compact('acl_modules', 'staffPermissions', 'roleID'));
    //     } else {
    //         return Redirect::back();
    //     }
    // }

    public function permissions($role_id)
    {
        $roleID = base64_decode($role_id);

        if ($roleID) {

            $acl_modules = Acl::get();

            $permissions = StaffPermission::where('role_id', $roleID)->get();

            
            // Important Fix 👇
            $staffPermissions = [];
            
            // dd($permissions);
            foreach ($permissions as $perm) {
                $staffPermissions[$perm->module_id] = $perm;
            }

            return view(
                "adminpnlx." . $this->modelName . ".permission",
                compact('acl_modules', 'staffPermissions', 'roleID')
            );

        } else {
            return Redirect::back();
        }
    }


    public function savePermissions(Request $request, $encodedRoleId)
    {
        $roleId = base64_decode($encodedRoleId);
        $formData = $request->all();
        // dd($formData);

        // Purane permissions delete
        StaffPermission::where('role_id', $roleId)->delete();
        foreach ($formData as $key => $value) {
            if (str_starts_with($key, 'acl_module_')) {

                $moduleId = $value;

                $obj = new StaffPermission();
                $obj->role_id   = $roleId;
                $obj->module_id = $moduleId;
                $obj->listing_permission = $request->input('listing_permission_' . $moduleId, 0);
                $obj->view_permission    = $request->input('view_permission_' . $moduleId, 0);
                $obj->create_permission  = $request->input('create_permission_' . $moduleId, 0);
                $obj->update_permission  = $request->input('update_permission_' . $moduleId, 0);
                $obj->delete_permission  = $request->input('delete_permission_' . $moduleId, 0);
                $obj->save(); 
            }
        }

        // return redirect()->back()->with('success', 'Permissions updated successfully.');
        return redirect()->route($this->modelName . '.index');
    }



}