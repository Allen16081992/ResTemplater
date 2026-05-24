<?php
    // 1. DEFINED PATHS: The "Map"
    define('APP_ROOT', dirname(__DIR__)); 
    define('CONTROLLER_PATH', APP_ROOT . '/config/controller');
    define('CLASS_PATH', APP_ROOT . '/config/classes');
    define('DB_PATH', APP_ROOT . '/config/database');

    // 2. THE AGGRESSIVE AUTOLOADER: Uses the "Map" to find the file
    spl_autoload_register(function ($class) {
        // 1. Determine the category based on the class name
        if (str_contains($class, 'Control')) { $file = CONTROLLER_PATH . '/' . $class . '.php'; } 
        elseif (str_contains($class, 'Codex')) { $file = CLASS_PATH . '/' . $class . '.php'; } 
        else { $file = DB_PATH . '/' . $class . '.php'; }

        //echo "Searching for: " . $file . "<br>"; 
        
        // 2. Only check the relevant file
        if (file_exists($file)) {
            require_once $file;
        }
    });