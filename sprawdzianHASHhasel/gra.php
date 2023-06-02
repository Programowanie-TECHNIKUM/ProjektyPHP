<?php
session_start();
$nick = $_SESSION['nick'];
echo "Witaj $nick! Mam nadzieje ze zostaniesz z nami na dluzej!";