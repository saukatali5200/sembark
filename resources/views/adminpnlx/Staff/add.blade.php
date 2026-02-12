@extends('adminpnlx.layout.default')
@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12">
            <div class="row">
                <a href="{{Route('Staff.index')}}" class="btn btn-sm btn-primary m-2"> Staff List</a>
            </div>
                <div class="row">
                    <div class="card shadow mb-4">
                        <div class="card-header">
                            <strong class="card-title">Staff Information</strong>
                        </div>
                        <div class="card-body">
                            <form method="post" action="{{ route($modelName . '.store') }}" class="mws-form toggle-height"
                                enctype="multipart/form-data" autocomplete="off">
                                @csrf
                                <div class="form-row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name">Role</label>
                                         <select class="form-control" name="role_id">
                                           <option value="">Select Role </option>
                                          @foreach($roles as $role)
                                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                                            @endforeach
                                         </select>
                                        @if ($errors->has('name'))
                                            <span class="error text-danger">{{ $errors->first('name') }}</span>
                                        @endif
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="name">Name</label>
                                        <input type="text" name="name" class="form-control" id="name"
                                            @error('name') is-invalid @enderror" value="{{ old('name') }}">
                                        @if ($errors->has('name'))
                                            <span class="error text-danger">{{ $errors->first('name') }}</span>
                                        @endif
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="email">Email</label>
                                        <input type="text" name="email" class="form-control" id="email"
                                            @error('email') is-invalid @enderror" value="{{ old('email') }}">
                                        @if ($errors->has('email'))
                                            <span class="error text-danger">{{ $errors->first('email') }}</span>
                                        @endif
                                    </div>


                                    <div class="col-md-6 mb-3">
                                        <label for="phone_number">Phone No.</label>
                                        <input type="text" name="phone_number" class="form-control" id="phone_number"
                                            @error('phone_number') is-invalid @enderror" value="{{ old('phone_number') }}">
                                        @if ($errors->has('phone_number'))
                                            <span class="error text-danger">{{ $errors->first('phone_number') }}</span>
                                        @endif
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="password"> Password</label>
                                        <input type="password" name="password" class="form-control" id="password"
                                            @error('password') is-invalid @enderror" value="{{ old('password') }}">
                                        @if ($errors->has('password'))
                                            <span class="error text-danger">{{ $errors->first('password') }}</span>
                                        @endif
                                    </div>

                                    <label for="description">Description</label>
                                    <textarea name="description" class="form-control" id="description" placeholder="Enter Description" rows="3"
                                        @error('description') is-invalid @enderror" value="{{ old('description') }}"></textarea>
                                    @if ($errors->has('description'))
                                        <span class="error text-danger">{{ $errors->first('description') }}</span>
                                    @endif
                                </div>

                                <button class="btn btn-primary" type="submit">Submit</button>
                            </form>
                        </div> <!-- /.card-body -->
                    </div> <!-- /.card -->
                </div> <!-- end section -->
            </div> <!-- /.col-12 col-lg-10 col-xl-10 -->
        </div> <!-- .row -->
    </div>
@endsection
