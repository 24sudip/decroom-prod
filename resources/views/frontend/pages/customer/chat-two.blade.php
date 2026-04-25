@extends('frontend.layouts.master')
@section('title', 'Chat')
@section('content')

<section id="quicktech-customer-profile" style="background: url('{{ asset('frontend/images/profile-bg.png') }}') no-repeat center / cover;">
    <div class="container py-5">

        <div class="row">
            <!-- Sidebar Navigation -->
            <div class="col-lg-3 col-12 mb-4">

            </div>
            <!-- Main Content - Chat Section -->
            <div class="col-lg-9 col-12">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-0">
                        <div class="modern-chat-container" id="cardContent">
                            @if (!isset($cmessages))
                            <!-- Chat List Sidebar -->
                            <div class="chat-sidebar">
                                <div class="chat-sidebar-header p-3 border-bottom">
                                    <h5 class="mb-0">Messages</h5>
                                </div>
                                <div class="chat-list">
                                    @foreach ($chatlist as $value)
                                        <div class="chat-list-item {{ isset($sellerInfo) && $sellerInfo->id == $value->id ? 'active' : '' }}"
                                             onclick="redirectToChat({{ $value->id }})">
                                            <div class="chat-avatar">
                                                <img src="{{ asset($value->logo) }}" alt="logo" />
                                            </div>
                                            <div class="chat-info">
                                                <div class="chat-shop-name">{{ $value->shop_name }}</div>
                                                <div class="chat-preview">Tap to view messages</div>
                                            </div>
                                            <div class="chat-time"></div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            <!-- Chat Area -->
                            <div class="chat-area">
                                @if (isset($cmessages))
                                    <div class="chat-header p-3 border-bottom d-flex align-items-center">
                                        <div class="chat-header-avatar me-3">
                                            <img src="{{ asset($sellerInfo->vendorDetails->logo) }}" alt="logo" />
                                        </div>
                                        <div class="chat-header-info">
                                            <a href="{{ route('vendor.shop.view', $sellerInfo->id) }}">
                                                <h6 class="mb-0">{{ $sellerInfo->vendorDetails->shop_name }}</h6>
                                            </a>
                                            <small class="text-muted">Online</small>
                                        </div>
                                    </div>

                                    <div class="chat-messages p-3" id="messageBottom">
                                        @foreach ($cmessages as $key => $value)
                                            <div class="message {{ $value->isCustomer != null ? 'sent' : 'received' }}">
                                                <div class="message-content">
                                                    @if($value->file)
                                                        <div class="file-preview">
                                                            @php
                                                                $fileExtension = pathinfo($value->file, PATHINFO_EXTENSION);
                                                                $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

                                                                // Format file size dynamically
                                                                $fileSize = $value->file_size ?? 0;
                                                                if ($fileSize >= 1048576) {
                                                                    $displaySize = number_format($fileSize / 1048576, 2) . ' MB';
                                                                } elseif ($fileSize >= 1024) {
                                                                    $displaySize = number_format($fileSize / 1024, 2) . ' KB';
                                                                } else {
                                                                    $displaySize = $fileSize . ' bytes';
                                                                }
                                                            @endphp

                                                            @if(in_array(strtolower($fileExtension), $imageExtensions))
                                                                <img src="{{ asset('public/'.$value->file) }}" alt="Image"
                                                                     onerror="this.src='{{ asset('public/'.$value->file) }}'">
                                                            @else
                                                                <i class="fas fa-file fa-2x text-primary me-2"></i>
                                                            @endif

                                                            <div class="file-info">
                                                                <!--<div class="file-name">{{ basename($value->file) }}</div>-->
                                                                <div class="file-size">{{ $displaySize }}</div>
                                                            </div>
                                                            <a href="{{ asset('public/'.$value->file) }}" download class="ms-2 text-decoration-none">
                                                                <i class="fas fa-download text-primary"></i>
                                                            </a>
                                                        </div>
                                                    @endif

                                                    @if($value->message)
                                                        <div class="message-bubble">
                                                            <div class="message-text">{{ $value->message }}</div>
                                                            <div class="message-time">
                                                                @if($value->created_at)
                                                                    {{ \Carbon\Carbon::parse($value->created_at)->format('h:i A') }}
                                                                @else
                                                                    {{ now()->format('h:i A') }}
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <!-- Input Bar -->
                                    <div class="chat-input p-3 border-top">
                                        <!-- Simple file input with direct onclick -->
                                        <button class="chat-icon-btn" title="Camera" type="button" onclick="document.getElementById('file-input').click()">
                                            <i class="fa fa-camera"></i>
                                        </button>

                                        <button class="chat-icon-btn" title="Add File" type="button" onclick="document.getElementById('file-input').click()">
                                            <i class="fa fa-plus"></i>
                                        </button>

                                        <form action="{{ route('customer.send-message') }}" id="contactForm" method="POST" enctype="multipart/form-data" class="d-flex flex-grow-1">
                                            @csrf
                                            <input type="hidden" name="sellerId" value="{{ $sellerInfo->id }}">

                                            <input type="file" id="file-input" name="file" style="display: none;"
                                                   accept=".pdf,.doc,.docx,.txt,.zip,.jpg,.jpeg,.png,.gif,.webp,image/*">

                                            <input id="message-input" name="message" class="chat-input-field" placeholder="Type your message...">
                                            <button type="submit" id="sendBtn" class="chat-icon-btn" title="Send">
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
                                    <div class="chat-welcome d-flex align-items-center justify-content-center h-100">
                                        <div class="text-center">
                                            <i class="fas fa-comments fa-4x text-muted mb-3"></i>
                                            <h5 class="text-muted">Select a conversation to start messaging</h5>
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
</section>
<script>
// document.addEventListener("DOMContentLoaded", function () {
//     var myModal = new bootstrap.Modal(document.getElementById('exampleModal'));
//     myModal.show();
// });
let modalInstance;

