<?php

if (empty($movie->image)) {
  $movie->image = "movie_cover.jpg";
}

?>

<div class="card movie-card">
  <div class="card-img-top" style="background-image:url('<?= $BASE_URL ?>img/movies/<?= $movie->image ?>')"></div>
  <div class="card-body">
    <p class="card-raitig">
      <i class="fas fa-star"></i>
      <span class="raiting" style="color: white;"><?= $movie->rating ?></span>
    </p>
    <h5 class="card-title">
      <a style="text-decoration: none;" href="<?= $BASE_URL ?>movie.php?id=<?= $movie->id ?>"><?= $movie->title ?></a>
    </h5>
    <a href="<?= $BASE_URL ?>movie.php?id=<?= $movie->id ?>" class="btn btn-primary rate-btn">Avaliar</a>
    <a href="<?= $BASE_URL ?>movie.php?id=<?= $movie->id ?>" class="btn btn-primary card-btn">Conhecer</a>
  </div>
</div>