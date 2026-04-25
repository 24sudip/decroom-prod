@extends('backend.layouts.master-layouts')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>{{ isset($faq) ? 'Edit' : 'Create' }} FAQ</h2>
            <a href="{{ route('faqs.index') }}" class="btn btn-secondary">← Back to List</a>
        </div>

        <form action="{{ isset($faq) ? route('faqs.update', $faq->id) : route('faqs.store') }}" method="POST">
            @csrf
            @if (isset($faq))
                @method('PUT')
            @endif

            <div class="form-group">
                <label>Question</label>
                <input type="text" name="question" class="form-control" value="{{ $faq->question ?? old('question') }}"
                    required>
            </div>

            <div class="form-group">
                <label>Answer</label>
                <textarea name="answer" class="form-control" rows="4" required>{{ $faq->answer ?? old('answer') }}</textarea>
            </div>

            <div class="form-group form-check">
                <input type="checkbox" name="status" class="form-check-input"
                    {{ isset($faq) && $faq->status ? 'checked' : '' }}>
                <label class="form-check-label">Active</label>
            </div>

            <button type="submit" class="btn btn-success">{{ isset($faq) ? 'Update' : 'Create' }}</button>
        </form>
    </div>
@endsection
