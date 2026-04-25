@extends('backend.layouts.master-layouts')

@section('title', __('User Create'))

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('build/libs/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" />
@endsection

@section('content')
    <div class="container-fluid my-5">
        <div class="card">
            <div class="card-header py-2 bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="title">Create User</h5>
                <a href="{{ route('user.index') }}" class="btn btn-sm btn-info">User List</a>
            </div>
            <div class="card-body">
                <form class="my-3" action="{{ route('user.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row mb-2">
                        <label class="col-sm-6 col-md-3 form-label">Role <span class="text-danger">*</span></label>
                        <div class="col-sm-6 col-md-9">
                            <select class="form-control @error('role_id') is-invalid @enderror" name="role_id"
                                id="role_id" required>
                                <option value="">Select Role</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->role_name }}</option>
                                @endforeach
                            </select>
                            @error('role_id')
                                <span class="text-danger invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-2">
                        <label class="col-sm-6 col-md-3 form-label">User Name <span class="text-danger">*</span></label>
                        <div class="col-sm-6 col-md-9">
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name') }}">
                            @error('name')
                                <span class="text-danger invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-2">
                        <label class="col-sm-6 col-md-3 form-label">Phone Number</label>
                        <div class="col-sm-6 col-md-9">
                            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                value="{{ old('phone') }}">
                            @error('phone')
                                <span class="text-danger invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-2">
                        <label class="col-sm-6 col-md-3 form-label">Email</label>
                        <div class="col-sm-6 col-md-9">
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email') }}">
                            @error('email')
                                <span class="text-danger invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-2">
                        <label class="col-sm-6 col-md-3 form-label">New Password <span class="text-danger">*</span></label>
                        <div class="col-sm-6 col-md-9">
                            <input type="password" name="password"
                                class="form-control @error('password') is-invalid @enderror">
                            @error('password')
                                <span class="text-danger invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-2">
                        <label class="col-sm-6 col-md-3 form-label">Confirm Password</label>
                        <div class="col-sm-6 col-md-9">
                            <input type="password" name="password_confirmation" class="form-control">
                        </div>
                    </div>

                    <div class="row mb-2">
                        <label class="col-sm-6 col-md-3 form-label">Profile Photo</label>
                        <div class="col-sm-6 col-md-9">
                            <input id="image" type="file" name="image"
                                class="form-control @error('image') is-invalid @enderror">
                            @error('image')
                                <span class="text-danger invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-md btn-primary float-end">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
