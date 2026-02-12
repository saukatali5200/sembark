<?php

namespace App\Http\Controllers\adminpnlx;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Config;
use App\Models\User;
use App\Models\EmailAction;
use App\Models\EmailTemplate;

use Illuminate\Support\Facades\{DB, Session, Validator, Redirect, Auth, Hash, Str, Notification};
use Illuminate\Support\Facades\{URL, View, Response, Cookie, File, Mail, Blade, Cache, Http};
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{

    public $modelName   = 'User';
    public function __construct(Request $request)
    {
        View()->Share('sectionName', $this->modelName);
        View()->Share('modelName', $this->modelName);
        $this->request = $request;
    }

    public function index()
    {
        if (!hasPermission('User', 'listing_permission')) {
            return redirect()->route('Admin.dashboard');
        }
         return View("adminpnlx.$this->modelName.index");
    }

    public function getUsers(Request $request)
    {
        if ($request->ajax()) {
            $data = User::select(['id','image', 'username','email','phone_number','dob','gender','created_at','is_active']);

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
                    <a href="'.route("User.edit", base64_encode($row->id)).'" class="btn btn-warning btn-sm">Edit</a>
                    <a href="'.route("User.destroy", base64_encode($row->id)).'" class="btn btn-danger btn-sm">Delete</a>
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
                'first_name'                       =>  'required',
                'last_name'                        =>  'required',
                'email'                            =>  'required|email|unique:users',
                'phone_number'                     =>  'required',
                // 'gender'                            =>  'required',
                'password'                         =>  'required',
                // 'confirm_password'                 =>  'same:password',
                'image'                            =>  'nullable|mimes:jpeg,png,jpg',
            ),
            array(
                'first_name.required'            => 'The first name field must be required',
                'last_name.required'             => 'The last name field must be required',
                'email.required'                 => 'The email field must be required',
                'phone_number.required'          => 'The phone number field must be required',
                // 'gender.required'                => 'The gender field must be required',
                'password.required'              => 'The password field must be required',
                // 'confirm_password.required'      => 'The confirm password field must be required',
                'image.required'                 => 'The image field must be required',
                'image.mimes'                    => 'The image field must be type .png .jpeg .jpg',
            )
        );
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {

                $obj                            = new User;
                $obj->first_name                = $request->input('first_name');
                $obj->last_name                 = $request->input('last_name');
                $obj->username                  =  $obj->first_name . $obj->last_name; 
                $obj->email                     = $request->input('email');
                $obj->phone_number              = $request->input('phone_number');
                $obj->gender                    = $request->input('gender');
                $obj->dob                       = $request->input('dob');
                $obj->password                  = Hash::make($request->input('password'));

                if($request->hasFile('image')){
                    $extension = $request->file('image')->getClientOriginalExtension();
                    $fileName = time().'-image.'.$extension;
                    $folderName = strtoupper(date('M'). date('Y'))."/";
                    $folderPath = Config('constants.USER_IMAGE_ROOT_PATH').$folderName;
                    if(!File::exists($folderPath)) {
                        File::makeDirectory($folderPath, $mode = 0777,true);
                    }
                    if($request->file('image')->move($folderPath, $fileName)){
                    $image = $folderName.$fileName;
                    }
                    $obj->image = $image;
                }

            $obj->save();
            Session::flash('success', "User has been Added successfully");
            return redirect()->route($this->modelName . '.index');
        }
    }

    public function show($modelId)
    {
        $modelId = base64_decode($modelId);
        if ($modelId) {
            $modelDetails = User::where('id', $modelId)->first();
            return view('adminpnlx.' . $this->modelName . '.view', compact('modelDetails'));
        } else {
            return redirect::back();
        }
    }

    public function edit($id)
    {
        $modelId = base64_decode($id);
        if ($modelId) {
            $modelDetails = User::where('id', $modelId)->first();
            return view("adminpnlx." . $this->modelName . ".edit", compact('modelDetails'));
        } else {
            return Redirect::back();
        }
    }

    public function update(Request $request, $id)
    {
        $modelId                = base64_decode($id);
        $model                  = User::findorFail($modelId);

        if (empty($model)) {
            return Redirect::back();
        }
        $formData                =    $request->all();
        if (!empty($formData)) {
            $validator = Validator::make(
                $request->all(),
                array(
                    'first_name'                       =>  'required',
                    'last_name'                        =>  'required',
                    'email'                            =>  'required',
                    'phone_number'                     =>  'required',
                    'image'                            =>  'nullable|mimes:jpeg,png,jpg',
                ),
                array(
                    'first_name.required'            => 'The first name field must be required',
                    'last_name.required'             => 'The last name field must be required',
                    'email.required'                 => 'The email field must be required',
                    'phone_number.required'          => 'The phone number field must be required',
                    'image.mimes'                    => 'The image field must be type .png .jpeg .jpg',
                )
            );
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            } else {
                $obj                            = $model;
                $obj->first_name                = $request->input('first_name');
                $obj->last_name                 = $request->input('last_name');
                $obj->username                  = $obj->first_name .' '.$obj->last_name; 
                $obj->email                     = $request->input('email');
                $obj->phone_number              = $request->input('phone_number');
                $obj->gender                    = $request->input('gender');
                $obj->description               = $request->input('description');

                if($request->hasFile('image')){
                    $extension = $request->file('image')->getClientOriginalExtension();
                    $fileName = time().'-image.'.$extension;
                    $folderName = strtoupper(date('M'). date('Y'))."/";
                    $folderPath = Config('constants.USER_IMAGE_ROOT_PATH').$folderName;
                    if(!File::exists($folderPath)) {
                        File::makeDirectory($folderPath, $mode = 0777,true);
                    }
                    if($request->file('image')->move($folderPath, $fileName)){
                    $image = $folderName.$fileName;
                    }
                    $obj->image = $image;
                }
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
            User::where('id', $modelId)->delete();
            $statusMessage   =   "User has been deleted successfully";
            Session()->flash('flash_notice', $statusMessage);
        }
        return redirect::back();
    }

}