@extends('frontend.seller.seller_master')
@section('title', 'Transactions')
@section('content')

<style>
    h2 { font-size: 22px; font-weight: 600; }
    .total-balance { float: right; font-size: 30px; font-weight: 700; }
    .total-balance::before { content: "৳"; }

    .transaction-item {
        display: flex;
        justify-content: space-between;
        border-left: 4px solid #e1e1e1;
        padding-left: 15px;
        margin-bottom: 20px;
    }
    .credit { border-color: #28a745; }
    .debit { border-color: #dc3545; }
    .amount.credit { color: #28a745; }
    .amount.debit { color: #dc3545; }
</style>

<div class="container-fluid py-4">
    <div class="card shadow-sm">
        <div class="card-body">

            <h2>
                Transactions
                <span class="total-balance">{{ number_format($available_balance, 2) }}</span>
            </h2>

            <!-- Filter -->
            <form method="GET" class="row mt-4">
                <div class="col-md-4">
                    <label>Start Date</label>
                    <input type="date" class="form-control" name="starting_at" value="{{ $starting_at }}">
                </div>
                <div class="col-md-4">
                    <label>End Date</label>
                    <input type="date" class="form-control" name="ending_at" value="{{ $ending_at }}">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button class="btn btn-primary w-100">Filter</button>
                </div>
            </form>

            <hr>

            @if ($transactions->count() > 0)

                <div class="transaction-item">
                    <strong>Opening Balance</strong>
                    <strong>৳{{ number_format($transactions->first()->current - $transactions->first()->amount, 2) }}</strong>
                </div>

                @foreach ($transactions as $item)
                    <div class="transaction-item {{ $item->credit ? 'credit' : 'debit' }}">
                        <div>
                            <h4>{{ $item->title }}</h4>
                            <small class="text-muted">
                                {{ \Carbon\Carbon::parse($item->updated_at)->format('M d, Y h:i A') }}
                            </small><br>
                            <small class="text-muted">{{ $item->note }}</small>
                        </div>

                        <div class="text-end">
                            <strong class="amount {{ $item->credit ? 'credit' : 'debit' }}">
                                ৳{{ number_format($item->amount, 2) }}
                            </strong>
                        </div>
                    </div>
                @endforeach

            @else
                <p class="text-center mt-5">No transactions found</p>
            @endif

        </div>
    </div>
</div>

@endsection
