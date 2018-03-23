<?php
try {
    include 'config.php';
    include 'functions.php';
    $db = new DatabaseConnection($settings['PDO']);
 
    // Look for an account
    $account_id = $db->authenticate_via_session_id($_COOKIE['session_id']);

    $res = $db->db->query(
        "SELECT * FROM `papers` JOIN `paper_variants` USING (paper_id)  WHERE account_id=$account_id"
    );
    echo "<pre>";
    foreach($res as $variant) {
        print_r($variant);
    }
}
catch (Exception $ex) {
    die("<h1>Something went wrong</h1>". $ex->getMessage() );
}
?>