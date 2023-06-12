
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

<div class="container">
    <?php
        foreach ($themes as $theme) 
            if ($theme->active == 1) {
    ?>
        <div class="contenu">                       
            <?php echo $theme->nom; ?>
            </br>
            <a href="<?=admin_url()?>/themes/<?=$theme->id_themes?>/"><img src=../wp-content/uploads/natquizfiles/<?php echo $theme->image; ?> alt="Photo correspondant au thème"></a>
            </br>
            <?php echo $theme->descriptif; ?>
            </br>
            <a href="<?=admin_url()?>/themes//themes/<?=$theme->id_themes?>/">Consulter les questions du thème.</a>
        </div>  
    <?php }  ?>           
</div>

<body>

</div>

</body>
</html>   


