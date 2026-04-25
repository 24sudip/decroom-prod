@extends('backend.layouts.master-layouts')

@section('title', __('Seller Ledger'))

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('build/libs/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" />

    <style>
        .filterform .form-group label {
            font-weight: 600;
        }
        .filterbutton button, 
        .filterbutton a {
            height: 38px;
            display: flex;
            align-items: center;
        }
        .short_button {
            margin-top: 15px;
        }
        table td {
            vertical-align: middle !important;
        }
        .badge-pill {
            padding: 6px 12px;
        }
    </style>
@endsection

@section('content')
<section class="content">
    <div class="row">
        <div class="col-12">

            <div class="card">

                {{-- Card Header --}}
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title m-0">Seller Ledger</h3>
                </div>

                {{-- Filter Section --}}
                <div class="card-body pb-0">
                    <div class="filterform">
                        <form action="" method="GET">
                            @csrf
                            <div class="row">

                                {{-- Seller --}}
                                <div class="col-sm-7">
                                    <div class="form-group">
                                        <label>Seller</label>
                                        <select name="vendor_id" class="form-control select2">
                                            <option selected disabled>Select Seller</option>
                                            @foreach ($sellers as $value)
                                                <option value="{{ $value->id }}" 
                                                    @if ($vendor_id == $value->id) selected @endif>
                                                    {{ optional($value->vendorDetails)->shop_name ?? 'N/A' }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- Filter Buttons --}}
                                <div class="col-sm-5 d-flex align-items-end">
                                    <div class="form-group d-flex filterbutton">
                                        <button class="btn btn-primary">Filter</button>
                                        <a class="btn btn-secondary mx-2" href="{{ route('seller.transactions') }}">
                                            Reset
                                        </a>
                                    </div>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>

                {{-- Table Section --}}
                <div class="card-body user-border">
                    <table id="example" class="table table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Name</th>
                                <th>Details</th>
                                <th>Debit</th>
                                <th>Credit</th>
                                <th>Balance</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($show_datas as $key => $value)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>

                                    {{-- Seller Info --}}
                                    <td>
                                        {{ optional(optional($value->vendor)->vendorDetails)->shop_name ?? 'N/A' }}
                                        <br>
                                        {{ $value->vendor->phone ?? 'N/A' }}
                                    </td>

                                    {{-- Title + Note --}}
                                    <td>
                                        {{ $value->title }}
                                        <br>
                                        <i><small>{{ $value->note }}</small></i>
                                    </td>

                                    {{-- Debit --}}
                                    <td class="text-danger">
                                        @if (!$value->credit)
                                            BDT {{ number_format($value->amount, 2) }}
                                        @endif
                                    </td>

                                    {{-- Credit --}}
                                    <td class="text-success">
                                        @if ($value->credit)
                                            BDT {{ number_format($value->amount, 2) }}
                                        @endif
                                    </td>

                                    {{-- Balance --}}
                                    <td>
                                        BDT {{ number_format($value->current, 2) }}
                                    </td>

                                    {{-- Status --}}
                                    <td>
                                        @if ($value->status)
                                            <span class="text-success font-weight-bold">Completed</span>
                                            <br>
                                            <span class="badge badge-warning badge-pill">
                                                {{ App\User::find($value->approved_by)->name ?? '' }}
                                            </span>
                                        @else
                                            <span class="text-warning font-weight-bold">Pending</span>
                                        @endif

                                        <p class="mt-1 mb-0">
                                            <strong>Date: </strong>
                                            {{ date('F d, Y', strtotime($value->updated_at)) }}
                                        </p>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>

            </div>

        </div>
    </div>
</section>
@endsection
