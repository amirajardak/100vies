<?php
session_start();
session_unset();
session_destroy();
header("Location: form.php"); // page de connexion
exit;