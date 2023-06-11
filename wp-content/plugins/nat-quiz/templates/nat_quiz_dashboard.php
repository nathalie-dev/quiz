<?php

echo '<h1>Tableau de bord Quiz</h1>';
echo '<p>Bienvenue dans le tableau de bord Quiz ! (version include)</p>';

// récuperation de la variable mode dans l'url
if (isset(($_GET['mode']))) {
    $mode = $_GET['mode'];
}

// récuperation de la variable action dans l'url
if (isset(($_GET['type']))) {
    $type = $_GET['type'];
}


if((isset($mode) && $mode=="error") && (isset($type) && $type=="dir")) {
    echo '<div class="error">Erreur ! Le répertoire de stockage des images n\'a pas été créé, le module ne pourra pas fonctionner correctement !</div>';
}