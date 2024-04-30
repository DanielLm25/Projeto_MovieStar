<?php

require_once("globals.php");
require_once("db.php");


$flassMessage = [];
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MovieStar</title>
  <link rel="shortcut icon" href="<?= $BASE_URL ?>img/moviestar.ico" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.css" integrity="sha512-VcyUgkobcyhqQl74HS1TcTMnLEfdfX6BbjhH8ZBjFU9YTwHwtoRtWSGzhpDVEJqtMlvLM2z3JIixUOu63PNCYQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="<?= $BASE_URL ?>CSS/styles.css">
</head>

<body>

  <header>
    <nav id="main-navbar" class="navbar navbar-expand-lg navbar-light">
      <div class="container-fluid">
        <a href="<?= $BASE_URL ?>" class="navbar-brand">
          <img src="<?= $BASE_URL ?>img/logo.svg" alt="MovieStar" id="logo" class="me-3">
          <span id="moviestar-title">MovieStar</span>
        </a>

        <form action="" method="GET" id="search-form" class="d-flex">
          <input type="text" name="q" id="search" class="form-control me-2" placeholder="Buscar Filmes" aria-label="Search">
          <button class="btn my-2 my-sm-0" type="submit">
            <i class="fas fa-search"></i>
          </button>
        </form>
        <div class="collapse navbar-collapse">
          <ul class="navbar-nav">
            <li class="nav-item">
              <a href="<?= $BASE_URL ?>auth.php" class="nav-link">Entrar / Cadastrar </a>
            </li>
          </ul>
        </div>
    </nav>
  </header>

  <?php if (!empty($flassMessage["msg"])) :  ?>
    <div class="msg-container">
      <p class="msg <?= $flassMessage["type"] ?>"><?= $flassMessage["msg"] ?></p>
    </div>
  <?php endif; ?>