@extends('adminpnlx.layout.default')
@section('content')

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12">

            <div class="mb-3">
                <a href="{{ route('Role.index') }}" class="btn btn-sm btn-primary">
                    ← Role List
                </a>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header">
                    <strong class="card-title">Role Permissions</strong>
                </div>

                <div class="card-body">
                    <form method="post"
                        action="{{ route($modelName . '.savePermissions', base64_encode($roleID)) }}"
                        autocomplete="off">
                        @csrf

                        <table class="table table-bordered table-hover align-middle text-center">
                            <thead class="table-dark">
                                <tr>
                                    <th>
                                        <input type="checkbox" class="form-check-input selectAllCheck"> All
                                    </th>
                                    <th>Module Name</th>
                                    <th>List</th>
                                    <th>View</th>
                                    <th>Create</th>
                                    <th>Update</th>
                                    <th>Delete</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($acl_modules as $acl_module)
                                    @php
                                        $i = $acl_module->id;
                                        $permission = $staffPermissions[$i] ?? null;
                                    @endphp
                                    <tr>

                                        {{-- MODULE ALL --}}
                                        <td>
                                            <input type="checkbox"
                                                   class="form-check-input module-all"
                                                   id="module_all_{{ $i }}"
                                                   data-id="{{ $i }}">
                                        </td>

                                        <td class="text-start fw-semibold">
                                            <input type="hidden" name="acl_module_{{$i}}" value="{{$i}}">
                                            {{ $acl_module->name }}
                                        </td>

                                        {{-- LIST --}}
                                        <td>
                                            <input type="hidden" name="listing_permission_{{ $i }}" value="0">
                                            <input type="checkbox"
                                                   class="form-check-input perm_{{ $i }}"
                                                   name="listing_permission_{{ $i }}"
                                                   value="1"
                                                   {{ isset($permission) && $permission->listing_permission == 1 ? 'checked' : '' }}>
                                        </td>

                                        {{-- VIEW --}}
                                        <td>
                                            <input type="hidden" name="view_permission_{{ $i }}" value="0">
                                            <input type="checkbox"
                                                   class="form-check-input perm_{{ $i }}"
                                                   name="view_permission_{{ $i }}"
                                                   value="1"
                                                   {{ isset($permission) && $permission->view_permission == 1 ? 'checked' : '' }}>
                                        </td>

                                        {{-- CREATE --}}
                                        <td>
                                            <input type="hidden" name="create_permission_{{ $i }}" value="0">
                                            <input type="checkbox"
                                                   class="form-check-input perm_{{ $i }}"
                                                   name="create_permission_{{ $i }}"
                                                   value="1"
                                                   {{ isset($permission) && $permission->create_permission == 1 ? 'checked' : '' }}>
                                        </td>

                                        {{-- UPDATE --}}
                                        <td>
                                            <input type="hidden" name="update_permission_{{ $i }}" value="0">
                                            <input type="checkbox"
                                                   class="form-check-input perm_{{ $i }}"
                                                   name="update_permission_{{ $i }}"
                                                   value="1"
                                                   {{ isset($permission) && $permission->update_permission == 1 ? 'checked' : '' }}>
                                        </td>

                                        {{-- DELETE --}}
                                        <td>
                                            <input type="hidden" name="delete_permission_{{ $i }}" value="0">
                                            <input type="checkbox"
                                                   class="form-check-input perm_{{ $i }}"
                                                   name="delete_permission_{{ $i }}"
                                                   value="1"
                                                   {{ isset($permission) && $permission->delete_permission == 1 ? 'checked' : '' }}>
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="mt-3 text-end">
                            <button type="submit" class="btn btn-primary">
                                Save Permissions
                            </button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- JavaScript Logic --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ================================
    // MODULE ALL CHECKBOX
    // ================================
    document.querySelectorAll('.module-all').forEach(function (checkbox) {

        checkbox.addEventListener('change', function () {

            let id = this.dataset.id;
            let permissions = document.querySelectorAll('.perm_' + id);

            permissions.forEach(function (perm) {
                perm.checked = checkbox.checked;
            });

        });

    });

    // ================================
    // INDIVIDUAL PERMISSION CHANGE
    // ================================
    document.querySelectorAll('[class^="perm_"]').forEach(function (checkbox) {

        checkbox.addEventListener('change', function () {

            let className = this.className.split(' ').find(c => c.startsWith('perm_'));
            let id = className.split('_')[1];

            let permissions = document.querySelectorAll('.perm_' + id);
            let moduleAll = document.getElementById('module_all_' + id);

            let allChecked = true;

            permissions.forEach(function (perm) {
                if (!perm.checked) {
                    allChecked = false;
                }
            });

            moduleAll.checked = allChecked;

        });

    });

    // ================================
    // HEADER SELECT ALL
    // ================================
    document.querySelector('.selectAllCheck').addEventListener('change', function () {

        let checked = this.checked;

        document.querySelectorAll('tbody input[type="checkbox"]').forEach(function (checkbox) {
            checkbox.checked = checked;
        });

    });

    // ================================
    // PAGE LOAD AUTO CHECK MODULE ALL
    // ================================
    document.querySelectorAll('.module-all').forEach(function (checkbox) {

        let id = checkbox.dataset.id;
        let permissions = document.querySelectorAll('.perm_' + id);

        let allChecked = true;

        permissions.forEach(function (perm) {
            if (!perm.checked) {
                allChecked = false;
            }
        });

        checkbox.checked = allChecked;

    });

});
</script>

@endsection
