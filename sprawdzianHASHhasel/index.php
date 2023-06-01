<?php

$c = mysqli_connect('localhost', 'root', '', 'spr');

    if(isset($_POST['haslogen'])) {
        $hash = password_hash($_POST['haslogen'], PASSWORD_DEFAULT);

        echo 'password default: '.$hash."<br>";
        echo 'sha1: '.sha1($_POST['haslo'])."<br>";
        echo 'md5:'.md5($_POST['haslo']);

    }

    if(isset($_POST['sprawdzhaslo']) and isset($_POST['sprawdzhashhaslo'])) {
        if($_POST['typ'] == 'passworddefault') {
            echo password_verify($_POST['sprawdzhaslo'], $_POST['sprawdzhashhaslo']);

        } else if($_POST['typ'] == 'sha1') {

            if(sha1($_POST['sprawdzhaslo']) == $_POST['sprawdzhashhaslo']) {
                echo 'true';
            } else {
                echo 'false';
            }

        } else {
            if(md5($_POST['sprawdzhaslo']) == $_POST['sprawdzhashhaslo']) {
                echo 'true';
            } else {
                echo 'false';
            }

        }
    }

    if(isset($_POST['nick'])) {
        $haslo1 = $_POST['haslo1'];
        $haslo2 = $_POST['haslo2'];
        $email = $_POST['email'];
        $nick = $_POST['nick'];
        $data = $_POST['data'];


        $dobrze = true;

        if($haslo1 != $haslo2) {
            $dobrze = false;
            echo 'hasla sie roznia';
        }

        if(strlen($nick) < 3 and strlen($nick) > 12) {
            $dobrze = false;
            echo 'masz za dlugi albo za krotki nick';
        }

        if(!ctype_alnum($nick)) {
            $dobrze = false;
            echo 'masz dziwne znaki w nicku';
        }

        $sprawdzlogin = mysqli_query($c, "select * FROM user WHERE nick LIKE '$nick'");

        if(mysqli_num_rows($sprawdzlogin) <> 0) {
            $dobrze = false;
            echo 'taki uzytkownik jest juz u nas w bazie';
        }

        $hashhaslo = password_hash($haslo2, PASSWORD_DEFAULT);

        if($dobrze) {
            $q = mysqli_query($c, "INSERT INTO `user` (`nick`, `haslo`, `email`, `data`) VALUES ('$nick', '$hashhaslo', '$email', '$data')");
            echo 'zarejestrowales sie!';
        }

    }

if(isset($_POST['loginL']) and isset($_POST['passL'])) {
    echo 'test';
    $login = htmlentities($_POST['loginL'], ENT_QUOTES, 'UTF-8');
    $pass = htmlentities($_POST['passL'], ENT_QUOTES, 'UTF-8');

    $query = mysqli_query($c, "SELECT * FROM user WHERE `nick` LIKE '$login' ");


    if(mysqli_num_rows($query) <> 0) {
        $row = mysqli_fetch_array($query);
        print_r($row);

        if(password_verify($pass, $row[2])) {
            header('location: gra.php');
        } else {
            echo "niepoprawny login lub haslo";
        }
    } else {
        echo 'niepoprawny login lub haslo';
    }
}



?>

<html>
    <body>
        <form method="post">
            <input type = "text" name = "haslogen" placeholder="wpisz swoje haslo">
            <input type = "submit">
        </form>
        <br>
        <form method="post">
            <input type = "text" name = "sprawdzhaslo" placeholder="wpisz normalne">
            <input type = "text" name = "sprawdzhashhaslo" placeholder="wpisz hash">
            <input type = "radio" name = "typ" value = "passworddefault">passworddefault
            <input type = "radio" name = "typ" value = "sha1">sha1
            <input type = "radio" name = "typ" value = "md5">md5
            <input type = "submit">
        </form>
        <br>
        <br>
        <h1>Rejestrowanie</h1>
        <form method = "post">
            <input type = "text" name ="nick" placeholder="wpisz nick"><br>
            <input type = "password" name = "haslo1" placeholder="Wpisz haslo"><br>
            <input type = "password" name = "haslo2" placeholder="Wpisz haslo"><br>
            <input type = "email" name = "email" placeholder="Wpisz mail"><br>
            <input type = "date" name = "data">
            <input type = "submit">
        </form>
        <br>
        <h1>Logowanie</h1>
        <form method = "post">
            <input type = "text" name = "loginL" placeholder="login">
            <input type = "password" name = "passL" placeholder="haslo">
            <input type = "submit">
        </form>
    </body>
</html>
