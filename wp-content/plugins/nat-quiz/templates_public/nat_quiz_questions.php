<?php
$natquiz = new natquiz();

$response['error'] = false;
$response['message'] = null;
$mode = "reponse";

if (!is_numeric($theme_id)) {
        $response['error'] = true;
        $response['message'] = 'L\'ID du theme n\'existe pas !';
}


//fonction pour valider le quiz
function nat_quiz_validate_quiz()
{
        global $theme_id;
        $natquiz = new natquiz();

        global $wpdb;
        $final['error'] = false;
        $final['message'] = null;
        $final['quiz'] = array();
        $table_name = $wpdb->prefix . 'nat_quiz_reponses';

        foreach ($_POST as $key => $value) {
                $tmp = array();
                list($caca, $id_question) = explode('_', $key);
                $tmp['question'] = stripslashes($natquiz->get_name_question($theme_id,$id_question));
                $tmp['id_question'] = $id_question;
                $tmp['all_response'] = $natquiz->get_all_reponses($id_question);
                $tmp['response'] = $value;
                if ($value == $natquiz->verif_good_response($id_question)) {
                        $tmp['statut'] = '1';
                        $tmp['point'] = '1';
                } else {
                        // la reponse est fausse
                        $tmp['statut'] = '0';
                        $tmp['point'] = '0';
                }
                if($tmp['question']!='') { $final['quiz'][] = $tmp; }
        }
        return $final;
}

function calcul_pourcentage($total,$response)
{ 
  $resultat = ($total/$response) * 100;
  return round($resultat); // Arrondi la valeur
} 

if (isset($_POST['valid_quiz'])) {
        $response = nat_quiz_validate_quiz();
}

echo '<div id="natquiz">';
// gestion des messages de retour
if ($response['error']) {
        echo '<div class="error">' . $response['message'] . '</div>';
} else {
        if ($response['message'] != '') {
                echo '<div class="notice notice-success">' . $response['message'] . '</div>';
        }
}




/* afficher tableau reponses (debug) */
//print_r($response['quiz']);
?>
<div class = "reponse">
<?php
if(isset($response['quiz'])) {

        $soumiss_quiz = '<ul>';
        $total = 0;
        foreach($response['quiz'] as $soum_reponse) {
                //echo 'la question : '.$soum_reponse['question']."<br>";
                //echo 'l id de la question : '.$soum_reponse['id_question']."<br>";
                $ret = 'Pour la question : <strong>'.$soum_reponse ['question'].'</strong> <br> Vous aviez le choix parmi les réponses suivantes : '.str_replace("\n",", ",stripslashes($soum_reponse['all_response'][0]->mauvaise_reponses).", ".stripslashes($soum_reponse['all_response'][0]->bonne_reponse)).". <br> Vous avez choisi : <strong>".stripslashes($soum_reponse['response'])."</strong>. <br>";
                if($soum_reponse['statut']=='0') { $ret .= '<span class="bad_response">Malheureusement, ce n\'était pas la bonne reponse !</span>'; } else { $ret .= '<span class="good_response">Bien joué, c\'est la bonne reponse !</span>'; $total++; }
                $ret .= '<br>';
                $soumiss_quiz .= '<li>'.$ret.'</li>';
        }
        $soumiss_quiz .= '</ul>'; 

        $nombre_question = count($response['quiz']);
        $pcent_reusite = calcul_pourcentage($total,$nombre_question);
       
        $soumiss_result = '<div class = "resultat">';
        $soumiss_result .= '<br> Vous avez <strong>'.$total.'</strong> bonnes réponses sur <strong>'.$nombre_question.'</strong> ! ';
        $soumiss_result .= '<br> Vous avez <strong>'.$pcent_reusite. '</strong> % ! ';
        switch ($pcent_reusite) {
                case ($pcent_reusite >= 90 && $pcent_reusite <= 100):
                        $soumiss_result .= "Excellent travail, tu es un expert ! 🏆 👏";
                break;
                case ($pcent_reusite >= 80 && $pcent_reusite < 90):
                        $soumiss_result .= "Bien, ça commence à faire beaucoup de bonnes réponses. 🏅 💪";
                break;
                case ($pcent_reusite >= 70 && $pcent_reusite < 80):
                        $soumiss_result .= "Bien joué ! 🥈 ";
                break;
                case ($pcent_reusite >= 60 && $pcent_reusite < 70):
                        $soumiss_result .= "Pas mal ! 😎";
                break;
                case ($pcent_reusite >= 50 && $pcent_reusite < 60):
                        $soumiss_result .= "C'est tout juste la moyenne ! 👍";
                        break;
                case ($pcent_reusite >= 40 && $pcent_reusite < 50):
                        $soumiss_result .= "Je sais que tu peux mieux faire ! 🙂";
                        break;
                case ($pcent_reusite >= 30 && $pcent_reusite < 40):
                        $soumiss_result .= "Lâche pas, tu vas y arriver ! 🙂";
                        break;
                case ($pcent_reusite >= 20 && $pcent_reusite < 30):
                        $soumiss_result .= "Allez, encore un effort ! 😕";
                        break;
                case ($pcent_reusite >= 10 && $pcent_reusite < 20):
                        $soumiss_result .= "Ce n'est pas terrible tout ça ! 👎";
                        break;
                case ($pcent_reusite >= 0 && $pcent_reusite < 10):
                        $soumiss_result .= "Tu n'as plus qu'a revoir tes classiques et recommencer le quiz ! ☹️";
                        break;
            default:
            $soumiss_result .= "Je n'ai pas de réponse à ce score.";
                break;
        } 
             
        if (is_user_logged_in()) {
                // L'utilisateur est connecté
                $user_id = get_current_user_id();
                // on enregistre dans la base la reponse au quiz
                $score = $natquiz->add_score_by_user($user_id,$soumiss_quiz,$soumiss_result,$theme_id,$pcent_reusite);
        
        } else {
                // L'utilisateur n'est pas connecté
                echo 'Vous n\'etes pas identifié, <a href="">identifiez vous</a> ici ou <a href="">créer votre compte</a>';
        }
             


         // on affiche les reponses 
         echo  $soumiss_result;
         echo $soumiss_quiz;
         
             ?>

             
             <br>
             <a class="" href="<?=admin_url()?>/themes/">Retour à la liste des thèmes</a> <br> <a class="" href="">Recommencer le Quiz</a>
        </div>
        
<?php } ?>
</div>
<?php
/* end debug */

