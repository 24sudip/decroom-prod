@extends('frontend.layouts.master_for_details')

@section('title', 'Brands')

@section('content')

<style>
    .brand-search {
        width: 30%;
        margin-left: auto;
        position: relative;
    }
    .brand-search input {
        width: 100%;
        padding: 13px;
        border-radius: 5px;
        border: 1px solid #ddd;
    }
    .brand-search button {
        position: absolute;
        right: 11px;
        top: 12px;
        border: none;
        background-color: transparent;
        font-size: 19px;
    }
    .quicktech-category-main {
        text-align: center;
        margin-bottom: 15px;
    }
    .quicktech-category-main img {
        max-width: 100%;
        height: auto;
    }
    /* Hide brand names but keep them searchable */
    .brand-name {
        display: none;
    }
</style>

<section id="quikctech-allbrand">
    <div class="container-fluid">
        <div class="row my-4">
            <div class="col-lg-12">
                <div class="quicketch-flashsale-head justify-content-center">
                   <h3 style="font-weight: 600;">All Brands</h3>
                </div>
                <div class="brand-search">
                    <input type="text" id="brandSearch" placeholder="Search your brands here">
                    <button><i class="fa-solid fa-magnifying-glass"></i></button>
                </div>
            </div>
        </div>
        <div class="row" id="brandList">
            @forelse ($brands as $brand)
                <div class="col-lg-2 col-6 col-sm-4 brand-item">
                    <div class="quicktech-category-main">
                        <a href="{{ url('brand-product/' . $brand->id) }}">
                            <div class="quikctech-category-img">
                                <img src="{{ asset('public/storage/brands/' . ($brand->image ?? 'public/storage/brands/default.png')) }}"
                                    alt="{{ $brand->name }}">
                            </div>
                            <p class="brand-name">{{ $brand->name }}</p> 
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center">
                    <p>No brands available.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<script>
    document.getElementById('brandSearch').addEventListener('keyup', function() {
        let searchValue = this.value.toLowerCase();
        let items = document.querySelectorAll('#brandList .brand-item');

        items.forEach(function(item) {
            let brandName = item.querySelector('.brand-name').textContent.toLowerCase();
            if (brandName.includes(searchValue)) {
                item.style.display = "";
            } else {
                item.style.display = "none";
            }
        });
    });
</script>

@endsection
