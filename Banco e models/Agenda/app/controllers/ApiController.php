<?php
/**
 * ApiController - REST API que expõe dados em JSON para consumo externo
 * 
 * Endpoints:
 *   GET  /api/compromissos          - Lista compromissos
 *   GET  /api/compromissos/{id}     - Detalhe de um compromisso
 *   POST /api/compromissos          - Cria compromisso
 *   PUT  /api/compromissos/{id}     - Atualiza compromisso
 *   DELETE /api/compromissos/{id}   - Remove compromisso
 *   GET  /api/dashboard             - Estatísticas para gráficos
 */
class ApiController extends Controller
{
    private Compromisso $model;

    public function __construct()
    {
        $this->model = new Compromisso();
    }

    /**
     * Valida autenticação via sessão ou header X-API-Token (simplificado)
     */
    private function verificarAuth(): void
    {
        if (!$this->autenticado()) {
            $this->json(['erro' => 'Não autenticado. Faça login primeiro.'], 401);
        }
    }

    /**
     * GET /api/compromissos
     */
    public function listar(): void
    {
        $this->verificarAuth();

        $filtros = [
            'categoria' => $_GET['categoria'] ?? '',
            'status'    => $_GET['status'] ?? '',
            'busca'     => $_GET['busca'] ?? '',
        ];

        $compromissos = $this->model->listar($_SESSION['usuario_id'], $filtros);
        $this->json(['sucesso' => true, 'total' => count($compromissos), 'dados' => $compromissos]);
    }

    /**
     * GET /api/compromissos/{id}
     */
    public function detalhe(int $id): void
    {
        $this->verificarAuth();

        $compromisso = $this->model->buscarPorId($id, $_SESSION['usuario_id']);
        if (!$compromisso) {
            $this->json(['erro' => 'Compromisso não encontrado.'], 404);
        }

        $this->json(['sucesso' => true, 'dados' => $compromisso]);
    }

    /**
     * POST /api/compromissos
     */
    public function criar(): void
    {
        $this->verificarAuth();

        $body = json_decode(file_get_contents('php://input'), true) ?? [];

        $dados = [
            'usuario_id' => $_SESSION['usuario_id'],
            'titulo'     => htmlspecialchars(strip_tags($body['titulo'] ?? '')),
            'descricao'  => htmlspecialchars(strip_tags($body['descricao'] ?? '')),
            'data_hora'  => $body['data_hora'] ?? '',
            'local'      => htmlspecialchars(strip_tags($body['local'] ?? '')),
            'categoria'  => $body['categoria'] ?? 'outro',
            'status'     => 'pendente',
        ];

        if (empty($dados['titulo']) || empty($dados['data_hora'])) {
            $this->json(['erro' => 'Campos obrigatórios: titulo, data_hora.'], 422);
        }

        if ($this->model->criar($dados)) {
            $this->json(['sucesso' => true, 'mensagem' => 'Compromisso criado.'], 201);
        } else {
            $this->json(['erro' => 'Erro ao criar compromisso.'], 500);
        }
    }

    /**
     * GET /api/stats - Estatísticas para gráficos
     */
    public function stats(): void
    {
        $this->verificarAuth();
        $stats = $this->model->estatisticas($_SESSION['usuario_id']);
        $this->json(['sucesso' => true, 'dados' => $stats]);
    }
}
