<?php require_once("templates/header.php"); ?>

<div id="main-container" class="container-fluid">
  <div class="col-md-12">
    <div class="row" id="auth-row">
      <div class="col-md-4 login-container">
        <h2 class="auth-title">Entrar</h2>
        <form action="" method="POST">
          <input type="hidden" name="type" value="login">

          <div class="form-group mb-3">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Digite o seu email">
          </div>
          <div class="form-group mb-3">
            <label for="password">Senha:</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Digite sua senha">
          </div>
          <input type="submit" class="btn card-btn" value="Entrar">
        </form>
      </div>
      <div class="col-md-4 register-container">
        <h2 class="auth-title">Criar Conta</h2>
        <form action="" method="POST">
          <input type="hidden" name="type" value="register">
          <div class="form-group mb-3">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Digite o seu email">
          </div>
          <div class="form-group mb-3">
            <label for="name">Nome:</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Digite o seu nome">
          </div>
          <div class="form-group mb-3">
            <label for="lastname">Sobrenome:</label>
            <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Digite o seu sobrenome">
          </div>
          <div class="form-group mb-3">
            <label for="confirmpassword">Confirmação de Senha:</label>
            <input type="password" class="form-control" id="confirmpassword" name="confirmpassword" placeholder="Confirme sua senha">
          </div>
          <input type="submit" class="btn card-btn" value="Registrar">
        </form>
      </div>
    </div>
  </div>
</div>

<?php require_once("templates/footer.php"); ?>