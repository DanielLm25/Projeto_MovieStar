<?php

require_once("models/User.php");
require_once("models/Message.php");


class UserDAO implements UserDAOInterface
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




  public function buildUser($data)
  {

    $user = new User();

    $user->id = $data["id"];
    $user->name = $data["name"];
    $user->lastname = $data["lastname"];
    $user->email = $data["email"];
    $user->password = $data["password"];
    $user->image = $data["image"];
    $user->bio = $data["bio"];
    $user->token = $data["token"];


    return $user;
  }


  public function create(User $user, $authUser = false)
  {

    $stmt = $this->conn->prepare("INSERT INTO users(name, lastname,email,password,token) VALUES (:name, :lastname, :email,:password, :token)");
    $stmt->bindParam(":name", $user->name);
    $stmt->bindParam(":lastname", $user->lastname);
    $stmt->bindParam(":email", $user->email);
    $stmt->bindParam(":password", $user->password);
    $stmt->bindParam(":token", $user->token);
    $stmt->execute();


    if ($authUser) {
      $this->setTokenToSession($user->token);
    }
  }




  public function update(User $user, $redirect = true)
  {

    $stmt = $this->conn->prepare("UPDATE users SET
    name = :name,
    lastname = :lastname,
    email = :email,
    image = :image,
    bio = :bio,
    token = :token
    WHERE id = :id
");

    $stmt->bindParam(":name", $user->name);
    $stmt->bindParam(":lastname", $user->lastname);
    $stmt->bindParam(":email", $user->email);
    $stmt->bindParam(":image", $user->image);
    $stmt->bindParam(":bio", $user->bio);
    $stmt->bindParam(":token", $user->token);
    $stmt->bindParam(":id", $user->id);

    $stmt->execute();

    if ($redirect) {
      $this->message->setMessage("Dados atualizados com sucesso!", "success", "index.php");
    }
  }
  public function verifyToken($protected = false)
  {
    if (!empty($_SESSION["token"])) {

      $token = $_SESSION["token"];

      $user = $this->findByToken($token);

      if ($user) {
        return $user;
      } else if ($protected) {
        $this->message->setMessage("Faça a autenticação para acessar essa página!", "error", "index.php");
      }
    } else if ($protected) {
      $this->message->setMessage("Faça a autenticação para acessar essa página!", "error", "index.php");
    }
  }




  public function setTokenToSession($token, $redirect = true)
  {
    // Salvar token na sessão
    $_SESSION["token"] = $token;

    // Mensagem de depuração
    echo "Token definido na sessão: $token<br>";

    if ($redirect) {
      // Redirecionar e mostrar mensagem de sucesso
      $this->message->setMessage("Seja bem-vindo 1!", "success", "index.php");
    }
  }




  public function authenticateUser($email, $password)
  {
    // Busca o usuário pelo email
    $user = $this->findByEmail($email);

    // Verifica se o usuário foi encontrado
    if ($user) {
      // Verifica se a senha fornecida corresponde à senha armazenada
      if (password_verify($password, $user->password)) {
        // Gera um novo token para o usuário
        $token = $user->generateToken();

        // Define o token na sessão
        $this->setTokenToSession($token, false);

        // Atualiza o token do usuário no banco de dados
        $user->token = $token;
        $this->update($user, false);

        // Exibe uma mensagem de sucesso
        $this->message->setMessage("Seja bem-vindo!", "success", "index.php");

        // Redireciona o usuário para a página editprofile.ph        exit(); // Garante que o script pare de ser executado após o redirecionamento
      } else {
        // Se a senha estiver incorreta, exibe uma mensagem de erro
        $this->message->setMessage("A senha fornecida não corresponde à senha armazenada no banco de dados!", "error", "auth.php");
        return false;
      }
    } else {
      // Se o usuário não for encontrado, exibe uma mensagem de erro
      $this->message->setMessage("Usuário não encontrado no banco de dados!!", "error", "auth.php");
      return false;
    }
  }



  public function findByEmail($email)
  {

    if ($email != "") {
      $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = :email");
      $stmt->bindParam(":email", $email);
      $stmt->execute();

      if ($stmt->rowCount() > 0) {

        $data = $stmt->fetch();
        $user = $this->buildUser($data);
        return $user;
      } else {
        return false;
      }
    } else {
      return false;
    }
  }
  public function findById($id)
  {


    if ($id != "") {
      $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = :id");
      $stmt->bindParam(":id", $id);
      $stmt->execute();

      if ($stmt->rowCount() > 0) {

        $data = $stmt->fetch();
        $user = $this->buildUser($data);
        return $user;
      } else {
        return false;
      }
    } else {
      return false;
    }
  }
  public function findByToken($token)
  {


    if ($token != "") {
      $stmt = $this->conn->prepare("SELECT * FROM users WHERE token = :token");
      $stmt->bindParam(":token", $token);
      $stmt->execute();

      if ($stmt->rowCount() > 0) {

        $data = $stmt->fetch();
        $user = $this->buildUser($data);
        return $user;
      } else {
        return false;
      }
    } else {
      return false;
    }
  }

  public function destroyToken()
  {

    $_SESSION["token"] = "";

    $this->message->setMessage("Logout realizado com sucesso!", "success", "auth.php");
  }

  public function changePassword(User $user)
  {
    $stmt = $this->conn->prepare("UPDATE users SET password = :password WHERE id=:id");

    $stmt->bindParam(":password", $user->password);
    $stmt->bindParam(":id", $user->id);

    $stmt->execute();

    $this->message->setMessage("Senha alterada com sucesso!", "sucess", "editprofile.php");
  }
}
