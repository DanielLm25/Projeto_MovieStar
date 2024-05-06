<?php

include_once("templates/header.php");

// Checa autenticação
require_once("models/User.php");
require_once("dao/UserDAO.php");
require_once("dao/MovieDAO.php");

// Verifica o token, se não for o correto redireciona para a home
$auth = new UserDAO($conn, $BASE_URL);
$userData = $auth->verifyToken();

$movieDao = new MovieDAO($conn, $BASE_URL);

$id = filter_input(INPUT_GET, "id");

if (empty($id)) {
  $this->message->setMessage("O filme não foi encontrado", "error", "index.php");
} else {
  $movie = $movieDao->findById($id);

  if (!$movie) {
    $this->message->setMessage("O filme não foi encontrado", "error", "index.php");
  }
}

if ($movie->image == "") {
  $movie->image = "movie_cover.jpg";
}

?>

<div id="main-container" class="container-fluid">
  <div class="row">
    <div class="col-md-6 offset-md-1">
      <h1><?= $movie->title ?></h1>
      <p class="page-description">Altere os dados do filme no formulário abaixo</p>
      <form id="edit-movie-form" action="<?= $BASE_URL ?>movie_process.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="type" value="update">
        <input type="hidden" name="id" value="<?= $movie->id ?>">

        <div class="form-group mb-4"> <!-- Adicionando espaço extra abaixo deste campo -->
          <label for="title">Título:</label>
          <input type="text" class="form-control" id="title" name="title" placeholder="Digite o título do filme" value="<?= $movie->title ?>">
        </div>
        <div class="form-group mb-4"> <!-- Adicionando espaço extra abaixo deste campo -->
          <label for="image">Imagem:</label>
          <input type="file" name="image" id="image" class="form-control-file">
        </div>
        <div class="form-group mb-4"> <!-- Adicionando espaço extra abaixo deste campo -->
          <label for="length">Duração:</label>
          <input type="text" class="form-control" id="length" name="length" placeholder="Digite a duração do filme" value="<?= $movie->length ?>">
        </div>
        <div class="form-group mb-4"> <!-- Adicionando espaço extra abaixo deste campo -->
          <label for="category">Categoria do filme:</label>
          <select class="form-control" name="category" id="category">
            <option value="">Selecione</option>
            <option value="Ação" <?= $movie->category === "Ação" ? "selected" : "" ?>>Ação</option>
            <option value="Drama" <?= $movie->category === "Drama" ? "selected" : "" ?>>Drama</option>
            <option value="Comédia" <?= $movie->category === "Comédia" ? "selected" : "" ?>>Comédia</option>
            <option value="Fantasia / Ficção" <?= $movie->category === "Fantasia / Ficção" ? "selected" : "" ?>>Fantasia / Ficção</option>
            <option value="Romance" <?= $movie->category === "Romance" ? "selected" : "" ?>>Romance</option>
          </select>
        </div>
        <div class="form-group mb-4"> <!-- Adicionando espaço extra abaixo deste campo -->
          <label for="trailer">Trailer:</label>
          <input type="text" class="form-control" id="trailer" name="trailer" placeholder="Insira o link do trailer" value="<?= $movie->trailer ?>">
        </div>
        <div class="form-group mb-4"> <!-- Adicionando espaço extra abaixo deste campo -->
          <label for="description">Descrição:</label>
          <textarea class="form-control" name="description" id="description" rows="5" placeholder="Descreva o filme..."><?= $movie->description ?></textarea>
        </div>
        <div class="form-group mt-4">
          <input type="submit" class="btn card-btn" value="Atualizar filme">
        </div>
      </form>
    </div>
    <div class="col-md-3">
      <div class="movie-image-container" style="background-image: url('<?= $BASE_URL ?>img/movies/<?= $movie->image ?>')"></div>
    </div>
  </div>
</div>

<?php

include_once("templates/footer.php");

?>