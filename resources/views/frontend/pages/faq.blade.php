@extends('frontend.layouts.master')
@section('title', 'Faq')
@section('content')
    <section id="quicktech-privacy">
        <div class="container">
            <div class="row my-5">
                <div class="col-lg-11">
                    <div class="quikctech-privacy-inner">

                        <h2 class="quickctech-pri-head">FAQ</h2>

                        @forelse ($faqs as $faq)
                            <div class="quickctech-pri-section mb-5">
                                {{-- Title --}}
                                <h4 class="h4 text-primary">{{ $loop->iteration }}. {{ $faq->question }}</h4>

                                {{-- Description --}}
                                <div class="text-body">
                                    {!! $faq->answer !!}
                                </div>
                            </div>
                        @empty
                            <p>No data found.</p>
                        @endforelse

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
