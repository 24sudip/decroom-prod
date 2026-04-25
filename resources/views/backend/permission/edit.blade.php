@extends('backend.layouts.master-layouts')

@section('title', __('Permission Edit'))

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('build/libs/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" />
@endsection

@section('content')
    <div class="container my-2">
        <div class="card">
            <div class="card-header py-2 bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="title">Update Permission</h5>
                <a href="{{ route('permission.index') }}" class="btn btn-sm btn-info">Permission List</a>
            </div>
            <div class="card-body">
                <form class="space-y-3" action="{{ route('permission.update', $permission->id) }}" method="POST">

                    @csrf
                    @method('PUT')

                    <div class="row mb-2">
                        <label class="col-sm-6 col-md-3 form-label">Module Name</label>
                        <div class="col-sm-6 col-md-9">
                            <select
                                class="form-control @error('module_id') is-invalid
                                @enderror"
                                name="module_id" id="module_id" data-choice="active">

                                @foreach ($modules as $module)
                                    <option class="py-2" value="{{ $module->id }}"
                                        {{ $permission->module_id == $module->id ? 'selected' : '' }}>
                                        {{ $module->module_name }}</option>
                                @endforeach

                            </select>

                            @error('module_id')
                                <span class="text-red invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-2">
                        <label class="col-sm-6 col-md-3 form-label">Permission Name</label>
                        <div class="col-sm-6 col-md-9">
                            <input type="text" name="permission_name"
                                class="form-control @error('permission_name') is-invalid @enderror"
                                value="{{ $permission->permission_name }}" placeholder="Permission Name..">

                            @error('permission_name')
                                <span class="text-red invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-md btn-primary float-right">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
