<!-- Right Sidebar -->
<div class="right-bar">
    <div data-simplebar class="h-100">
        <div class="rightbar-title d-flex align-items-center px-3 py-4">
            <h5 class="m-0 me-2">{{ __('Settings') }}</h5>
            <a href="javascript:void(0);" class="right-bar-toggle ms-auto">
                <i class="mdi mdi-close noti-icon"></i>
            </a>
        </div>
        <!-- Settings -->
        <hr class="mt-0" />
        <h6 class="text-center mb-0">{{ __('Choose Layouts') }}</h6>
        <div class="p-4">
            <div class="mb-2">
                <img src="{{ URL::asset('build/images/layouts/layout-1.jpg') }}" class="img-thumbnail" alt="">
            </div>
            <div class="form-check form-switch mb-3">
                <input type="checkbox" class="form-check-input theme-choice" id="light-mode-switch" checked />
                <label class="form-check-label" for="light-mode-switch">{{ __('Light Mode') }}</label>
            </div>
            <div class="mb-2">
                <img src="{{ URL::asset('build/images/layouts/layout-2.jpg') }}" class="img-thumbnail" alt="">
            </div>
            <div class="form-check form-switch mb-3">
                <input type="checkbox" class="form-check-input theme-choice" id="dark-mode-switch"
                    data-bsStyle="{{ URL::asset('build/css/bootstrap-dark.min.css') }}"
                    data-appStyle="{{ URL::asset('build/css/app-dark.min.css') }}" />
                <label class="form-check-label" for="dark-mode-switch">{{ __('Dark Mode') }}</label>
            </div>
            <div class="mb-2">
                <img src="{{ URL::asset('build/images/layouts/layout-3.jpg') }}" class="img-thumbnail" alt="">
            </div>
            <div class="form-check form-switch mb-5">
                <input type="checkbox" class="form-check-input theme-choice" id="rtl-mode-switch"
                    data-appStyle="{{ URL::asset('build/css/app-rtl.min.css') }}" />
                <label class="form-check-label" for="rtl-mode-switch">{{ __('RTL Mode') }}</label>
            </div>
            <a href="{{ url('/') }}" class="btn btn-success w-100 mt-3" target="_blank"><i
                    class="bx bx-home-circle me-1"></i> {{ __('Front Side') }}</a>
            <a href="https://1.envato.market/doctorly-laravel" class="btn btn-primary w-100 mt-3" target="_blank"><i
                    class="mdi mdi-cart me-1"></i> {{ __('Purchase Now') }}</a>
        </div>
    </div> <!-- end slim-scroll-menu-->
</div>
<!-- /Right-bar -->

<div class="rightbar-overlay"></div>
