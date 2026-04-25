@extends('backend.layouts.master-layouts')

@section('title', 'Add Social Media')

@section('content')

<div class="row">
    <div class="col-md-6 offset-md-3">

        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Add Social Media</h5>
            </div>

            <div class="card-body">

                <form action="{{ url('editor/social-media/save') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label>Name*</label>
                        <input type="text" name="name" class="form-control" placeholder="Facebook, Twitter..." required>
                    </div>

                    <div class="mb-3">
                        <label>Icon (FontAwesome Class)*</label>
                        <input type="text" name="icon" class="form-control" placeholder="fa fa-facebook" required>
                    </div>

                    <div class="mb-3">
                        <label>Link*</label>
                        <input type="url" name="link" class="form-control" placeholder="https://facebook.com/yourpage" required>
                    </div>

                    <div class="mb-3">
                        <label>Status*</label>
                        <select name="status" class="form-control" required>
                            <option value="1">Published</option>
                            <option value="0">Unpublished</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-success w-100">Save</button>
                </form>

            </div>
        </div>

    </div>
</div>

@endsection
