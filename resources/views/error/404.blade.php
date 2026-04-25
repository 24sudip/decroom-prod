@extends('backend.layouts.master-without-nav')
@section('title','404 Not Found')
@section('body')
<body>
@endsection
@section('content')
<div class="container text-center mt-5">
    <h1>404</h1>
    <h3>Page Not Found</h3>
    <p>The page you are looking for does not exist.</p>
    <a href="{{ url('/') }}" class="btn btn-primary">Go Home</a>
</div>
@endsection
