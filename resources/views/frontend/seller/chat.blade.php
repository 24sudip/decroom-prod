@extends('frontend.seller.seller_master')
@section('title', 'Seller Chat')
@section('content')

<!--<div class="container-fluid py-4">-->
<!--</div>-->
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <!-- Top Menu Bar -->

                <!-- seller-menu-top -->
                @include('frontend.include.seller-menu-top')

                <!-- Profile Navigation -->
                <style>
                    .ser-active {
                        background-color: #1dbf73 !important;
                        color: white !important;
                        border: 2px solid #1dbf73 !important;
                    }
                </style>
                <div class="quikctech-button-menu-profile mt-3">
                    <ul>
                        <li><a style="padding: 0px 10px;" class="ser-active" href="{{ route('vendor.profile') }}">Post</a></li>
                        <li><a style="padding: 0px 10px;" class="ser-active" href="">Message</a></li>
                        <!--<li><a style="padding: 0px 10px;" class="seller-active " href="">About</a></li>-->
                        <li><a style="padding: 0px 10px;" class="ser-active" href="{{ route('vendor.profile.edit') }}">Edit Profile</a></li>
                    </ul>
                </div>

                <!-- Mobile Message List Button -->
                <div class="quikctech-mobile-all-message mt-3 d-block d-lg-none px-3">
                    <button class="btn btn-primary" data-bs-toggle="offcanvas" data-bs-target="#message-list">
                        All Messages
                    </button>
                </div>

                <!-- Main Chat Container -->
                <div class="quikctech-seller-message mt-4">
                    <div class="quikctech-chat-flex">
                        <!-- Left: Conversations Sidebar -->
                        <div class="quikctech-left sasc d-none d-lg-block">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="quikctech-tabs">
                                    <div class="quikctech-tab active">All</div>
                                    <!--<div class="quikctech-tab">Unread</div>-->
                                    <!--<div class="quikctech-tab">Product</div>-->
                                    <!--<div class="quikctech-tab">Service</div>-->
                                </div>
                            </div>

                            <div class="quikctech-list quikctech-conversation-list">
                                @foreach ($customers as $value)
                                    <div class="quikctech-item {{ isset($customerInfo) && $customerInfo->id == $value->id ? 'active' : '' }}"
                                         data-conv-id="{{ $value->id }}" onclick="redirectToChat('{{ $value->name }}', {{ $value->id }})">
                                        <div class="quikctech-avatar">
                                            <img src="{{ asset($value->image) }}" alt="{{ $value->name }}"
                                                 onerror="this.src='{{ asset('public/frontend/images/default-user.png') }}'" />
                                        </div>
                                        <div class="quikctech-meta">
                                            <div class="d-flex align-items-center">
                                                <div class="quikctech-title me-auto">{{ $value->name }}</div>
                                                <div class="quikctech-time">
                                                    @if(isset($value->last_message_time))
                                                        {{ \Carbon\Carbon::parse($value->last_message_time)->diffForHumans() }}
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="quikctech-sub">
                                                @if(isset($value->last_message))
                                                    {{ strlen($value->last_message) > 30 ? substr($value->last_message, 0, 30) . '...' : $value->last_message }}
                                                @else
                                                    Tap to view messages
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                                @if($customers->count() == 0)
                                    <div class="text-center py-4">
                                        <i class="fas fa-users fa-2x text-muted mb-2"></i>
                                        <p class="text-muted mb-0">No customers yet</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Right: Chat Area -->
                        <div class="quikctech-right">
                            @if (isset($smessages) && count($smessages) > 0)
                                <div class="quikctech-header">
                                    <div class="quikctech-title-ribbon" id="quikctech-header-name">
                                        {{ $customerInfo->name }}
                                    </div>
                                    <div class="quikctech-title-ribbon">
                                        {{ $customerInfo->email ?? $customerInfo->phone }}
                                    </div>
                                    <div class="quikctech-title-ribbon">Id: {{ $customerInfo->id }}</div>
                                    <div class="quikctech-status" id="quikctech-header-status">
                                        @php
                                            $customer = Auth::guard('customer')->user();
                                        @endphp

                                        @if($customer && $customer->last_seen)
                                            Active {{ \Carbon\Carbon::parse($customer->last_seen)->diffForHumans() }}
                                        @else
                                            Offline
                                        @endif
                                    </div>
                                    {{--<div class="ms-auto">
                                        <i class="fas fa-ellipsis-vertical"></i>
                                    </div>--}}
                                </div>
                                <div class="quikctech-header">
                                    <div class="quikctech-title-ribbon">{{ $customerInfo->address }}</div>
                                </div>
                                <!-- Chat Body -->
                                <div class="quikctech-chatbody
                                " id="messageBottom">
                                    @foreach ($smessages as $key => $value)
                                        @php
                                            $fileExtension = $value->file ? pathinfo($value->file, PATHINFO_EXTENSION) : null;
                                            $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

                                            // Format file size dynamically
                                            $fileSize = $value->file_size ?? 0;
                                            if ($fileSize >= 1048576) {
                                                $displaySize = number_format($fileSize / 1048576, 2) . ' MB';
                                            } elseif ($fileSize >= 1024) {
                                                $displaySize = number_format($fileSize / 1024, 2) . ' KB';
                                            } else {
                                                $displaySize = $fileSize > 0 ? $fileSize . ' bytes' : '';
                                            }
                                        @endphp

                                        @if($value->isSeller != null)
                                            <!-- ✅ Sent Message (Right Side) -->
                                            <div class="quikctech-msg-row quikctech-msg-right">
                                                <div class="quikctech-avatar-small">
                                                    <div class="avatar-placeholder rounded-circle bg-primary d-inline-flex align-items-center justify-content-center"
                                                         style="width: 36px; height: 36px;">
                                                        <i class="fas fa-store fa-sm text-white"></i>
                                                    </div>
                                                </div>
                                                <div>
                                                    @if($value->file)
                                                        <div class="file-preview">
                                                            @if(in_array(strtolower($fileExtension), $imageExtensions))
                                                                <img src="{{ asset('public/'.$value->file) }}" alt="Image"
                                                                    onerror="this.src='{{ asset('public/'.$value->file) }}'">
                                                            @else
                                                                <i class="fas fa-file fa-2x text-primary me-2"></i>
                                                            @endif

                                                            <div class="file-info">
                                                                <div class="file-size">{{ $displaySize }}</div>
                                                            </div>
                                                            <a href="{{ asset('public/'.$value->file) }}" download class="ms-2 text-decoration-none">
                                                                <i class="fas fa-download text-primary"></i>
                                                            </a>
                                                        </div>
                                                    @endif

                                                    @if($value->message)
                                                        <div class="quikctech-bubble quikctech-bubble-right">
                                                            {{ $value->message }}
                                                        </div>
                                                    @endif

                                                    <div class="quikctech-small-time">
                                                        {{ \Carbon\Carbon::parse($value->created_at)->format('h:i A') }}
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <!-- ✅ Received Message (Left Side) -->
                                            <div class="quikctech-msg-row quikctech-msg-left">
                                                <div class="quikctech-avatar-small">
                                                    <div class="avatar-placeholder rounded-circle bg-secondary d-inline-flex align-items-center justify-content-center"
                                                         style="width: 36px; height: 36px;">
                                                        <i class="fas fa-user fa-sm text-white"></i>
                                                    </div>
                                                </div>
                                                <div>
                                                    @if($value->file)
                                                        <div class="file-preview">
                                                            @if(in_array(strtolower($fileExtension), $imageExtensions))
                                                                <img src="{{ asset('public/'.$value->file) }}" alt="Image"
                                                                     onerror="this.src='{{ asset('public/'.$value->file) }}'">
                                                            @else
                                                                <i class="fas fa-file fa-2x text-primary me-2"></i>
                                                            @endif

                                                            <div class="file-info">
                                                                <div class="file-size">{{ $displaySize }}</div>
                                                            </div>
                                                            <a href="{{ asset('public/'.$value->file) }}" download class="ms-2 text-decoration-none">
                                                                <i class="fas fa-download text-primary"></i>
                                                            </a>
                                                        </div>
                                                    @endif

                                                    @if($value->message)
                                                        <div class="quikctech-bubble quikctech-bubble-left">
                                                            {{ $value->message }}
                                                        </div>
                                                    @endif

                                                    <div class="quikctech-small-time">
                                                        {{ \Carbon\Carbon::parse($value->created_at)->format('h:i A') }}
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>


                                <!-- Input Bar -->
                                <div class="quikctech-inputbar">
                                    <!-- Simple file input with direct onclick -->
                                    <button class="quikctech-icon-btn" title="Camera" type="button" onclick="document.getElementById('file-input').click()">
                                        <i class="fa fa-camera"></i>
                                    </button>

                                    <button class="quikctech-icon-btn" title="Add File" type="button" onclick="document.getElementById('file-input').click()">
                                        <i class="fa fa-plus"></i>
                                    </button>

                                    <form action="{{ route('vendor.send-message') }}" id="contactForm" method="POST" enctype="multipart/form-data" class="d-flex flex-grow-1">
                                        @csrf
                                        <input type="hidden" name="customerId" value="{{ $customerInfo->id }}">

                                        <input type="file" id="file-input" name="file" style="display: none;"
                                               accept=".pdf,.doc,.docx,.txt,.zip,.jpg,.jpeg,.png,.gif,.webp,image/*">
                                        <!--<input id="quikctech-input" name="smessage" class="quikctech-input" placeholder="Write a message...">                                            -->
                                        <input type="text" id="messageInput" name="smessage" class="quikctech-input" placeholder="Type a message...">

                                        <button type="submit" id="sendBtn" class="quikctech-icon-btn" title="Send">
                                            <i class="fa fa-paper-plane"></i>
                                        </button>
                                    </form>
                                </div>

                                <script>
                                // Simple file change handler
                                document.getElementById('file-input').addEventListener('change', function() {
                                    if (this.files.length > 0) {
                                        document.getElementById('contactForm').submit();
                                    }
                                });
                                </script>
                            @else
                                <!-- Welcome State -->
                                <div class="quikctech-chatbody d-flex align-items-center justify-content-center">
                                    <div class="text-center">
                                        <i class="fas fa-comments fa-4x text-muted mb-3"></i>
                                        <h5 class="text-muted">Select a customer to start messaging</h5>
                                        <p class="text-muted">Your conversations with customers will appear here</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Offcanvas for Mobile Message List -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="message-list">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title">Messages</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body p-0">
        <div class="quikctech-tabs p-3">
            <div class="quikctech-tab active">All</div>
            <!--<div class="quikctech-tab">Unread</div>-->
            <!--<div class="quikctech-tab">Product</div>-->
            <!--<div class="quikctech-tab">Service</div>-->
        </div>
        <div class="quikctech-list quikctech-conversation-list">
            @foreach ($customers as $value)
                <div class="quikctech-item {{ isset($customerInfo) && $customerInfo->id == $value->id ? 'active' : '' }}"
                     data-conv-id="{{ $value->id }}" onclick="redirectToChat('{{ $value->name }}', {{ $value->id }})">
                    <div class="quikctech-avatar">
                        <img src="{{ asset($value->image) }}" alt="{{ $value->name }}"
                            onerror="this.src='{{ asset('public/frontend/images/default-user.png') }}'" />
                    </div>
                    <div class="quikctech-meta">
                        <div class="d-flex align-items-center">
                            <div class="quikctech-title me-auto">{{ $value->name }}</div>
                            <div class="quikctech-time">
                                @if(isset($value->last_message_time))
                                    {{ \Carbon\Carbon::parse($value->last_message_time)->diffForHumans() }}
                                @endif
                            </div>
                        </div>
                        <div class="quikctech-sub">
                            @if(isset($value->last_message))
                                {{ strlen($value->last_message) > 30 ? substr($value->last_message, 0, 30) . '...' : $value->last_message }}
                            @else
                                Tap to view messages
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<script>
    function redirectToChat(name, customerId) {
        const offcanvas = document.getElementById('message-list');
        const bsOffcanvas = bootstrap.Offcanvas.getInstance(offcanvas);
        if (bsOffcanvas) {
            bsOffcanvas.hide();
        }

        var url = '{{ url("vendor/me/seller/chat-withcustomer") }}/' + encodeURIComponent(name) + '/' + customerId;
        window.location.href = url;
    }


</script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const chatBody = document.getElementById("messageBottom");
    const messageInput = document.getElementById("messageInput"); // 👈 set this ID on your input

    // ✅ Scroll chat to bottom
    if (chatBody) {
        chatBody.scrollTop = chatBody.scrollHeight;
    }

    // ✅ Focus message input
    if (messageInput) {
        messageInput.focus();
    }
});

// ✅ Scroll again after new message is added dynamically (AJAX)
function scrollToBottom() {
    const chatBody = document.getElementById("messageBottom");
    if (chatBody) {
        chatBody.scrollTop = chatBody.scrollHeight;
    }

    const messageInput = document.getElementById("messageInput");
    if (messageInput) {
        messageInput.focus();
    }
}
</script>


<script type="text/javascript">
    jQuery.noConflict();

    jQuery(document).ready(function($) {
        console.log('Chat system initialized');

        // Auto-scroll to bottom
        const chatMessages = document.getElementById('messageBottom');
        if (chatMessages) {
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        // Simple click handlers for file input
        document.getElementById('camera-btn').addEventListener('click', function() {
            console.log('Camera button clicked');
            document.getElementById('file-input').click();
        });

        document.getElementById('file-btn').addEventListener('click', function() {
            console.log('File button clicked');
            document.getElementById('file-input').click();
        });

        // Handle file selection
        document.getElementById('file-input').addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                console.log('File selected:', file.name, 'Size:', file.size, 'Type:', file.type);

                // Auto-submit form when file is selected
                if (file.size > 10 * 1024 * 1024) {
                    alert('File size too large. Please select a file smaller than 10MB.');
                    this.value = '';
                    return;
                }

                // Submit the form immediately when file is selected
                $('#contactForm').submit();
            }
        });

        // Form submission handler
        $('#contactForm').on('submit', function(event) {
            event.preventDefault();

            const messageText = $('#quikctech-input').val().trim();
            const fileInput = document.getElementById('file-input');
            const hasFile = fileInput.files.length > 0;

            console.log('Form submission - Message:', messageText, 'Has file:', hasFile);

            // Don't submit if both message and file are empty
            if (!messageText && !hasFile) {
                console.log('Empty message and no file, not submitting');
                return;
            }

            // Show loading state
            let sendBtn = $('#sendBtn');
            let originalContent = sendBtn.html();
            sendBtn.prop('disabled', true);
            sendBtn.html('<i class="fas fa-spinner fa-spin"></i>');

            // Create FormData from the form
            let formData = new FormData(this);

            // If there's a file, make sure it's included
            if (hasFile) {
                formData.append('file', fileInput.files[0]);
            }

            console.log('Submitting form with FormData');

            $.ajax({
                type: "POST",
                url: "{{ route('vendor.send-message') }}",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    console.log('Server response:', response);

                    if (response.success) {
                        console.log('Message sent successfully');

                        // Clear inputs
                        $('#quikctech-input').val('');
                        document.getElementById('file-input').value = '';

                        // Refresh chat immediately
                        refreshChatContent();
                    } else {
                        console.error('Backend error:', response.message);
                        if (response.errors) {
                            let errorMessage = 'Please fix the following errors:\n';
                            for (let field in response.errors) {
                                errorMessage += response.errors[field][0] + '\n';
                            }
                            alert(errorMessage);
                        } else {
                            alert('Error: ' + (response.message || 'Unknown error occurred'));
                        }
                    }

                    sendBtn.prop('disabled', false);
                    sendBtn.html('<i class="fa fa-paper-plane"></i>');
                },
                error: function(xhr, status, error) {
                    console.error('AJAX error:', xhr.responseText);
                    console.log('Status:', status, 'Error:', error);

                    let errorMessage = 'Error sending message. Please try again.';

                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        errorMessage = 'Please fix the following errors:\n';
                        for (let field in errors) {
                            errorMessage += errors[field][0] + '\n';
                        }
                    } else if (xhr.status === 500) {
                        errorMessage = 'Server error. Please try again later.';
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }

                    alert(errorMessage);
                    sendBtn.prop('disabled', false);
                    sendBtn.html('<i class="fa fa-paper-plane"></i>');
                }
            });
        });

        // Refresh chat content function
        function refreshChatContent() {
            const currentCustomerId = $('input[name="customerId"]').val();
            if (!currentCustomerId) {
                console.log('No customer ID found, cannot refresh chat');
                return;
            }

            console.log('Refreshing chat content for customer:', currentCustomerId);

            $.ajax({
                type: "GET",
                url: "{{ url('vendor/me/seller/chat/content') }}",
                data: {
                    customerId: currentCustomerId,
                    _token: '{{ csrf_token() }}'
                },
                dataType: "html",
                success: function(html) {
                    console.log('Chat content refreshed successfully');
                    $('.quikctech-right').html(html);

                    // Re-scroll to bottom after content load
                    setTimeout(() => {
                        const chatMessages = document.getElementById('messageBottom');
                        if (chatMessages) {
                            chatMessages.scrollTop = chatMessages.scrollHeight;
                        }
                        // Re-attach event listeners
                        attachEventListeners();
                    }, 100);
                },
                error: function(xhr) {
                    console.error('Error refreshing chat:', xhr);
                    console.log('Response text:', xhr.responseText);
                }
            });
        }

        // Re-attach event listeners after AJAX content load
        function attachEventListeners() {
            console.log('Attaching event listeners...');

            // Remove existing event listeners and add new ones
            const cameraBtn = document.getElementById('camera-btn');
            const fileBtn = document.getElementById('file-btn');
            const fileInput = document.getElementById('file-input');

            // Clone and replace to remove old event listeners
            const newCameraBtn = cameraBtn.cloneNode(true);
            const newFileBtn = fileBtn.cloneNode(true);
            const newFileInput = fileInput.cloneNode(true);

            cameraBtn.parentNode.replaceChild(newCameraBtn, cameraBtn);
            fileBtn.parentNode.replaceChild(newFileBtn, fileBtn);
            fileInput.parentNode.replaceChild(newFileInput, fileInput);

            // Add event listeners using vanilla JavaScript
            document.getElementById('camera-btn').addEventListener('click', function() {
                console.log('Camera button clicked (reattached)');
                document.getElementById('file-input').click();
            });

            document.getElementById('file-btn').addEventListener('click', function() {
                console.log('File button clicked (reattached)');
                document.getElementById('file-input').click();
            });

            document.getElementById('file-input').addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    console.log('File selected (reattached):', file.name);

                    if (file.size > 10 * 1024 * 1024) {
                        alert('File size too large. Please select a file smaller than 10MB.');
                        this.value = '';
                        return;
                    }

                    $('#contactForm').submit();
                }
            });

            // Form submission
            $('#contactForm').off('submit').on('submit', function(event) {
                event.preventDefault();

                const messageText = $('#quikctech-input').val().trim();
                const fileInput = document.getElementById('file-input');
                const hasFile = fileInput.files.length > 0;

                if (!messageText && !hasFile) {
                    console.log('Empty message and no file, not submitting');
                    return;
                }

                let sendBtn = $('#sendBtn');
                let originalContent = sendBtn.html();
                sendBtn.prop('disabled', true);
                sendBtn.html('<i class="fas fa-spinner fa-spin"></i>');

                let formData = new FormData(this);

                if (hasFile) {
                    formData.append('file', fileInput.files[0]);
                }

                $.ajax({
                    type: "POST",
                    url: "{{ route('vendor.send-message') }}",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        console.log('Server response (reattached):', response);

                        if (response.success) {
                            $('#quikctech-input').val('');
                            document.getElementById('file-input').value = '';
                            refreshChatContent();
                        } else {
                            if (response.errors) {
                                let errorMessage = 'Please fix the following errors:\n';
                                for (let field in response.errors) {
                                    errorMessage += response.errors[field][0] + '\n';
                                }
                                alert(errorMessage);
                            } else {
                                alert('Error: ' + (response.message || 'Unknown error'));
                            }
                        }

                        sendBtn.prop('disabled', false);
                        sendBtn.html('<i class="fa fa-paper-plane"></i>');
                    },
                    error: function(xhr) {
                        console.error('Error (reattached):', xhr);
                        sendBtn.prop('disabled', false);
                        sendBtn.html('<i class="fa fa-paper-plane"></i>');
                        alert('Error sending message. Please try again.');
                    }
                });
            });

            // Enter key to submit
            $('#quikctech-input').off('keydown').on('keydown', function(e) {
                if (e.keyCode === 13 && !e.shiftKey) {
                    e.preventDefault();
                    const messageText = $(this).val().trim();
                    const hasFile = document.getElementById('file-input').files.length > 0;

                    if (messageText || hasFile) {
                        $('#contactForm').submit();
                    }
                }
            });
        }

        // Auto-refresh chat every 5 seconds if a conversation is active
        @if(isset($customerInfo) && count($smessages) > 0)
        setInterval(refreshChatContent, 5000);
        @endif

        // Initial attachment of event listeners
        attachEventListeners();
    });
