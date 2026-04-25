@extends('backend.layouts.master-layouts')

@section('title', 'Update Social Media')

@section('content')

<div class="row">
    <div class="col-md-6 offset-md-3">

        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">Update Social Media</h5>
            </div>

            <div class="card-body">

                <form action="{{ url('editor/social-media/update') }}" method="POST">
                    @csrf

                    <input type="hidden" name="hidden_id" value="{{ $edit_data->id }}">

                    <div class="mb-3">
                        <label>Name*</label>
                        <input type="text" name="name" class="form-control" value="{{ $edit_data->name }}" required>
                    </div>

                    <div class="mb-3">
                        <label>Icon*</label>
                        <input type="text" name="icon" class="form-control" value="{{ $edit_data->icon }}" required>
                    </div>

                    <div class="mb-3">
                        <label>Link*</label>
                        <input type="url" name="link" class="form-control" value="{{ $edit_data->link }}" required>
                    </div>

                    <div class="mb-3">
                        <label>Status*</label>
                        <select name="status" class="form-control" required>
                            <option value="1" @if($edit_data->status == 1) selected @endif>Published</option>
                            <option value="0" @if($edit_data->status == 0) selected @endif>Unpublished</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Update</button>

                </form>

            </div>
        </div>

    </div>
</div>

@endsection
