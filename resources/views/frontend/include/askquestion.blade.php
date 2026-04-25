<section id="quicktech-askquestion">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="quicktech-ask-inner">

                    <div class="quicktech-review-head">
                        <h4>{{ __('messages.ask_a_question') }}</h4>
                    </div>

                    {{-- Customer Ask Question --}}
                    @auth('customer')
                        <form action="{{ route('product.question.store', $product->id) }}" 
                              method="POST" 
                              class="quicktech-input-question-form">
                            @csrf

                            <div class="quicktech-input-question">
                                <input type="text"
                                       name="question"
                                       placeholder="{{ __('messages.write_your_question', ['product' => $product->name]) }}"
                                       value="{{ old('question') }}"
                                       required minlength="5" maxlength="500">
                                <button type="submit" class="ask-btn">{{ __('messages.ask') }}</button>
                            </div>

                            @error('question')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </form>
                    @else
                        {{-- Not logged in --}}
                        <div class="quicktech-input-question">
                            <input type="text"
                                   placeholder="{{ __('messages.login_to_ask') }}"
                                   disabled>
                            <a href="{{ route('customer.login') }}" class="ask-btn">{{ __('messages.login_to_ask_btn') }}</a>
                        </div>
                    @endauth


                    {{-- Show Questions --}}
                    @if($questions->count())
                        <div class="quicktech-questions-list mt-4">
                            <h5>{{ __('messages.recent_questions_answers') }}</h5>

                            @foreach($questions as $question)
                                <div class="question-item mt-3 p-3 border rounded">

                                    <div class="d-flex justify-content-between align-items-start">

                                        <div class="question-text">
                                            <strong>{{ __('messages.question_label') }} {{ $question->question }}</strong><br>

                                            <small class="text-muted">
                                                {{ __('messages.asked_by', ['name' => $question->customer->name ?? __('messages.anonymous')]) }} •
                                                {{ $question->created_at->diffForHumans() }}
                                            </small>

                                            {{-- Answer Section --}}
                                            @if($question->answer)
                                                <div class="answer-text mt-2 p-2 bg-light rounded">
                                                    <strong>{{ __('messages.answer_label') }}</strong> {{ $question->answer }}<br>
                                                    <small class="text-muted">
                                                        {{ __('messages.answered_by', ['name' => $question->answered_by ?? __('messages.vendor')]) }} •
                                                        {{ $question->answered_at->diffForHumans() }}
                                                    </small>
                                                </div>
                                            @else
                                                <div class="answer-text mt-2">
                                                    <small class="text-warning">{{ __('messages.waiting_for_response') }}</small>
                                                </div>
                                            @endif
                                        </div>

                                        {{-- Vendor Answer Button --}}
                                        @auth('vendor')
                                            @if(!$question->answer)
                                                <button 
                                                    class="btn btn-sm btn-outline-primary answer-btn"
                                                    data-question-id="{{ $question->id }}"
                                                    data-question-text="{{ $question->question }}">
                                                    {{ __('messages.answer') }}
                                                </button>
                                            @endif
                                        @endauth

                                    </div>

                                </div>
                            @endforeach


                            {{-- Pagination --}}
                            <div class="quikctech-pagination mt-4">
                                {{ $questions->links() }}
                            </div>

                        </div>

                    @else
                        <div class="text-center mt-4">
                            <p>{{ __('messages.no_questions_yet') }}</p>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</section>


{{-- Vendor Answer Modal --}}
@auth('vendor')
<div class="modal fade" id="answerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">{{ __('messages.answer_question') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="answerForm" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-body">
                    <label class="form-label">{{ __('messages.question') }}:</label>
                    <p id="questionText" class="form-control-plaintext"></p>

                    <label for="answer" class="form-label mt-2">{{ __('messages.your_answer') }}:</label>
                    <textarea name="answer" id="answer" class="form-control"
                              rows="4" required minlength="5" maxlength="1000"></textarea>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('messages.cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('messages.submit_answer') }}</button>
                </div>

            </form>
        </div>
    </div>
</div>
@endauth


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // Vendor Answer Button
    const answerButtons = document.querySelectorAll('.answer-btn');
    const answerModal = new bootstrap.Modal(document.getElementById('answerModal'));
    const answerForm = document.getElementById('answerForm');
    const questionTextBox = document.getElementById('questionText');

    answerButtons.forEach(btn => {
        btn.addEventListener('click', function () {

            let qId = this.dataset.questionId;
            let qText = this.dataset.questionText;

            questionTextBox.innerText = qText;
            answerForm.action = `/products/questions/${qId}/answer`;

            answerModal.show();
        });
    });

});
</script>
@endpush


@push('styles')
<style>
.quicktech-input-question {
    display:flex;
    gap:10px;
    margin-top:20px;
}
.quicktech-input-question input {
    flex:1;
    padding:12px 15px;
    border:1px solid #ddd;
    border-radius:6px;
}
.ask-btn {
    background:#0AAFCF;
    color:#fff;
    padding:12px 24px;
    border-radius:6px;
    text-decoration:none;
}
.answer-text { border-left:3px solid #28a745; padding-left:15px; }
</style>
@endpush