</script>

<style>
/* Your existing CSS styles remain exactly the same */
.quicktech-seller-menu-top {
    background: #fff;
    padding: 15px 20px;
    border-bottom: 1px solid #eaeaea;
}

.quicktech-seller-menu-top ul {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    justify-content: flex-end;
    gap: 20px;
}

.quicktech-seller-menu-top ul li a img {
    width: 24px;
    height: 24px;
    transition: transform 0.3s;
}

.quicktech-seller-menu-top ul li a:hover img {
    transform: scale(1.1);
}

.quikctech-button-menu-profile ul {
    list-style: none;
    padding: 0 20px;
    margin: 0;
    display: flex;
    gap: 30px;
    border-bottom: 1px solid #eaeaea;
}

.quikctech-button-menu-profile ul li a {
    text-decoration: none;
    color: #6c757d;
    padding: 10px 0;
    font-weight: 500;
    position: relative;
    transition: color 0.3s;
}

.quikctech-button-menu-profile ul li a:hover,
.quikctech-button-menu-profile ul li a.seller-active {
    color: #007bff;
}

.quikctech-button-menu-profile ul li a.seller-active::after {
    content: '';
    position: absolute;
    bottom: -1px;
    left: 0;
    width: 100%;
    height: 3px;
    background: #007bff;
    border-radius: 3px 3px 0 0;
}

