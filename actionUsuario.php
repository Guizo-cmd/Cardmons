<?php include "header.php" ?>

    <?php

        // Verifica o método de requisição do servidor
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            // Define o bloco de variáveis para armazenar as informações recebidas do formulário
            $nome = $email = $senha = $confirmarSenha = "";

            // Variável booleana para controle de erros de preenchimento
            $erroPreenchimento = false;

            // Validação do campo nome
            if (empty($_POST["nome"])) {
                echo "<div class='alert alert-warning text-center'>
                        <i class='bi bi-exclamation-triangle me-1'></i>
                        O campo <strong>NOME</strong> é obrigatório!
                      </div>";
                $erroPreenchimento = true;
            } else {
                // Filtra e armazena o valor na variável
                $nome = filtrar_entrada($_POST["nome"]);

                // Utiliza preg_match() para verificar se há apenas letras e espaços no nome
                if (!preg_match('/^[\p{L} ]+$/u', $nome)) {
                    echo "<div class='alert alert-warning text-center'>
                            <i class='bi bi-exclamation-triangle me-1'></i>
                            O campo <strong>NOME</strong> deve conter apenas letras!
                          </div>";
                    $erroPreenchimento = true;
                }

                // Verifica comprimento mínimo
                if (strlen($nome) < 3) {
                    echo "<div class='alert alert-warning text-center'>
                            <i class='bi bi-exclamation-triangle me-1'></i>
                            O campo <strong>NOME</strong> deve ter pelo menos 3 caracteres!
                          </div>";
                    $erroPreenchimento = true;
                }
            }

            // Validação do campo e-mail
            if (empty($_POST["email"])) {
                echo "<div class='alert alert-warning text-center'>
                        <i class='bi bi-exclamation-triangle me-1'></i>
                        O campo <strong>E-MAIL</strong> é obrigatório!
                      </div>";
                $erroPreenchimento = true;
            } else {
                $email = filtrar_entrada($_POST["email"]);

                // Valida formato do e-mail
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    echo "<div class='alert alert-warning text-center'>
                            <i class='bi bi-exclamation-triangle me-1'></i>
                            O <strong>E-MAIL</strong> informado não é válido!
                          </div>";
                    $erroPreenchimento = true;
                } else {
                    // Verifica se o e-mail já está cadastrado no banco de dados
                    include "conexaoBD.php";

                    $verificarEmail = "SELECT email FROM usuario WHERE email LIKE '$email' ";
                    $res = mysqli_query($conn, $verificarEmail) or die("Erro ao verificar o e-mail!");

                    if (mysqli_num_rows($res) > 0) {
                        echo "<div class='alert alert-warning text-center'>
                                <i class='bi bi-exclamation-triangle me-1'></i>
                                O e-mail <strong>$email</strong> já está cadastrado no sistema!
                              </div>";
                        $erroPreenchimento = true;
                    }
                }
            }

            // Validação do campo senha
            if (empty($_POST["senha"])) {
                echo "<div class='alert alert-warning text-center'>
                        <i class='bi bi-exclamation-triangle me-1'></i>
                        O campo <strong>SENHA</strong> é obrigatório!
                      </div>";
                $erroPreenchimento = true;
            } else {
                // Armazena a senha já em MD5
                $senha = md5(filtrar_entrada($_POST["senha"]));

                // Verifica comprimento mínimo (antes do MD5)
                if (strlen($_POST["senha"]) < 4) {
                    echo "<div class='alert alert-warning text-center'>
                            <i class='bi bi-exclamation-triangle me-1'></i>
                            A <strong>SENHA</strong> deve ter pelo menos 4 caracteres!
                          </div>";
                    $erroPreenchimento = true;
                }
            }

            // Validação do campo confirmar senha
            if (empty($_POST["confirmarSenha"])) {
                echo "<div class='alert alert-warning text-center'>
                        <i class='bi bi-exclamation-triangle me-1'></i>
                        O campo <strong>CONFIRMAR SENHA</strong> é obrigatório!
                      </div>";
                $erroPreenchimento = true;
            } else {
                // Aplica MD5 e compara com a senha
                $confirmarSenha = md5(filtrar_entrada($_POST["confirmarSenha"]));

                if ($senha != $confirmarSenha) {
                    echo "<div class='alert alert-warning text-center'>
                            <i class='bi bi-exclamation-triangle me-1'></i>
                            As <strong>SENHAS</strong> informadas são diferentes!
                          </div>";
                    $erroPreenchimento = true;
                }
            }

            // Se NÃO houver erro de preenchimento, realiza a inserção no BD
            if (!$erroPreenchimento) {

                // Data e hora do cadastro
                $dataCadastro = date("Y-m-d H:i:s");

                // Query para inserção do usuário na tabela usuario
                $inserirUsuario = "INSERT INTO usuario (nome, email, senha, tipo, data_cadastro)
                                   VALUES ('$nome', '$email', '$senha', 'cliente', '$dataCadastro')";

                include "conexaoBD.php";

                // Executa a query e exibe mensagem de resultado
                if (mysqli_query($conn, $inserirUsuario)) {

                    echo "
                        <div class='container'>
                            <div class='alert alert-success text-center'>
                                <i class='bi bi-check-circle me-1'></i>
                                <strong>USUÁRIO</strong> cadastrado com sucesso!
                            </div>
                            <div class='container mt-3'>
                                <table class='table table-bordered'>
                                    <tr>
                                        <th>NOME</th>
                                        <td>$nome</td>
                                    </tr>
                                    <tr>
                                        <th>E-MAIL</th>
                                        <td>$email</td>
                                    </tr>
                                    <tr>
                                        <th>TIPO</th>
                                        <td>Cliente</td>
                                    </tr>
                                    <tr>
                                        <th>DATA DE CADASTRO</th>
                                        <td>" . date('d/m/Y H:i', strtotime($dataCadastro)) . "</td>
                                    </tr>
                                </table>
                                <div class='text-center mt-3 mb-5'>
                                    <a href='formLogin.php' class='btn btn-warning fw-bold text-dark'>
                                        <i class='bi bi-box-arrow-in-right me-1'></i> Fazer Login
                                    </a>
                                </div>
                            </div>
                        </div>
                    ";

                } else {
                    echo "<div class='alert alert-danger text-center'>
                            <i class='bi bi-x-circle me-1'></i>
                            Erro ao tentar cadastrar o <strong>USUÁRIO</strong> no banco de dados!
                          </div>";
                }
            }

        } else {
            // Redireciona para o formulário se acessado diretamente sem POST
            header("Location: formUsuario.php");
        }

        // Função para filtrar entrada de dados
        function filtrar_entrada($dado) {
            $dado = trim($dado);         // Remove espaços desnecessários
            $dado = stripslashes($dado); // Remove barras invertidas
            $dado = htmlspecialchars($dado); // Converte caracteres especiais em entidades HTML
            return $dado;
        }

    ?>

<?php include "footer.php" ?>
