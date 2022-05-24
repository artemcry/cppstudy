<?    
    require_once "header.php";
    ?>
<div class="header">
        <div >
            <p class="lesson-text">Уроки по С++</p>
            <p class="lesson-desc"><?require_once "info.html";?></p>
        </div>

        <span class="top-list">
            <p class="top-text"> Топ уроків</p>
            <? 
            $topless = mysqli_query($link, "SELECT name, id FROM lessons ORDER BY views DESC LIMIT 10;");
            foreach($topless as $less) {
                ?><a class="top-name" href="../lesson.php?id=<?=$less["id"]?>"><?=$less["name"]?></a><br><?
            }
            ?>
        </span>
</div>
    <?
    $headers = "SELECT * FROM headers ORDER BY date ASC";
    $headers =  mysqli_query($link, $headers);
    if(!$headers) {
        echo "DB error";
        exit;
    }
    ?>
    <div class ="lesson-list"><?
        foreach($headers as $h) {
            $header = $h["name"];
            $lessons = "SELECT * FROM lessons WHERE header='{$header}' ORDER BY date ASC";
            $lessons =  mysqli_query($link, $lessons);
            ?>
            <p class="lesson-list-header"><?=$header?></p>
            <?
            foreach($lessons as $lesson) { ?>      
                <a class="lesson-list-name" href="../lesson.php?id=<?=$lesson["id"]?>">
                <?=$lesson["name"]?>        
                </a><br><?
            }
        }
    ?></div>
