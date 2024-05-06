<?php

require_once("templates/header.php");
require_once("dao/MovieDAO.php");

$movieDao = new MovieDAO($conn, $BASE_URL);

$latestMovies = $movieDao->getLatestMovies();
$actionMovies = $movieDao->getMoviesByCategory("Ação"); // Você precisa preencher $actionMovies com filmes de ação.
$fantasyfictionMovies = $movieDao->getMoviesByCategory("Fantasia / Ficção"); // Você precisa preencher $fantasyfictionMovies com filmes de ficção/fantasia.
$dramaMovies = $movieDao->getMoviesByCategory("Drama");
?>
<div id="main-container" class="container-fluid">
  <h2 class="section-title">Filmes novos</h2>
  <p class="section-description">Veja as críticas dos últimos filmes adicionados no MovieStar.</p>
  <div class="movies-container">
    <?php if (count($latestMovies) > 0) : ?>
      <?php foreach ($latestMovies as $movie) : ?>
        <?php require("templates/movie_card.php"); ?>
      <?php endforeach; ?>
    <?php else : ?>
      <p class="empty-list">Ainda não há filmes cadastrados!</p>
    <?php endif; ?>
  </div>
  <h2 class="section-title">Ação</h2>
  <p class="section-description">Veja os melhores filmes de ação.</p>
  <div class="movies-container">
    <?php if (count($actionMovies) > 0) : ?>
      <?php foreach ($actionMovies as $movie) : ?>
        <?php require("templates/movie_card.php"); ?>
      <?php endforeach; ?>
    <?php else : ?>
      <p class="empty-list">Ainda não há filmes de ação cadastrados!</p>
    <?php endif; ?>
  </div>
  <h2 class="section-title">Ficção / Fantasia</h2>
  <p class="section-description">Veja os melhores filmes de Ficção / Fantasia.</p>
  <div class="movies-container">
    <?php if (count($fantasyfictionMovies) > 0) : ?>
      <?php foreach ($fantasyfictionMovies as $movie) : ?>
        <?php require("templates/movie_card.php"); ?>
      <?php endforeach; ?>
    <?php else : ?>
      <p class="empty-list">Ainda não há filmes de ficção/fantasia cadastrados!</p>
    <?php endif; ?>
  </div>
  <h2 class="section-title">Drama</h2>
  <p class="section-description">Veja os melhores filmes de drama.</p>
  <div class="movies-container">
    <?php if (count($dramaMovies) > 0) : ?>
      <?php foreach ($dramaMovies as $movie) : ?>
        <?php require("templates/movie_card.php"); ?>
      <?php endforeach; ?>
    <?php else : ?>
      <p class="empty-list">Ainda não há filmes de drama cadastrados!</p>
    <?php endif; ?>
  </div>
</div>

<?php
require_once("templates/footer.php");
?>