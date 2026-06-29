<?php

    include "header.php";
    include "conexaoBD.php";

    // Verifica se o usuário está logado
    if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
        header("Location: formLogin.php");
        exit();
    }

    // Verifica se o ID da carta foi informado
    if (!isset($_GET['id_carta'])) {
        header("Location: index.php");
        exit();
    }

    $idCarta = (int) $_GET['id_carta'];

    // Busca os dados da carta
    $buscarCarta = "SELECT * FROM carta_pokemon WHERE id_carta = $idCarta AND estoque > 0";
    $resCarta    = mysqli_query($conn, $buscarCarta);

    if (mysqli_num_rows($resCarta) == 0) {
        echo "<div class='alert alert-danger text-center mt-5'>Carta não disponível para compra!</div>";
        include "footer.php";
        exit();
    }

    $carta = mysqli_fetch_assoc($resCarta);

?>

<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">

                <div class="card shadow-sm border-0 mt-3">
                    <div class="card-header text-center py-4"
                         style="background: linear-gradient(135deg, #1a1a2e, #0f3460);">
                        <h4 class="mb-0 text-warning">
                            <i class="bi bi-cart-fill me-2"></i> Confirmar Pedido
                        </h4>
                    </div>
                    <div class="card-body p-4">

                        <!-- Resumo da Carta -->
                        <div class="d-flex align-items-center mb-4 p-3 bg-light rounded">
                            <img src="<?php echo htmlspecialchars($carta['imagem_url']); ?>"
                                 alt="<?php echo htmlspecialchars($carta['nome']); ?>"
                                 style="width:80px; height:80px; object-fit:cover; border-radius:6px;"
                                 class="me-3">
                            <div>
                                <h5 class="fw-bolder mb-1">
                                    <?php echo htmlspecialchars($carta['nome']); ?>
                                </h5>
                                <p class="text-muted small mb-1">
                                    <?php echo htmlspecialchars($carta['tipo']); ?> &bull;
                                    <?php echo htmlspecialchars($carta['raridade']); ?>
                                </p>
                                <p class="text-success fw-bold mb-0">
                                    R$ <?php echo number_format($carta['preco'], 2, ',', '.'); ?>
                                </p>
                            </div>
                        </div>

                        <!-- Formulário de Pedido -->
                        <form action="actionPedido.php" method="POST" class="was-validated">

                            <!-- Campo oculto: ID da carta -->
                            <input type="hidden" name="id_carta" value="<?php echo $carta['id_carta']; ?>">

                            <!-- Quantidade -->
                            <div class="form-floating mb-3">
                                <input type="number" class="form-control" id="quantidade"
                                       name="quantidade" placeholder="Quantidade"
                                       min="1" max="<?php echo $carta['estoque']; ?>"
                                       value="1" required>
                                <label for="quantidade">
                                    <i class="bi bi-hash me-1"></i>
                                    Quantidade (máx. <?php echo $carta['estoque']; ?> un.)
                                </label>
                                <div class="invalid-feedback">Informe uma quantidade válida.</div>
                            </div>

                            <!-- Endereço de Entrega -->
                            <div class="form-floating mb-4">
                                <input type="text" class="form-control" id="enderecoEntrega"
                                       name="enderecoEntrega" placeholder="Endereço"
                                       maxlength="200" required>
                                <label for="enderecoEntrega">
                                    <i class="bi bi-geo-alt me-1"></i> Endereço de Entrega
                                </label>
                                <div class="invalid-feedback">Informe o endereço de entrega.</div>
                            </div>

                            <!-- Forma de Pagamento -->
                            <div class="form-floating mb-4">
                                <select class="form-select" id="formaPagamento"
                                        name="formaPagamento" required>
                                    <option value="" disabled selected>Selecione a forma de pagamento</option>
                                    <option value="Pix">Pix</option>
                                    <option value="Cartão de Crédito">Cartão de Crédito</option>
                                    <option value="Cartão de Débito">Cartão de Débito</option>
                                    <option value="Boleto">Boleto Bancário</option>
                                </select>
                                <label for="formaPagamento">
                                    <i class="bi bi-credit-card me-1"></i> Forma de Pagamento
                                </label>
                                <div class="invalid-feedback">Selecione a forma de pagamento.</div>
                            </div>

                            <!-- Total calculado via JS -->
                            <div class="alert alert-warning text-center fw-bold" id="totalPedido">
                                Total: R$ <?php echo number_format($carta['preco'], 2, ',', '.'); ?>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-warning fw-bold text-dark btn-lg flex-fill">
                                    <i class="bi bi-bag-check me-1"></i> Confirmar Pedido
                                </button>
                                <a href="visualizarCarta.php?id_carta=<?php echo $carta['id_carta']; ?>"
                                   class="btn btn-outline-secondary btn-lg">
                                    Cancelar
                                </a>
                            </div>

                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

<!-- Script para calcular total dinamicamente -->
<script>
    const precoCarta    = <?php echo $carta['preco']; ?>;
    const inputQtd      = document.getElementById('quantidade');
    const divTotal      = document.getElementById('totalPedido');

    // Atualiza o total ao mudar a quantidade
    inputQtd.addEventListener('input', function() {
        const qtd   = parseInt(this.value) || 1;
        const total = (precoCarta * qtd).toFixed(2).replace('.', ',');
        divTotal.textContent = `Total: R$ ${total.replace(/\B(?=(\d{3})+(?!\d))/g, '.')}`;
    });
</script>

<?php include "footer.php" ?>
