<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold mb-0"><i class="bi bi-list-check me-2 text-primary"></i>Meus Compromissos</h2>
    <a href="<?= BASE_URL ?>/compromissos/novo" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Novo
    </a>
</div>

<!-- Filtros -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="<?= BASE_URL ?>/compromissos" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label small fw-semibold mb-1">Buscar</label>
                <input type="text" name="busca" class="form-control form-control-sm"
                       placeholder="Título ou descrição..." value="<?= htmlspecialchars($filtros['busca']) ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold mb-1">Categoria</label>
                <select name="categoria" class="form-select form-select-sm">
                    <option value="">Todas</option>
                    <?php foreach (['pessoal','trabalho','saude','estudo','outro'] as $cat): ?>
                        <option value="<?= $cat ?>" <?= $filtros['categoria'] === $cat ? 'selected' : '' ?>>
                            <?= ucfirst($cat) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold mb-1">Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">Todos</option>
                    <?php foreach (['pendente','concluido','cancelado'] as $st): ?>
                        <option value="<?= $st ?>" <?= $filtros['status'] === $st ? 'selected' : '' ?>>
                            <?= ucfirst($st) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-1">
                <button type="submit" class="btn btn-primary btn-sm w-100">
                    <i class="bi bi-search me-1"></i>Filtrar
                </button>
                <a href="<?= BASE_URL ?>/compromissos" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-x"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Tabela de compromissos -->
<?php if (empty($compromissos)): ?>
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-5 text-muted">
            <i class="bi bi-calendar-x fs-1 d-block mb-3 text-secondary"></i>
            <h5>Nenhum compromisso encontrado</h5>
            <p class="mb-3">Crie seu primeiro compromisso para começar.</p>
            <a href="<?= BASE_URL ?>/compromissos/novo" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i>Criar compromisso
            </a>
        </div>
    </div>
<?php else: ?>
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">Título</th>
                        <th>Data/Hora</th>
                        <th>Local</th>
                        <th>Categoria</th>
                        <th>Status</th>
                        <th class="text-center pe-3">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($compromissos as $c): ?>
                        <?php
                        $passado = strtotime($c['data_hora']) < time();
                        $linhaClass = ($passado && $c['status'] === 'pendente') ? 'table-warning' : '';
                        ?>
                        <tr class="<?= $linhaClass ?>">
                            <td class="ps-3 fw-semibold">
                                <?= htmlspecialchars($c['titulo']) ?>
                                <?php if ($c['descricao']): ?>
                                    <br><small class="text-muted fw-normal"><?= htmlspecialchars(mb_substr($c['descricao'], 0, 50)) ?>...</small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <small>
                                    <i class="bi bi-calendar me-1"></i>
                                    <?= date('d/m/Y', strtotime($c['data_hora'])) ?>
                                    <br>
                                    <i class="bi bi-clock me-1"></i>
                                    <?= date('H:i', strtotime($c['data_hora'])) ?>
                                </small>
                            </td>
                            <td class="text-muted small">
                                <?= $c['local'] ? '<i class="bi bi-geo-alt me-1"></i>' . htmlspecialchars($c['local']) : '—' ?>
                            </td>
                            <td>
                                <span class="badge badge-categoria badge-<?= $c['categoria'] ?>">
                                    <?= ucfirst($c['categoria']) ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-status badge-<?= $c['status'] ?>">
                                    <?= ucfirst($c['status']) ?>
                                </span>
                            </td>
                            <td class="text-center pe-3">
                                <div class="btn-group btn-group-sm">
                                    <a href="<?= BASE_URL ?>/compromissos/editar/<?= $c['id'] ?>"
                                       class="btn btn-outline-primary" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" class="btn btn-outline-danger"
                                            title="Excluir"
                                            onclick="confirmarDelecao(<?= $c['id'] ?>, '<?= htmlspecialchars(addslashes($c['titulo'])) ?>')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white text-muted small">
            <i class="bi bi-info-circle me-1"></i>
            <?= count($compromissos) ?> compromisso(s) encontrado(s)
        </div>
    </div>
<?php endif; ?>

<!-- Modal de confirmação de exclusão -->
<div class="modal fade" id="modalDeletar" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold text-danger">
                    <i class="bi bi-exclamation-triangle me-2"></i>Confirmar exclusão
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Deseja excluir o compromisso <strong id="nomeDeletar"></strong>?
                Esta ação não pode ser desfeita.
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form id="formDeletar" method="POST" class="d-inline">
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash me-1"></i>Excluir
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function confirmarDelecao(id, titulo) {
    document.getElementById('nomeDeletar').textContent = titulo;
    document.getElementById('formDeletar').action = '<?= BASE_URL ?>/compromissos/deletar/' + id;
    new bootstrap.Modal(document.getElementById('modalDeletar')).show();
}
</script>
