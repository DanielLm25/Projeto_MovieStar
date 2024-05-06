<?php
require_once("models/Movie.php");
require_once("models/Message.php");
require_once("dao/ReviewDAO.php");


class MovieDAO implements MovieDAOInterface
{

  private $conn;
  private $url;
  private $message;


  public function __construct(PDO $conn, $url)
  {

    $this->conn = $conn;
    $this->url = $url;
    $this->message = new Message($url);
  }

  public function buildMovie($data)
  {

    $movie = new Movie();

    $movie->id = $data["id"];
    $movie->title = $data["title"];
    $movie->description = $data["description"];
    $movie->image = $data["image"];
    $movie->trailer = $data["trailer"];
    $movie->category = $data["category"];
    $movie->length = $data["length"];
    $movie->users_id = $data["users_id"];

    $reviewDao = new ReviewDAO($this->conn, $this->url);

    $rating = $reviewDao->getRatings($movie->id);

    $movie->rating = $rating;
    return $movie;
  }
  public function findAll()
  {
  }
  public function getLatestMovies()
  {

    $movies = [];

    $stmt = $this->conn->query("SELECT * FROM movies ORDER BY id DESC");

    $stmt->execute();

    if ($stmt->rowCount() > 0) {

      $moviesArray = $stmt->fetchAll();
      foreach ($moviesArray as $movie) {
        $movies[] = $this->buildMovie($movie);
      }
    }
    return $movies;
  }
  public function getMoviesByCategory($category)
  {
    $movies = [];

    // Preparar a consulta SQL com um marcador de posição (:category)
    $stmt = $this->conn->prepare("SELECT * FROM movies WHERE category=:category ORDER BY id DESC");

    // Vincular o parâmetro $category ao marcador de posição na consulta SQL
    $stmt->bindParam(":category", $category);

    // Executar a consulta preparada
    $stmt->execute();

    // Verificar se há linhas retornadas pela consulta
    if ($stmt->rowCount() > 0) {
      // FetchAll para obter todas as linhas retornadas
      $moviesArray = $stmt->fetchAll();
      // Iterar sobre os resultados e construir objetos Movie
      foreach ($moviesArray as $movie) {
        $movies[] = $this->buildMovie($movie);
      }
    }
    // Retornar a lista de filmes
    return $movies;
  }

  public function getMoviesByUserId($id)
  {

    $movies = [];

    // Preparar a consulta SQL com um marcador de posição (:category)
    $stmt = $this->conn->prepare("SELECT * FROM movies WHERE users_id=:users_id");

    // Vincular o parâmetro $category ao marcador de posição na consulta SQL
    $stmt->bindParam(":users_id", $id);

    // Executar a consulta preparada
    $stmt->execute();

    // Verificar se há linhas retornadas pela consulta
    if ($stmt->rowCount() > 0) {
      // FetchAll para obter todas as linhas retornadas
      $moviesArray = $stmt->fetchAll();
      // Iterar sobre os resultados e construir objetos Movie
      foreach ($moviesArray as $movie) {
        $movies[] = $this->buildMovie($movie);
      }
    }
    // Retornar a lista de filmes
    return $movies;
  }

  public function findById($id)
  {

    $movie = [];

    // Preparar a consulta SQL com um marcador de posição (:category)
    $stmt = $this->conn->prepare("SELECT * FROM movies WHERE id=:id");

    // Vincular o parâmetro $category ao marcador de posição na consulta SQL
    $stmt->bindParam(":id", $id);

    // Executar a consulta preparada
    $stmt->execute();

    // Verificar se há linhas retornadas pela consulta
    if ($stmt->rowCount() > 0) {
      // FetchAll para obter todas as linhas retornadas
      $movieData = $stmt->fetch();

      $movie = $this->buildMovie($movieData);

      return $movie;
    } else {
      return false;
    }
    // Retornar a lista de filmes
  }
  public function findByTitle($title)
  {
    $movies = [];

    // Preparar a consulta SQL com um marcador de posição (:category)
    $stmt = $this->conn->prepare("SELECT * FROM movies WHERE title LIKE :title");

    // Vincular o parâmetro $category ao marcador de posição na consulta SQL
    $stmt->bindValue(":title", '%' . $title . '%');

    // Executar a consulta preparada
    $stmt->execute();

    // Verificar se há linhas retornadas pela consulta
    if ($stmt->rowCount() > 0) {
      // FetchAll para obter todas as linhas retornadas
      $moviesArray = $stmt->fetchAll();
      // Iterar sobre os resultados e construir objetos Movie
      foreach ($moviesArray as $movie) {
        $movies[] = $this->buildMovie($movie);
      }
    }
    // Retornar a lista de filmes
    return $movies;
  }
  public function create(Movie $movie)
  {


    $stmt = $this->conn->prepare("INSERT INTO movies 
    (title, description, image, trailer, category, length, users_id) 
    VALUES (:title, :description, :image, :trailer, :category, :length, :users_id)");
    $stmt->bindParam(":title", $movie->title);
    $stmt->bindParam(":description", $movie->description);
    $stmt->bindParam(":image", $movie->image);
    $stmt->bindParam(":trailer", $movie->trailer);
    $stmt->bindParam(":category", $movie->category);
    $stmt->bindParam(":length", $movie->length);
    $stmt->bindParam(":users_id", $movie->users_id);
    $stmt->execute();

    $this->message->setMessage("Filme adicionado com sucesso!", "success", "index.php");
  }
  public function update(Movie $movie)
  {

    $stmt = $this->conn->prepare("UPDATE movies SET  
    title=:title, description=:description, image=:image, trailer=:trailer, category=:category, length=:length 
    WHERE id=:id");
    $stmt->bindParam(":title", $movie->title);
    $stmt->bindParam(":description", $movie->description);
    $stmt->bindParam(":image", $movie->image);
    $stmt->bindParam(":trailer", $movie->trailer);
    $stmt->bindParam(":category", $movie->category);
    $stmt->bindParam(":length", $movie->length);
    $stmt->bindParam(":id", $movie->id);
    $stmt->execute();

    $this->message->setMessage("Filme atualizado com sucesso!", "success", "index.php");
  }
  public function destroy($id)
  {

    $stmt = $this->conn->prepare("DELETE FROM movies WHERE id=:id");
    $stmt->bindParam(":id", $id);
    $stmt->execute();

    $this->message->setMessage("Filme removido com sucesso!", "success", "index.php");
  }
}
