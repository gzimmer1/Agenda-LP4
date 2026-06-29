<?php
// Prepara dados para os gráficos
$statusLabels = $statusData = [];
$statusCores = [
    'pendente'  => '#0d6efd',
    'concluido' => '#198754',
    'cancelado' => '#dc3545',
];
foreach ($stats['por_status'] as $s) {
    $statusLabels[] = ucfirst($s['status']);
    $statusData[]   = (int)$s['total'];
    $statusCoresArr[] = $statusCores[$s['status']] ?? '#6c757d';
}

$catLabels = $catData = $catCores = [];
$paletaCat = ['#0d6efd','#198754','#dc3545','#ffc107','#0dcaf0'];
foreach ($stats['por_categoria'] as $i => $c) {
    $catLabels[] = ucfirst($c['categoria']);
    $catData[]   = (int)$c['total'];
    $catCores[]  = $paletaCat[$i % count($paletaCat)];
}

$diaLabels = $diaData = [];
$diasPT = ['Sunday'=>'Dom','Monday'=>'Seg','Tuesday'=>'Ter',
           'Wednesday'=>'Qua','Thursday'=>'Qui','Friday'=>'Sex','Saturday'=>'Sáb'];
foreach ($stats['por_dia'] as $d) {
    $diaLabels[] = $diasPT[$d['dia']] ?? $d['dia'];
    $diaData[]   = (int)$d['total'];
}

