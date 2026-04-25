<div class="quikctech-seller-dasboard mt-4">
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <strong>{{ session('success') }}</strong>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <strong>{{ session('error') }}</strong>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    <div class="quikctech-dashboard-head">
        <h5><img style="height: 20px;" src="{{ asset('frontend/images/dashboard (1).png') }}" alt="{{ __('messages.dashboard_icon') }}"> {{ __('messages.dashboard') }}</h5>
    </div>

    <hr>

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="quikctech-dashboard-title">{{ __('messages.dashboard_order_statistics') }}</h5>
        <select class="form-select w-auto">
            <option>{{ __('messages.overall_statistics') }}</option>
        </select>
    </div>

    <!-- Top Row -->
    <div class="row g-3 mb-3">
        <div class="col-lg-3 col-md-4">
            <a href="{{ route('vendor.orders.list', ['status' => 2]) }}">
                <div class="quikctech-dashboard-card quikctech-dashboard-bg-blue">
                    <div>
                        <div class="quikctech-dashboard-value text-primary">{{ $orderStats['accepted'] }}</div>
                        <div class="quikctech-dashboard-label">{{ __('messages.accepted') }}</div>
                    </div>
                    <img src="{{ asset('frontend/images/communication (1).png') }}" style="height: 44px;" alt="{{ __('messages.accepted_icon') }}">
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-4">
            <a href="{{ route('vendor.orders.list', ['status' => 1]) }}">
                <div class="quikctech-dashboard-card quikctech-dashboard-bg-yellow">
                    <div>
                        <div class="quikctech-dashboard-value text-warning">{{ $orderStats['pending'] }}</div>
                        <div class="quikctech-dashboard-label">{{ __('messages.pending') }}</div>
                    </div>
                    <img src="{{ asset('frontend/images/real-time.png') }}" style="height: 44px;" alt="{{ __('messages.pending_icon') }}">
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-4">
            <a href="{{ route('vendor.orders.list', ['status' => 3]) }}">
                <div class="quikctech-dashboard-card quikctech-dashboard-bg-green">
                    <div>
                        <div class="quikctech-dashboard-value text-success">{{ $orderStats['in_process'] }}</div>
                        <div class="quikctech-dashboard-label">{{ __('messages.in_process') }}</div>
                    </div>
                    <img src="{{ asset('frontend/images/ready.png') }}" style="height: 44px;" alt="{{ __('messages.in_process_icon') }}">
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-4">
            <a href="{{ route('vendor.orders.list', ['status' => 4]) }}">
                <div class="quikctech-dashboard-card quikctech-dashboard-bg-red">
                    <div>
                        <div class="quikctech-dashboard-value text-danger">{{ $orderStats['picked_up'] }}</div>
                        <div class="quikctech-dashboard-label">{{ __('messages.picked_up') }}</div>
                    </div>
                    <img src="{{ asset('frontend/images/international-shipping.png') }}" style="height: 44px;" alt="{{ __('messages.picked_up_icon') }}">
                </div>
            </a>
        </div>
    </div>

    <!-- Bottom Row -->
    <div class="row g-3">
        <div class="col-lg-3 col-md-4">
            <a href="{{ route('vendor.orders.list', ['status' => 6]) }}">
                <div class="quikctech-dashboard-card quicktech-dashboard-b-card quikctech-dashboard-bg-white">
                    <div class="quikctech-dashboard-label"><img src="{{ asset('frontend/images/shipped.png') }}" alt="{{ __('messages.delivered_icon') }}"> {{ __('messages.delivered') }}</div>
                    <div class="quikctech-dashboard-value text-primary">{{ $orderStats['delivered'] }}</div>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-4">
            <a href="{{ route('vendor.orders.list', ['status' => 8]) }}">
                <div class="quikctech-dashboard-card quicktech-dashboard-b-card quikctech-dashboard-bg-white">
                    <div class="quikctech-dashboard-label"><img src="{{ asset('frontend/images/transaction (1).png') }}" alt="{{ __('messages.return_icon') }}"> {{ __('messages.return') }}</div>
                    <div class="quikctech-dashboard-value text-primary">{{ $orderStats['return'] }}</div>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-4">
            <a href="{{ route('vendor.orders.list', ['status' => 5]) }}">
                <div class="quikctech-dashboard-card quicktech-dashboard-b-card quikctech-dashboard-bg-white">
                    <div class="quikctech-dashboard-label"><img src="{{ asset('frontend/images/deadline (1).png') }}" alt="{{ __('messages.rescheduled_icon') }}"> {{ __('messages.rescheduled') }}</div>
                    <div class="quikctech-dashboard-value text-primary">{{ $orderStats['rescheduled'] }}</div>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-4">
            <a href="{{ route('vendor.orders.list') }}">
                <div class="quikctech-dashboard-card quicktech-dashboard-b-card quikctech-dashboard-bg-white">
                    <div class="quikctech-dashboard-label"><img src="{{ asset('frontend/images/all.png') }}" alt="{{ __('messages.all_icon') }}"> {{ __('messages.all') }}</div>
                    <div class="quikctech-dashboard-value text-primary">{{ $orderStats['all'] }}</div>
                </div>
            </a>
        </div>
    </div>
</div>
<br>