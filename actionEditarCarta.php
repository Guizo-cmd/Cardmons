<?php include "header.php" ?>

    <?php

        // Verifica se o usuário está logado e é administrador
        if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true || $_SESSION['tipoUsuario'] != 'administrador') {
            header("Location: formLogin.php");
            exit();
        }

        // Verifica o método de requisição do servidor
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            // Variáveis de controle de erro
            $erroPreenchimento = false;
            $erroUpload        = false;

            // Verifica se o ID da carta foi recebido
            if (empty($_POST['id_carta'])) {
                echo "<div class='alert alert-warning text-center'>
                        <i class='bi bi-exclamation-triangle me-1'></i>
                        A carta não foi identificada!
                      </div>";
                $erroPreenchimento = true;
            } else {
                $idCarta    = (int) $_POST['id_carta'];
                $imagemAtual = filtrar_entrada($_POST['imagemAtual']);
            }

            // Validação do campo nome
            if (empty($_POST["nome"])) {
                echo "<div class='alert alert-warning text-center'>O campo <strong>NOME</strong> é obrigatório!</div>";
                $erroPreenchimento = true;
            } else {
                $nome = filtrar_entrada($_POST["nome"]);
            }

            // Validação do campo tipo
            if (empty($_POST["tipo"])) {
                echo "<div class='alert alert-warning text-center'>O campo <strong>TIPO</strong> é obrigatório!</div>";
                $erroPreenchimento = true;
            } else {
                $tipo = filtrar_entrada($_POST["tipo"]);
            }

            // Validação do campo raridade
            if (empty($_POST["raridade"])) {
                echo "<div class='alert alert-warning text-center'>O campo <strong>RARIDADE</strong> é obrigatório!</div>";
                $erroPreenchimento = true;
            } else {
                $raridade = filtrar_entrada($_POST["raridade"]);
            }

            // Validação do campo edicao
            if (empty($_POST["edicao"])) {
                echo "<div class='alert alert-warning text-center'>O campo <strong>EDIÇÃO</strong> é obrigatório!</div>";
                $erroPreenchimento = true;
            } else {
                $edicao = filtrar_entrada($_POST["edicao"]);
            }

            // Validação do campo preco
            if (empty($_POST["preco"]) || $_POST["preco"] <= 0) {
                echo "<div class='alert alert-warning text-center'>O campo <strong>PREÇO</strong> deve ser maior que zero!</div>";
                $erroPreenchimento = true;
            } else {
                $preco = (float) $_POST["preco"];
            }

            // Validação do campo estoque
            if (!isset($_POST["estoque"]) || $_POST["estoque"] < 0) {
                echo "<div class='alert alert-warning text-center'>O campo <strong>ESTOQUE</strong> não pode ser negativo!</div>";
                $erroPreenchimento = true;
            } else {
                $estoque = (int) $_POST["estoque"];
            }

            // Início da validação da imagem (campo opcional na edição)
            if ($_FILES["imagem"]["size"] != 0) {

                $diretorio    = "assets/img/";
                $nomeArquivo  = basename($_FILES['imagem']['name']);
                $imagemUrl    = $diretorio . $nomeArquivo;
                $tipoDaImagem = strtolower(pathinfo($imagemUrl, PATHINFO_EXTENSION));

                // Verifica tamanho máximo (5MB)
                if ($_FILES["imagem"]["size"] > 5000000) {
                    echo "<div class='alert alert-warning text-center'>A <strong>IMAGEM</strong> deve ter tamanho máximo de 5MB!</div>";
                    $erroUpload = true;
                }

                // Verifica formato da imagem
                if ($tipoDaImagem != "jpg" && $tipoDaImagem != "jpeg" && $tipoDaImagem != "png" && $tipoDaImagem != "webp") {
                    echo "<div class='alert alert-warning text-center'>A <strong>IMAGEM</strong> deve estar nos formatos JPG, JPEG, PNG ou WEBP!</div>";
                    $erroUpload = true;
                }

                // Move o arquivo para o diretório de imagens
                if (!move_uploaded_file($_FILES["imagem"]["tmp_name"], $imagemUrl)) {
                    echo "<div class='alert alert-warning text-center'>Erro ao mover a <strong>IMAGEM</strong>!</div>";
                    $erroUpload = true;
                }

            } else {
                // Mantém a imagem atual se nenhuma nova for enviada
                $imagemUrl = $imagemAtual;
            }

            // Se NÃO houver erros, realiza a atualização no BD
            if (!$erroPreenchimento && !$erroUpload) {

                include "conexaoBD.php";

                // Query para atualização dos dados da carta
                $editarCarta = "
                    UPDATE carta_pokemon
                    SET
                        nome       = '$nome',
                        tipo       = '$tipo',
                        raridade   = '$raridade',
                        edicao     = '$edicao',
                        preco      = $preco,
                        estoque    = $estoque,
                        imagem_url = '$imagemUrl'
                    WHERE id_carta = $idCarta
                ";

                if (mysqli_query($conn, $editarCarta)) {

                    echo "
                        <div class='container'>
                            <div class='alert alert-success text-center'>
                                <i class='bi bi-check-circle me-1'></i>
                                <strong>CARTA</strong> atualizada com sucesso!
                            </div>
                            <div class='container mt-3'>
                                <div class='text-center mb-3'>
                                    <img src='$imagemUrl' style='max-height:180px; object-fit:contain;'
                                         class='img-thumbnail'>
                                </div>
                                <table class='table table-bordered'>
                                    <tr><th>NOME</th><td>$nome</td></tr>
                                    <tr><th>TIPO</th><td>$tipo</td></tr>
                                    <tr><th>RARIDADE</th><td>$raridade</td></tr>
                                    <tr><th>EDIÇÃO</th><td>$edicao</td></tr>
                                    <tr><th>PREÇO</th><td>R$ " . number_format($preco, 2, ',', '.') . "</td></tr>
                                    <tr><th>ESTOQUE</th><td>$estoque unidade(s)</td></tr>
                                </table>
                                <div class='text-center mt-3 mb-5 d-flex gap-2 justify-content-center'>
                                    <a href='visualizarCarta.php?id_carta=$idCarta' class='btn btn-warning fw-bold text-dark'>
                                        <i class='bi bi-eye me-1'></i> Visualizar Carta
                                    </a>
                                    <a href='gerenciarCartas.php' class='btn btn-outline-dark'>
                                        <i class='bi bi-collection me-1'></i> Gerenciar Cartas
                                    </a>
                                </div>
                            </div>
                        </div>
                    ";

                } else {
                    echo "<div class='alert alert-danger text-center'>
                            Erro ao atualizar a <strong>CARTA</strong>!
                          </div>" . mysqli_error($conn);
                }
            }

        } else {
            // Redireciona se acessado diretamente sem POST
            header("Location: gerenciarCartas.php");
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
