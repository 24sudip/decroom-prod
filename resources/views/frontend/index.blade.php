@extends('frontend.layouts.master')

@section('title', 'Home')

@section('content')
    <!-- banner -->
    @include('frontend.include.banner')
    <!-- banner -->

    <!-- service  offer-->
    @include('frontend.include.service_offer')
    <!-- service offer -->

    <!-- product  offer-->
    @include('frontend.include.product_offer')
    <!-- product offer -->

    <!-- products cate -->
    @include('frontend.include.product_category')
    <!-- products cate -->

    <!-- service center -->
    @include('frontend.include.service_center')
    <!-- service center -->
@endsection

