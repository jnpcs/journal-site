<?php
    include 'config.php';
    include 'functions.php';
    $author_id=authentcate();    
    if($author_id==-1){
        echo("No such author, bye!");
        die();
    }else{
        // author exists, provide his papers status
        $db=connect_db(); // можно работать
        $res=$db -> query("SELECT * FROM `papers` JOIN `paper_variants` USING (paper_id)  WHERE account_id==$author_id);
        echo $res;
    }
?>