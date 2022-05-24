<?php
require_once 'header.php';

if(isset($_SESSION["UID"]))
    header("Location: index.php");
    
function out() {
    $_SESSION["UID"] = null;
    unset($_SESSION["admin"]);
    header("Location: index.php");
    exit;
}

if(isset($_POST["login"])) {
    login($link);
    header("Location: index.php");
}
elseif (isset($_POST["out"])) {
    out();
    header("Location: index.php");
}
elseif (isset($_POST["sign-up"])) {
    sign_up($link);
    header("Location: index.php");
}

function login($link)
{    
    $name = $_POST["name"];
    $pss = $_POST["password"];
    $get_post = "SELECT name, is_admin FROM users WHERE name = '$name' AND password = '$pss'";
    $res =  mysqli_query($link, $get_post);

    if(mysqli_num_rows($res) == 1) {
        $s = $res->fetch_assoc();
        $_SESSION["UID"] = $s["name"]; 
        $_SESSION["admin"] = $s["is_admin"] == "1" ? true : false;   
    }  
    else {
        $_SESSION["login_fail"] = true;
        header("Location: login.php");    
        exit;
    }
}

function sign_up($link) {
    $mail = $_POST["e-mail"];
    $name = $_POST["name"];   
    $pss = $_POST["password"];   
    $mq = "SELECT 1 FROM users WHERE mail = '$mail'";
    $nq = "SELECT 1 FROM users WHERE name = '$name'";

    $mail_q = mysqli_num_rows(mysqli_query($link, $mq)) == 0;
    $name_q = mysqli_num_rows(mysqli_query($link, $nq)) == 0;
    
    if($mail === "" || $name === "" || $pss === "" || !($name_q && $mail_q)) {
        $_SESSION["sign-up-errors"] = ["uniqe-mail" => $mail_q, "uniqe-name" => $name_q];
        $_SESSION["sign-up-errors"]["empty-string"] = ($mail === "" || $name === "" || $pss === "");
        header("Location: login.php");
        exit;
    }
    else {
        $_SESSION["UID"] = $name;
        $_SESSION["admin"] = false;
        mysqli_query($link, "INSERT INTO `users` (`mail`, `name`, `password`, `is_admin`) 
                            VALUES ('{$mail}', '{$name}', '{$pss}', '0')");
        
    }    
}?>

<?if (isset($_POST["get_sign_up"]) || isset($_SESSION["sign-up-errors"])) {?>
    <div class="sign-up">
    <form action="login.php" method="post" >
            <input type="text" name="e-mail" placeholder="E-Mail"> 
            <br>
            <input type="text" name="name" placeholder="Придумайте нікнейм">
            <br>
            <input type="password" name="password" placeholder="Пароль">
            <br>
            <input type="submit" name="sign-up" value="Зареєструватися">
            <?if(isset($_SESSION["sign-up-errors"])) {?> <br>
                <? if(!$_SESSION["sign-up-errors"]["uniqe-mail"]) { ?>
                <div style="color:red">Email занятий</div> 
                <?}?>
                <? if(!$_SESSION["sign-up-errors"]["uniqe-name"]) { ?>
                <div style="color:red">Ім'я занято</div>
                <?}
                if($_SESSION["sign-up-errors"]["empty-string"]) { ?>
                    <div style="color:red">Введіть всі поля</div>
                <?}
                unset($_SESSION["sign-up-errors"]);
                exit;
            }?>
        </form>
    </div>
<?}
else {?>
    <div class="login">
        <form action="login.php" method="post" >
            <input class="login-input" type="text" name="name" placeholder="Ваше ім'я або E-Mail"> 
            <br>   
            <input class="login-input" type="password" name="password" placeholder="Пароль">
            <br>       
            <input class="login-enter" type="submit" name="login" value="Ввійти"> <br>
            <input class="login-enter" type="submit" name="get_sign_up" value="Створити Акаунт" />
            <?if($_SESSION["login_fail"]) {
                $_SESSION["login_fail"] = false;?> 
                <br>
                <div style="color:red">Помилка входу</div>            
            <?}?>
        </form>
    </div>
<?} 



?>
