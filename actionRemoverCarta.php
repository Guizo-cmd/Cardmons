<?php

    session_start();

    // Verifica se o usuário está logado e é administrador
    if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true || $_SESSION['tipoUsuario'] != 'administrador') {
        header("Location: formLogin.php");
        exit();
    }

    // Verifica se o ID da carta foi informado via GET
    if (isset($_GET['id_carta'])) {

        $idCarta = (int) $_GET['id_carta'];

        include "conexaoBD.php";

        // Query para remover a carta do banco de dados
        $removerCarta = "DELETE FROM carta_pokemon WHERE id_carta = $idCarta";

        if (mysqli_query($conn, $removerCarta)) {
            // Redireciona com mensagem de sucesso
            header("Location: gerenciarCartas.php?msg=removida");
            exit();
        } else {
            header("Location: gerenciarCartas.php?msg=erro");
            exit();
        }

    } else {
        header("Location: gerenciarCartas.php");
        exit();
    }

?>
