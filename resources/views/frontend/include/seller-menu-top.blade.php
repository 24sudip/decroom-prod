<div class="quicktech-seller-menu-top">
    <ul>
        <li><a href="{{ route('vendor.dashboard') }}"><img src="{{ asset('frontend/images/store 1.png') }}" alt="{{ __('messages.store') }}"></a></li>
        <li><a href="{{ route('vendor.profile.edit') }}"><img src="{{ asset('frontend/images/settings (2).png') }}" alt="{{ __('messages.settings') }}"></a></li>
        <li><a href="{{ route('vendor.message') }}"><img src="{{ asset('frontend/images/volunteering.png') }}" alt="{{ __('messages.volunteer') }}"></a></li>
        {{-- <li><a href="#"><i class='bx bx-bell'></i></a></li> --}}
        @php
            $user = auth('vendor')->user();
            $vendor = App\Vendor::where('user_id', $user->id)->first();
            $vendorInfo = $user ? ($user->vendorDetails ?? $vendor) : null;
            $n_count = $vendorInfo->unreadNotifications()->count();
        @endphp
        <li class="nav-item dropdown dropdown-large">
            <a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="{{ asset('frontend/images/bell.png') }}" alt="{{ __('messages.notifications') }}">
                <span class="alert-count" id="notification-count">{{ $n_count }}</span>
            </a>
            <div class="dropdown-menu dropdown-menu-end">
                <a href="javascript:;">
                    <div class="msg-header">
                        <p class="msg-header-title">{{ $vendorInfo->notifications->count() }} Notifications</p>
                        {{-- <p class="msg-header-clear ms-auto"></p> --}}
                    </div>
                </a>
                <div class="header-notifications-list">
                    @forelse ($vendorInfo->unreadNotifications as $notification)
                    <a class="dropdown-item" href="javascript:;" onclick="markNotificationRead('{{ $notification->id }}')">
                        <div class="d-flex align-items-center">
                            <div class="notify bg-light-danger text-danger"><i class="bx bx-cart-alt"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="msg-name">{{ $notification->data['message'] }}
                                    <span class="msg-time float-end">
                                        {{ Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}
                                    </span>
                                </h6>
                                <p class="msg-info">New</p>
                            </div>
                        </div>
                    </a>
                    @empty
                    <a href="javascript:;">
                        <div class="text-center msg-footer">No Notification</div>
                    </a>
                    @endforelse
                </div>
            </div>
        </li>
    </ul>
</div>
<script>
function markNotificationRead(notificationId) {
    fetch("/mark-notification-as-read/" + notificationId, {
        method: 'POST',
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
        },
        body: JSON.stringify({})
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById("notification-count").textContent = data.count;
    })
    .catch(error => {
        console.log('Error', error);
    });
}
</script>
