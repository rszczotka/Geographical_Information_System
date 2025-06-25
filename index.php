<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>GIS</title>
    <style>
      nav {
        margin-bottom: 40px;
      }
      .row{
        margin-left:20px;
        margin-right:20px;
      }
      #obraz {
        height: 150px;
        width: auto;
        object-fit: contain;
      }
      #karta{
        margin-bottom: 20px;
      }
    </style>

</head>
<body>

<?php
session_start();

  ?><script>
    //? Popup login/logout
if (localStorage.getItem('LoginPopup') === 'true') {
  // show the popup
  const Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 3000,
      timerProgressBar: true,
      showCloseButton: true,
    })
    
    Toast.fire({
      icon: 'success',
      title: 'Pomyślnie zalogowano'
    })
  localStorage.setItem('LoginPopup', 'false');
}
if (localStorage.getItem('LogoutPopup') === 'true') {
  // show the popup
  const Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 3000,
      timerProgressBar: true,
      showCloseButton: true,
    })
    
    Toast.fire({
      icon: 'success',
      title: 'Pomyślnie wylogowano'
    })
  localStorage.setItem('LogoutPopup', 'false');
}
//? Popup dodanie propozycji
if (localStorage.getItem('SuggestionSubmitPositive') === 'true') {
  // show the popup
  Swal.fire({
    icon: 'success',
    title: 'Propozycja dodana pomyślnie!',
    text: 'Wkrótce zostanie ona zrecenzowana przez administratora',
  })
  localStorage.setItem('SuggestionSubmitPositive', 'false');
}
if (localStorage.getItem('SuggestionSubmitNegative') === 'true') {
  // show the popup
  Swal.fire({
    icon: 'error',
    title: 'Podczas dodawania wysąpił błąd!',
    text: 'Spróbuj ponownie później lub skontaktuj się z administratorem',
  })
  localStorage.setItem('SuggestionSubmitNegative', 'false');
}
  </script><?php

include('admin/connect.php');

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
    <a class="navbar-brand" href="#">GIS</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="#">Home</a>
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


<div class="row">
    <form action="" method="get">
      <div class="input-group mb-3">
        <input type="text" class="form-control" name="search" placeholder="Szukaj..." aria-describedby="button-addon2">
        <button class="btn btn-outline-success" type="submit" id="button-addon2">Szukaj</button>
        <button class="btn btn-outline-danger" onclick="reset_search()" type="reset" id="button-addon2">Resetuj</button>
      </div>
    </form><br><br>
    <script>
      function reset_search() {
        window.location.href = "http://localhost/zadania/GIS/index.php";
      }
    </script>
    <?php


if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $query = "SELECT * FROM obiekty WHERE id_obiekt LIKE '%$search%' OR nazwa_obiekt LIKE '%$search%' OR miejscowosc LIKE '%$search%' OR kod_pocztowy LIKE '%$search%' OR ulica LIKE '%$search%' OR nr_budynku LIKE '%$search%' OR opis LIKE '%$search%'";
} else {
    $query = "SELECT * FROM obiekty";
} 

if ($result = $db->query($query)) {
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_object()) {
          $result_obraz = $db->query("SELECT * FROM obrazy WHERE id_obiekt = $row->id_obiekt");
          $row_obraz = $result_obraz->fetch_object();
            echo "<div class='col-2' id='karta'>";
            echo "  <div class='cardy'>";
            echo "      <div class='card' style='width: 18rem;'>";
            if (!empty($row_obraz)) {
              echo "<img src='data:image/jpeg;base64," . base64_encode($row_obraz->zawartosc_obrazu) ."' class='card-img-top' alt='' id='obraz'>";
            }
            else{
              echo "<div id='obraz'></div>";
            }
            echo "          <div class='card-body'>";
            echo "                  <h5 class='card-title'>" . $row->nazwa_obiekt . "</h5>";
            echo "                  <p class='card-text'>" . $row->miejscowosc . "</p>";
            echo "                  <form action='http://localhost/zadania/GIS/obiekt/obiekt.php' method='get'>";
            echo "                    <input type='text' name='id_obiekt' value=" . $row->id_obiekt . " hidden>";
            echo "                    <input type='submit' class='btn btn-primary' value='Więcej'></input>";
            echo "                  </form>";
            echo "          </div>";
            echo "     </div>";
            echo "   </div>";
            echo "</div>";
        }
    } else {
        echo "No results to display!";
    }
} else {
    echo "Error: " . $db->error;
}
$db->close();

    ?>
</div>

</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>
</html>