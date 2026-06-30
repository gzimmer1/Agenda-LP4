<?php
/**
 * AuthController - Autenticação de usuários (login, cadastro, logout)
 */
class AuthController extends Controller
{
    private Usuario $usuarioModel;

    public function __construct()
    {
        $this->usuarioModel = new Usuario();
    }

    /**
     * GET /auth/login
     */
    public function login(): void
    {
        if ($this->autenticado()) {
            $this->redirecionar('/dashboard');
        }
        $this->renderizar('auth/login', ['titulo' => 'Entrar'], 'auth');
    }

    /**
     * POST /auth/login
     */
    public function autenticar(): void
    {
        $email = $this->post('email');
        $senha = $_POST['senha'] ?? '';

        $usuario = $this->usuarioModel->buscarPorEmail($email);

        if ($usuario && password_verify($senha, $usuario['senha'])) {
            $_SESSION['usuario_id']   = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            $_SESSION['flash']        = ['tipo' => 'success', 'msg' => 'Bem-vindo, ' . $usuario['nome'] . '!'];
            $this->redirecionar('/dashboard');
        }

        $_SESSION['flash'] = ['tipo' => 'danger', 'msg' => 'E-mail ou senha incorretos.'];
        $this->redirecionar('/auth/login');
    }

    /**
     * GET /auth/cadastro
     */
    public function cadastro(): void
    {
        if ($this->autenticado()) {
            $this->redirecionar('/dashboard');
        }
        $this->renderizar('auth/cadastro', ['titulo' => 'Criar conta'], 'auth');
    }

    /**
     * POST /auth/cadastro
     */
    public function registrar(): void
    {
        $nome  = $this->post('nome');
        $email = $this->post('email');
        $senha = $_POST['senha'] ?? '';

        if (strlen($nome) < 2 || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($senha) < 6) {
            $_SESSION['flash'] = ['tipo' => 'warning', 'msg' => 'Preencha todos os campos corretamente.'];
            $this->redirecionar('/auth/cadastro');
            return;
        }

        if ($this->usuarioModel->emailExiste($email)) {
            $_SESSION['flash'] = ['tipo' => 'danger', 'msg' => 'Este e-mail já está cadastrado.'];
            $this->redirecionar('/auth/cadastro');
            return;
        }

        if ($this->usuarioModel->criar($nome, $email, $senha)) {
            $_SESSION['flash'] = ['tipo' => 'success', 'msg' => 'Conta criada! Faça o login.'];
            $this->redirecionar('/auth/login');
        } else {
            $_SESSION['flash'] = ['tipo' => 'danger', 'msg' => 'Erro ao criar conta. Tente novamente.'];
            $this->redirecionar('/auth/cadastro');
        }
    }

    /**
     * GET /auth/logout
     */
    public function logout(): void
    {
        session_destroy();
        header('Location: ' . BASE_URL . '/auth/login');
        exit;
    }
}
