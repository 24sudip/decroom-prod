@extends('frontend.layouts.master')
@section('title', 'Return and Refund Policy')

@section('content')
    <section id="quicktech-privacy">
        <div class="container">
            <div class="row my-5">
                <div class="col-lg-11">
                    <div class="quikctech-privacy-inner">

                        <h2 class="quickctech-pri-head">Return and Refund Policy</h2>

                        @forelse ($terms as $term)
                            <div class="quickctech-pri-section mb-5">
                                {{-- Title --}}
                                <h4 class="h4 text-primary">{{ $loop->iteration }}. {{ $term->title }}</h4>

                                {{-- Description --}}
                                <div class="text-body">
                                    {!! $term->content !!}
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