.quikctech-mobile-all-message {
    display: none;
}

.quikctech-seller-message {
    padding: 0 20px 20px 20px;
}

.quikctech-chat-flex {
    display: flex;
    height: 600px;
    background: #fff;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 0 20px rgba(0,0,0,0.1);
}

.quikctech-left {
    width: 35%;
    border-right: 1px solid #eaeaea;
    display: flex;
    flex-direction: column;
    background: #fff;
}

.quikctech-tabs {
    display: flex;
    padding: 15px;
    gap: 10px;
    border-bottom: 1px solid #eaeaea;
}

.quikctech-tab {
    padding: 8px 15px;
    border-radius: 20px;
    background: #f8f9fa;
    color: #6c757d;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.3s;
}

.quikctech-tab:hover,
.quikctech-tab.active {
    background: #007bff;
    color: #fff;
}

.quikctech-list {
    flex: 1;
    overflow-y: auto;
}

.quikctech-item {
    display: flex;
    padding: 15px;
    border-bottom: 1px solid #f0f0f0;
    cursor: pointer;
    transition: background 0.3s;
}

.quikctech-item:hover,
.quikctech-item.active {
    background: #f8f9fa;
}

.quikctech-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    overflow: hidden;
    margin-right: 15px;
    flex-shrink: 0;
}

