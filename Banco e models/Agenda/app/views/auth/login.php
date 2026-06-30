<form action="<?= BASE_URL ?>/auth/login" method="POST" novalidate>

    <div class="mb-3">
        <label class="form-label fw-semibold">E-mail</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
            <input type="email" name="email" class="form-control" placeholder="seu@email.com"
                   value="admin@agenda.com" required autofocus>
        </div>
    </div>

    <div class="mb-4">
        <label class="form-label fw-semibold">Senha</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-lock"></i></span>
            <input type="password" name="senha" id="senha" class="form-control" placeholder="••••••" value="password" required>
            <button class="btn btn-outline-secondary" type="button" onclick="toggleSenha()">
                <i class="bi bi-eye" id="olho"></i>
            </button>
        </div>
        <div class="form-text text-end">Demo: <code>admin@agenda.com</code> / <code>password</code></div>
    </div>

    <button type="submit" class="btn btn-primary w-100 fw-semibold">
        <i class="bi bi-box-arrow-in-right me-2"></i>Entrar
    </button>

    <hr class="my-3">

    <p class="text-center text-muted small mb-0">
        Não tem conta?
        <a href="<?= BASE_URL ?>/auth/cadastro" class="text-decoration-none">Criar conta</a>
    </p>

</form>

<script>
function toggleSenha() {
    const campo = document.getElementById('senha');
    const olho = document.getElementById('olho');
    if (campo.type === 'password') {
        campo.type = 'text';
        olho.className = 'bi bi-eye-slash';
    } else {
        campo.type = 'password';
        olho.className = 'bi bi-eye';
    }
}
</script>
