<?php
/**
 * Controller base com métodos auxiliares compartilhados
 */
abstract class Controller
{
    /**
     * Renderiza uma view dentro do layout padrão
     */
    protected function renderizar(string $view, array $dados = [], string $layout = 'main'): void
    {
        // Disponibiliza dados como variáveis na view
        extract($dados);

        $viewPath = VIEW_PATH . '/' . $view . '.php';
        $layoutPath = VIEW_PATH . '/layouts/' . $layout . '.php';

        if (!file_exists($viewPath)) {
            http_response_code(404);
            die('View não encontrada: ' . htmlspecialchars($view));
        }

        // Captura o conteúdo da view
        ob_start();
        require $viewPath;
        $conteudo = ob_get_clean();

        // Renderiza o layout com o conteúdo
        require $layoutPath;
    }

    /**
     * Retorna JSON (para endpoints de API)
     */
    protected function json(mixed $dados, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($dados, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }

    /**
     * Redireciona para uma URL
     */
    protected function redirecionar(string $url): void
    {
        header('Location: ' . BASE_URL . $url);
        exit;
    }

    /**
     * Verifica se usuário está autenticado
     */
    protected function autenticado(): bool
    {
        return isset($_SESSION['usuario_id']);
    }

    /**
     * Exige autenticação ou redireciona para login
     */
    protected function exigeLogin(): void
    {
        if (!$this->autenticado()) {
            $this->redirecionar('/auth/login');
        }
    }

    /**
     * Sanitiza entrada do usuário
     */
    protected function sanitizar(string $valor): string
    {
        return htmlspecialchars(strip_tags(trim($valor)), ENT_QUOTES, 'UTF-8');
    }

    /**
     * Verifica se requisição é POST
     */
    protected function isPost(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    /**
     * Obtém e sanitiza campo POST
     */
    protected function post(string $campo, string $padrao = ''): string
    {
        return isset($_POST[$campo]) ? $this->sanitizar($_POST[$campo]) : $padrao;
    }
}
