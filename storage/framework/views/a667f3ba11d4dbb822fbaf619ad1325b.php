<section id="quicktech-askquestion">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="quicktech-ask-inner">

                    <div class="quicktech-review-head">
                        <h4><?php echo e(__('messages.ask_a_question')); ?></h4>
                    </div>

                    
                    <?php if(auth()->guard('customer')->check()): ?>
                        <form action="<?php echo e(route('product.question.store', $product->id)); ?>" 
                              method="POST" 
                              class="quicktech-input-question-form">
                            <?php echo csrf_field(); ?>

                            <div class="quicktech-input-question">
                                <input type="text"
                                       name="question"
                                       placeholder="<?php echo e(__('messages.write_your_question', ['product' => $product->name])); ?>"
                                       value="<?php echo e(old('question')); ?>"
                                       required minlength="5" maxlength="500">
                                <button type="submit" class="ask-btn"><?php echo e(__('messages.ask')); ?></button>
                            </div>

                            <?php $__errorArgs = ['question'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="text-danger mt-2"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </form>
                    <?php else: ?>
                        
                        <div class="quicktech-input-question">
                            <input type="text"
                                   placeholder="<?php echo e(__('messages.login_to_ask')); ?>"
                                   disabled>
                            <a href="<?php echo e(route('customer.login')); ?>" class="ask-btn"><?php echo e(__('messages.login_to_ask_btn')); ?></a>
                        </div>
                    <?php endif; ?>


                    
                    <?php if($questions->count()): ?>
                        <div class="quicktech-questions-list mt-4">
                            <h5><?php echo e(__('messages.recent_questions_answers')); ?></h5>

                            <?php $__currentLoopData = $questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="question-item mt-3 p-3 border rounded">

                                    <div class="d-flex justify-content-between align-items-start">

                                        <div class="question-text">
                                            <strong><?php echo e(__('messages.question_label')); ?> <?php echo e($question->question); ?></strong><br>

                                            <small class="text-muted">
                                                <?php echo e(__('messages.asked_by', ['name' => $question->customer->name ?? __('messages.anonymous')])); ?> •
                                                <?php echo e($question->created_at->diffForHumans()); ?>

                                            </small>

                                            
                                            <?php if($question->answer): ?>
                                                <div class="answer-text mt-2 p-2 bg-light rounded">
                                                    <strong><?php echo e(__('messages.answer_label')); ?></strong> <?php echo e($question->answer); ?><br>
                                                    <small class="text-muted">
                                                        <?php echo e(__('messages.answered_by', ['name' => $question->answered_by ?? __('messages.vendor')])); ?> •
                                                        <?php echo e($question->answered_at->diffForHumans()); ?>

                                                    </small>
                                                </div>
                                            <?php else: ?>
                                                <div class="answer-text mt-2">
                                                    <small class="text-warning"><?php echo e(__('messages.waiting_for_response')); ?></small>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        
                                        <?php if(auth()->guard('vendor')->check()): ?>
                                            <?php if(!$question->answer): ?>
                                                <button 
                                                    class="btn btn-sm btn-outline-primary answer-btn"
                                                    data-question-id="<?php echo e($question->id); ?>"
                                                    data-question-text="<?php echo e($question->question); ?>">
                                                    <?php echo e(__('messages.answer')); ?>

                                                </button>
                                            <?php endif; ?>
                                        <?php endif; ?>

                                    </div>

                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


                            
                            <div class="quikctech-pagination mt-4">
                                <?php echo e($questions->links()); ?>

                            </div>

                        </div>

                    <?php else: ?>
                        <div class="text-center mt-4">
                            <p><?php echo e(__('messages.no_questions_yet')); ?></p>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
</section>



<?php if(auth()->guard('vendor')->check()): ?>
<div class="modal fade" id="answerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title"><?php echo e(__('messages.answer_question')); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="answerForm" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <div class="modal-body">
                    <label class="form-label"><?php echo e(__('messages.question')); ?>:</label>
                    <p id="questionText" class="form-control-plaintext"></p>

                    <label for="answer" class="form-label mt-2"><?php echo e(__('messages.your_answer')); ?>:</label>
                    <textarea name="answer" id="answer" class="form-control"
                              rows="4" required minlength="5" maxlength="1000"></textarea>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo e(__('messages.cancel')); ?></button>
                    <button type="submit" class="btn btn-primary"><?php echo e(__('messages.submit_answer')); ?></button>
                </div>

            </form>
        </div>
    </div>
</div>
<?php endif; ?>


<?php $__env->startPush('scripts'); ?>
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
<?php $__env->stopPush(); ?>


<?php $__env->startPush('styles'); ?>
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
<?php $__env->stopPush(); ?><?php /**PATH E:\new-project-quick-tech\decroom-prod\resources\views/frontend/include/askquestion.blade.php ENDPATH**/ ?>