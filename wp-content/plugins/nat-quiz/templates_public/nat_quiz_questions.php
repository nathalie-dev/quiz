<?php
$natquiz = new natquiz();

$response['error'] = false;
$response['message'] = null;
$id_question = null;
$mode = "reponse";

if(!is_numeric($theme_id)) {
        $response['error'] = true;  
        $response['message'] = 'L\'ID du theme n\'existe pas !';  
}


//fonction pour valider le quiz
function nat_quiz_validate_quiz()
{
        $natquiz = new natquiz();

        $final['error'] = false;
        $final['message'] = null;

        foreach($_POST as $key => $value) {
                list($caca,$id_question) = explode('_',$key);   
                if($value==$natquiz->verif_good_response($id_question)) {
                        // la reponse est bonne
                       
                } else {
                        // la reponse est fausse
                        
                }

        }
        return $final;
}   

if(isset($_POST['valid_quiz'])) {
        $response = nat_quiz_validate_quiz();
}

// gestion des messages de retour
if ($response['error']) {
        echo '<div class="error">' . $response['message'] . '</div>';
} else {
        if ($response['message'] != '') {
                echo '<div class="notice notice-success">' . $response['message'] . '</div>';
        }
}

if(!$response['error']) {
?>

<div id="natquiz"> 
        <h1>
            <span>Quizz</span></br>
        <?php  echo 'Question du thème '.$natquiz->get_name_theme($theme_id)."\n"; ?>
        </h1>

 <pre>
<?php print_r($_POST) ?> 
</pre> 

<form action="" class="quiz-form" method="POST">
<?php


// recupere les questions
$questions = $natquiz->get_all_questions($theme_id);
shuffle($questions);
//print_r($questions);

 //echo 'Question du theme '.$natquiz->get_name_theme($theme_id)."\n"; 
foreach ($questions as $question) {
        if ($question->active == 1) {                
        /* boucle question on */         
        echo '<div class="question-block">'."\n";
        echo  '<h4>'.stripslashes($question->question).'</h4>'."\n"; 


        //recupere les reponses
        $reponses = $natquiz->get_all_reponses($question->id_questions);
        foreach($reponses as $reponse) {
                $reponse->mauvaise_reponses .= "\n".$reponse->bonne_reponse;
                $rep_question = explode("\n",stripslashes($reponse->mauvaise_reponses));
                shuffle($rep_question);
                $i = 0;
                foreach($rep_question as $rep) {
                        if($rep!="") {
                                $i++;
                                echo '<div><input type="radio" class="required" id="rep_'.$i.'_'.$question->id_questions.'" name="q_'.$question->id_questions.'" value="'.$rep.'" /> <label for="rep_'.$i.'_'.$question->id_questions.'">'.$rep.'</label> </div>'."\n";
                        }
                }

        }
        echo '</div>'."\n";
        }
        }
      
        /* boucle question off */
}
 ?>
  <button type="submit" name="valid_quiz">VALIDER ✔️</button>
       

       <div class="results">
               <h2>Cliquez sur <span>valider</span> pour voir les <span>résultats.</span></h2>
               <p class="mark"></p>
               <p class="help"></p>
       </div>

</form>
</div>




