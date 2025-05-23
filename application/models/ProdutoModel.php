<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ProdutoModel extends CI_Model
{
    private $tabela = 'produtos';

    public function salvar($dados)
    {
        $this->db->insert($this->tabela, $dados);
        return $this->db->insert_id();
    }

    public function atualizar($dados)
    {
        return $this->db
            ->where('id', $dados->id)
            ->update($this->tabela, [
                'nome' => $dados->nome,
                'preco' => $dados->preco
            ]);
    }

    public function buscarPeloId($id)
    {
        return $this->db
            ->get_where($this->tabela, ['id' => $id])
            ->row();
    }

    public function delete($id)
    {
        return $this->db->delete($this->tabela, ['id' => $id]);
    }

    public function all()
    {
        return $this->db
            ->select('produtos.id, produtos.nome, produtos.preco, estoques.id as estoque_id, estoques.variacao, estoques.quantidade')
            ->from('produtos')
            ->join('estoques', 'estoques.produto_id = produtos.id')
            ->order_by('produtos.id')
            ->get()
            ->result();
    }


    public function exibir($id)
    {
        return $this->db
            ->select('p.id, p.nome, p.preco, e.variacao, e.quantidade')
            ->from('produtos p')
            ->join('estoques e', 'e.produto_id = p.id', 'left')
            ->where('p.id', $id)
            ->limit(1)
            ->get()
            ->row();
    }
}
