<?php
$natquiz = new natquiz();

// recupere les reponses
$reponses = $natquiz->get_all_reponses();
//print_r($reponses);

foreach ($reponses as $reponse) {
?>
                        
<?php echo $natquiz->get_name_theme($reponse->theme_associer); ?>
</br>                            
<?php echo $natquiz->get_name_question($reponse->theme_associer,$reponse->question_associer); ?>
</br>                           
<?php echo $reponse->answers; ?>
</br>                                                       
<?php echo $reponse->score; ?>
</br>                           
<?php if ($reponse->active == 0) {
    echo "$reponse->id_reponses"; 
}
?>
<?php } ?>



