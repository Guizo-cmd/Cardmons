<?php include "header.php" ?>

    <?php

        // Verifica se o usuário está logado e é administrador
        if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true || $_SESSION['tipoUsuario'] != 'administrador') {
            header("Location: formLogin.php");
            exit();
        }

        // Verifica o método de requisição do servidor
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            // Define o bloco de variáveis para armazenar as informações recebidas do formulário
            $nome = $tipo = $raridade = $edicao = $preco = $estoque = $imagemUrl = "";

            // Variável booleana para controle de erros de preenchimento
            $erroPreenchimento = false;
            $erroUpload        = false;

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

            // Início da validação da imagem
            $diretorio    = "assets/img/";
            $erroUpload   = false;

            // Verifica se o arquivo foi enviado
            if ($_FILES["imagem"]["size"] != 0) {

                $nomeArquivo  = basename($_FILES['imagem']['name']);
                $imagemUrl    = $diretorio . $nomeArquivo;
                $tipoDaImagem = strtolower(pathinfo($imagemUrl, PATHINFO_EXTENSION));

                // Verifica o tamanho (máx. 5MB)
                if ($_FILES["imagem"]["size"] > 5000000) {
                    echo "<div class='alert alert-warning text-center'>A <strong>IMAGEM</strong> deve ter tamanho máximo de 5MB!</div>";
                    $erroUpload = true;
                }

                // Verifica os formatos permitidos
                if ($tipoDaImagem != "jpg" && $tipoDaImagem != "jpeg" && $tipoDaImagem != "png" && $tipoDaImagem != "webp" && $tipoDaImagem != "jfif") {
                    echo "<div class='alert alert-warning text-center'>A <strong>IMAGEM</strong> deve estar nos formatos JPG, JPEG, PNG, WEBP ou JFIF!</div>";
                    $erroUpload = true;
                }

                // Move o arquivo para o diretório de imagens
                if (!move_uploaded_file($_FILES["imagem"]["tmp_name"], $imagemUrl)) {
                    echo "<div class='alert alert-warning text-center'>Erro ao mover a <strong>IMAGEM</strong> para o diretório $diretorio!</div>";
                    $erroUpload = true;
                }

            } else {
                echo "<div class='alert alert-warning text-center'>O campo <strong>IMAGEM</strong> é obrigatório!</div>";
                $erroUpload = true;
            }

            // Se NÃO houver erros, realiza a inserção no BD
            if (!$erroPreenchimento && !$erroUpload) {

                include "conexaoBD.php";

                // Query para inserção da carta no banco de dados
                $inserirCarta = "INSERT INTO carta_pokemon (nome, tipo, raridade, edicao, preco, estoque, imagem_url)
                                 VALUES ('$nome', '$tipo', '$raridade', '$edicao', $preco, $estoque, '$imagemUrl')";

                if (mysqli_query($conn, $inserirCarta)) {

                    echo "
                        <div class='container'>
                            <div class='alert alert-success text-center'>
                                <i class='bi bi-check-circle me-1'></i>
                                <strong>CARTA</strong> cadastrada com sucesso!
                            </div>
                            <div class='container mt-3'>
                                <div class='text-center mb-3'>
                                    <img src='$imagemUrl' style='max-height:200px; object-fit:contain;'
                                         class='img-thumbnail' alt='$nome'>
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
                                    <a href='formCarta.php' class='btn btn-warning fw-bold text-dark'>
                                        <i class='bi bi-plus-circle me-1'></i> Adicionar outra Carta
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
                            Erro ao inserir a <strong>CARTA</strong> no banco de dados!
                          </div>" . mysqli_error($conn);
                }
            }

        } else {
            // Redireciona se acessado sem POST
            header("Location: formCarta.php");
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
