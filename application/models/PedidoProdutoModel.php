<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PedidoProdutoModel extends CI_Model
{
    private $tabela = 'pedido_produtos';

    public function salvar($dados)
    {
        return $this->db->insert($this->tabela, $dados);
    }

    public function buscarPeloProdutoId($produtoId)
    {
        return $this->db->get_where($this->tabela, ['produto_id' => $produtoId])->row();
    }
}