// Totais rápidos
$totalGeral = array_sum($statusData);
$totalPendente = 0; $totalConcluido = 0;
foreach ($stats['por_status'] as $s) {
    if ($s['status'] === 'pendente')  $totalPendente  = $s['total'];
    if ($s['status'] === 'concluido') $totalConcluido = $s['total'];
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold mb-0"><i class="bi bi-speedometer2 me-2 text-primary"></i>Dashboard</h2>
    <a href="<?= BASE_URL ?>/compromissos/novo" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Novo compromisso
    </a>
</div>

<!-- Cards de resumo -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card border-0 bg-primary text-white shadow-sm h-100">
            <div class="card-body text-center py-3">
                <i class="bi bi-calendar3 fs-2"></i>
                <div class="fs-1 fw-bold"><?= $totalGeral ?></div>
                <div class="small opacity-75">Total</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 bg-warning text-dark shadow-sm h-100">
            <div class="card-body text-center py-3">
                <i class="bi bi-clock fs-2"></i>
                <div class="fs-1 fw-bold"><?= $totalPendente ?></div>
                <div class="small opacity-75">Pendentes</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 bg-success text-white shadow-sm h-100">
            <div class="card-body text-center py-3">
                <i class="bi bi-check-circle fs-2"></i>
                <div class="fs-1 fw-bold"><?= $totalConcluido ?></div>
                <div class="small opacity-75">Concluídos</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 bg-info text-white shadow-sm h-100">
            <div class="card-body text-center py-3">
                <i class="bi bi-calendar-week fs-2"></i>
                <div class="fs-1 fw-bold"><?= $stats['proximos_7d'] ?></div>
                <div class="small opacity-75">Próx. 7 dias</div>
            </div>
        </div>
    </div>
</div>

<!-- Gráficos -->
<div class="row g-4 mb-4">

    <!-- Gráfico de rosca - por status -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 fw-semibold pt-3">
                <i class="bi bi-pie-chart me-1 text-primary"></i>Por Status
            </div>
            <div class="card-body d-flex align-items-center justify-content-center" style="min-height:220px">
                <?php if (empty($statusData)): ?>
                    <p class="text-muted text-center">Nenhum dado ainda.</p>
                <?php else: ?>
                    <canvas id="graficoStatus" style="max-height:200px"></canvas>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Gráfico de barras - por categoria -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 fw-semibold pt-3">
                <i class="bi bi-bar-chart me-1 text-success"></i>Por Categoria
            </div>
            <div class="card-body d-flex align-items-center justify-content-center" style="min-height:220px">
                <?php if (empty($catData)): ?>
                    <p class="text-muted text-center">Nenhum dado ainda.</p>
                <?php else: ?>
                    <canvas id="graficoCategoria" style="max-height:200px"></canvas>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Gráfico de linhas - por dia da semana -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 fw-semibold pt-3">
                <i class="bi bi-graph-up me-1 text-info"></i>Por Dia (30 dias)
            </div>
            <div class="card-body d-flex align-items-center justify-content-center" style="min-height:220px">
                <?php if (empty($diaData)): ?>
                    <p class="text-muted text-center">Nenhum dado ainda.</p>
                <?php else: ?>
                    <canvas id="graficoDia" style="max-height:200px"></canvas>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Próximos compromissos -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 fw-semibold pt-3 d-flex justify-content-between align-items-center">
        <span><i class="bi bi-calendar-event me-1 text-warning"></i>Próximos compromissos</span>
        <a href="<?= BASE_URL ?>/compromissos" class="btn btn-sm btn-outline-primary">Ver todos</a>
    </div>
    <div class="card-body p-0">
        <?php if (empty($proximos)): ?>
            <div class="text-center text-muted py-4">
                <i class="bi bi-calendar-x fs-3 d-block mb-2"></i>
                Nenhum compromisso pendente.
                <a href="<?= BASE_URL ?>/compromissos/novo">Criar agora</a>
            </div>
        <?php else: ?>
            <div class="list-group list-group-flush">
                <?php foreach ($proximos as $c): ?>
                    <div class="list-group-item list-group-item-action d-flex align-items-center gap-3 py-3">
                        <span class="badge badge-categoria badge-<?= $c['categoria'] ?> p-2 rounded-2">
                            <i class="bi bi-<?= iconeCategoria($c['categoria']) ?>"></i>
                        </span>
                        <div class="flex-grow-1">
                            <div class="fw-semibold"><?= htmlspecialchars($c['titulo']) ?></div>
                            <small class="text-muted">
                                <i class="bi bi-clock me-1"></i>
                                <?= date('d/m/Y H:i', strtotime($c['data_hora'])) ?>
                                <?php if ($c['local']): ?>
                                    &nbsp;·&nbsp;<i class="bi bi-geo-alt me-1"></i><?= htmlspecialchars($c['local']) ?>
                                <?php endif; ?>
                            </small>
                        </div>
                        <a href="<?= BASE_URL ?>/compromissos/editar/<?= $c['id'] ?>" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-pencil"></i>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
// Helper inline para ícone de categoria
function iconeCategoria(string $cat): string {
    return match($cat) {
        'trabalho' => 'briefcase',
        'saude'    => 'heart-pulse',
        'estudo'   => 'book',
        'pessoal'  => 'person',
        default    => 'calendar',
    };
}
?>

<script>
// Gráfico de rosca - Status
<?php if (!empty($statusData)): ?>
new Chart(document.getElementById('graficoStatus'), {
    type: 'doughnut',
    data: {
        labels: <?= json_encode($statusLabels) ?>,
        datasets: [{
            data: <?= json_encode($statusData) ?>,
            backgroundColor: <?= json_encode($statusCoresArr ?? []) ?>,
            borderWidth: 2,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'bottom' } }
    }
});
<?php endif; ?>

// Gráfico de barras - Categorias
<?php if (!empty($catData)): ?>
new Chart(document.getElementById('graficoCategoria'), {
    type: 'bar',
    data: {
        labels: <?= json_encode($catLabels) ?>,
        datasets: [{
            label: 'Compromissos',
            data: <?= json_encode($catData) ?>,
            backgroundColor: <?= json_encode($catCores) ?>,
            borderRadius: 6,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
    }
});
<?php endif; ?>

// Gráfico de linha - Por dia
<?php if (!empty($diaData)): ?>
new Chart(document.getElementById('graficoDia'), {
    type: 'line',
    data: {
        labels: <?= json_encode($diaLabels) ?>,
        datasets: [{
            label: 'Compromissos',
            data: <?= json_encode($diaData) ?>,
            borderColor: '#0dcaf0',
            backgroundColor: 'rgba(13,202,240,0.1)',
            tension: 0.4,
            fill: true,
            pointBackgroundColor: '#0dcaf0',
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
    }
});
<?php endif; ?>
</script>
