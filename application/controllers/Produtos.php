<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property ProdutoModel $ProdutoModel
 * @property EstoqueModel $EstoqueModel
 * @property PedidoProdutoModel $PedidoProdutoModel
 * @property CI_Input $input
 * @property CI_Loader $load
 * @property CI_DB_query_builder $db
 */
class Produtos extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->model('ProdutoModel');
		$this->load->model('EstoqueModel');
		$this->load->model('PedidoProdutoModel');
	}

	public function index()
	{
		$data['produtos'] = $this->ProdutoModel->all();
		$this->load->view('produtos', $data);
	}

	public function exibir($id)
	{
		if ($this->input->method() !== 'get') {
			show_error('Método não permitido', 405);
		}

		$produto = $this->ProdutoModel->exibir($id);
		if (!$produto) {
			show_error('Produto não encontrado', 404);
		}

		$data = [
			'produtos' => $this->ProdutoModel->all(),
			'produto_editando' => $produto
		];

		$this->load->view('produtos', $data);
	}

	public function salvar()
	{
		if ($this->input->method() !== 'post') {
			show_error('Método não permitido', 405);
		}

		$this->db->trans_begin();

		$dadosProduto = [
			'nome' => $this->input->post('nome'),
			'preco' => $this->input->post('preco')
		];

		$produtoId = $this->ProdutoModel->salvar($dadosProduto);
		if (!$produtoId) {
			$this->db->trans_rollback();
			show_error('Erro ao salvar o produto.', 500);
		}

		$dadosEstoque = [
			'produto_id' => $produtoId,
			'variacao' => $this->input->post('variacao'),
			'quantidade' => $this->input->post('estoque')
		];

		if (!$this->EstoqueModel->salvar($dadosEstoque)) {
			$this->db->trans_rollback();
			show_error('Erro ao salvar o estoque.', 500);
		}

		$this->db->trans_commit();
		redirect('/');
	}

	public function atualizar($id)
	{
		if (!in_array($this->input->method(), ['put', 'post'])) {
			show_error('Método não permitido', 405);
		}

		parse_str(file_get_contents('php://input'), $input);

		$produto = $this->ProdutoModel->buscarPeloId($id);
		$estoque = $this->EstoqueModel->buscarPeloProdutoId($id);

		if (!$produto || !$estoque) {
			show_error('Produto ou estoque não encontrado.', 400);
		}

		$this->db->trans_begin();

		$produto->nome = $input['nome'] ?? $produto->nome;
		$produto->preco = $input['preco'] ?? $produto->preco;

		if (!$this->ProdutoModel->atualizar($produto)) {
			$this->db->trans_rollback();
			show_error('Erro ao atualizar o produto.', 500);
		}

		$estoque->variacao = $input['variacao'] ?? $estoque->variacao;
		$estoque->quantidade = $input['estoque'] ?? $estoque->quantidade;

		if (!$this->EstoqueModel->atualizar($estoque)) {
			$this->db->trans_rollback();
			show_error('Erro ao atualizar o estoque.', 500);
		}

		$this->db->trans_commit();
		redirect('/');
	}

	public function excluir($id)
	{
		if (!in_array($this->input->method(), ['delete', 'post'])) {
			show_error('Método não permitido', 405);
		}

		$produto = $this->ProdutoModel->buscarPeloId($id);
		$estoque = $this->EstoqueModel->buscarPeloProdutoId($id);

		if (!$produto || !$estoque) {
			show_error('Produto ou estoque não encontrado.', 400);
		}

		if ($this->PedidoProdutoModel->buscarPeloProdutoId($id)) {
			show_error('Produto vinculado a pedido não pode ser deletado.', 400);
		}

		$this->db->trans_begin();

		if (!$this->EstoqueModel->delete($estoque->id)) {
			$this->db->trans_rollback();
			show_error('Erro ao deletar o estoque.', 500);
		}

		if (!$this->ProdutoModel->delete($produto->id)) {
			$this->db->trans_rollback();
			show_error('Erro ao deletar o produto.', 500);
		}

		$this->db->trans_commit();
		redirect('/');
	}
}
