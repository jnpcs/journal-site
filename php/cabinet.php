<?php
<<<<<<< HEAD
    print_r($_COOKIE);
=======
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
>>>>>>> a17f0c4c8adedc20bf7285a744021d00cdee7037
?>