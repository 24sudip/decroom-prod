@extends('backend.layouts.master-layouts')

@section('title', 'Manage Social Media')

@section('content')

<div class="card">
    <div class="card-header bg-dark text-white">
        <h5 class="mb-0">Social Media List</h5>
    </div>

    <div class="card-body">

        <table class="table table-bordered table-striped">
            <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Icon</th>
                <th>Link</th>
                <th>Status</th>
                <th width="18%">Actions</th>
            </tr>
            </thead>

            <tbody>
            @foreach($show_datas as $key => $data)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $data->name }}</td>
                    <td>
                        <i class="{{ $data->icon }}"></i>
                        &nbsp; {{ $data->icon }}
                    </td>
                    <td><a href="{{ $data->link }}" target="_blank">{{ $data->link }}</a></td>

                    <td>
                        @if($data->status == 1)
                            <span class="badge bg-success">Published</span>
                        @else
                            <span class="badge bg-warning text-dark">Unpublished</span>
                        @endif
                    </td>

                    <td>

                        <a href="{{ url('editor/social-media/edit/'.$data->id) }}" 
                           class="btn btn-sm btn-info">Edit</a>

                        @if($data->status == 1)
                            <form action="{{ url('editor/social-media/unpublished') }}" method="POST" class="d-inline">
                                @csrf
                                <input type="hidden" name="hidden_id" value="{{ $data->id }}">
                                <button class="btn btn-sm btn-warning">Unpublish</button>
                            </form>
                        @else
                            <form action="{{ url('editor/social-media/published') }}" method="POST" class="d-inline">
                                @csrf
                                <input type="hidden" name="hidden_id" value="{{ $data->id }}">
                                <button class="btn btn-sm btn-success">Publish</button>
                            </form>
                        @endif

                        <form action="{{ url('editor/social-media/delete') }}" method="POST" class="d-inline" 
                              onsubmit="return confirm('Are you sure you want to delete this?');">
                            @csrf
                            <input type="hidden" name="hidden_id" value="{{ $data->id }}">
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </form>

                    </td>
                </tr>
            @endforeach
            </tbody>

        </table>

    </div>
</div>

@endsection
