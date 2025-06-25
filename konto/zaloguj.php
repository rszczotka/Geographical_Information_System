<?php
session_start();
include('../admin/connect.php');

$login = mysqli_real_escape_string($db, $_POST['login']);
$haslo = mysqli_real_escape_string($db, $_POST['haslo']);

$query = "SELECT haslo FROM uzytkownicy WHERE login='$login'";
$result = mysqli_query($db, $query);

if ($result) {
  $row = mysqli_fetch_assoc($result);
  $hash_haslo = $row['haslo'];
  if (password_verify($haslo, $hash_haslo)) {
    $result = $db->query("SELECT id_uzytkownika FROM uzytkownicy WHERE login='$login' AND haslo='$hash_haslo'");
    $row = $result->fetch_object();

    $_SESSION['id_uzytkownika'] = $row->id_uzytkownika;
    ?>
      <script>
        localStorage.setItem('LoginPopup', 'true');
        window.location.href = 'http://localhost/zadania/GIS/index.php';
      </script>
    <?php
  } else {
    echo "<script type='text/javascript'>alert('Hasło jest niepoprawne, przekierowywuję z powrotem na logowanie');window.location.href='logowanie_konta.php';</script>";
  }
} else {
  echo "query failed";
}

?>
