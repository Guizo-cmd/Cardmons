<?php

    error_reporting(0); // Desabilita alertas de erros de execução
    session_start();

    // Verifica se há sessão ativa e carrega as variáveis de sessão
    if (isset($_SESSION['logado']) && $_SESSION['logado'] === true) {
        $idUsuario    = $_SESSION['idUsuario'];
        $nomeUsuario  = $_SESSION['nomeUsuario'];
        $emailUsuario = $_SESSION['emailUsuario'];
        $tipoUsuario  = $_SESSION['tipoUsuario'];

        // Usa a função explode para fragmentar o nome do usuário
        $nomeCompleto = explode(' ', $nomeUsuario);
        $primeiroNome = $nomeCompleto[0]; // Armazena o primeiro fragmento do nome
    }

?>

<!DOCTYPE html>
<html lang="pt-br">
    <?php
        // Configura o fuso horário para America/São Paulo
        date_default_timezone_set('America/Sao_Paulo');
    ?>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="Cardmons - A maior loja de cartas Pokémon" />
        <meta name="author" content="" />
        <title>Cardmons - Cartas Pokémon</title>
        <!-- Favicon -->
        <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
        <!-- Bootstrap Icons CDN -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
        <!-- Google Fonts: Press Start 2P para título temático -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
        <!-- Agency Theme CSS (inclui Bootstrap) -->
        <link href="css/styles.css" rel="stylesheet" />

        <style>
            /* Fonte temática para o título -->
            .fonte-pokemon {
                font-family: 'Press Start 2P', cursive;
                font-size: 1.6rem;
                color: #2A67A8;
                text-shadow: 2px 2px 0px #c00;
                letter-spacing: 1px;
            }

            /* Faixa diagonal de carta esgotada */
            .faixa-esgotado {
                position: absolute;
                top: 0;
                right: 0;
                width: 55%;
                background: #dc3545;
                color: white;
                text-align: center;
                font-weight: bold;
                font-size: 0.65rem;
                padding: 5px 0;
                z-index: 10;
                box-shadow: 0 2px 5px rgba(0,0,0,0.3);
            }

            /* Efeito hover nos cards */
            .card-hover {
                position: relative;
                transition: transform 0.2s ease, box-shadow 0.2s ease;
                cursor: pointer;
            }

            .card-hover:hover {
                transform: translateY(-5px);
                box-shadow: 0 8px 20px rgba(0,0,0,0.2);
            }

            /* Overlay ao passar o mouse */
            .card-overlay {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.6);
                color: white;
                display: flex;
                align-items: center;
                justify-content: center;
                font-weight: bold;
                font-size: 1rem;
                opacity: 0;
                transition: opacity 0.3s ease;
                border-radius: 0.375rem;
            }

            .card-hover:hover .card-overlay {
                opacity: 1;
            }

            /* Imagem em escala de cinza para cartas sem estoque */
            .imagem-esgotada {
                filter: grayscale(100%);
                opacity: 0.8;
            }

            /* Badge de raridade colorido */
            .badge-comum    { background-color: #6c757d; }
            .badge-incomum  { background-color: #198754; }
            .badge-rara     { background-color: #0d6efd; }
            .badge-ultra    { background-color: #6f42c1; }
            .badge-hiper    { background-color: #dc3545; }
            .badge-secreta  { background-color: #fd7e14; }

            body {
                font-family: 'Nunito', sans-serif;
            }

            /* Navbar customizada */
            #mainNav {
                background-color: #0C2B54 !important;
            }

            #mainNav .navbar-brand {
                color: #E65A5A !important;
            }

            #mainNav .nav-link {
                color: rgba(255,255,255,0.85) !important;
            }

            #mainNav .nav-link:hover {
                color: #FFF4D0 !important;
            }

            /* Header hero */
            header.masthead {
                background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
                padding: 80px 0 60px;
            }

            /* Padding bottom para não ficar atrás do footer fixo */
            body {
                padding-bottom: 60px;
            }
        </style>

    </head>
    <body id="page-top">

        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-dark fixed-top" id="mainNav">
            <div class="container">
                <a class="navbar-brand" href="index.php">
                    <span class="fonte-pokemon" style="font-size:1rem;">Cardmons</span>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive"
                        aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="bi bi-list"></i>
                </button>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php">
                                <i class="bi bi-house-fill me-1"></i> Início
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php#catalogo">
                                <i class="bi bi-grid-fill me-1"></i> Catálogo
                            </a>
                        </li>

                        <?php
                            // Exibe menu conforme o nível de acesso
                            if (isset($_SESSION['logado']) && $_SESSION['logado'] === true) {

                                if ($tipoUsuario == 'administrador') {
                                    // Menu do Administrador
                                    echo "
                                        <li class='nav-item dropdown'>
                                            <a class='nav-link dropdown-toggle' href='#' id='menuAdmin'
                                               role='button' data-bs-toggle='dropdown' aria-expanded='false'>
                                                <i class='bi bi-shield-fill me-1'></i> Admin
                                            </a>
                                            <ul class='dropdown-menu dropdown-menu-end' aria-labelledby='menuAdmin'>
                                                <li><a class='dropdown-item' href='formCarta.php'>
                                                    <i class='bi bi-plus-circle me-1'></i> Adicionar Carta
                                                </a></li>
                                                <li><hr class='dropdown-divider' /></li>
                                                <li><a class='dropdown-item' href='gerenciarCartas.php'>
                                                    <i class='bi bi-collection me-1'></i> Gerenciar Cartas
                                                </a></li>
                                                <li><a class='dropdown-item' href='gerenciarPedidos.php'>
                                                    <i class='bi bi-bag-check me-1'></i> Gerenciar Pedidos
                                                </a></li>
                                                <li><a class='dropdown-item' href='gerenciarUsuarios.php'>
                                                    <i class='bi bi-people me-1'></i> Gerenciar Usuários
                                                </a></li>
                                                <li><hr class='dropdown-divider' /></li>
                                                <li><a class='dropdown-item text-danger' href='logout.php'>
                                                    <i class='bi bi-box-arrow-right me-1'></i> Sair
                                                </a></li>
                                            </ul>
                                        </li>
                                    ";
                                } else {
                                    // Menu do Cliente
                                    echo "
                                        <li class='nav-item dropdown'>
                                            <a class='nav-link dropdown-toggle' href='#' id='menuCliente'
                                               role='button' data-bs-toggle='dropdown' aria-expanded='false'>
                                                <i class='bi bi-person-circle me-1'></i> $primeiroNome
                                            </a>
                                            <ul class='dropdown-menu dropdown-menu-end' aria-labelledby='menuCliente'>
                                                <li><a class='dropdown-item' href='meusPedidos.php'>
                                                    <i class='bi bi-bag me-1'></i> Meus Pedidos
                                                </a></li>
                                                <li><hr class='dropdown-divider' /></li>
                                                <li><a class='dropdown-item text-danger' href='logout.php'>
                                                    <i class='bi bi-box-arrow-right me-1'></i> Sair
                                                </a></li>
                                            </ul>
                                        </li>
                                    ";
                                }

                            } else {
                                // Menu de visitante (sem sessão)
                                echo "
                                    <li class='nav-item'>
                                        <a class='nav-link' href='formLogin.php'>
                                            <i class='bi bi-box-arrow-in-right me-1'></i> Login
                                        </a>
                                    </li>
                                    <li class='nav-item'>
                                        <a class='nav-link' href='formUsuario.php'>
                                            <i class='bi bi-person-plus me-1'></i> Cadastrar-se
                                        </a>
                                    </li>
                                ";
                            }
                        ?>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Header Hero (exibido apenas na página inicial) -->
        <?php if (basename($_SERVER['PHP_SELF']) == 'index.php'): ?>
        <header class="masthead">
            <div class="container text-center">
                <div style="background: url('assets/img/fundo.png') center center/cover no-repeat;"></div>
                <div class="masthead-subheading text-warning mb-3" style="font-size:0.8rem; letter-spacing:4px;">
                    BEM-VINDO AO
                </div>
                <div class="masthead-heading mb-4 fonte-pokemon">
                    Cardmons
                </div>
                <p class="text-white-50 mb-4" style="font-size:1.1rem;">
                    A maior loja de cartas Pokémon — encontre raridades, edições especiais e muito mais!
                </p>
                <a
                    class="btn btn-xl fw-bold text-white"
                    href="#catalogo"
                    style="background:#2A67A8; border-color:#2A67A8;">
                    Ver Catálogo
</a>
                </a>
            </div>
        </header>
        <?php else: ?>
        <!-- Espaçador para páginas internas (compensar navbar fixa) -->
        <div style="padding-top: 76px;"></div>
        <?php endif; ?>


        