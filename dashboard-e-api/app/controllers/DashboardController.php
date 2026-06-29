<?php
/**
 * DashboardController - Painel de controle com gráficos e estatísticas
 */
class DashboardController extends Controller
{
    private Compromisso $model;

    public function __construct()
    {
        $this->model = new Compromisso();
    }

    /**
     * GET /dashboard - Exibe painel principal
     */
    public function index(): void
    {
        $this->exigeLogin();

        $uid  = $_SESSION['usuario_id'];
        $stats   = $this->model->estatisticas($uid);
        $proximos = $this->model->proximos($uid, 5);

        $this->renderizar('dashboard/index', [
            'titulo'   => 'Dashboard',
            'stats'    => $stats,
            'proximos' => $proximos,
        ]);
    }

    /**
     * GET /api/dashboard - Retorna dados JSON para gráficos (REST API)
     */
    public function api(): void
    {
        $this->exigeLogin();

        $uid   = $_SESSION['usuario_id'];
        $stats = $this->model->estatisticas($uid);

        $this->json([
            'sucesso' => true,
            'dados'   => $stats,
        ]);
    }
}
