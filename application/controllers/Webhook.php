<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property PedidoModel $PedidoModel
 * @property CI_Input $input
 * @property CI_Loader $load
 */
class Webhook extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('PedidoModel');
    }

    public function pedido($id)
    {
        if ($this->input->method() !== 'post') {
            show_error('Método não permitido', 405);
        }

        $data = json_decode(file_get_contents('php://input'));

        if (empty($data->status)) {
            show_error('Status não informado.', 400);
        }

        $pedido = $this->PedidoModel->buscarPeloId($id);
        if (!$pedido) {
            show_error('Pedido não encontrado.', 400);
        }

        if ($data->status === 'cancelado') {
            return $this->PedidoModel->delete($pedido->id);
        }

        $pedido->status = $data->status;
        return $this->PedidoModel->atualizar($pedido);
    }
}
