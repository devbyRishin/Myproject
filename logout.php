<?php
session_start();
session_destroy();
header("Location: lund.html");
exit;
?>