function toggleModalByScreen() {
    const modalEl = document.getElementById('exampleModal');

    if (window.innerWidth < 768) {
        if (!modalInstance) {
            modalInstance = new bootstrap.Modal(modalEl);
        }
        modalInstance.show();
    } else {
        if (modalInstance) {
            modalInstance.hide();
        }
    }
}

window.addEventListener('load', toggleModalByScreen);
window.addEventListener('resize', toggleModalByScreen);
</script>

<script>
    function redirectToChat(sellerId) {
        let url = "{{ route('customer.chat-with-seller', ':id') }}";
        url = url.replace(':id', sellerId);
        window.location.href = url;
    }
</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const chatBody = document.getElementById("messageBottom");
        const messageInput = document.getElementById("message-input");

        // ✅ Scroll to bottom on page load
        if (chatBody) {
            chatBody.scrollTop = chatBody.scrollHeight;
        }

        // ✅ Focus on input field
        if (messageInput) {
            messageInput.focus();
        }
    });

    // ✅ Reusable function — call after sending a message (e.g. via AJAX)
    function scrollToBottom() {
        const chatBody = document.getElementById("messageBottom");
        const messageInput = document.getElementById("message-input");

        if (chatBody) {
            chatBody.scrollTop = chatBody.scrollHeight;
        }

        if (messageInput) {
            messageInput.focus();
        }
    }

    // ✅ Automatically scroll after form submit (even on refresh)
    document.getElementById('contactForm')?.addEventListener('submit', function() {
        setTimeout(scrollToBottom, 500);
    });
</script>


