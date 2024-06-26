<?php

include_once("templates/header.php");

// Checa autenticação
require_once("models/User.php");
require_once("dao/UserDAO.php");
require_once("dao/MovieDAO.php");


// Verifica o token, se não for o correto redireciona para a home
$userDao = new UserDAO($conn, $BASE_URL);
$movieDao = new MovieDAO($conn, $BASE_URL);

$userData = $userDao->verifyToken(true);

$userMovies = $movieDao->getMoviesByUserId($userData->id);

?>

<div id="main-container" class="container-fluid">
  <h2 class="section-title">Dashboard</h2>
  <p class="section-description">Atualize ou adicione as informações dos filmes que você enviou</p>
  <div class="col-md-12" id="add-movie-container">
    <a href="<?= $BASE_URL ?>newmovie.php" class="btn card-btn">
      <i class="fas fa-plus"></i> Adicionar Filme
    </a>
  </div>
  <div class="col-md-12" id="movies-dashboard">
    <table class="table table-hover table-dark">
      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">Titulo</th>
          <th scope="col">Notas</th>
          <th scope="col" class="actions-column">Ações</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($userMovies as $movie) : ?>

          <tr>
            <td scope="row"><?= $movie->id ?></td>
            <td><a href="<?= $BASE_URL ?>movie.php?id=<?= $movie->id ?>" class="table-movie-title" style="text-decoration: none;"><?= $movie->title ?></a></td>
            <td><i class="fas fa-star"></i> 9</td>
            <td class="actions-column">
              <a href="<?= $BASE_URL ?>editmovie.php?id=<?= $movie->id ?>" class="edit-btn"><i class="far fa-edit"></i> Editar</a>
              <form action="<?= $BASE_URL ?>movie_process.php" method="POST">
                <input type="hidden" name="type" value="delete">
                <input type="hidden" name="id" value="<?= $movie->id ?>">
                <button type="submit" class="delete-btn">
                  <i class="fas fa-times"></i> Deletar
                </button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>

      </tbody>
    </table>
  </div>
</div>


<?php
require_once("templates/footer.php");
?>