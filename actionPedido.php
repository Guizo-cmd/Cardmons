<?php include "header.php" ?>

    <?php

        // Verifica se o usuário está logado
        if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
            header("Location: formLogin.php");
            exit();
        }

        // Verifica o método de requisição do servidor
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $erroPreenchimento = false;

            // Validação: ID da carta
            if (empty($_POST['id_carta'])) {
                echo "<div class='alert alert-warning text-center'>Carta não identificada!</div>";
                $erroPreenchimento = true;
            } else {
                $idCarta = (int) $_POST['id_carta'];
            }

            // Validação: Quantidade
            if (empty($_POST['quantidade']) || (int) $_POST['quantidade'] < 1) {
                echo "<div class='alert alert-warning text-center'>Informe uma <strong>QUANTIDADE</strong> válida!</div>";
                $erroPreenchimento = true;
            } else {
                $quantidade = (int) $_POST['quantidade'];
            }

            // Validação: Endereço de entrega
            if (empty($_POST['enderecoEntrega'])) {
                echo "<div class='alert alert-warning text-center'>O campo <strong>ENDEREÇO DE ENTREGA</strong> é obrigatório!</div>";
                $erroPreenchimento = true;
            } else {
                $enderecoEntrega = filtrar_entrada($_POST['enderecoEntrega']);
            }

            // Validação: Forma de pagamento
            if (empty($_POST['formaPagamento'])) {
                echo "<div class='alert alert-warning text-center'>Selecione a <strong>FORMA DE PAGAMENTO</strong>!</div>";
                $erroPreenchimento = true;
            } else {
                $formaPagamento = filtrar_entrada($_POST['formaPagamento']);
            }

            if (!$erroPreenchimento) {

                include "conexaoBD.php";

                // Busca os dados atuais da carta para verificar estoque e preço
                $buscarCarta = "SELECT * FROM carta_pokemon WHERE id_carta = $idCarta AND estoque > 0";
                $resCarta    = mysqli_query($conn, $buscarCarta);

                if (mysqli_num_rows($resCarta) == 0) {
                    echo "<div class='alert alert-danger text-center'>
                            Carta não disponível ou sem estoque!
                          </div>";
                } else {
                    $carta = mysqli_fetch_assoc($resCarta);

                    // Verifica se a quantidade solicitada é possível
                    if ($quantidade > $carta['estoque']) {
                        echo "<div class='alert alert-warning text-center'>
                                Quantidade solicitada (<strong>$quantidade</strong>) maior que o estoque disponível
                                (<strong>{$carta['estoque']}</strong>)!
                              </div>";
                    } else {

                        $precoUnit  = $carta['preco'];
                        $subtotal   = $precoUnit * $quantidade;
                        $valorTotal = $subtotal;
                        $dataPedido = date("Y-m-d H:i:s");
                        $idUsuario  = $_SESSION['idUsuario'];

                        // Inicia transação para garantir consistência
                        mysqli_begin_transaction($conn);

                        try {

                            // 1. Insere o Pedido
                            $inserirPedido = "INSERT INTO pedido (data_pedido, status, valor_total, id_usuario)
                                             VALUES ('$dataPedido', 'pendente', $valorTotal, $idUsuario)";

                            if (!mysqli_query($conn, $inserirPedido)) {
                                throw new Exception("Erro ao criar pedido: " . mysqli_error($conn));
                            }

                            $idPedido = mysqli_insert_id($conn); // Recupera o ID do pedido inserido

                            // 2. Insere o Item do Pedido
                            $inserirItem = "INSERT INTO item_pedido (quantidade, preco_unit, subtotal, id_pedido, id_carta)
                                           VALUES ($quantidade, $precoUnit, $subtotal, $idPedido, $idCarta)";

                            if (!mysqli_query($conn, $inserirItem)) {
                                throw new Exception("Erro ao criar item do pedido: " . mysqli_error($conn));
                            }

                            // 3. Atualiza o estoque da carta
                            $novoEstoque     = $carta['estoque'] - $quantidade;
                            $atualizarEstoque = "UPDATE carta_pokemon SET estoque = $novoEstoque WHERE id_carta = $idCarta";

                            if (!mysqli_query($conn, $atualizarEstoque)) {
                                throw new Exception("Erro ao atualizar estoque: " . mysqli_error($conn));
                            }

                            // Confirma a transação
                            mysqli_commit($conn);

                            // Exibe confirmação do pedido
                            echo "
                                <div class='container'>
                                    <div class='alert alert-success text-center'>
                                        <i class='bi bi-check-circle me-1'></i>
                                        <strong>PEDIDO</strong> realizado com sucesso!
                                    </div>
                                    <div class='card shadow-sm border-0 mt-3 mb-5'>
                                        <div class='card-header bg-dark text-warning text-center py-3'>
                                            <h5 class='mb-0'>
                                                <i class='bi bi-receipt me-2'></i> Resumo do Pedido #$idPedido
                                            </h5>
                                        </div>
                                        <div class='card-body p-4'>
                                            <div class='d-flex align-items-center mb-3 p-3 bg-light rounded'>
                                                <img src='" . htmlspecialchars($carta['imagem_url']) . "'
                                                     style='width:70px; height:70px; object-fit:cover; border-radius:6px;'
                                                     class='me-3'>
                                                <div>
                                                    <strong>" . htmlspecialchars($carta['nome']) . "</strong><br>
                                                    <small class='text-muted'>" . htmlspecialchars($carta['tipo']) . " &bull; " . htmlspecialchars($carta['raridade']) . "</small>
                                                </div>
                                            </div>
                                            <table class='table table-bordered'>
                                                <tr><th>Quantidade</th><td>$quantidade unidade(s)</td></tr>
                                                <tr><th>Preço unitário</th><td>R$ " . number_format($precoUnit, 2, ',', '.') . "</td></tr>
                                                <tr><th>Total</th><td class='fw-bold text-success'>R$ " . number_format($valorTotal, 2, ',', '.') . "</td></tr>
                                                <tr><th>Endereço de Entrega</th><td>$enderecoEntrega</td></tr>
                                                <tr><th>Forma de Pagamento</th><td>$formaPagamento</td></tr>
                                                <tr><th>Status</th><td><span class='badge bg-warning text-dark'>Pendente</span></td></tr>
                                                <tr><th>Data do Pedido</th><td>" . date('d/m/Y H:i', strtotime($dataPedido)) . "</td></tr>
                                            </table>
                                            <div class='text-center mt-3 d-flex gap-2 justify-content-center'>
                                                <a href='meusPedidos.php' class='btn btn-warning fw-bold text-dark'>
                                                    <i class='bi bi-bag me-1'></i> Meus Pedidos
                                                </a>
                                                <a href='index.php' class='btn btn-outline-dark'>
                                                    <i class='bi bi-grid me-1'></i> Continuar Comprando
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            ";

                        } catch (Exception $e) {
                            // Reverte a transação em caso de erro
                            mysqli_rollback($conn);
                            echo "<div class='alert alert-danger text-center'>
                                    <i class='bi bi-x-circle me-1'></i>
                                    Erro ao processar o pedido. Tente novamente!
                                  </div>";
                        }
                    }
                }
            }

        } else {
            header("Location: index.php");
        }

        // Função para filtrar entrada de dados
        function filtrar_entrada($dado) {
            $dado = trim($dado);
            $dado = stripslashes($dado);
            $dado = htmlspecialchars($dado);
            return $dado;
        }

    ?>

<?php include "footer.php" ?>
