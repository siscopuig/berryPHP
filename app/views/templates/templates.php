<?php
$feedback_negative = Session::get('feedback_negative');
$feedback_positive = Session::get('feedback_positive');

if (isset($feedback_negative)) {
    echo '<div class="feedback-negative">' . $feedback_negative . '</div>';
}

if (isset($feedback_positive)) {
    echo '<div class="feedback-positive">' . $feedback_positive . '</div>';
}



