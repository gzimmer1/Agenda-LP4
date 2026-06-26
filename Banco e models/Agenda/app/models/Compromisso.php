<?php
/**
 * Model Compromisso - CRUD completo de compromissos
 */
class Compromisso
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Lista compromissos do usuário com filtros opcionais
     */
    public function listar(int $usuarioId, array $filtros = []): array
    {
        $sql = 'SELECT * FROM compromissos WHERE usuario_id = :uid';
        $params = [':uid' => $usuarioId];

        if (!empty($filtros['categoria'])) {
            $sql .= ' AND categoria = :categoria';
            $params[':categoria'] = $filtros['categoria'];
        }
        if (!empty($filtros['status'])) {
            $sql .= ' AND status = :status';
            $params[':status'] = $filtros['status'];
        }
        if (!empty($filtros['busca'])) {
            $sql .= ' AND (titulo LIKE :busca OR descricao LIKE :busca)';
            $params[':busca'] = '%' . $filtros['busca'] . '%';
        }

        $sql .= ' ORDER BY data_hora ASC';

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Busca compromisso por ID (verifica dono)
     */
    public function buscarPorId(int $id, int $usuarioId): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM compromissos WHERE id = ? AND usuario_id = ? LIMIT 1'
        );
        $stmt->execute([$id, $usuarioId]);
        $resultado = $stmt->fetch();
        return $resultado ?: null;
    }

    /**
     * Cria novo compromisso
     */
    public function criar(array $dados): bool
    {
        $stmt = $this->db->prepare(
            'INSERT INTO compromissos (usuario_id, titulo, descricao, data_hora, local, categoria, status)
             VALUES (:usuario_id, :titulo, :descricao, :data_hora, :local, :categoria, :status)'
        );
        return $stmt->execute([
            ':usuario_id' => $dados['usuario_id'],
            ':titulo'     => $dados['titulo'],
            ':descricao'  => $dados['descricao'] ?? null,
            ':data_hora'  => $dados['data_hora'],
            ':local'      => $dados['local'] ?? null,
            ':categoria'  => $dados['categoria'],
            ':status'     => $dados['status'] ?? 'pendente',
        ]);
    }

    /**
     * Atualiza compromisso existente
     */
    public function atualizar(int $id, int $usuarioId, array $dados): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE compromissos SET titulo = :titulo, descricao = :descricao,
             data_hora = :data_hora, local = :local, categoria = :categoria, status = :status
             WHERE id = :id AND usuario_id = :usuario_id'
        );
        return $stmt->execute([
            ':titulo'     => $dados['titulo'],
            ':descricao'  => $dados['descricao'] ?? null,
            ':data_hora'  => $dados['data_hora'],
            ':local'      => $dados['local'] ?? null,
            ':categoria'  => $dados['categoria'],
            ':status'     => $dados['status'],
            ':id'         => $id,
            ':usuario_id' => $usuarioId,
        ]);
    }

    /**
     * Remove compromisso (soft delete via cancelamento ou hard delete)
     */
    public function deletar(int $id, int $usuarioId): bool
    {
        $stmt = $this->db->prepare(
            'DELETE FROM compromissos WHERE id = ? AND usuario_id = ?'
        );
        return $stmt->execute([$id, $usuarioId]);
    }

    /**
     * Dados para o dashboard
     */
    public function estatisticas(int $usuarioId): array
    {
        // Totais por status
        $stmt = $this->db->prepare(
            'SELECT status, COUNT(*) as total FROM compromissos
             WHERE usuario_id = ? GROUP BY status'
        );
        $stmt->execute([$usuarioId]);
        $porStatus = $stmt->fetchAll();

        // Totais por categoria
        $stmt = $this->db->prepare(
            'SELECT categoria, COUNT(*) as total FROM compromissos
             WHERE usuario_id = ? GROUP BY categoria ORDER BY total DESC'
        );
        $stmt->execute([$usuarioId]);
        $porCategoria = $stmt->fetchAll();

        // Compromissos nos próximos 7 dias
        $stmt = $this->db->prepare(
            'SELECT COUNT(*) as total FROM compromissos
             WHERE usuario_id = ? AND status = "pendente"
             AND data_hora BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 7 DAY)'
        );
        $stmt->execute([$usuarioId]);
        $proximos = $stmt->fetch()['total'];

        // Por dia da semana (últimos 30 dias)
        $stmt = $this->db->prepare(
            'SELECT DAYNAME(data_hora) as dia, COUNT(*) as total
             FROM compromissos WHERE usuario_id = ?
             AND data_hora >= DATE_SUB(NOW(), INTERVAL 30 DAY)
             GROUP BY DAYNAME(data_hora), DAYOFWEEK(data_hora)
             ORDER BY DAYOFWEEK(data_hora)'
        );
        $stmt->execute([$usuarioId]);
        $porDia = $stmt->fetchAll();

        return [
            'por_status'    => $porStatus,
            'por_categoria' => $porCategoria,
            'proximos_7d'   => $proximos,
            'por_dia'       => $porDia,
        ];
    }

    /**
     * Próximos compromissos pendentes
     */
    public function proximos(int $usuarioId, int $limite = 5): array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM compromissos WHERE usuario_id = ? AND status = "pendente"
             AND data_hora >= NOW() ORDER BY data_hora ASC LIMIT ?'
        );
        $stmt->execute([$usuarioId, $limite]);
        return $stmt->fetchAll();
    }
}
