# Desafio Técnico - Sistema de Pedidos com Estoque

Este projeto implementa um sistema de pedidos com controle de produtos, estoque, carrinho de compras, regras de frete, consulta de CEP via ViaCEP, e webhook para atualização de status de pedidos.

## ✅ Funcionalidades implementadas

- Cadastro, edição e exclusão de produtos com variações e estoque
- Carrinho com controle de sessão e cálculo de frete
- Regra de frete:
  - R$ 20,00 para pedidos até R$ 51,99
  - R$ 15,00 entre R$ 52,00 e R$ 166,59
  - Frete grátis acima de R$ 200,00
- Consulta de CEP via API pública (ViaCEP)
- Finalização de pedido com persistência dos dados e controle de estoque
- Webhook para atualização e cancelamento de pedidos
- Uso de transações (`commit`/`rollback`) para garantir integridade
- Arquitetura MVC, código simples e limpo, sem overengineering

## 🚧 Funcionalidades não implementadas

As funcionalidades abaixo foram consideradas, mas optou-se por priorizar a entrega estável e funcional das partes essenciais do sistema, conforme o enunciado principal:

- Tela ou gestão completa de cupons
- Envio de e-mail após finalização do pedido

## ▶️ Passo a passo para rodar o programa

1. Tenha o **PHP 7.4 ou 8.x** instalado

2. Clone o projeto e abra a pasta

3. Importe o banco de dados:
   mysql -u root -p < montik.sql

4. Inicie o servidor:
	php -S localhost:8000

5. Acesse no navegador:
	http://localhost:8000/

Teste do webhook
Rota -> POST http://localhost:8000/webhook/pedido/{id}
