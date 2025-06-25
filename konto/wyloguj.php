<?php
session_start();
session_unset();
session_destroy();
?>
<script>
    localStorage.setItem('LogoutPopup', 'true');
    window.location.href = 'http://localhost/zadania/GIS/index.php';
</script>
