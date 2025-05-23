<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
	<meta charset="utf-8">
	<title>Cadastro de Produtos</title>
	<style>
		body {
			font-family: Arial, sans-serif;
			background: #f3f3f3;
			margin: 0;
			padding: 30px;
		}

		.container {
			max-width: 600px;
			background: white;
			margin: 0 auto;
			padding: 20px 30px;
			box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
			border-radius: 8px;
		}

		h1 {
			text-align: center;
			margin-bottom: 20px;
			color: #333;
		}

		form {
			display: flex;
			flex-direction: column;
			gap: 12px;
			margin-bottom: 30px;
		}

		input,
		select {
			padding: 10px;
			border: 1px solid #ccc;
			border-radius: 4px;
		}

		button {
			padding: 10px;
			background-color: #007bff;
			border: none;
			color: white;
			cursor: pointer;
			border-radius: 4px;
			font-weight: bold;
		}

		button:hover {
			background-color: #0056b3;
		}

		.produto-card {
			background: #fafafa;
			border: 1px solid #ddd;
			padding: 12px;
			margin-bottom: 10px;
			border-radius: 4px;
		}

		.inline-form {
			display: inline;
		}

		#carrinho {
			position: fixed;
			top: 30px;
			right: 30px;
			width: 300px;
			background: white;
			border: 1px solid #ccc;
			border-radius: 8px;
			padding: 15px;
			box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
		}

		#carrinho h2 {
			margin-top: 0;
		}

		#carrinho ul {
			list-style: none;
			padding-left: 0;
		}

		#carrinho li {
			margin-bottom: 8px;
			font-size: 14px;
			display: flex;
			justify-content: space-between;
			align-items: center;
		}

		.item-carrinho {
			flex: 1;
		}

		.btn-remover {
			background-color: #dc3545;
			color: white;
			border: none;
			padding: 2px 6px;
			border-radius: 3px;
			cursor: pointer;
			font-size: 12px;
		}

		.btn-remover:hover {
			background-color: #c82333;
		}

		#total-carrinho {
			font-weight: bold;
			margin-top: 10px;
			padding-top: 10px;
			border-top: 1px solid #ddd;
		}

		#frete-info {
			font-size: 14px;
			margin-top: 5px;
			color: #666;
		}

		.frete-gratis {
			color: #28a745 !important;
			font-weight: bold;
		}

		#btn-comprar {
			width: 100%;
			margin-top: 10px;
			background-color: #28a745;
		}

		#btn-comprar:hover {
			background-color: #218838;
		}

		.btn-add-carrinho {
			background-color: #28a745;
		}

		.btn-add-carrinho:hover {
			background-color: #218838;
		}

		/* Modal Styles */
		.modal {
			display: none;
			position: fixed;
			z-index: 1000;
			left: 0;
			top: 0;
			width: 100%;
			height: 100%;
			background-color: rgba(0, 0, 0, 0.5);
		}

		.modal-content {
			background-color: white;
			margin: 5% auto;
			padding: 20px;
			border-radius: 8px;
			width: 90%;
			max-width: 500px;
			box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
		}

		.modal-header {
			display: flex;
			justify-content: space-between;
			align-items: center;
			margin-bottom: 20px;
			padding-bottom: 10px;
			border-bottom: 1px solid #eee;
		}

		.close {
			color: #aaa;
			font-size: 28px;
			font-weight: bold;
			cursor: pointer;
		}

		.close:hover {
			color: black;
		}

		.form-group {
			margin-bottom: 15px;
		}

		.form-group label {
			display: block;
			margin-bottom: 5px;
			font-weight: bold;
			color: #333;
		}

		.form-row {
			display: flex;
			gap: 10px;
		}

		.form-row .form-group {
			flex: 1;
		}

		.loading {
			opacity: 0.6;
			pointer-events: none;
		}

		.error {
			color: #dc3545;
			font-size: 12px;
			margin-top: 5px;
		}

		.success {
			color: #28a745;
			font-size: 12px;
			margin-top: 5px;
		}

		#checkout-form {
			max-height: 400px;
			overflow-y: auto;
		}
	</style>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
	<div class="container">
		<h1>Cadastro de Produtos</h1>

		<form method="post" action="<?= isset($produto_editando) ? '/produtos/atualizar/' . $produto_editando->id : '/produtos/salvar' ?>">
			<?php if (isset($produto_editando)): ?>
				<input type="hidden" name="_method" value="put">
			<?php endif; ?>

			<input type="text" name="nome" placeholder="Nome" value="<?= $produto_editando->nome ?? '' ?>" required>
			<input type="number" name="preco" placeholder="Preço" value="<?= $produto_editando->preco ?? '' ?>" required>
			<input type="text" name="variacao" placeholder="Variação" value="<?= $produto_editando->variacao ?? '' ?>" required>
			<input type="number" name="estoque" placeholder="Estoque" value="<?= $produto_editando->quantidade ?? '' ?>" required>

			<button type="submit"><?= isset($produto_editando) ? 'Atualizar Produto' : 'Salvar Produto' ?></button>
		</form>

		<?php if (!empty($produtos)): ?>
			<?php foreach ($produtos as $produto): ?>
				<div class="produto-card">
					<strong><?= $produto->nome ?></strong><br>
					Preço: R$ <?= number_format($produto->preco, 2, ',', '.') ?><br>
					Variação: <?= $produto->variacao ?><br>
					Estoque: <?= (int) $produto->quantidade ?><br>
					<a href="/produtos/exibir/<?= $produto->id ?>">Editar</a> |
					<form method="post" action="/produtos/excluir/<?= $produto->id ?>" class="inline-form" onsubmit="return confirm('Deseja excluir este produto?')">
						<button type="submit">Excluir</button>
					</form> |
					<button class="btn-add-carrinho" onclick="adicionarAoCarrinho({
						id: <?= $produto->id ?>, 
						nome: '<?= addslashes($produto->nome) ?>', 
						preco: <?= $produto->preco ?>,
						variacao: '<?= addslashes($produto->variacao) ?>'
					})">
						Adicionar ao Carrinho
					</button>
				</div>
			<?php endforeach; ?>
		<?php else: ?>
			<p>Nenhum produto cadastrado.</p>
		<?php endif; ?>
	</div>

	<div id="carrinho">
		<h2>Carrinho</h2>
		<ul id="lista-carrinho"></ul>
		<div id="subtotal-carrinho">Subtotal: R$ 0,00</div>
		<div id="frete-info">Frete: R$ 0,00</div>
		<div id="total-carrinho">Total: R$ 0,00</div>
		<button id="btn-comprar" onclick="abrirCheckout()">Finalizar Compra</button>
	</div>

	<div id="checkout-modal" class="modal">
		<div class="modal-content">
			<div class="modal-header">
				<h2>Finalizar Pedido</h2>
				<span class="close" onclick="fecharCheckout()">&times;</span>
			</div>

			<form id="checkout-form">
				<div class="form-group">
					<label for="nome_solicitante">Nome Completo *</label>
					<input type="text" id="nome_solicitante" name="nome_solicitante" required>
				</div>

				<div class="form-group">
					<label for="email">E-mail *</label>
					<input type="email" id="email" name="email" required>
				</div>

				<div class="form-row">
					<div class="form-group">
						<label for="cep">CEP *</label>
						<input type="text" id="cep" name="cep" placeholder="00000-000" maxlength="9" required>
						<div id="cep-error" class="error"></div>
						<div id="cep-success" class="success"></div>
					</div>
				</div>

				<div class="form-row">
					<div class="form-group">
						<label for="cidade">Cidade</label>
						<input type="text" id="cidade" name="cidade" readonly>
					</div>
					<div class="form-group">
						<label for="uf">UF</label>
						<input type="text" id="uf" name="uf" readonly>
					</div>
				</div>

				<div class="form-group">
					<label for="numero">Número *</label>
					<input type="text" id="numero" name="numero" placeholder="Número da residência" required>
				</div>

				<div class="form-group">
					<label for="complemento">Complemento</label>
					<input type="text" id="complemento" name="complemento" placeholder="Apartamento, bloco, etc.">
				</div>

				<div style="margin-top: 20px; padding-top: 15px; border-top: 1px solid #eee;">
					<div><strong>Resumo do Pedido:</strong></div>
					<div id="resumo-pedido"></div>
				</div>

				<button type="submit" style="width: 100%; margin-top: 15px; background-color: #28a745;">
					Confirmar Pedido
				</button>
			</form>
		</div>
	</div>

	<script>
		let carrinho = [];

		function adicionarAoCarrinho(produto) {
			const item = carrinho.find(p => p.id === produto.id);
			if (item) {
				item.qtd += 1;
			} else {
				carrinho.push({
					...produto,
					qtd: 1
				});
			}
			atualizarCarrinho();
		}

		function removerDoCarrinho(produtoId) {
			const index = carrinho.findIndex(p => p.id === produtoId);
			if (index > -1) {
				if (carrinho[index].qtd > 1) {
					carrinho[index].qtd -= 1;
				} else {
					carrinho.splice(index, 1);
				}
			}
			atualizarCarrinho();
		}

		function calcularFrete(subtotal) {
			if (subtotal >= 200) return 0;
			if (subtotal >= 52 && subtotal <= 166.59) return 15;
			return 20;
		}

		function atualizarCarrinho() {
			const lista = document.getElementById('lista-carrinho');
			let subtotal = 0;

			lista.innerHTML = '';
			carrinho.forEach(item => {
				const itemTotal = item.preco * item.qtd;
				subtotal += itemTotal;

				lista.innerHTML += `
					<li>
						<span class="item-carrinho">
							${item.nome}<br>
							<small>${item.variacao} - x${item.qtd} - R$ ${itemTotal.toFixed(2)}</small>
						</span>
						<button class="btn-remover" onclick="removerDoCarrinho(${item.id})">-</button>
					</li>
				`;
			});

			const frete = calcularFrete(subtotal);
			const total = subtotal + frete;

			document.getElementById('subtotal-carrinho').textContent = `Subtotal: R$ ${subtotal.toFixed(2).replace('.', ',')}`;
			document.getElementById('frete-info').innerHTML = frete === 0 ?
				'<span class="frete-gratis">Frete: GRÁTIS!</span>' :
				`Frete: R$ ${frete.toFixed(2).replace('.', ',')}`;
			document.getElementById('total-carrinho').textContent = `Total: R$ ${total.toFixed(2).replace('.', ',')}`;
		}

		function abrirCheckout() {
			if (carrinho.length === 0) {
				alert('Carrinho vazio!');
				return;
			}
			atualizarResumo();
			document.getElementById('checkout-modal').style.display = 'block';
		}

		function fecharCheckout() {
			document.getElementById('checkout-modal').style.display = 'none';
		}

		function atualizarResumo() {
			let subtotal = 0;
			let html = '';

			carrinho.forEach(item => {
				const itemTotal = item.preco * item.qtd;
				subtotal += itemTotal;
				html += `<div style="display: flex; justify-content: space-between; margin: 5px 0;">
					<span>${item.nome} (x${item.qtd})</span>
					<span>R$ ${itemTotal.toFixed(2).replace('.', ',')}</span>
				</div>`;
			});

			const frete = calcularFrete(subtotal);
			const total = subtotal + frete;

			html += `<hr style="margin: 10px 0;">
				<div style="display: flex; justify-content: space-between;">
					<span>Subtotal:</span><span>R$ ${subtotal.toFixed(2).replace('.', ',')}</span>
				</div>
				<div style="display: flex; justify-content: space-between;">
					<span>Frete:</span><span>${frete === 0 ? 'GRÁTIS' : 'R$ ' + frete.toFixed(2).replace('.', ',')}</span>
				</div>
				<div style="display: flex; justify-content: space-between; font-weight: bold; margin-top: 10px;">
					<span>Total:</span><span>R$ ${total.toFixed(2).replace('.', ',')}</span>
				</div>`;

			document.getElementById('resumo-pedido').innerHTML = html;
		}

		document.getElementById('cep').addEventListener('input', function(e) {
			let valor = e.target.value.replace(/\D/g, '');
			if (valor.length <= 8) {
				valor = valor.replace(/^(\d{5})(\d)/, '$1-$2');
				e.target.value = valor;
			}
			if (valor.replace('-', '').length === 8) {
				buscarCEP(valor.replace('-', ''));
			}
		});

		function buscarCEP(cep) {
			if (cep.length !== 8) return;

			document.getElementById('cep-success').textContent = 'Buscando...';

			fetch(`https://viacep.com.br/ws/${cep}/json/`)
				.then(response => response.json())
				.then(data => {
					if (data.erro) {
						document.getElementById('cep-error').textContent = 'CEP não encontrado';
					} else {
						document.getElementById('cep-success').textContent = 'Endereço encontrado!';
						document.getElementById('cidade').value = data.localidade || '';
						document.getElementById('uf').value = data.uf || '';
					}
				})
				.catch(() => {
					document.getElementById('cep-error').textContent = 'Erro ao buscar CEP';
				});
		}

		document.getElementById('checkout-form').addEventListener('submit', function(e) {
			e.preventDefault();

			const formData = new FormData(this);
			const data = {
				nome_solicitante: formData.get('nome_solicitante'),
				email: formData.get('email'),
				cep: formData.get('cep').replace('-', ''),
				cidade: formData.get('cidade'),
				uf: formData.get('uf'),
				numero: formData.get('numero'),
				complemento: formData.get('complemento') || null,
				carrinho: JSON.stringify(carrinho.map(item => ({
					id: item.id,
					qtd: item.qtd
				})))
			}

			$.ajax({
				url: 'http://localhost:8000/pedidos/salvar',
				type: 'POST',
				data: data,
				success: function(response) {
					alert('Pedido realizado com sucesso!');
					carrinho = [];
					atualizarCarrinho();
					fecharCheckout();
				},
				error: function() {
					alert('Erro ao processar pedido. Tente novamente.');
				}
			});
		});

		window.onclick = function(event) {
			if (event.target === document.getElementById('checkout-modal')) {
				fecharCheckout();
			}
		}
	</script>

</body>

</html>