@extends('frontend.seller.seller_master')
@section('title', 'Wallet')
@section('content')

<style>
    @import url('https://fonts.googleapis.com/css?family=Open+Sans:300,400,600|Roboto:300,400');

    h2 { margin: 0; font-weight: 600; font-size: 22px; }
    h3 { margin: 0 0 5px; font-weight: 600; font-size: 16px; }

    .wallet-box {
        background: #f8f9fa;
        padding: 30px;
        border-radius: 8px;
        height: 100%;
    }

    .transactions-wrapper {
        padding: 30px;
    }

    .total-balance {
        font-size: 32px;
        font-weight: 700;
        float: right;
    }

    .total-balance::before { content: "৳"; }

    .transaction-item {
        display: flex;
        justify-content: space-between;
        border-left: 4px solid #e1e1e1;
        padding-left: 15px;
        margin-bottom: 20px;
        padding-bottom: 10px;
    }

    .transaction-item.credit { border-color: #28a745; }
    .transaction-item.credit .amount { color: #28a745; }

    .transaction-item.debit { border-color: #dc3545; }
    .transaction-item.debit .amount { color: #dc3545; }

    .pending-amount { font-weight: 700; color: #ff9800; }
</style>

<div class="container-fluid py-4">
    <div class="row">

        <!-- Withdraw / Pending Section -->
        <div class="col-lg-4">
            <div class="wallet-box shadow-sm">

                <h2>Withdraw Balance</h2>

                <form action="{{ route('vendor.send-request') }}" method="POST" class="mt-4">
                    @csrf
                    <input type="number" class="form-control mb-3" name="amount" min="1" placeholder="Enter Amount" required>
                    <input type="text" class="form-control mb-3" name="note" placeholder="Any note...">
                    <button class="btn btn-primary w-100">Send Request</button>
                </form>

                <hr>

                <h3>Pending Withdraw</h3>
                <p class="pending-amount">৳{{ number_format($total_pending, 2) }}</p>

                @foreach ($pendings as $pending)
                    <div class="transaction-item">
                        <div>
                            <h4>{{ \Carbon\Carbon::parse($pending->created_at)->format('M d, Y') }}</h4>
                            <small class="text-muted">{{ $pending->note }}</small>
                        </div>
                        <div class="text-end">
                            <strong>৳{{ number_format($pending->amount, 2) }}</strong>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>

        <!-- Transactions Section -->
        <div class="col-lg-8">
            <div class="transactions-wrapper shadow-sm bg-white">

                <h2>
                    Current Balance
                    <span class="total-balance">{{ number_format($available_balance, 2) }}</span>
                </h2>

                <div class="transactions mt-4">

                    @foreach ($transactions as $item)
                        <div class="transaction-item {{ $item->credit ? 'credit' : 'debit' }}">
                            <div>
                                <h3>{{ $item->title }}</h3>
                                <small class="text-muted">
                                    {{ \Carbon\Carbon::parse($item->updated_at)->format('M d, Y h:i A') }}
                                </small><br>
                                <small class="text-muted">{{ $item->note }}</small>
                            </div>

                            <div class="text-end">
                                <strong class="amount">৳{{ number_format($item->amount, 2) }}</strong>
                            </div>
                        </div>
                    @endforeach

                    <a href="{{ route('vendor.transactions') }}" class="btn btn-light w-100 mt-3">
                        View All Transactions →
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
