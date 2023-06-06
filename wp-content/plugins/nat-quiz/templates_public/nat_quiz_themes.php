
<div id="natquiz">
<p>Si vous êtes des fans de quiz en tout genre, ce site de quizz gratuit est fait pour vous.</p> 
<p>Incollable, indétrônable, vos amis redoutent de vous affronter lors des soirées quiz.
Histoire, actualités, people, télé, cinéma, sports, musique, culture générale, etc... les
domaines sont variés et les questions nombreuses.</p> 
<p>Découvrez nos questions et entraînez-vous gratuitement à répondre à tous nos quizz
gratuits.</p> 

<?php

$natquiz = new natquiz();
// recupere les themes
$themes = $natquiz->get_all_themes();
//print_r($themes);
?>

<div class="all_themes">
    <?php
        foreach ($themes as $theme) 
            if ($theme->active == 1) {
    ?>
        <div class="theme">                       
            <?php echo $theme->nom; ?>
            </br>
            <img src="/quiz/wp-content/plugins/nat-quiz/image/<?=$theme->image?>" alt="négatif de photo" >
            </br>
            <?php echo $theme->descriptif; ?>
            </br>
            <a href="//localhost/quiz/questions-quiz/<?=$theme->nom?>">Consulter les questions du thème.</a>
        </div>  
    <?php }  ?>           
</div>

<div class="container">
    <?php
        foreach ($themes as $theme) 
            if ($theme->active == 1) {
    ?>
        <div class="contenu">                       
            <?php echo $theme->nom; ?>
            </br>
            <img src="/quiz/wp-content/plugins/nat-quiz/image/<?=$theme->image?>" alt="négatif de photo">
            </br>
            <?php echo $theme->descriptif; ?>
            </br>
            <a href="//localhost/quiz/questions-quiz/<?=$theme->nom?>">Consulter les questions du thème.</a>
        </div>  
    <?php }  ?>           
</div>

<body>

<!-- <div class="themes">
    <div class="theme1">
       <h2>Cinéma</h2> 
       <img src="quiz/wp-content/plugins/nat-quiz/image/cinema.webp" alt="négatif de photo">
       <p>Vous êtes nombreux à adorer le cinéma, pourtant peu de personnes peuvent se vanter d’être de vrais cinéphiles…</p>
       <a href="<?= admin_url() ?>admin.php?page=nat-quiz-questions-cinema">Consulter les questions du thème Cinéma.</a>
    </div>

    <div class="theme2">
        <h2>Cuisine</h2>
    <p>Vous aimez recevoir chez vous, vos amis vous considèrent comme un vrai cordon-bleu ? Mais qu’en est-il de vos connaissances en matière de cuisine ?</p>
    <a href="<?= admin_url() ?>admin.php?page=nat-quiz-questions-cuisine">Consulter les questions du thème Cuisine.</a>
    </div>

    <div class="theme3">
        <h2>Culture générale</h2>
        <p>Quel est le niveau de votre culture générale ?</p>
        <a href="<?= admin_url() ?>admin.php?page=nat-quiz-questions-culture-generale">Consulter les questions du thème Culture générale.</a>
    </div>

    <div class="theme4">
        <h2>Musique</h2>
        <p>La musique est indéniablement présente dans notre vie, à la TV, sur internet,  à la radio… Testez vos connaissances et vérifiez si vous êtes l’as de la musique.</p>
        <a href="<?= admin_url() ?>admin.php?page=nat-quiz-questions-musique">Consulter les questions du thème Musique.</a>
    </div>

    <div class="theme5">
        <h2>Sport</h2>
        <p>Sportif du dimanche ou athlète accompli, que vous pratiquiez assidûment une activité sportive ou ne manquiez aucun évènement sportif à la télévision, nos quizz Sport sont faits pour vous !</p>
        <a href="<?= admin_url() ?>admin.php?page=nat-quiz-questions-sport">Consulter les questions du thème Sport.</a>
    </div>

    <div class="theme6">
        <h2>Vacance</h2>
        <p>Les destinations de vacances des Français, le barbecue, les glaces, la crème solaire, les autoroutes, etc...</p>
        <a href="<?= admin_url() ?>admin.php?page=nat-quiz-questions-vacance">Consulter les questions du thème Vacance.</a>
    </div> 
</div> -->

</div>

</body>
</html>   


