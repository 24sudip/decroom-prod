@extends('frontend.layouts.master')
@section('title', 'Quick Checkout')

@section('content')
    <div class="container">
        <h4>Quick Checkout for: {{ $product->name }}</h4>
        {{-- Add your quick checkout form or flow here --}}
    </div>
@endsection
