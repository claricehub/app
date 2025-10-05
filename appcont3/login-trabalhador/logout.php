<?php
session_start();
session_destroy();
header("Location: ../root/index.php");
exit();
?>