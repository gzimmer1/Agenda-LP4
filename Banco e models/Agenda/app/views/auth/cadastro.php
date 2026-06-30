<form action="<?= BASE_URL ?>/auth/cadastro" method="POST" novalidate>

    <div class="mb-3">
        <label class="form-label fw-semibold">Nome completo</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-person"></i></span>
            <input type="text" name="nome" class="form-control" placeholder="Seu nome" required autofocus>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label fw-semibold">E-mail</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
            <input type="email" name="email" class="form-control" placeholder="seu@email.com" required>
        </div>
    </div>

    <div class="mb-4">
        <label class="form-label fw-semibold">Senha <small class="text-muted">(mín. 6 caracteres)</small></label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-lock"></i></span>
            <input type="password" name="senha" class="form-control" placeholder="••••••" minlength="6" required>
        </div>
    </div>

    <button type="submit" class="btn btn-success w-100 fw-semibold">
        <i class="bi bi-person-plus me-2"></i>Criar conta
    </button>

    <hr class="my-3">

    <p class="text-center text-muted small mb-0">
        Já tem conta?
        <a href="<?= BASE_URL ?>/auth/login" class="text-decoration-none">Entrar</a>
    </p>

</form>
