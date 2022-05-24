<? require_once "header.php";


$is_edit = isset($_POST["edit"]);

if(isset($_POST["publish"])) {
    $header = $_POST["header"];

    if($_POST["new-header"] !== "") {
        if(mysqli_query($link, "INSERT INTO `headers` (`name`) VALUES ('{$_POST['new-header']}')"))
            $header = $_POST["new-header"];
        else {
             echo("ERROR");
             exit;
        }
    }
    $i = "SELECT MAX(id) FROM lessons";
    $iid = intval(mysqli_fetch_assoc(mysqli_query($link, $i))["MAX(id)"])+1;

    $fl = fopen("data/lessons/{$iid}.html", 'w');
    fwrite($fl, $_POST["content"]);
    fclose($fl);


    $g = "INSERT INTO `lessons` (`user_name`, `name`, `header`) 
    VALUES ('{$_SESSION["UID"]}', '{$_POST["name"]}', '{$header}')";

    if(mysqli_query($link, $g))
        header("Location: lesson.php?id={$iid}");    
    else 
        echo "Error";
    exit;
}
elseif(isset($_POST["send-edit"])) {
    $do = "UPDATE `lessons` SET `name` = '{$_POST["name"]}', 
    `header` = '{$_POST["header"]}' WHERE `lessons`.`id` = {$_POST["id"]}";
    if(mysqli_query($link, $do)) {
        $fl = fopen("data/lessons/{$_POST["id"]}.html", 'w');
        fwrite($fl, $_POST["content"]);
        fclose($fl);
        header("Location: /lesson.php?id={$_POST["id"]}"); 
    }   
    else 
        echo "Error";
    exit;
}



$cr = "SELECT * FROM headers";
$cr =  mysqli_query($link, $cr);
?>

<div class="maker" >
    <form action="makelesson.php" method="post">

        <?
        $id = isset($_GET["id"]) ? $_GET["id"] : $_POST["edit-id"];
        if($is_edit) { 
            $l = mysqli_fetch_assoc(mysqli_query($link, "SELECT * FROM lessons WHERE id='{$id}'"));                
        }?>
        <input class="maker-name" placeholder="Назва" type="text" name="name" <?if($is_edit) echo "value=\"{$l["name"]}\""?>>

        <select class="maker-headers" name="header" id="header">           
            <? foreach($cr as $c) {?>
                <option selected ><?=$c["name"]?></option>  
            <?}?>                    
        </select>

        <input placeholder="Розділ" class="maker-new-line" style="display:none" class="new-header" type="text" name="new-header" id="new-header">
        <input class="maker-new-button" id="show-input" style="display: inline;" type="button"  onclick="addHead()" value="+"><br>

        <div class="editor">
            <script type="text/javascript" src="../nicEdit.js"></script>
            <script type="text/javascript">
                bkLib.onDomLoaded(function() { nicEditors.allTextAreas() });
            </script>
            <textarea class="editor-area"  name="content" cols="40">
                    <?if($is_edit) {
                        echo file_get_contents("data/lessons/{$id}.html");
                    }?>
            </textarea>
        </div>
        <input class="maker-done" type="submit"  name=<?=$is_edit ? "send-edit" : "publish"?> value="Опублікувати">
        <input type="hidden" name="id" value="<?=$id?>">
        <script>
        function addHead() {
            document.getElementById("header").style.display = "none";
            document.getElementById("new-header").style.display = "inline";
            document.getElementById("show-input").style.display = "none";        
        }
        </script>
    </form>
</div>

