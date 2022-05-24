<?php 
require_once "header.php";?>

<div class="lesson">
    <?
    $id =  $_GET["id"]; 
    $uid = $_SESSION["UID"];   

    if(!isset($_COOKIE["viewed-{$id}-{$uid}"])) {
        setcookie("viewed-{$id}-{$uid}", 1);
        mysqli_query($link, "UPDATE lessons SET views=views+1 WHERE id={$id}");    
    }

    $lesson = mysqli_query($link, "SELECT * FROM lessons WHERE id='{$id}'");    

    if($lesson && mysqli_num_rows($lesson) == 1) {
        $isl = "SELECT 1 FROM likes WHERE lesson_id = '{$id}' AND user_name = '{$uid}'";
        $liked = mysqli_num_rows(mysqli_query($link, $isl)) == 1;

        if(isset($_POST["change_like"])) {
            if (!isset($_SESSION["UID"])){
                header("Location: login.php");
                exit;
            }
            $change_like = "DELETE FROM likes WHERE lesson_id = '{$id}' AND user_name = '{$uid}'"; // dislike
            if(!$liked) 
                $change_like = "INSERT INTO `likes` (`lesson_id`, `user_name`) VALUES ('{$id}', '{$uid}')";    // like
            $liked = !$liked;
            mysqli_query($link, $change_like);
        }
        elseif(isset($_POST["comment"])) {
            if (!isset($_SESSION["UID"])){
                header("Location: login.php");
                exit;
            }
            $d = date('Y-m-d H:i:s');
            $c = $_POST["content"];
            $comment = "INSERT INTO `comments` (`id`, `lesson_id`, `user_name`, `date`, `content`) 
                            VALUES (NULL, '{$id}', '{$uid}', '{$d}', '{$c}')"; 
            mysqli_query($link, $comment);
        }
        
        $likes = "SELECT COUNT(lesson_id) FROM likes WHERE lesson_id = '{$id}'";
        $likes =  mysqli_fetch_assoc(mysqli_query($link, $likes))["COUNT(lesson_id)"];
        $l = null;
        foreach($lesson as $t)
            $l = $t;    // get lesson data, 1 loop
       ?>
       <div class="lesson-body">
            <p style="text-align: center;  font: 40px bold;"><?=$l["name"]?></p>

                       
            <p style="text-align: center;  font: 25px bold; margin-bottom: 5px;"><?=$l["tittle"]?></p>
            <p  style="display: inline; font: 20px bold;"><?=$l["user_name"]?> | <?=$l["date"]?> |</p>            
            <img class="views-img" src="views.png">            
            <p style="display: inline; font: 20px bold;"><?=$l["views"]?></p>

            
            <form action="lesson.php?id=<?=$id?>"  method="post" >
                <input class="like-button" type="submit" value="<?=$liked? "💖": "❤ "?>"name="change_like">
                <p style=" display: inline; text-align: center;" ><?=$likes?></p>
            </form>

            <?if($_SESSION["admin"]) {?>
                <form action="makelesson.php" method="post" >
                    <input class="lesson-edit-button" type="submit" name="edit" value="Редагувати">
                    <input type="hidden" value="<?=$id?>" name="edit-id">
                </form>
            <?}?>
             
            <?
            $content = file_get_contents("data/lessons/{$id}.html");
            ?>
            <p style="display: inline;  font: 20px;"><?=$content?></p>                      
        </div>
    <?}
    else {?>
        <p style="color:silver; font-size: 30px;">Урок відсутній</p>
    <?exit;
    }
    ?><br>

    <div class="comments">
    <p class="comments-text">Коментарі:</p>

        <?
        $get_comments = "SELECT * FROM comments WHERE lesson_id='{$id}' ORDER BY date DESC";
        $res =  mysqli_query($link, $get_comments);
        if(!$res) {
            echo "DB error";
            exit;
        }
        ?>
        <form action="lesson.php?id=<?=$id?>" method="post">
            <textarea type="text" name="content" class="comment"><?=isset($_GET["reply"]) ? "@".$_GET["reply"].", " : "" ?></textarea>
            <br>
            <input type="submit" name="comment" value="Написати" style="margin-top:3px">
        </form>
        <? foreach($res as $v) { ?>
            <div >
                    <p style="display: inline; font: 22px bold;"><?=$v["user_name"]?></p>
                    <p style="display: inline;  font: 13px bold;"><?=$v["date"]?></p>
                    <a href="../lesson.php?id=<?=$id?>&reply=<?=$v["user_name"]?>" style="text-align:right;">Відповісти</a>
                    <p style=" font: 15px;"><?=$v["content"]?></p>
            </div>
        <?}?>
    </div>

    

</div>

