<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PedidoModel extends CI_Model
{
    private $tabela = 'pedidos';

    public function salvar($dados)
    {
        $this->db->insert($this->tabela, $dados);
        return $this->db->insert_id();
    }

    public function atualizar($pedido)
    {
        return $this->db->where('id', $pedido->id)
            ->update($this->tabela, [
                'status' => $pedido->status
            ]);
    }

    public function buscarPeloProdutoId($produtoId)
    {
        return $this->db->get_where($this->tabela, ['produto_id' => $produtoId])->row();
    }

    public function buscarPeloId($pedidoId)
    {
        return $this->db->get_where($this->tabela, ['id' => $pedidoId])->row();
    }

    public function delete($id)
    {
        return $this->db->delete($this->tabela, ['id' => $id]);
    }
}
