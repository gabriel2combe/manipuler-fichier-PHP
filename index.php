<?php

include('inc/head.php'); 


if(isset($_POST["delete"])) {
    $delete = "files/" . $_POST['delete'] ;
    unlink($delete);
}

if(isset($_POST["deleteDir"]) && empty($delete) && count(scandir($_POST['deleteDir']))<=2) {
    $delete = $_POST['deleteDir'];
    rmdir($delete);
}else if(isset($_POST["deleteDir"]) && empty($delete) && count(scandir($_POST['deleteDir']))>2){
    echo "<script type='text/javascript'>alert('Directory must be empty');</script>" ;
}

if ($_GET == NULL) {

    $dir=opendir("files");
    while($file = readdir($dir)){
        if(!in_array($file,array(".",".."))) {
            echo '<a href="?f='.$file.'">';
            echo $file;
            echo'</a></br>';
            echo '<form action="" method="post">';
            echo '<button type="submit" class="boutonDelete" value="files/'. $file . '" name="deleteDir">Delete</button></br>';
        }
    }
} else {
    echo 'Dans le répertoire <strong>' . $_GET["f"].'</strong>. <a href="index.php" class="bouton" >Retour index</a><br>';
}
?>

<?php
if(isset($_GET["f"])) {

    $dir=opendir("files/" . $_GET["f"]);
    while(false !== ($file = readdir($dir))) {
        if (!in_array($file, array(".", ".."))) {

            $filetype = filetype('files/' . $_GET["f"] . "/" . $file);

            switch ($filetype) {
                case "file":
                $ext = new SplFileInfo($file);
                switch ($ext->getExtension()) {
                    case "txt":
                    case "html":
                    echo '<a href="?f=' . $_GET["f"] . '&r=' . $file . '">';
                    echo $file;
                    echo '</a>';
                    echo '<form action="index.php/' . $_GET["f"] . '\" method="post">';
                    echo '<button type="submit" class="boutonDelete" value="' .$_GET["f"]."/". $file . '" name="delete">Delete</button></br></form>';
                    break;


                    case "jpg":
                    echo '<a href="?f=' . $_GET["f"] . '&r=' . $file . '">';
                    echo $file;
                    echo '</a>';
                    echo '<form action="" method="post">';
                    echo '<button type="submit" class="boutonDelete" value="' .$_GET["f"]. "/" . $file . '" name="delete">Delete</button></br></form>';
                    break;

                    default :
                    echo "erreur du filetype </br>";
                    break;

                }
                        break; //break de case "file"
                        case "dir":
                        echo '<a href="?f=' . $_GET["f"] . "/" . $file . '">';
                        echo $file ;
                        echo '</a><img src=assets/images/ark-open.png style="width:20px"; /></br>';
                        echo '<form action="" method="post">';
                        echo '<button type="submit" class="boutonDelete" value="files/'. $_GET["f"] . "/". $file . '" name="deleteDir">Delete</button></br></form>';
                        break;

                    }
                }
            }
        }
        if(isset($_POST["contenu"])){
                $fichier = "files/" . $_GET["f"] . "/" . $_GET["r"];
                $file=fopen($fichier, "w");
                fwrite($file,$_POST["contenu"]);
                fclose($file);
            }

        if(isset ($_GET['r']) && $ext->getExtension()=='txt'){

            $fichier = "files/" . $_GET["f"] . "/" . $_GET['r'];
            $contenu = file_get_contents($fichier);


            ?>

            <form method="POST" action="">
                <textarea name="contenu" style="width: 100%;height: 300px;"><?php
                echo $contenu;
                ?></textarea>
                <input name="file" type="hidden" value="<?php echo $_GET["f"]; ?>"/>
                <input type="submit" name="file" value="envoyer"/>
            </form>

            <?php
        } else if (isset ($_GET['r']) && $ext->getExtension()=='jpg'){

         echo '<img src="files/' . $_GET["f"] . '/' . $_GET['r'] . '">';

     }

     
    ?>



    <?php include('inc/foot.php'); ?>