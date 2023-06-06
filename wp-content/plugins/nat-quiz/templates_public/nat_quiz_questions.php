<?php
$natquiz = new natquiz();

// recupere les questions
$questions = $natquiz->get_all_questions();
//print_r($questions);

foreach ($questions as $question) {
?>                      
    <?php echo $natquiz->get_name_theme($question->theme_associer); ?>
    </br>                       
    <?php echo  $question->question; ?>
    </br>
    <?php if ($question->active == 0) {
            echo "$question->theme_associer </br>" ; 
            echo "$question->question </br>"; 
            } 
    ?>  
                            
    <?php } ?>

