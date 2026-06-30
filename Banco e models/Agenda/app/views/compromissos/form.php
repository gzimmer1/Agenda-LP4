<?php
$c = $compromisso ?? null;
$dataHora = $c ? date('Y-m-d\TH:i', strtotime($c['data_hora'])) : date('Y-m-d\TH:i', strtotime('+1 hour'));
?>

<div class="d-flex align-items-center gap-2 mb-4">
    <a href="<?= BASE_URL ?>/compromissos" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h2 class="fw-bold mb-0">
        <i class="bi bi-<?= $c ? 'pencil-square' : 'plus-circle' ?> me-2 text-primary"></i>
        <?= htmlspecialchars($titulo) ?>
    </h2>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form action="<?= $acao ?>" method="POST" novalidate id="formCompromisso">

            <div class="row g-3">

                <!-- Título -->
                <div class="col-12">
                    <label class="form-label fw-semibold">Título <span class="text-danger">*</span></label>
                    <input type="text" name="titulo" class="form-control" required maxlength="200"
                           placeholder="Ex: Reunião com o cliente"
                           value="<?= htmlspecialchars($c['titulo'] ?? '') ?>">
                </div>

                <!-- Descrição -->
                <div class="col-12">
                    <label class="form-label fw-semibold">Descrição</label>
                    <textarea name="descricao" class="form-control" rows="3"
                              placeholder="Detalhes adicionais..."><?= htmlspecialchars($c['descricao'] ?? '') ?></textarea>
                </div>

                <!-- Data/Hora -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Data e Hora <span class="text-danger">*</span></label>
                    <input type="datetime-local" name="data_hora" class="form-control" required
                           value="<?= $dataHora ?>">
                </div>

                <!-- Local -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Local</label>
                    <input type="text" name="local" class="form-control" maxlength="200"
                           placeholder="Ex: Sala 3, Online, Rua..."
                           value="<?= htmlspecialchars($c['local'] ?? '') ?>">
                </div>

                <!-- Categoria -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Categoria</label>
                    <select name="categoria" class="form-select">
                        <?php
                        $cats = ['pessoal'=>'Pessoal','trabalho'=>'Trabalho',
                                 'saude'=>'Saúde','estudo'=>'Estudo','outro'=>'Outro'];
                        foreach ($cats as $val => $label):
                            $sel = ($c['categoria'] ?? 'outro') === $val ? 'selected' : '';
                        ?>
                            <option value="<?= $val ?>" <?= $sel ?>><?= $label ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Status (só ao editar) -->
                <?php if ($c): ?>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Status</label>
                    <select name="status" class="form-select">
                        <?php
                        $statuses = ['pendente'=>'Pendente','concluido'=>'Concluído','cancelado'=>'Cancelado'];
                        foreach ($statuses as $val => $label):
                            $sel = $c['status'] === $val ? 'selected' : '';
                        ?>
                            <option value="<?= $val ?>" <?= $sel ?>><?= $label ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php endif; ?>

            </div><!-- .row -->

            <hr class="my-4">

            <div class="d-flex gap-2 justify-content-end">
                <a href="<?= BASE_URL ?>/compromissos" class="btn btn-outline-secondary">
                    <i class="bi bi-x-lg me-1"></i>Cancelar
                </a>
                <button type="submit" class="btn btn-primary fw-semibold" id="btnSalvar">
                    <i class="bi bi-check-lg me-1"></i>
                    <?= $c ? 'Salvar alterações' : 'Criar compromisso' ?>
                </button>
            </div>

        </form>
    </div>
</div>

<script>
// Validação simples com feedback visual
document.getElementById('formCompromisso').addEventListener('submit', function(e) {
    const titulo = this.querySelector('[name="titulo"]');
    const dataHora = this.querySelector('[name="data_hora"]');
    let valido = true;

    [titulo, dataHora].forEach(campo => {
        if (!campo.value.trim()) {
            campo.classList.add('is-invalid');
            valido = false;
        } else {
            campo.classList.remove('is-invalid');
        }
    });

    if (!valido) {
        e.preventDefault();
        return;
    }

    document.getElementById('btnSalvar').disabled = true;
    document.getElementById('btnSalvar').innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Salvando...';
});

// Remove classe de erro ao digitar
document.querySelectorAll('.form-control, .form-select').forEach(el => {
    el.addEventListener('input', () => el.classList.remove('is-invalid'));
});
</script>
