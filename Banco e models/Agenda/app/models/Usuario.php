<?php
/**
 * Model Usuario - Gerencia dados de usuários no banco
 */
class Usuario
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Busca usuário pelo e-mail
     */
    public function buscarPorEmail(string $email): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM usuarios WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        $usuario = $stmt->fetch();
        return $usuario ?: null;
    }

    /**
     * Busca usuário pelo ID
     */
    public function buscarPorId(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT id, nome, email, criado_em FROM usuarios WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        $usuario = $stmt->fetch();
        return $usuario ?: null;
    }

    /**
     * Cadastra novo usuário
     */
    public function criar(string $nome, string $email, string $senha): bool
    {
        $hash = password_hash($senha, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare(
            'INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)'
        );
        return $stmt->execute([$nome, $email, $hash]);
    }

    /**
     * Verifica se e-mail já está em uso
     */
    public function emailExiste(string $email): bool
    {
        $stmt = $this->db->prepare('SELECT id FROM usuarios WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        return (bool) $stmt->fetch();
    }
}
