
<div id="natquiz">
<p>Êtes-vous prêt à passer un bon moment avec nous ? </p> 
<p>Alors n’hésitez plus, choisissez un thème qui vous plaît et partez à la découverte de nos questions et surtout n’oubliez pas de valider pour afficher votre score ! </p> 
<p>Défier vos amis, votre famille, vos collègues et voir qui seront les meilleurs …</p> 
<p>Que le meilleur gagne ! </p>

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
            <span><?php echo $theme->nom; ?></pspan>
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


