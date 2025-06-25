<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">

    <title>Document</title>
    <style>
      button {
        width: 100%;
      }
    </style>
</head>
<body>
<?php
session_start();
include('../admin/connect.php');

if(isset($_SESSION['id_uzytkownika'])){
    $id_uzytkownika = (int)$_SESSION['id_uzytkownika'];
    $result_user = $db->query("SELECT * FROM uzytkownicy WHERE id_uzytkownika = $id_uzytkownika");
    $row_user = $result_user->fetch_object();
} else {
    $id_uzytkownika = 1;
    $result_user = $db->query("SELECT * FROM uzytkownicy WHERE id_uzytkownika = $id_uzytkownika");
    $row_user = $result_user->fetch_object();
}
?>

<nav class="navbar navbar-expand-lg bg-body-tertiary bg-dark" data-bs-theme="dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="http://localhost/zadania/GIS/index.php">GIS</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="http://localhost/zadania/GIS/index.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="http://localhost/zadania/GIS/konto/logowanie_konta.php">Logowanie/Zakładanie konta</a>
        </li>
        
            
            <li><a class="dropdown-item" hidden href="#">Another action</a></li>
            <!-- Jeśli ustawiona zmienna sesyjna to pokaż opcje menu -->
            <?php 
              if(isset($_SESSION['id_uzytkownika'])){
                echo'<li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  Akcje
                </a>
                <ul class="dropdown-menu">';
                if($row_user->poziom_uprawnien >= 2){
                  echo' <li><a class="dropdown-item" href="http://localhost/zadania/GIS/obiekt/dodawanie_obiektu.php">Dodawanie obiektu</a></li>';
                }
                if($row_user->poziom_uprawnien == 1){
                  echo' <li><a class="dropdown-item" href="http://localhost/zadania/GIS/obiekt/propozycja_dodania_obiektu.php">Propozycja dodania obiektu</a></li>';
                }
                if($row_user->poziom_uprawnien >= 3){
                  echo' <li><a class="dropdown-item" href="http://localhost/zadania/GIS/admin/admin_panel.php">Panel administratora</a></li>';
                }
                if($row_user->poziom_uprawnien >= 1){
                  echo '
                  <li><hr id="divider_user_actions" class="dropdown-divider">
                  <li><a id="logout_button" class="dropdown-item" href="http://localhost/zadania/GIS/konto/wyloguj.php">Wyloguj</a></li>
                  ';
                } else{
                  echo '
                <li><a id="logout_button" class="dropdown-item" href="http://localhost/zadania/GIS/konto/wyloguj.php">Wyloguj</a></li>
                ';
                }

                
                echo '</ul>';
              }
            ?>
        </li>
        <li class="nav-item">
          <a class="nav-link disabled"><?php echo $row_user->login ?></a>
        </li>
      </ul>
    </div>
  </div>
</nav>
<script>
if (localStorage.getItem('SuccessAccountCreation') === 'true') {
  Swal.fire({
    icon: 'info',
    title: 'Pomyślnie utworzono konto',
    text: 'Teraz możesz się zalogować ',
  })
  localStorage.setItem('SuccessAccountCreation', 'false');
}
  </script>


<div class="container">
  <div class="row justify-content-center mt-5">
    <div class="col-md-6">
      <div class="card">
        <div class="card-body">
          <form action="zaloguj.php" method="post">
            <div class="mb-3">
              <label for="login" class="form-label">Login:</label>
              <input type="text" class="form-control" id="login" name="login" required>
            </div>
            <div class="mb-3">
              <label for="haslo" class="form-label">Hasło:</label>
              <input type="password" class="form-control" id="haslo" name="haslo" required>
            </div>
              <button type="submit" class="btn btn-primary">Zaloguj</button>
            </div>
          </form>
          <p class="text-muted mt-3 mb-0 text-center" id="zakladanie-link">Nie masz jeszcze konta? <a href="zakladanie_konta.php">Utwórz je</a></p>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  const zakladanieLink = document.getElementById("zakladanie-link");
  zakladanieLink.addEventListener("click", function(event) {
    event.preventDefault();
    window.location.href = "zakladanie_konta.php";
  });
</script>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>

</html>