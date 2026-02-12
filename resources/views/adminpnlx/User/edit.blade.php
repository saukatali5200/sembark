@extends('adminpnlx.layout.default')
@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="row">
                    <a href="{{ Route('User.index') }}" class="btn btn-sm btn-primary m-2"> User List</a>
                </div>
                <div class="row">
                    <div class="card shadow mb-4">
                        <div class="card-header">
                            <strong class="card-title">User Information</strong>
                        </div>
                        <div class="card-body">
                            <form method="post"
                                action="{{ route($modelName . '.update', base64_encode($modelDetails->id)) }}"
                                class="mws-form toggle-height" enctype="multipart/form-data" autocomplete="off">
                                @csrf

                                <div class="form-row">

                                    <div class="col-md-6 mb-3">
                                        <label for="first_name">First Name</label>
                                        <input type="text" name="first_name" class="form-control" id="first_name"
                                            @error('first_name') is-invalid @enderror"
                                            value="{{ $modelDetails->first_name ?? old('first_name') }}">
                                        @if ($errors->has('first_name'))
                                            <span class="error text-danger">{{ $errors->first('first_name') }}</span>
                                        @endif
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="last_name">Last Name</label>
                                        <input type="text" name="last_name" class="form-control" id="last_name"
                                            @error('last_name') is-invalid @enderror"
                                            value="{{ $modelDetails->last_name ?? old('last_name') }}">
                                        @if ($errors->has('last_name'))
                                            <span class="error text-danger">{{ $errors->first('last_name') }}</span>
                                        @endif
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="email">Email</label>
                                        <input type="text" name="email" class="form-control" id="email"
                                            @error('email') is-invalid @enderror"
                                            value="{{ $modelDetails->email ?? old('email') }}">
                                        @if ($errors->has('email'))
                                            <span class="error text-danger">{{ $errors->first('email') }}</span>
                                        @endif
                                    </div>


                                    <div class="col-md-6 mb-3">
                                        <label for="phone_number">Phone No.</label>
                                        <input type="text" name="phone_number" class="form-control" id="phone_number"
                                            @error('phone_number') is-invalid @enderror"
                                            value="{{ $modelDetails->phone_number ?? old('phone_number') }}">
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

                                    <div class="col-md-12 mb-3">
                                        <label for="description">Description</label>
                                        <textarea name="description" class="form-control" id="description" placeholder="Enter Description" rows="3"
                                            @error('description') is-invalid @enderror">{{ $modelDetails->description ?? old('description') }}</textarea>
                                        @if ($errors->has('description'))
                                            <span class="error text-danger">{{ $errors->first('description') }}</span>
                                        @endif
                                    </div>
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
