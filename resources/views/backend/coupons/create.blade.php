@extends('backend.layouts.master-layouts')
@section('title', 'Create Coupon')

@section('content')
    <div class="card">
        <div class="card-body">
            <h4 class="mb-3">Create New Coupon</h4>
            @include('backend.coupons.form', ['coupon' => null])
        </div>
    </div>
@endsection
