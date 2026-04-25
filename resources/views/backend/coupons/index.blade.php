@extends('backend.layouts.master-layouts')
@section('title', 'Coupon List')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h4>All Coupons</h4>
            <a href="{{ route('coupons.create') }}" class="btn btn-success">+ Add Coupon</a>
        </div>

        <div class="card-body">
            {{-- @include('partials.alerts') --}}

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Min Purchase</th>
                        <th>Start</th>
                        <th>End</th>
                        <th>Status</th>
                        <th width="150">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($coupons as $coupon)
                        <tr>
                            <td>{{ $coupon->code }}</td>
                            <td>{{ ucfirst($coupon->type) }}</td>
                            <td>{{ $coupon->amount }}</td>
                            <td>{{ $coupon->min_purchase }}</td>
                            <td>{{ $coupon->start_date->format('d M Y') }}</td>
                            <td>{{ $coupon->end_date->format('d M Y') }}</td>
                            <td>
                                <span class="badge bg-{{ $coupon->status ? 'success' : 'danger' }}">
                                    {{ $coupon->status ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('coupons.edit', $coupon->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('coupons.destroy', $coupon->id) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Are you sure to delete this coupon?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">No coupons found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $coupons->links() }}
        </div>
    </div>
@endsection
