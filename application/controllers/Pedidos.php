<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property PedidoModel $PedidoModel
 * @property ProdutoModel $ProdutoModel
 * @property EstoqueModel $EstoqueModel
 * @property PedidoProdutoModel $PedidoProdutoModel
 * @property CI_Input $input
 * @property CI_Loader $load
 * @property CI_DB_query_builder $db
 */
class Pedidos extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->model('PedidoModel');
		$this->load->model('ProdutoModel');
		$this->load->model('EstoqueModel');
		$this->load->model('PedidoProdutoModel');
	}

	public function salvar()
	{
		if ($this->input->method() !== 'post') {
			show_error('Método não permitido', 405);
		}

		$carrinho = json_decode($this->input->post('carrinho'));
		if (!$carrinho || !is_array($carrinho)) {
			show_error('Carrinho inválido.', 400);
		}

		$pedido = [
			'nome_solicitante' => $this->input->post('nome_solicitante'),
			'email' => $this->input->post('email'),
			'cep' => $this->input->post('cep'),
			'cidade' => $this->input->post('cidade'),
			'uf' => $this->input->post('uf'),
			'numero' => $this->input->post('numero'),
			'complemento' => $this->input->post('complemento') ?? null,
			'status' => 'pago'
		];

		$this->db->trans_begin();

		$pedidoId = $this->PedidoModel->salvar($pedido);
		if (!$pedidoId) {
			$this->db->trans_rollback();
			show_error('Erro ao salvar pedido.', 500);
		}

		foreach ($carrinho as $item) {
			$produto = $this->ProdutoModel->buscarPeloId($item->id);
			if (!$produto) {
				$this->db->trans_rollback();
				show_error('Produto inválido ou estoque insuficiente.', 400);
			}

			$estoque = $this->EstoqueModel->buscarPeloProdutoId($item->id);
			if ($estoque->quantidade == 0 || $item->qtd > $estoque->quantidade) {
				$this->db->trans_rollback();
				show_error('Produto inválido ou estoque insuficiente.', 400);
			}

			$estoque->quantidade = $estoque->quantidade - $item->qtd;
			if (!$this->EstoqueModel->atualizar($estoque)) {
				$this->db->trans_rollback();
				show_error('Erro ao atualizar o estoque.', 500);
			};

			$dados = [
				'pedido_id' => $pedidoId,
				'produto_id' => $produto->id,
				'quantidade' => $item->qtd,
				'preco_unitario' => $produto->preco
			];

			if (!$this->PedidoProdutoModel->salvar($dados)) {
				$this->db->trans_rollback();
				show_error('Erro ao salvar item.', 500);
			}
		}

		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			show_error('Erro na transação.', 500);
		} else {
			$this->db->trans_commit();
			echo json_encode(['mensagem' => 'Pedido realizado com sucesso']);
		}
	}
}
