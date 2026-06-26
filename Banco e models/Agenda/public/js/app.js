/**
 * Agenda de Compromissos - JavaScript principal
 */

document.addEventListener('DOMContentLoaded', function () {

    // ===== Auto-fechar alertas após 4 segundos =====
    document.querySelectorAll('.alert.fade.show').forEach(function (alerta) {
        setTimeout(function () {
            const bsAlert = bootstrap.Alert.getOrCreateInstance(alerta);
            if (bsAlert) bsAlert.close();
        }, 4000);
    });

    // ===== Tooltip do Bootstrap em elementos com data-bs-toggle="tooltip" =====
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function (el) {
        new bootstrap.Tooltip(el);
    });

    // ===== Confirmação de exclusão (fallback sem modal) =====
    document.querySelectorAll('[data-confirmar]').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            const msg = this.dataset.confirmar || 'Tem certeza?';
            if (!confirm(msg)) e.preventDefault();
        });
    });

    // ===== Destaca linha da tabela ao passar o mouse =====
    document.querySelectorAll('.table-hover tbody tr').forEach(function (tr) {
        tr.style.cursor = 'default';
    });

    // ===== Filtros: submete form ao mudar select =====
    document.querySelectorAll('select[name="categoria"], select[name="status"]').forEach(function (sel) {
        sel.addEventListener('change', function () {
            // Só auto-submete se houver botão de submit no form pai
            const form = this.closest('form');
            if (form) form.submit();
        });
    });

    // ===== Animação suave ao carregar a página =====
    document.body.style.opacity = '0';
    document.body.style.transition = 'opacity 0.25s ease';
    requestAnimationFrame(function () {
        document.body.style.opacity = '1';
    });

});

/**
 * Utilitário: formata data BR
 */
function formatarData(dataISO) {
    if (!dataISO) return '—';
    const d = new Date(dataISO);
    return d.toLocaleDateString('pt-BR') + ' ' + d.toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit' });
}

/**
 * Utilitário: requisição fetch para a REST API interna
 */
async function apiGet(endpoint) {
    const BASE = document.querySelector('meta[name="base-url"]')?.content ?? '';
    try {
        const res = await fetch(BASE + endpoint, {
            headers: { 'Accept': 'application/json' }
        });
        return await res.json();
    } catch (e) {
        console.error('Erro na API:', e);
        return null;
    }
}
