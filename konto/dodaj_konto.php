<?php

include('../admin/connect.php');

$login = mysqli_real_escape_string($db, $_POST['login']);
$haslo = mysqli_real_escape_string($db, $_POST['haslo']);
$email = mysqli_real_escape_string($db, $_POST['email']);
$imie = mysqli_real_escape_string($db, $_POST['imie']);
$nazwisko = mysqli_real_escape_string($db, $_POST['nazwisko']);

$hash_haslo = password_hash($haslo, PASSWORD_DEFAULT);

// sprawdzenie, czy login już istnieje w bazie danych
$login_query = "SELECT COUNT(*) FROM uzytkownicy WHERE login = '$login'";
$login_result = mysqli_query($db, $login_query);
$login_count = mysqli_fetch_array($login_result)[0];

if ($login_count > 0) {
    // jeśli login już istnieje, wyświetl odpowiedni komunikat i przerwij działanie skryptu
    ?>
        <script>
            localStorage.setItem('AlreadyUsedLogin', 'true');
            window.location=document.referrer;
            exit();
        </script>
    <?php
    exit();
}


$query = "INSERT INTO uzytkownicy (login, haslo, email, imie, nazwisko) VALUES ('$login', '$hash_haslo', '$email', '$imie', '$nazwisko')";

$result = mysqli_query($db, $query);

?>
    <script>
        localStorage.setItem('SuccessAccountCreation', 'true');
        window.location.href = "logowanie_konta.php";
    </script>
<?php   
