<?php
try {
    include('config.php');
    include('functions.php');
    $db = new DatabaseConnection($settings['PDO']);
 
    // Look for an account
    $account_id = $db->authenticate_via_password($_POST['email'], $_POST['password']);

    // create new session
    $session_id= $db->create_session();
    
    // report success
    die (json_encode(array(
        'authenticated' => 1,
        'session_id' => $session_id,
        'redirect' => $_POST['redirect']
    )));
} 
catch (Exception $err) {
    // forward all errors to client
    die (json_encode(array(
        'authenticated' => 0,
        'error' => $err -> getMessage(),
        'log' => $log_array
    )));
}
?>