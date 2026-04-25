@extends('backend.layouts.master-layouts')
@section('title', 'Edit Coupon')

@section('content')
    <div class="card">
        <div class="card-body">
            <h4 class="mb-3">Edit Coupon</h4>
            @include('coupons.form', ['coupon' => $coupon])
        </div>
    </div>
@endsection
