CREATE DATABASE IF NOT EXISTS montik
DEFAULT CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE montik;

-- Produtos
CREATE TABLE IF NOT EXISTS produtos (
	id INT AUTO_INCREMENT PRIMARY KEY,
	nome VARCHAR(100) NOT NULL,
	preco DECIMAL(10,2) NOT NULL
);

-- Estoque (por produto ou variação)
CREATE TABLE IF NOT EXISTS estoques (
	id INT AUTO_INCREMENT PRIMARY KEY,
	produto_id INT NOT NULL,
	variacao VARCHAR(100) NOT NULL,
	quantidade INT NOT NULL,
	FOREIGN KEY (produto_id) REFERENCES produtos(id) ON DELETE CASCADE
);

-- Pedidos
CREATE TABLE IF NOT EXISTS pedidos (
	id INT AUTO_INCREMENT PRIMARY KEY,
	nome_solicitante VARCHAR(100) NOT NULL,
	email VARCHAR(100) NOT NULL,
	cep VARCHAR(9) NOT NULL,
	cidade VARCHAR(100) NOT NULL,
	uf CHAR(2) NOT NULL,
	numero VARCHAR(20) NOT NULL,
	complemento VARCHAR(255),
	status ENUM('pendente', 'pago', 'cancelado') DEFAULT 'pendente',
	data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Itens do Pedido
CREATE TABLE IF NOT EXISTS pedido_produtos (
	id INT AUTO_INCREMENT PRIMARY KEY,
	pedido_id INT NOT NULL,
	produto_id INT NOT NULL,
	quantidade INT NOT NULL,
	preco_unitario DECIMAL(10,2) NOT NULL,
	FOREIGN KEY (pedido_id) REFERENCES pedidos(id) ON DELETE CASCADE,
	FOREIGN KEY (produto_id) REFERENCES produtos(id)
);
