<?php

session_start();

require_once "importcss.php";
require_once "database.php";

?>
<div class="header-panel">
<? if($_SESSION["admin"]) {?>
        <form action="makelesson.php" method="post" style="display: inline;">
            <input type="submit" name="makelesson" value="Створити Урок">        
        </form>
    <?}?>
    <? if(isset($_SESSION["UID"])) { ?>
        <form action="chosen.php" method="post" style="display: inline;">
            <input type="submit" name="chosen" value="Вибране">
        </form>
        <form action="login.php" method="post" style="display: inline;">
            <input type="submit" name="out" value="Вихід(<?=$_SESSION["UID"]?>)">
        <?} else { ?>
        <form action="login.php" method="post" style="display: inline;">
            <input type="submit" name="get_login" value="Вхід">
        <?}?>
        </form>
</div><br>

<div class="logo">
    <a href="../index.php">CPP Study</a>
</div>