<script type="text/javascript">
    jQuery.noConflict();

    jQuery(document).ready(function($) {
        console.log('Customer chat system initialized');

        // Auto-scroll to bottom
        const chatMessages = document.getElementById('messageBottom');
        if (chatMessages) {
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

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

            const messageText = $('#message-input').val().trim();
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
                url: "{{ route('customer.send-message') }}",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    console.log('Server response:', response);

                    if (response.success) {
                        console.log('Message sent successfully');

                        // Clear inputs
                        $('#message-input').val('');
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
            const currentSellerId = $('input[name="sellerId"]').val();
            if (!currentSellerId) {
                console.log('No seller ID found, cannot refresh chat');
                return;
            }

            console.log('Refreshing chat content for seller:', currentSellerId);

            $.ajax({
                type: "GET",
                url: "{{ url('customer/chat/content') }}",
                data: {
                    sellerId: currentSellerId,
                    _token: '{{ csrf_token() }}'
                },
                dataType: "html",
                success: function(html) {
                    console.log('Chat content refreshed successfully');
                    $('.chat-area').html(html);

                    // Re-scroll to bottom after content load
                    setTimeout(() => {
                        const chatMessages = document.getElementById('messageBottom');
                        if (chatMessages) {
                            chatMessages.scrollTop = chatMessages.scrollHeight;
                        }
                    }, 100);
                },
                error: function(xhr) {
                    console.error('Error refreshing chat:', xhr);
                    console.log('Response text:', xhr.responseText);
                }
            });
        }

        // Enter key to submit
        $('#message-input').on('keydown', function(e) {
            if (e.keyCode === 13 && !e.shiftKey) {
                e.preventDefault();
                const messageText = $(this).val().trim();
                const hasFile = document.getElementById('file-input').files.length > 0;

                if (messageText || hasFile) {
                    $('#contactForm').submit();
                }
            }
        });

        // Auto-refresh chat every 5 seconds if a conversation is active
        @if(isset($sellerInfo) && count($cmessages) > 0)
        setInterval(refreshChatContent, 5000);
        @endif
    });
</script>

<style>
.profile-sidebar .nav-link {
    color: #333;
    padding: 12px 15px;
    border-radius: 8px;
    margin-bottom: 5px;
    transition: all 0.3s ease;
}

.profile-sidebar .nav-link:hover,
.profile-sidebar .nav-link.active {
    background: linear-gradient(45deg, #007bff, #0056b3);
    color: white;
}

.profile-content {
    min-height: 500px;
}

.stat-card {
    transition: transform 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
}

.avatar-placeholder {
    box-shadow: 0 0 20px rgba(0,0,0,0.1);
}

.profile-header {
    text-shadow: 0 2px 4px rgba(0,0,0,0.5);
}

/* Modern Chat Styles */
.modern-chat-container {
    display: flex;
    height: 600px;
    background: #fff;
    border-radius: 10px;
    overflow: hidden;
}

.chat-sidebar {
    width: 35%;
    border-right: 1px solid #eaeaea;
    display: flex;
    flex-direction: column;
}

.chat-sidebar-header {
    background: #f8f9fa;
}

.chat-list {
    flex: 1;
    overflow-y: auto;
}

.chat-list-item {
    display: flex;
    padding: 15px;
    border-bottom: 1px solid #f0f0f0;
    cursor: pointer;
    transition: background 0.2s;
}

.chat-list-item:hover {
    background: #f8f9fa;
}

.chat-list-item.active {
    background: #e3f2fd;
}

.chat-avatar {
    margin-right: 12px;
}

.chat-avatar img {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;
}

.chat-info {
    flex: 1;
}

.chat-shop-name {
    font-weight: 600;
    margin-bottom: 4px;
}

.chat-preview {
    font-size: 0.85rem;
    color: #6c757d;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.chat-time {
    font-size: 0.75rem;
    color: #6c757d;
}

.chat-area {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.chat-header {
    background: #f8f9fa;
}

.chat-header-avatar img {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
}

.chat-messages {
    flex: 1;
    overflow-y: auto;
    background: #f5f7fb;
    padding: 20px;
}

.message {
    display: flex;
    margin-bottom: 16px;
}

.message.sent {
    justify-content: flex-end;
}

.message.received {
    justify-content: flex-start;
}

.message-content {
    max-width: 70%;
}

.message-bubble {
    padding: 12px 16px;
    border-radius: 18px;
    position: relative;
}

.message.sent .message-bubble {
    background: #007bff;
    color: white;
    border-bottom-right-radius: 4px;
}

.message.received .message-bubble {
    background: white;
    color: #333;
    border-bottom-left-radius: 4px;
    box-shadow: 0 1px 2px rgba(0,0,0,0.1);
}

.message-time {
    font-size: 0.7rem;
    margin-top: 4px;
    opacity: 0.8;
}

.message.sent .message-time {
    text-align: right;
}

/* File Preview Styles */
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

/* Chat Input Styles */
.chat-input {
    background: white;
    display: flex;
    align-items: center;
    gap: 10px;
}

.chat-icon-btn {
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

.chat-icon-btn:hover {
    background: #007bff;
    color: #fff;
}

.chat-input-field {
    flex: 1;
    border: none;
    background: #f8f9fa;
    border-radius: 20px;
    padding: 12px 20px;
    outline: none;
    resize: none;
}

.chat-welcome {
    background: #f8f9fa;
}

/* Responsive adjustments */
@media (max-width: 992px) {
    .modern-chat-container {
        flex-direction: column;
        height: auto;
    }

    .chat-sidebar {
        width: 100%;
        height: 300px;
        border-right: none;
        border-bottom: 1px solid #eaeaea;
    }

    .chat-area {
        height: 500px;
    }

    .file-preview {
        max-width: 250px;
    }
}

/* Scrollbar styling */
.chat-list::-webkit-scrollbar,
.chat-messages::-webkit-scrollbar {
    width: 6px;
}

.chat-list::-webkit-scrollbar-track,
.chat-messages::-webkit-scrollbar-track {
    background: #f1f1f1;
}

.chat-list::-webkit-scrollbar-thumb,
.chat-messages::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

.chat-list::-webkit-scrollbar-thumb:hover,
.chat-messages::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

.chat-icon-btn .fa-spinner {
    animation: fa-spin 1s infinite linear;
}
</style>

@endsection
