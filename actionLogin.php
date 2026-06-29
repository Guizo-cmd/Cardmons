<?php

    include "conexaoBD.php"; // Inclui o arquivo de conexão com o BD

    session_start(); // Função para iniciar uma sessão

    // Filtra as entradas recebidas do formulário
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $senha = mysqli_real_escape_string($conn, $_POST['senha']);

    // Query para buscar o usuário com e-mail e senha fornecidos (senha em MD5)
    $buscarLogin = "SELECT *
                    FROM usuario
                    WHERE email       = '$email'
                    AND   senha       = md5('$senha') ";

    // Executa a Query
    $efetuarLogin = mysqli_query($conn, $buscarLogin);

    // Verifica se encontrou um usuário correspondente
    if ($registro = mysqli_fetch_assoc($efetuarLogin)) {

        // Cria as variáveis de sessão
        $_SESSION['idUsuario']   = $registro['id_usuario'];
        $_SESSION['nomeUsuario'] = $registro['nome'];
        $_SESSION['emailUsuario']= $registro['email'];
        $_SESSION['tipoUsuario'] = $registro['tipo'];
        $_SESSION['logado']      = true;

        // Redireciona para a página inicial
        header("Location: index.php");
        exit();

    } else {
        // Redireciona de volta ao formulário com parâmetro de erro
        header("Location: formLogin.php?erroLogin=dadosInvalidos");
        exit();
    }

?>
