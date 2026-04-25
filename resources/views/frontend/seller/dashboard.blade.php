@extends('frontend.seller.seller_master')

@section('title', 'Seller Dashboard')

@section('content')

    <!-- seller-menu-top -->
    @include('frontend.include.seller-menu-top')
    <!-- seller-menu-top -->

    <!-- seller-dasboard-->
    @include('frontend.include.seller-dasboard')
    <!-- seller-dasboard -->

    <!-- dashboard-top-selling-->
    @include('frontend.include.dashboard-top-selling')
    <!-- dashboard-top-selling -->
@endsection
