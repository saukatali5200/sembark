<?php

use App\Models\StaffPermission;
use Illuminate\Support\Facades\Auth;

if (!function_exists('hasPermission')) {

    function hasPermission($moduleName, $permissionType)
    {
        $admin = Auth::guard('admins')->user();

        if (!$admin) {
            return false;
        }

        // Super Admin bypass (optional but recommended)
        if ($admin->role == 1) {
            return true;
        }

        static $permissions = null;

        // Ek baar permissions load karo (performance better)
        if ($permissions === null) {
            $permissions = StaffPermission::join('acls', 'acls.id', '=', 'staff_permissions.module_id')
                ->where('staff_permissions.role_id', $admin->role)
                ->select(
                    'acls.name as module_name',
                    'staff_permissions.listing_permission',
                    'staff_permissions.view_permission',
                    'staff_permissions.create_permission',
                    'staff_permissions.update_permission',
                    'staff_permissions.delete_permission'
                )
                ->get()
                ->keyBy('module_name');
        }

        if (!isset($permissions[$moduleName])) {
            return false;
        }

        return $permissions[$moduleName]->$permissionType == 1;
    }
}