.quikctech-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.quikctech-meta {
    flex: 1;
    min-width: 0;
}

.quikctech-title {
    font-weight: 600;
    color: #333;
    margin-bottom: 5px;
}

.quikctech-time {
    font-size: 12px;
    color: #6c757d;
}

.quikctech-sub {
    font-size: 14px;
    color: #6c757d;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.quikctech-right {
    flex: 1;
    display: flex;
    flex-direction: column;
    background: #f8f9fa;
}

.quikctech-header {
    display: flex;
    align-items: center;
    padding: 15px 20px;
    background: #fff;
    border-bottom: 1px solid #eaeaea;
}

.quikctech-title-ribbon {
    font-weight: 600;
    font-size: 18px;
    color: #333;
}

.quikctech-status {
    font-size: 14px;
    color: #6c757d;
    margin-left: 15px;
}

.quikctech-chatbody {
    flex: 1;
    padding: 20px;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.quikctech-msg-row {
    display: flex;
    margin-bottom: 15px;
}



.quikctech-msg-left {
    justify-content: flex-start;
}

.quikctech-avatar-small {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    overflow: hidden;
    margin-right: 10px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #007bff;
}

.quikctech-avatar-small i {
    color: white;
    font-size: 14px;
}

.quikctech-bubble {
    padding: 12px 16px;
    border-radius: 18px;
    position: relative;
    word-wrap: break-word;
    line-height: 1.4;
}

.quikctech-bubble-right {
    background: #007bff;
    color: white;
    border-bottom-right-radius: 4px;
}

.quikctech-bubble-left {
    background: white;
    color: #333;
    border-bottom-left-radius: 4px;
    box-shadow: 0 1px 2px rgba(0,0,0,0.1);
}

.quikctech-small-time {
    font-size: 11px;
    color: #6c757d;
    margin-top: 5px;
}

.quikctech-msg-right .quikctech-small-time {
    text-align: right;
}

.quikctech-msg-left .quikctech-small-time {
    text-align: left;
}

.file-preview {
    display: flex;
    align-items: center;
    padding: 12px;
    background: #fff;
    border-radius: 12px;
    margin-bottom: 8px;
    max-width: 300px;
    border: 1px solid #e9ecef;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.file-preview img {
    width: 50px;
    height: 50px;
    object-fit: cover;
    border-radius: 6px;
    margin-right: 12px;
}

.file-info {
    flex: 1;
    min-width: 0;
}

.file-name {
    font-weight: 500;
    margin-bottom: 4px;
    font-size: 14px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.file-size {
    font-size: 12px;
    color: #6c757d;
}

.quikctech-inputbar {
    display: flex;
    align-items: center;
    padding: 15px 20px;
    background: #fff;
    border-top: 1px solid #eaeaea;
    gap: 10px;
}

.quikctech-icon-btn {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f8f9fa;
    border: none;
    color: #6c757d;
    transition: all 0.3s;
    flex-shrink: 0;
}

.quikctech-icon-btn:hover {
    background: #007bff;
    color: #fff;
}

.quikctech-input {
    flex: 1;
    border: none;
    background: #f8f9fa;
    border-radius: 20px;
    padding: 12px 20px;
    outline: none;
}

.quikctech-list::-webkit-scrollbar,
.quikctech-chatbody::-webkit-scrollbar {
    width: 6px;
}

.quikctech-list::-webkit-scrollbar-track,
.quikctech-chatbody::-webkit-scrollbar-track {
    background: #f1f1f1;
}

.quikctech-list::-webkit-scrollbar-thumb,
.quikctech-chatbody::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

.quikctech-list::-webkit-scrollbar-thumb:hover,
.quikctech-chatbody::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

@media (max-width: 992px) {
    .quikctech-mobile-all-message {
        display: block;
    }

    .quikctech-left {
        display: none;
    }

    .quikctech-chat-flex {
        height: 500px;
    }
}

@media (max-width: 768px) {
    .quikctech-button-menu-profile ul {
        gap: 15px;
        overflow-x: auto;
        padding-bottom: 5px;
    }

    .quikctech-button-menu-profile ul li a {
        white-space: nowrap;
    }

    .file-preview {
        max-width: 250px;
    }
}

.quikctech-icon-btn .fa-spinner {
    animation: fa-spin 1s infinite linear;
}
</style>

@endsection
