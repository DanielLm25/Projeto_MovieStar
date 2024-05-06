<?php

require_once("db.php");
require_once("globals.php");
require_once("models/Movie.php");
require_once("models/Message.php");
require_once("dao/UserDAO.php");
require_once("dao/MovieDAO.php");

// Instanciando objetos necessários
$message = new Message($BASE_URL);
$userDao = new UserDAO($conn, $BASE_URL);
$movieDao = new MovieDAO($conn, $BASE_URL);

// Verificação do token de autenticação do usuário
$userData = $userDao->verifyToken();

// Recuperação do tipo de operação (create, update, delete)
$type = filter_input(INPUT_POST, "type");

// Verificação do tipo de operação
if ($type === "create") {
  // Recuperação dos dados do formulário
  $title = filter_input(INPUT_POST, "title");
  $description = filter_input(INPUT_POST, "description");
  $trailer = filter_input(INPUT_POST, "trailer");
  $category = filter_input(INPUT_POST, "category");
  $length = filter_input(INPUT_POST, "length");

  // Criação de um novo objeto Movie
  $movie = new Movie();

  // Verificação da presença dos campos obrigatórios
  if (!empty($title) && !empty($description) && !empty($category)) {
    // Atribuição dos valores aos atributos do objeto Movie
    $movie->title = $title;
    $movie->description = $description;
    $movie->trailer = $trailer;
    $movie->category = $category;
    $movie->length = $length; // Certifique-se de que o campo "length" está presente no formulário

    // Atribuição do ID do usuário atual ao filme
    $movie->users_id = $userData->id;

    // Processamento da imagem, se fornecida
    if (isset($_FILES["image"]) && !empty($_FILES["image"]["tmp_name"])) {
      $image = $_FILES["image"];
      $imageTypes = ["image/jpeg", "image/jpg", "image/png"];
      $jpgArray = ["image/jpeg", "image/jpg"];

      // Verificação do tipo da imagem
      if (in_array($image["type"], $imageTypes)) {
        if (in_array($image["type"], $jpgArray)) {
          $imageFile = imagecreatefromjpeg($image["tmp_name"]);
        } else {
          $imageFile = imagecreatefrompng($image["tmp_name"]);
        }

        // Geração de um nome único para a imagem
        $imageName = $movie->imageGenerateName();

        // Salvamento da imagem no diretório de destino
        imagejpeg($imageFile, "./img/movies/" . $imageName, 100);

        // Atribuição do nome da imagem ao objeto Movie
        $movie->image = $imageName;
      } else {
        $message->setMessage("Tipo inválido de imagem, insira png ou jpg!", "error", "back");
        exit;
      }
    }

    // Inserção do filme no banco de dados
    $movieDao->create($movie);
  } else {
    // Mensagem de erro caso campos obrigatórios estejam vazios
    $message->setMessage("Você precisa adicionar pelo menos: Título, Descrição e Categoria!", "error", "back");
  }
} elseif ($type === "delete") {
  // Operação de exclusão de filme
  // Recuperação do ID do filme a ser excluído
  $id = filter_input(INPUT_POST, "id");

  // Busca do filme no banco de dados pelo ID
  $movie = $movieDao->findById($id);

  if ($movie) {
    // Verificação da permissão do usuário para excluir o filme
    if ($movie->users_id === $userData->id) {
      // Exclusão do filme do banco de dados
      $movieDao->destroy($movie->id);
    } else {
      // Mensagem de erro caso o usuário não tenha permissão para excluir o filme
      $message->setMessage("Você não tem permissão para excluir este filme!", "error", "index.php");
    }
  } else {
    // Mensagem de erro caso o filme não seja encontrado no banco de dados
    $message->setMessage("Filme não encontrado!", "error", "index.php");
  }
} elseif ($type === "update") {
  // Operação de atualização de filme
  // Recuperação dos dados do formulário
  $title = filter_input(INPUT_POST, "title");
  $description = filter_input(INPUT_POST, "description");
  $trailer = filter_input(INPUT_POST, "trailer");
  $category = filter_input(INPUT_POST, "category");
  $length = filter_input(INPUT_POST, "length");
  $id = filter_input(INPUT_POST, "id");

  // Busca do filme no banco de dados pelo ID
  $movie = $movieDao->findById($id);

  if ($movie) {
    // Verificação da permissão do usuário para editar o filme
    if ($movie->users_id === $userData->id) {
      // Verificação da presença dos campos obrigatórios
      if (!empty($title) && !empty($description) && !empty($category)) {
        // Atribuição dos novos valores aos atributos do filme
        $movie->title = $title;
        $movie->description = $description;
        $movie->trailer = $trailer;
        $movie->category = $category;
        $movie->length = $length; // Certifique-se de que o campo "length" está presente no formulário

        // Processamento da nova imagem, se fornecida
        if (isset($_FILES["image"]) && !empty($_FILES["image"]["tmp_name"])) {
          $image = $_FILES["image"];
          $imageTypes = ["image/jpeg", "image/jpg", "image/png"];
          $jpgArray = ["image/jpeg", "image/jpg"];

          // Verificação do tipo da imagem
          if (in_array($image["type"], $imageTypes)) {
            if (in_array($image["type"], $jpgArray)) {
              $imageFile = imagecreatefromjpeg($image["tmp_name"]);
            } else {
              $imageFile = imagecreatefrompng($image["tmp_name"]);
            }
            // Geração de um novo nome único para a imagem
            $imageName = $movie->imageGenerateName();

            // Salvamento da nova imagem no diretório de destino
            imagejpeg($imageFile, "./img/movies/" . $imageName, 100);

            // Atribuição do novo nome da imagem ao filme
            $movie->image = $imageName;
          } else {
            $message->setMessage("Tipo inválido de imagem, insira png ou jpg!", "error", "back");
            exit;
          }
        }

        // Atualização dos dados do filme no banco de dados
        $movieDao->update($movie);
      } else {
        // Mensagem de erro caso campos obrigatórios estejam vazios
        $message->setMessage("Você precisa adicionar pelo menos: Título, Descrição e Categoria!", "error", "back");
      }
    } else {
      // Mensagem de erro caso o usuário não tenha permissão para editar o filme
      $message->setMessage("Você não tem permissão para editar este filme!", "error", "index.php");
    }
  } else {
    // Mensagem de erro caso o filme não seja encontrado no banco de dados
    $message->setMessage("Filme não encontrado!", "error", "index.php");
  }
} else {
  // Mensagem de erro para tipos de operação inválidos
  $message->setMessage("Informações inválidas!", "error", "index.php");
}
