-- ============================================
-- Agenda de Compromissos - Schema do Banco
-- ============================================

CREATE DATABASE IF NOT EXISTS agenda_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE agenda_db;

-- Tabela de usuários
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Tabela de compromissos
CREATE TABLE IF NOT EXISTS compromissos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    titulo VARCHAR(200) NOT NULL,
    descricao TEXT,
    data_hora DATETIME NOT NULL,
    local VARCHAR(200),
    categoria ENUM('pessoal','trabalho','saude','estudo','outro') DEFAULT 'outro',
    status ENUM('pendente','concluido','cancelado') DEFAULT 'pendente',
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Dados de exemplo
INSERT INTO usuarios (nome, email, senha) VALUES
('Admin Demo', 'admin@agenda.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');
-- senha: password

INSERT INTO compromissos (usuario_id, titulo, descricao, data_hora, local, categoria, status) VALUES
(1, 'Reunião com equipe', 'Revisão do projeto trimestral', DATE_ADD(NOW(), INTERVAL 1 DAY), 'Sala de Reuniões B', 'trabalho', 'pendente'),
(1, 'Consulta médica', 'Check-up anual', DATE_ADD(NOW(), INTERVAL 3 DAY), 'Clínica Central', 'saude', 'pendente'),
(1, 'Aula de inglês', 'Módulo 5 - Conversação', DATE_ADD(NOW(), INTERVAL 2 DAY), 'Online', 'estudo', 'pendente'),
(1, 'Aniversário da Maria', 'Não esquecer o presente!', DATE_ADD(NOW(), INTERVAL 5 DAY), 'Casa da Maria', 'pessoal', 'pendente'),
(1, 'Entrega do relatório', 'Relatório Q2 para diretoria', DATE_SUB(NOW(), INTERVAL 2 DAY), 'Email', 'trabalho', 'concluido'),
(1, 'Academia', 'Treino de musculação', DATE_SUB(NOW(), INTERVAL 1 DAY), 'Fitness Club', 'saude', 'concluido'),
(1, 'Revisão do carro', 'Troca de óleo e filtros', DATE_SUB(NOW(), INTERVAL 4 DAY), 'Auto Center', 'pessoal', 'concluido'),
(1, 'Palestra de tecnologia', 'IA e o futuro do trabalho', DATE_SUB(NOW(), INTERVAL 6 DAY), 'Auditório IFRS', 'estudo', 'cancelado');
