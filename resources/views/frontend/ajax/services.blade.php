@foreach($services as $service)
<div class="col-lg-4 col-6 col-sm-6">
    <a href="{{ url('service-details/' . $service->id) }}">
        <div class="quicktech-product">
            <div class="quikctech-img-product text-center">
                <img src="{{ asset('public/' . $service->attachment) }}"
                     style="width:100%;height:150px;object-fit:cover;">
            </div>

            <div class="quicktech-product-text">
                <h6>{{ Str::limit($service->title, 40) }}</h6>
                <p>৳ {{ number_format($service->total_cost) }}</p>
            </div>
        </div>
    </a>
</div>
@endforeach
