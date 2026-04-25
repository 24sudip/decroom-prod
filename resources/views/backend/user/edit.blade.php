@extends('backend.layouts.master-layouts')

@section('title', __('Edit User'))

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('build/libs/select2/css/select2.min.css') }}">
@endsection

@section('content')
    <div class="container-fluid my-5">
        <div class="card">
            <div class="card-header py-2 bg-warning text-dark d-flex justify-content-between align-items-center">
                <h5 class="title">Edit User</h5>
                <a href="{{ route('user.index') }}" class="btn btn-sm btn-info">User List</a>
            </div>
            <div class="card-body">
                <form class="my-3" action="{{ route('user.update', $user->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row mb-2">
                        <label class="col-sm-6 col-md-3 form-label">Role <span class="text-danger">*</span></label>
                        <div class="col-sm-6 col-md-9">
                            <select class="form-control @error('role_id') is-invalid @enderror" name="role_id" required>
                                <option value="">Select Role</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'selected' : '' }}>
                                        {{ $role->role_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role_id')
                                <span class="text-danger invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-2">
                        <label class="col-sm-6 col-md-3 form-label">User Name <span class="text-danger">*</span></label>
                        <div class="col-sm-6 col-md-9">
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', $user->name) }}">
                            @error('name')
                                <span class="text-danger invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-2">
                        <label class="col-sm-6 col-md-3 form-label">Phone Number</label>
                        <div class="col-sm-6 col-md-9">
                            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                value="{{ old('phone', $user->phone) }}">
                            @error('phone')
                                <span class="text-danger invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-2">
                        <label class="col-sm-6 col-md-3 form-label">Email</label>
                        <div class="col-sm-6 col-md-9">
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email', $user->email) }}">
                            @error('email')
                                <span class="text-danger invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-2">
                        <label class="col-sm-6 col-md-3 form-label">Profile Photo</label>
                        <div class="col-sm-6 col-md-9">
                            <input type="file" name="image" class="form-control @error('image') is-invalid @enderror">
                            @if ($user->image)
                                <small class="d-block mt-1">Current: <img
                                        src="{{ asset('storage/users/' . $user->image) }}" height="50"></small>
                            @endif
                            @error('image')
                                <span class="text-danger invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-md btn-warning float-end">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
