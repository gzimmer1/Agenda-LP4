<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($titulo ?? 'Agenda') ?> | <?= APP_NAME ?></title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <!-- CSS customizado -->
    <link href="<?= BASE_URL ?>/css/style.css" rel="stylesheet">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="<?= BASE_URL ?>/dashboard">
            <i class="bi bi-calendar-check me-2"></i><?= APP_NAME ?>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link <?= str_contains($_SERVER['REQUEST_URI'], 'dashboard') ? 'active' : '' ?>"
                       href="<?= BASE_URL ?>/dashboard">
                        <i class="bi bi-speedometer2 me-1"></i>Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= str_contains($_SERVER['REQUEST_URI'], 'compromissos') ? 'active' : '' ?>"
                       href="<?= BASE_URL ?>/compromissos">
                        <i class="bi bi-list-check me-1"></i>Compromissos
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>/compromissos/novo">
                        <i class="bi bi-plus-circle me-1"></i>Novo
                    </a>
                </li>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle me-1"></i>
                        <?= htmlspecialchars($_SESSION['usuario_nome'] ?? 'Usuário') ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item text-danger" href="<?= BASE_URL ?>/auth/logout">
                            <i class="bi bi-box-arrow-right me-2"></i>Sair
                        </a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Flash Messages -->
<?php if (!empty($_SESSION['flash'])): ?>
    <?php $flash = $_SESSION['flash']; unset($_SESSION['flash']); ?>
    <div class="container mt-3">
        <div class="alert alert-<?= $flash['tipo'] ?> alert-dismissible fade show" role="alert">
            <i class="bi bi-<?= $flash['tipo'] === 'success' ? 'check-circle' : ($flash['tipo'] === 'danger' ? 'x-circle' : 'exclamation-triangle') ?> me-2"></i>
            <?= htmlspecialchars($flash['msg']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
<?php endif; ?>

<!-- Conteúdo Principal -->
<main class="container py-4">
    <?= $conteudo ?>
</main>

<!-- Footer -->
<footer class="footer bg-light border-top py-3 mt-auto">
    <div class="container text-center text-muted small">
        <i class="bi bi-calendar-check me-1"></i><?= APP_NAME ?> &mdash; Desenvolvido com PHP + MySQL + Bootstrap
    </div>
</footer>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<!-- JS customizado -->
<script src="<?= BASE_URL ?>/js/app.js"></script>

<?php if (!empty($scriptsExtra)): ?>
    <?= $scriptsExtra ?>
<?php endif; ?>

</body>
</html>
