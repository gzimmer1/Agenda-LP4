<?php
/**
 * CompromissoController - CRUD completo de compromissos
 */
class CompromissoController extends Controller
{
    private Compromisso $model;

    public function __construct()
    {
        $this->model = new Compromisso();
    }

    /**
     * GET /compromissos - Lista todos os compromissos
     */
    public function index(): void
    {
        $this->exigeLogin();

        $filtros = [
            'categoria' => $_GET['categoria'] ?? '',
            'status'    => $_GET['status'] ?? '',
            'busca'     => $_GET['busca'] ?? '',
        ];

        $compromissos = $this->model->listar($_SESSION['usuario_id'], $filtros);

        $this->renderizar('compromissos/index', [
            'titulo'       => 'Meus Compromissos',
            'compromissos' => $compromissos,
            'filtros'      => $filtros,
        ]);
    }

    /**
     * GET /compromissos/novo - Formulário de criação
     */
    public function novo(): void
    {
        $this->exigeLogin();
        $this->renderizar('compromissos/form', [
            'titulo'      => 'Novo Compromisso',
            'compromisso' => null,
            'acao'        => BASE_URL . '/compromissos/salvar',
        ]);
    }

    /**
     * POST /compromissos/salvar - Salva novo compromisso
     */
    public function salvar(): void
    {
        $this->exigeLogin();

        $dados = [
            'usuario_id' => $_SESSION['usuario_id'],
            'titulo'     => $this->post('titulo'),
            'descricao'  => $this->post('descricao'),
            'data_hora'  => $this->post('data_hora'),
            'local'      => $this->post('local'),
            'categoria'  => $this->post('categoria'),
            'status'     => 'pendente',
        ];

        if (empty($dados['titulo']) || empty($dados['data_hora'])) {
            $_SESSION['flash'] = ['tipo' => 'warning', 'msg' => 'Título e data são obrigatórios.'];
            $this->redirecionar('/compromissos/novo');
            return;
        }

        if ($this->model->criar($dados)) {
            $_SESSION['flash'] = ['tipo' => 'success', 'msg' => 'Compromisso criado com sucesso!'];
        } else {
            $_SESSION['flash'] = ['tipo' => 'danger', 'msg' => 'Erro ao criar compromisso.'];
        }

        $this->redirecionar('/compromissos');
    }

    /**
     * GET /compromissos/editar/{id} - Formulário de edição
     */
    public function editar(int $id): void
    {
        $this->exigeLogin();

        $compromisso = $this->model->buscarPorId($id, $_SESSION['usuario_id']);
        if (!$compromisso) {
            $_SESSION['flash'] = ['tipo' => 'danger', 'msg' => 'Compromisso não encontrado.'];
            $this->redirecionar('/compromissos');
            return;
        }

        $this->renderizar('compromissos/form', [
            'titulo'      => 'Editar Compromisso',
            'compromisso' => $compromisso,
            'acao'        => BASE_URL . '/compromissos/atualizar/' . $id,
        ]);
    }

    /**
     * POST /compromissos/atualizar/{id} - Atualiza compromisso
     */
    public function atualizar(int $id): void
    {
        $this->exigeLogin();

        $dados = [
            'titulo'    => $this->post('titulo'),
            'descricao' => $this->post('descricao'),
            'data_hora' => $this->post('data_hora'),
            'local'     => $this->post('local'),
            'categoria' => $this->post('categoria'),
            'status'    => $this->post('status'),
        ];

        if (empty($dados['titulo']) || empty($dados['data_hora'])) {
            $_SESSION['flash'] = ['tipo' => 'warning', 'msg' => 'Título e data são obrigatórios.'];
            $this->redirecionar('/compromissos/editar/' . $id);
            return;
        }

        if ($this->model->atualizar($id, $_SESSION['usuario_id'], $dados)) {
            $_SESSION['flash'] = ['tipo' => 'success', 'msg' => 'Compromisso atualizado!'];
        } else {
            $_SESSION['flash'] = ['tipo' => 'danger', 'msg' => 'Erro ao atualizar.'];
        }

        $this->redirecionar('/compromissos');
    }

    /**
     * POST /compromissos/deletar/{id} - Remove compromisso
     */
    public function deletar(int $id): void
    {
        $this->exigeLogin();

        if ($this->model->deletar($id, $_SESSION['usuario_id'])) {
            $_SESSION['flash'] = ['tipo' => 'success', 'msg' => 'Compromisso removido.'];
        } else {
            $_SESSION['flash'] = ['tipo' => 'danger', 'msg' => 'Erro ao remover compromisso.'];
        }

        $this->redirecionar('/compromissos');
    }
}
