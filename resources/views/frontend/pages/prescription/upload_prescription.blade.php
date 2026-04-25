@extends('frontend.layouts.master_for_details')

@section('title', 'Upload Prescription')

@section('content')
    <section id="quicktech-prescription">
        <div class="container">
            <div class="row mt-5 mb-3">
                <div class="col-lg-6 m-auto">
                    <!-- Prescription Upload Section -->
                    <div class="quicktech-prescrip-container p-4 border rounded text-center">
                        <div class="quicktech-prescrip-empty mb-4">
                            <img src="{{ asset('images/pres-img.png') }}" alt="No Prescription" class="img-fluid"
                                style="max-height: 150px;">
                            <h5 class="mt-3 text-muted">-- No prescription found --</h5>
                        </div>

                        <!-- Upload Form -->
                        <form action="{{ route('frontend.prescriptions.upload') }}" method="POST"
                            enctype="multipart/form-data" class="quicktech-prescrip-form">
                            @csrf

                            <!-- Upload area -->
                            <div class="quicktech-prescrip-upload dashed-border p-3 d-flex align-items-center justify-content-start cursor-pointer"
                                onclick="document.getElementById('quicktech-prescrip-uploadfile').click();">
                                <div class="quicktech-prescrip-icon d-flex justify-content-center align-items-center">
                                    +
                                </div>
                                <div class="quicktech-prescrip-text ms-3 text-start">
                                    <h6 class="mb-1 fw-bold">Upload Prescription</h6>
                                    <small class="text-muted">Add up to 5 Prescriptions</small>
                                </div>
                            </div>

                            <!-- Hidden File input -->
                            <input type="file" name="files[]" class="form-control d-none"
                                id="quicktech-prescrip-uploadfile" accept="image/*,.pdf" multiple>

                            <!-- Other Fields -->
                            <div class="quicktech-prescrip-fields mt-4">
                                <div class="mb-3">
                                    <input type="text" name="name" class="form-control quicktech-prescrip-name"
                                        placeholder="Enter Name" required>
                                </div>
                                <div class="mb-3">
                                    <input type="tel" name="phone" class="form-control quicktech-prescrip-phone"
                                        placeholder="Enter Phone Number" required>
                                </div>
                                <div class="mb-3">
                                    <textarea name="description" class="form-control quicktech-prescrip-description" placeholder="Enter Description"
                                        rows="3"></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary w-100 quicktech-prescrip-submit">Submit
                                    Prescription</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
            <div class="row mb-5">
                <div class="col-lg-10 m-auto">
                    <!-- Why Upload Prescription Section -->
                    <div class="quicktech-prescrip-whyupload p-4 border rounded bg-white mb-4">
                        <h5 class="fw-bold mb-4 text-primary">Why Should You Upload a Prescription?</h5>

                        <ul class="list-unstyled quicktech-prescrip-list">
                            <li class="d-flex align-items-start mb-3">
                                <div class="quicktech-prescrip-icon-box me-3">
                                    <i class="bi bi-clipboard-check"></i>
                                </div>
                                <div class="quicktech-prescrip-text">
                                    <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24"
                                        class="text-grey600 text-24 mr-10" height="24" width="24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill="none" d="M0 0h24v24H0z"></path>
                                        <path
                                            d="M16.53 11.06 15.47 10l-4.88 4.88-2.12-2.12-1.06 1.06L10.59 17l5.94-5.94zM19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19a2 2 0 0 0 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11z">
                                        </path>
                                    </svg>
                                    Free yourself from the fear of losing prescriptions. You can find your digital
                                    prescription anytime for life.
                                </div>
                            </li>

                            <li class="d-flex align-items-start mb-3">
                                <div class="quicktech-prescrip-icon-box me-3">
                                    <i class="bi bi-shield-check"></i>
                                </div>
                                <div class="quicktech-prescrip-text">
                                    <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 384 512"
                                        class="text-grey600 text-24 mr-10" height="24" width="24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M16 64C16 28.7 44.7 0 80 0L304 0c35.3 0 64 28.7 64 64l0 384c0 35.3-28.7 64-64 64L80 512c-35.3 0-64-28.7-64-64L16 64zM224 448a32 32 0 1 0 -64 0 32 32 0 1 0 64 0zM304 64L80 64l0 320 224 0 0-320z">
                                        </path>
                                    </svg>
                                    No worries if the doctor's handwriting is unclear — our 'A' grade pharmacists will
                                    review your prescription and assist you with ordering medicines.
                                </div>
                            </li>

                            <li class="d-flex align-items-start mb-3">
                                <div class="quicktech-prescrip-icon-box me-3">
                                    <i class="bi bi-lock"></i>
                                </div>
                                <div class="quicktech-prescrip-text">
                                    <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24"
                                        class="text-grey600 text-24 mr-10" height="24" width="24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill="none" d="M0 0h24v24H0z"></path>
                                        <path
                                            d="M19 3h-4.18C14.4 1.84 13.3 1 12 1c-1.3 0-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm2 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z">
                                        </path>
                                    </svg>
                                    Your prescription will be securely stored and will not be shared with any third party.
                                </div>
                            </li>

                            <li class="d-flex align-items-start">
                                <div class="quicktech-prescrip-icon-box me-3">
                                    <i class="bi bi-file-earmark-medical"></i>
                                </div>
                                <div class="quicktech-prescrip-text">
                                    <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 1024 1024"
                                        class="text-grey600 text-24 mr-10" height="24" width="24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M832 464h-68V240c0-70.7-57.3-128-128-128H388c-70.7 0-128 57.3-128 128v224h-68c-17.7 0-32 14.3-32 32v384c0 17.7 14.3 32 32 32h640c17.7 0 32-14.3 32-32V496c0-17.7-14.3-32-32-32zM540 701v53c0 4.4-3.6 8-8 8h-40c-4.4 0-8-3.6-8-8v-53a48.01 48.01 0 1 1 56 0zm152-237H332V240c0-30.9 25.1-56 56-56h248c30.9 0 56 25.1 56 56v224z">
                                        </path>
                                    </svg>
                                    According to government regulations in Bangladesh, a prescription is mandatory to order
                                    certain medicines.
                                </div>
                            </li>
                        </ul>
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection
