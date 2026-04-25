<div class="col-lg-12">
    <div class="quikctech-product-details">
        <div class="quicktech-p-head">
            <h4>{{ __('messages.product_details') }}</h4>
        </div>
        <div class="quicktech-product-description">
            <h4>{!! $product->description !!}</h4>
        </div>
        
        @if($product->highlight)
        <div class="quicktech-product-description mt-4">
            <h4><strong>{{ __('messages.highlights') }}:</strong> {!! $product->highlight !!}</h4>
        </div>
        @endif

        @if($product->video_path || $product->youtube_url)
        <div class="row gapp mt-4">
            <div class="col-lg-6">
                <div class="quikctech-product-img-details">
                    @if($product->video_path && file_exists(public_path($product->video_path)))
                        <video src="{{ asset($product->video_path) }}" class="w-100" controls></video>
                    @elseif($product->youtube_url)
                        @php
                            // Extract video ID from YouTube URL
                            preg_match('/(youtu\.be\/|youtube\.com\/(watch\?v=|shorts\/))([\w\-]+)/', $product->youtube_url, $matches);
                            $youtubeId = $matches[3] ?? null;
                            $youtubeEmbed = $youtubeId ? "https://www.youtube.com/embed/{$youtubeId}" : null;
                        @endphp
        
                        @if($youtubeEmbed)
                        <div class="embed-responsive embed-responsive-16by9">
                            <iframe class="embed-responsive-item w-100" src="{{ $youtubeEmbed }}" allowfullscreen style="height: 300px;"></iframe>
                        </div>
                        @else
                            <p>{{ __('messages.invalid_youtube_url') }}</p>
                        @endif
                    @endif
                </div>
            </div>
        </div>
        @endif


        <div class="quicktech-specification mt-4">
            <h4>{{ __('messages.specification_of', ['name' => $product->name]) }}</h4>
            <div class="quikctech-spec-info">
                <div class="row gapp mt-3">
                    <div class="col-lg-6">
                        <div class="quicktech-spec-brand">
                            <h4>{{ __('messages.brand') }}
                                <br>
                                <p>{{ $product->brand->name ?? __('messages.no_brand') }}</p>
                            </h4>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="quicktech-spec-brand">
                            <h4>{{ __('messages.sku') }}
                                <br>
                                <p>{{ $product->sku ?? __('messages.not_available') }}</p>
                            </h4>
                        </div>
                    </div>
                    @if($product->weight || $product->length || $product->width || $product->height)
                    <div class="col-lg-6">
                        <div class="quicktech-spec-brand">
                            <h4>{{ __('messages.dimensions') }}
                                <br>
                                <p>
                                    @if($product->weight){{ __('messages.weight') }}: {{ $product->weight }}kg<br>@endif
                                    @if($product->length && $product->width && $product->height)
                                    {{ __('messages.size') }}: {{ $product->length }}x{{ $product->width }}x{{ $product->height }}cm
                                    @endif
                                </p>
                            </h4>
                        </div>
                    </div>
                    @endif
                    <div class="col-lg-6">
                        <div class="quicktech-spec-brand">
                            <h4>{{ __('messages.stock') }}
                                <br>
                                <p>{{ $product->stock > 0 ? $product->stock . ' ' . __('messages.items_available') : __('messages.out_of_stock') }}</p>
                            </h4>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="quicktech-spec-brand">
                            <h4 style="display: flex; gap: 20px;">{{ __('messages.whats_in_the_box') }}:
                                <p>{{ $product->free_items ?? $product->name }}</p>
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>