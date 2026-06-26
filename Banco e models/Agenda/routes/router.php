<?php
/**
 * Router - Mapeia URLs para controllers/actions
 * Padrão Front Controller do MVC
 */

function rotear(string $uri): void
{
    // Remove query string e base path
    $uri = strtok($uri, '?');
    $uri = '/' . trim(str_replace('/agenda/public', '', $uri), '/');
    if ($uri === '') $uri = '/';

    $metodo = $_SERVER['REQUEST_METHOD'];

    // ==========================================
    // Rotas de autenticação
    // ==========================================
    if ($uri === '/' || $uri === '/auth/login') {
        if ($metodo === 'POST') {
            (new AuthController())->autenticar();
        } else {
            (new AuthController())->login();
        }
        return;
    }

    if ($uri === '/auth/cadastro') {
        if ($metodo === 'POST') {
            (new AuthController())->registrar();
        } else {
            (new AuthController())->cadastro();
        }
        return;
    }

    if ($uri === '/auth/logout') {
        (new AuthController())->logout();
        return;
    }

    // ==========================================
    // Dashboard
    // ==========================================
    if ($uri === '/dashboard') {
        (new DashboardController())->index();
        return;
    }

    // ==========================================
    // CRUD de Compromissos
    // ==========================================
    if ($uri === '/compromissos') {
        (new CompromissoController())->index();
        return;
    }

    if ($uri === '/compromissos/novo') {
        (new CompromissoController())->novo();
        return;
    }

    if ($uri === '/compromissos/salvar' && $metodo === 'POST') {
        (new CompromissoController())->salvar();
        return;
    }

    if (preg_match('#^/compromissos/editar/(\d+)$#', $uri, $m)) {
        (new CompromissoController())->editar((int)$m[1]);
        return;
    }

    if (preg_match('#^/compromissos/atualizar/(\d+)$#', $uri, $m) && $metodo === 'POST') {
        (new CompromissoController())->atualizar((int)$m[1]);
        return;
    }

    if (preg_match('#^/compromissos/deletar/(\d+)$#', $uri, $m) && $metodo === 'POST') {
        (new CompromissoController())->deletar((int)$m[1]);
        return;
    }

    // ==========================================
    // REST API (JSON)
    // ==========================================
    if ($uri === '/api/compromissos') {
        $api = new ApiController();
        match($metodo) {
            'GET'  => $api->listar(),
            'POST' => $api->criar(),
            default => http_response_code(405),
        };
        return;
    }

    if (preg_match('#^/api/compromissos/(\d+)$#', $uri, $m)) {
        $api = new ApiController();
        match($metodo) {
            'GET'    => $api->detalhe((int)$m[1]),
            default  => http_response_code(405),
        };
        return;
    }

    if ($uri === '/api/stats') {
        (new ApiController())->stats();
        return;
    }

    // ==========================================
    // 404 Not Found
    // ==========================================
    http_response_code(404);
    echo '<h1>404 - Página não encontrada</h1>';
    echo '<a href="' . BASE_URL . '/dashboard">Voltar ao início</a>';
}
