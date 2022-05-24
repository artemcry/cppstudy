<? require_once "header.php";

    ?>
<div class="liked">
    <p class="liked-links-text">Уроки які вам сподобались: </p>
    <?
    $chosen = "SELECT lesson_id FROM likes WHERE user_name='{$_SESSION['UID']}'";
    $chosen = mysqli_fetch_all(mysqli_query($link, $chosen));

    foreach($chosen as $ch) { 
        $name = "SELECT name FROM lessons WHERE id='{$ch[0]}'";
        $name = mysqli_fetch_row(mysqli_query($link, $name))[0];
        ?>   
        <a class="liked-links" href="../lesson.php?id=<?=$ch[0]?>"> <?=$name?>        
        </a><br><?
    }?>
</div>