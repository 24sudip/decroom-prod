@extends('backend.layouts.master-layouts')

@section('title', __('Module Create'))

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('build/libs/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" />
@endsection

@section('content')
    <div class="container my-5">
        <div class="card">
            <div class="card-header py-2 bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="title">Create Module</h5>
                <a href="{{ route('module.index') }}" class="btn btn-sm btn-info">Module List</a>
            </div>
            <div class="card-body">
                <form class="my-3" action="{{ route('module.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <label class="col-sm-6 col-md-3 form-label">Module Name</label>
                        <div class="col-sm-6 col-md-9">
                            <input type="text" name="module_name"
                                class="form-control @error('module_name') is-invalid @enderror"
                                value="{{ old('module_name') }}" placeholder="Module Name..">

                            @error('module_name')
                                <span class="text-red invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-md btn-primary float-right">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
