<!--include ok -->
<?php
$natquiz = new natquiz();

// recupere les themes
$themes = $natquiz->get_all_themes();
print_r($themes);
?>
 <style>
.themes {
background-color: aqua;
border: 4px solid black;
margin: 10px;
padding: 10px;
}
</style>

<body>

<div class="themes">
<h2>Cinéma</h2>
<p>Vous êtes nombreux à adorer le cinéma, pourtant peu de personnes peuvent se vanter d’être de vrais cinéphiles…</p>
</div>

<div class="themes">
<h2>Cuisine</h2>
<p>Vous aimez recevoir chez vous, vos amis vous considèrent comme un vrai cordon-bleu ? Mais qu’en est-il de vos connaissances en matière de cuisine ?</p>
</div>

<div class="themes">
<h2>Musique</h2>
<p>La musique est indéniablement présente dans notre vie, à la TV, sur internet,  à la radio… Testez vos connaissances et vérifiez si vous êtes l’as de la musique.</p>
</div>

<div class="themes">
<h2>Sport</h2>
<p>Sportif du dimanche ou athlète accompli, que vous pratiquiez assidûment une activité sportive ou ne manquiez aucun évènement sportif à la télévision, nos quizz Sport sont faits pour vous !</p>
</div>

<div class="themes">
<h2>Vacance</h2>
<p>Les destinations de vacances des Français, le barbecue, les glaces, la crème solaire, les autoroutes, etc...</p>
</div>

</body>
</html>   


