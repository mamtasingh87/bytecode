<?php
$answer_given = FALSE;
$answered_today = FALSE;
$questionModel = $this->load->model('trivia/trivia_questions_model');
$UserAnsModel = $this->load->model('trivia/trivia_ques_user_model');
$question_detail = $questionModel->get_unanswered_questions($this->secure->get_user_session()->id);
if (isset($question_detail['qid'])){
    $answer_given = $UserAnsModel->check_for_user_attempt($this->secure->get_user_session()->id, $question_detail['qid']);
}
$answered_today = $questionModel->check_answered_today($this->secure->get_user_session()->id);
?>
<div id="left_column" class="col-left questions-box">
    <div class="block-title" id="block_header"><h3>Trivia for the Day</h3></div>
    <div class="block-cont trivia-in">
    <?php echo $this->session->flashdata('question_message'); ?>
    <?php echo $this->session->flashdata('question_error'); ?>
    <?php if (!empty($question_detail) && !($answer_given) && !($answered_today)): ?>
        <?php echo form_open(site_url('trivia/questionfront/questionday')); ?>
        <?php
        $options = $question_detail['options'];
        unset($question_detail['options']);
        ?>       
        <div>
            <h4><span><?php echo $question_detail['question'] ?></span></h4>
            <?php echo form_hidden('q_id', $question_detail['qid']) ?>
            <div>            
                <?php foreach ($options as $optionValues): ?>
                    <div class="control-group form-group">
                        <?php echo form_radio(array('name' => 'user_ans', 'value' => $optionValues['id'], 'checked' => set_radio('user_and', FALSE))); ?>
                        <?php echo form_label('<span>' . $optionValues['option_name'] . '</span>') ?>                    
                    </div>
                <?php endforeach; ?>
                <?php echo form_error('user_ans'); ?>
            </div>
            <div class="button-set">
                <input class="submit btn btn-primary" type="submit" value="Answer" />
            </div>
        </div>
        <?php echo form_close(); ?>
    <?php else: ?>
    <div id="timer_question_left" class="hidden">
                <div data-animated="FadeIn" id="timer" class="animated FadeIn">
                <div class="timer_box" id="hours"><h1>0</h1><p>Hour</p></div>
                <div class="timer_box" id="minutes"><h1>0</h1><p>Minute</p></div>
                <div class="timer_box" id="seconds"><h1>0</h1><p>Second</p></div>
            </div>
        </div>
    <?php endif; ?>
    </div>
</div>

<script type="text/javascript">
    function Timer() {
            var curObj = $("#timer_question_left");
            if(curObj.length){
                $("#timer_question_left").removeClass("hidden");
                $("#block_header h3").html('Time Remaining For Next Trivia Question');
                var dt = new Date();
                var hours = dt.getHours();
                var minutes = dt.getMinutes();
                var seconds = dt.getSeconds();
                $("#hours h1").html(23 - hours);
                $("#minutes h1").html(59 - minutes);
                $("#seconds h1").html(59 - seconds);
                setTimeout("Timer()", 1000);
          }
    }
    Timer();
</script>