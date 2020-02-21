<?php

//Fonction qui lit une archive
function get_content($readfile) {
    $readfile = file_get_contents($readfile);
    return $readfile;
}

/* Fonction qui crée un tableau à partir de l'archive avec comme clé le nom d'un fichier ou d'un dossier
et comme valeur le contenu du fichier correspondant ou "" si le dossier correspondant est vide */
function create_array(&$array){
    global $argv;
    $array_tmp = explode ("¤¤¤", get_content($argv[1]));
    foreach ($array_tmp as $key => $value) {
        if ($key % 2 == 0) {
            $key_array = $value;
        } else {
            $array[$key_array] = $value;
        }
    }
}

/*Fonction qui :
- compte le nombre d'éléments à recréer
-vérifie s'il s'agit d'un fichier ou d'un dossier
-fait une boucle "for" pour répéter la boucle tant qu'il reste des éléments à recréer */
function create_file(&$array) {
    $save = 0;
    foreach ($array as $key => $value) {
        if ($value != "") {
            $file = fopen("$key", "r+");
            fwrite($file, $value);
            fclose($file);
            if (file_exists($key)) {
                $save = prompt_file($key, $save);
            }
        } else {
            if (file_exists($key)) {
                $save = prompt_dir($key, $save);
            } else {
                mkdir($key);
            }
        }
    }
}

//Prompt proposé en cas de conflits pour les fichiers
function prompt_file($key, $save) {
    if ($save == 0) {
        if (file_exists($key)) {
            echo "\nFichier $key\n";
            echo "\n(tapez le chiffre suivi de la touche \"Entrée\")\n\n";
            echo "1. Ecraser\n";
            echo "2. Ne pas écraser\n";
            echo "3. Ecraser pour tous (ne plus redemander)\n";
            echo "4. Ne pas écraser pour tous (ne plus redemander)\n";
            echo "5. Arrêter et quitter\n";
        }
        $stdin = fopen("php://stdin", "r");
        $text = fgets($stdin);
    } else {
        $text = $save;
    }
    switch ($text) {
        case 1:
            fopen("$key", "w+");
            break;
        case 2:
            break;
        case 3:
            fopen("$key", "w+");
            return 3;
            break;
        case 4:
            return 4;
            break;
        case 5:
            exit;
            break;
    }
}

//Prompt proposé en cas de conflits pour les dossiers
function prompt_dir($key, $save) {
    if ($save == 0) {
        if (file_exists($key)) {
            echo "\nDossier $key\n";
            echo "\n(tapez le chiffre suivi de la touche \"Entrée\")\n\n";
            echo "1. Ecraser\n";
            echo "2. Ne pas écraser\n";
            echo "3. Ecraser pour tous (ne plus redemander)\n";
            echo "4. Ne pas écraser pour tous (ne plus redemander)\n";
            echo "5. Arrêter et quitter\n";
        }
        $stdin = fopen("php://stdin", "r");
        $text = fgets($stdin);
    } else {
        $text = $save;
    }
    switch ($text) {
        case 1:
            mkdir($key);
            break;
        case 2:
            break;
        case 3:
            mkdir($key);
            return 3;
            break;
        case 4:
            return 4;
            break;
        case 5:
            exit;
            break;
    }
}

//Fonction finale exécutant toutes les autres
function my_untar(&$array){
    create_array($array);
    create_file($array);
}

$array = [];
my_untar($array);
?>