<?php
spl_autoload_register(function($className) {    #gemini : prompt(je veux charger les classes automatiquement mais je n'y arrive pas sans causé d'erreur) reponse(le code)
    $className = str_replace("\\", "/", $className);
    if (file_exists(__DIR__ . '/../' . $className . '.php')) {
        require_once __DIR__ . '/../' . $className . '.php';
    }
});