<section id="quicktech-service-offer">
    <div class="container">
        <div class="row quicktech-head-border mt-3">
            <div class="col-lg-12">
                <div class="quicktech-head">
                    <h4>{{ __('messages.offer_on_services') }}</h4>
                    <a href="{{ route('service.index') }}">{{ __('messages.explore_all_offers') }}</a>
                </div>
            </div>
        </div>
        <div class="row gapp quicktech-border mb-5 pt-3 pb-4">
            <!-- Static Ad Section -->
            <div class="col-lg-3 col-6 col-sm-6">
                <div class="quicktech-side-ad">
            
                    @if(!empty($offerbanner) && !empty($offerbanner->image))
                        <a href="{{ $offerbanner->link_url }}"><img src="{{ asset('storage/banners/' . $offerbanner->image) }}"
                             class="w-100"
                             alt="{{ __('messages.special_offer') }}"></a>
                    @else
                        <img src="{{ asset('frontend/images/Colorful Simple Special Offer Poster 1.png') }}"
                             class="w-100"
                             alt="{{ __('messages.special_offer') }}">
                    @endif
            
                </div>
            </div>
            
            <!-- Dynamic Services Section -->
            @foreach($services as $service)
            <div class="col-lg-3 col-6 col-sm-6">
                <!-- FIX: Use route() instead of url() -->
                <a href="{{ route('service.details', $service->id) }}">
                    <div class="quicktech-product">
                        <div class="quikctech-img-product text-center">
                            @if($service->catalog && Str::contains($service->catalog, ['.mp4', '.mov', '.avi']))
                                <video src="{{ asset('public/' . $service->catalog) }}" class="w-100" controls></video>
                            @elseif($service->attachment && Str::contains($service->attachment, ['.mp4', '.mov', '.avi']))
                                <video src="{{ asset('public/' . $service->attachment) }}" class="w-100" controls></video>
                            @elseif($service->catalog && Str::contains($service->catalog, ['.webp', '.png', '.jpg', '.jpeg', '.svg', '.gif']))
                                <!-- Fallback if no video available -->
                                <div class="bg-light d-flex align-items-center justify-content-center" style="height: 150px;">
                                    <!--<i class="fa-solid fa-video text-muted" style="font-size: 2rem;"></i>-->
                                    <img src="{{ asset($service->catalog) }}" class="w-100" alt="{{ $service->title }}" style="height: 150px; object-fit: cover;">
                                </div>
                            @else
                                <!-- Fallback if no video available -->
                                <div class="bg-light d-flex align-items-center justify-content-center" style="height: 150px;">
                                    <!--<i class="fa-solid fa-video text-muted" style="font-size: 2rem;"></i>-->
                                    <img src="{{ asset($service->attachment) }}" class="w-100" alt="{{ $service->title }}" style="height: 150px; object-fit: cover;">
                                </div>
                            @endif
                        </div>
                        <div class="quicktech-product-text">
                            <h6>
                                {{ Str::limit($service->title, 50) }}
                                <br>
                                <span style="font-size: 13px; font-weight: 700;">
                                    <i class="fa-solid fa-shop"></i> 
                                    {{ $service->vendor->name ?? __('messages.vendor_name') }}
                                </span>
                            </h6>
                            <p>
                                <img src="{{ asset('frontend') }}/images/taka 1.png" alt="{{ __('messages.price') }}"> 
                                @if($service->discount > 0)
                                    <span class="text-danger">
                                        {{ number_format($service->total_cost - $service->discount) }} 
                                        <small class="text-muted text-decoration-line-through">{{ number_format($service->total_cost) }}</small>
                                    </span>
                                @else
                                    {{ $service->total_cost ? '৳ ' . number_format($service->total_cost) : __('messages.negotiable') }}
                                @endif
                            </p>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
            
            <!-- Fallback if no services available -->
            <!--@if($services->count() == 0)-->
            <!--    @for($i = 0; $i < 3; $i++)-->
            <!--    <div class="col-lg-3 col-6 col-sm-6">-->
            <!--        <div class="quicktech-product">-->
            <!--            <div class="quikctech-img-product text-center">-->
            <!--                <div class="bg-light d-flex align-items-center justify-content-center" style="height: 150px;">-->
            <!--                    <i class="fa-solid fa-video text-muted" style="font-size: 2rem;"></i>-->
            <!--                </div>-->
            <!--            </div>-->
            <!--            <div class="quicktech-product-text">-->
            <!--                <h6>-->
            <!--                    {{ __('messages.no_services_available') }}-->
            <!--                    <br>-->
            <!--                    <span style="font-size: 13px; font-weight: 700;">-->
            <!--                        <i class="fa-solid fa-shop"></i> {{ __('messages.coming_soon') }}-->
            <!--                    </span>-->
            <!--                </h6>-->
            <!--                <p><img src="{{ asset('frontend') }}/images/taka 1.png" alt="{{ __('messages.price') }}"> {{ __('messages.negotiable') }}</p>-->
            <!--            </div>-->
            <!--        </div>-->
            <!--    </div>-->
            <!--    @endfor-->
            <!--@endif-->
        </div>
    </div>
</section>