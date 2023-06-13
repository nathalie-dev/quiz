<?php
$natquiz = new natquiz();

$response['error'] = false;
$response['message'] = null;
$mode = "reponse";

if(!is_numeric($theme_id)) {
        $response['error'] = true;  
        $response['message'] = 'L\'ID du theme n\'existe pas !';  
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
<body>

<div id="natquiz"> 
        <h1>
            <span>Quizz</span>
            mettre le nom du thème ici
        </h1>
<div class="global-container">
       
        <form action="" class="quiz-form">
                
        <button type="submit">VALIDER ✔️</button>
        </form>

        <div class="results">
                <h2>Cliquez sur <span>valider</span> pour voir les <span>résultats.</span></h2>
                <p class="mark"></p>
                <p class="help"></p>
        </div>

</div>
</div>
</body>

<?php


// recupere les questions
$questions = $natquiz->get_all_questions($theme_id);
//print_r($questions);

 echo 'Question du theme '.$natquiz->get_name_theme($theme_id)."\n"; 
foreach ($questions as $question) {
        if ($question->active == 1) {                
        /* boucle question on */         
        echo '<div class="question-block">'."\n";
        echo  '<h4>'.stripslashes($question->question).'</h4>'."\n"; 


        //recupere les reponses
        $reponses = $natquiz->get_all_reponses($question->id_questions);
        foreach($reponses as $reponse) {
                $rep_question = explode("\n",stripslashes($reponse->mauvaise_reponses));
                $i = 0;
                foreach($rep_question as $rep) {
                        $i++;
                        echo '<div><input type="radio" id="rep'.$i.'" name="q'.$question->id_questions.'" value="'.$rep.'"/> <label for="rep'.$i.'">'.$rep.'</label> </div>'."\n";
                }
        }
        echo '</div>'."\n";
        }
        }
        echo '</div>'."\n";
        /* boucle question off */
}
 ?>
                <div class="question-block">
                        <h4>question 1</h4> 
                
                        <div>
                                <input type="radio" id="rep1" name="q1" value="a" />
                                <label for="rep1">rep1</label>
                        </div>
                        <div>
                                <input type="radio" id="rep2" name="q1" value="b" />       
                                <label for="rep2">rep2</label>
                        </div>
                        <div>
                                <input type="radio" id="rep3" name="q1" value="c" /> 
                                <label for="rep3">rep3</label>
                        </div>
                </div>
