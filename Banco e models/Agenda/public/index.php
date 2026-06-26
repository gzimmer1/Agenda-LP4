<?php
/**
 * Ponto de entrada da aplicação (Front Controller)
 * Toda requisição passa por aqui via .htaccess
 */

// Inicializa sessão
session_start();

// Carrega configurações
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/database.php';

// Autoload simples de classes (Models e Controllers)
spl_autoload_register(function (string $classe): void {
    $caminhos = [
        APP_PATH . '/models/' . $classe . '.php',
        APP_PATH . '/controllers/' . $classe . '.php',
    ];
    foreach ($caminhos as $caminho) {
        if (file_exists($caminho)) {
            require_once $caminho;
            return;
        }
    }
});

// Carrega o roteador e despacha a requisição
require_once ROOT_PATH . '/routes/router.php';
rotear($_SERVER['REQUEST_URI']);
