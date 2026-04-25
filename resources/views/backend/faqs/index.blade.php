@extends('backend.layouts.master-layouts')
@section('content')
    <div class="container">
        <h2>FAQ List</h2>
        <a href="{{ route('faqs.create') }}" class="btn btn-primary mb-3">Add FAQ</a>
        @foreach ($faqs as $faq)
            <div class="card mb-2">
                <div class="card-body">
                    <h5>{{ $faq->question }}</h5>
                    <p>{{ $faq->answer }}</p>
                    <a href="{{ route('faqs.edit', $faq->id) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('faqs.destroy', $faq->id) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        @endforeach

        {{ $faqs->links() }}
    </div>
@endsection