if (!isset($_POST['valid_quiz'])) {
        print_r($_POST);
?>

        <h1>
                <span>Quizz</span></br>
                <?php echo 'Question du thème ' . $natquiz->get_name_theme($theme_id) . "\n"; ?>
        </h1>

        <pre>

</pre>

        <form id="myForm" action="" class="quiz-form" method="POST">
                <?php


                // recupere les questions
                $questions = $natquiz->get_all_questions($theme_id);
                shuffle($questions);
                //print_r($questions);

                //echo 'Question du theme '.$natquiz->get_name_theme($theme_id)."\n"; 
                foreach ($questions as $question) {
                        if ($question->active == 1) {
                                /* boucle question on */
                                echo '<div class="question-block">' . "\n";
                                 
                                echo  '<h4>' . stripslashes($question->question) . '</h4>' . "\n";


                                //recupere les reponses
                                $reponses = $natquiz->get_all_reponses($question->id_questions);
                                foreach ($reponses as $reponse) {
                                        $reponse->mauvaise_reponses .= "\n" . $reponse->bonne_reponse;
                                        $rep_question = explode("\n", stripslashes($reponse->mauvaise_reponses));
                                        shuffle($rep_question);
                                        $i = 0;
                                        foreach ($rep_question as $rep) {
                                                if ($rep != "") {
                                                        
                                                        $i++;
                                                        echo '<div><input type="radio" class="ok_verif" required="true" id="rep_' . $i . '_' . $question->id_questions . '" name="q_' . $question->id_questions . '" value="' . $rep . '" /> <label for="rep_' . $i . '_' . $question->id_questions . '">' . $rep . '</label> </div>' . "\n";
                                                }
                                        }
                                }
                                echo '</div>' . "\n";
                        }
                }

                /* boucle question off */
                ?>
                <button type="submit" name="valid_quiz" disabled="true">VALIDER ✔️</button>


                <div class="results">
                        <h2>Cliquez sur <span>valider</span> pour voir les <span>résultats.</span></h2>
                        <p class="mark"></p>
                        <p class="help"></p>
                </div>

        </form>
<?php } ?>
</div>

<script>
        jQuery(document).ready(function($) {
                var verif_input = true;

                // quand on clique sur un input radio 
                $(".ok_verif").click(function() {
                       
                        // boucle pour verifier les inputs cochés
                        $(".ok_verif").each(function(input, value) {
                                // si verif_input est egal à faux et qu'elle est cochée
                                if (!verif_input && $(this).is(':checked') == true) {
                                        // alors elle est definit en vrai
                                        verif_input = true;
                                }
                                // si le nbre d'input coché est egale a 0
                                if ($('.ok_verif:checked').length == 0) {
                                        // alors elle est definit en faux
                                        verif_input = false;
                                }
                        });
                        // si verif_input est vrai on active le bouton
                        if (verif_input) {
                                $('button[name="valid_quiz"]').attr('disabled', false);
                        } else {
                                // sinon on le desactive
                                $('button[name="valid_quiz"]').attr('disabled', true);
                        }
                });

        });
</script>