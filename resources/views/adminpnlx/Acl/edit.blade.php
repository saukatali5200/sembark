@extends('adminpnlx.layout.default')
@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12">
            <div class="row">
                <a href="{{Route('Acl.index')}}" class="btn btn-sm btn-primary m-2"> Acl List</a>
            </div>
                <div class="row">
                    <div class="card shadow mb-4">
                        <div class="card-header">
                            <strong class="card-title">Acl Information</strong>
                        </div>
                        <div class="card-body">
                            <form method="post" action="{{ route($modelName . '.update', base64_encode($modelDetails->id)) }}"
                                class="mws-form toggle-height" enctype="multipart/form-data" autocomplete="off">
                                @csrf
                                
                                <div class="form-row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name">Name</label>
                                        <input type="text" name="name" class="form-control" id="name"
                                            @error('name') is-invalid @enderror"
                                            value="{{ $modelDetails->name ?? old('name') }}">
                                        @if ($errors->has('name'))
                                            <span class="error text-danger">{{ $errors->first('name') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="description">Note</label>
                                    <textarea name="description" class="form-control" id="description" placeholder="Enter Description" rows="3"
                                        @error('description') is-invalid @enderror" value="{{ old('description') }}">{{ $modelDetails->description ?? old('description') }}</textarea>
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
