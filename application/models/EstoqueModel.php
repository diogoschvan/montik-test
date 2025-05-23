<?php
defined('BASEPATH') or exit('No direct script access allowed');

class EstoqueModel extends CI_Model
{
    private $tabela = 'estoques';

    public function salvar($dados)
    {
        return $this->db->insert($this->tabela, $dados);
    }

    public function atualizar($estoque)
    {
        return $this->db->where('id', $estoque->id)
            ->update($this->tabela, [
                'quantidade' => $estoque->quantidade
            ]);
    }

    public function buscarPeloProdutoId($produtoId)
    {
        return $this->db->get_where($this->tabela, ['produto_id' => $produtoId])->row();
    }

    public function delete($id)
    {
        return $this->db->delete($this->tabela, ['id' => $id]);
    }
}
