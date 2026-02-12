<?php

namespace App\Http\Controllers\adminpnlx;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Admin;
use App\Models\User;
use App\Models\Role;
use App\Models\Acl;

use Illuminate\Support\Facades\{DB, Session, Validator, Redirect, Auth, Hash, Str, Notification};
use Illuminate\Support\Facades\{URL, View, Response, Cookie, File, Mail, Blade, Cache, Http};
use Yajra\DataTables\Facades\DataTables;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $adminCount = Admin::count();
        $userCount = User::count();
        $roleCount = Role::count();
        $aclCount = Acl::count();
        return view('adminpnlx.dashboard.index', compact('adminCount', 'userCount', 'roleCount', 'aclCount'));
    }

   public function logout()
    {
        Auth::guard('admins')->logout();
        return Redirect::route('Auth.login');
    }
}
