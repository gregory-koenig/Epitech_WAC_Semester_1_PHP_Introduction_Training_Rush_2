<?php

//Récupère le contenu des fichiers
function get_file($path) {
    $content = file_get_contents($path);
    return $content;
}

//Remplit le tableau avec le nom des fichiers en clé et le contenu en valeur
function fill_tab(&$array, $path) {
    $array[$path]=  get_file($path);
}

//Remplit le tableau avec les dossiers et sous-dossiers vides en clé et un contenu vide en valeur
function fill_tab_void(&$array, $path){
    $array[$path] = "";
}

//explore les dossiers et les fichiers passés en paramètres et exécute les fonctions ci-dessus
function explorer(&$array, $path) {
    if (is_file($path)) {
        fill_tab($array, $path);
    } elseif (is_dir($path)) {
        $folder = scandir($path);
        fill_tab_void($array, $path);
        foreach ($folder as $value) {
            if ($value != "." && $value != "..") {
                explorer($array, $path . "/" . $value);
            }
        }
    }
}

//écrit le nom du fichier et son contenu
function write_file($write, $open, $key, $value) {
    $write = fwrite($open, "\n¤¤¤\n");
	$write = fwrite($open, $key);
	$write = fwrite($open, "\n¤¤¤\n");
	$write = fwrite($open, $value);
	return $write;
}

//répète la fonction write_file pour chaque clé et chaque contenu
function loop_write($loop, $stock = "") {
    $open=fopen("output.mytar", "w+");
    foreach ($loop as $key => $value) {
        write_file($stock, $open, $key, $value);
    }
    if ($open!=false) {
        fclose($open);
    }
}

//fonction finale qui crée le fichier contenant les fichiers passés en paramètres
function my_tar(&$array){
    global $argc, $argv;
    for ($i = 1; $i < $argc; $i++) {
        explorer($array, $argv[$i]);
    }
    loop_write($array);
}

$array = [];
my_tar($array);

?>