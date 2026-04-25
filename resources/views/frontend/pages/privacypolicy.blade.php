@extends('frontend.layouts.master')
@section('title', 'Privacy Policy')
@section('content')
    <section id="quicktech-privacy">
        <div class="container">
            <div class="row my-5">
                <div class="col-lg-11">
                    <div class="quikctech-privacy-inner">

                        <h2 class="quickctech-pri-head">Privacy Policy</h2>

                        @forelse ($privacyPolicies as $policy)
                            <div class="quickctech-pri-section mb-5">
                                {{-- Title --}}
                                <h4 class="h4 text-primary">{{ $loop->iteration }}. {{ $policy->title }}</h4>

                                {{-- Description --}}
                                <div class="text-body">
                                    {!! $policy->content !!}
                                </div>
                            </div>
                        @empty
                            <p>No privacy policies found.</p>
                        @endforelse

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
