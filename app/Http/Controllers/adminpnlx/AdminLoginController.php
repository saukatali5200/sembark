<?php

namespace App\Http\Controllers\adminpnlx;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Config;
use App\Models\Admin;
use App\Models\EmailAction;
use App\Models\EmailTemplate;

use Illuminate\Support\Facades\{DB, Session, Validator, Redirect, Auth, Hash, Str, Notification};
use Illuminate\Support\Facades\{URL, View, Response, Cookie, File, Mail, Blade, Cache, Http};

class AdminLoginController extends Controller
{
    public $modelName = 'Auth';
    public function __construct(Request $request)
    {
        View()->Share('modelName', $this->modelName);

        $this->request = $request;
    }

    public function login(Request $request)
    {
        if ($request->isMethod('post')) {
            $formData = $request->all();
            $validator = Validator::make(
                $request->all(),
                array(
                    'email'     => 'required|email|exists:admins',
                    'password'  => 'required',
                ),
                array(
                    'email.required'                => 'The email field must be required',
                    'password.required'             => 'The password field must be required',
                )
            );
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            } else {
                $email = $request->input('email');
                $password = $request->input('password');
                if (Auth::guard('admins')->attempt(['email' => $email, 'password' => $password])) {
                    if (Auth::guard('admins')->user()->is_active == 0) {
                        Auth::guard('admins')->logout();
                        Session::flash('error', 'Your account is deactivated.Please contact to the admin.');
                        return redirect::route('Admin.login');
                    }
                    if (!Auth::guard('admins')->user()->is_active == 1) {
                        Auth::guard('admins')->logout();
                        Session::flash('error', 'Your account is deactivated.Please contact to the admin.');
                        return redirect::route('Admin.login')
                        ->withSuccess('Your account is deactivated.Please contact to the admin.');
                    }

                    return redirect::route('Admin.dashboard')
                        ->withSuccess('You have Successfully logged in');
                }
                return redirect::route('Admin.login')
                    ->withError('Sorry! You have entered invalid credentials');
            }
        }
        if (Auth::guard('admins')->user()) {
            return redirect::route('Admin.dashboard')
                ->withSuccess('You have Already logged in');
        }
        return view('adminpnlx.' . $this->modelName . '.login');
    }

    
}
