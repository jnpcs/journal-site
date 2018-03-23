<?php
class DatabaseConnection {
    // connection
    public $db;

    // authenticated account id or null
    private $account_id;

    // establish a connection
    public function __construct($settings_pdo) {
        $this->db = new PDO(
            $settings_pdo['connection'] ,
            $settings_pdo['user'],
            $settings_pdo['pass']
        );
        $this->db -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->db -> exec("set names utf8");
        $this->account_id = NULL;
    }

    // try to authenticate via given username and password
    public function authenticate_via_password($email, $password) {
        $query= $this->db->prepare("Select account_id from accounts where email=? and passwd=SHA1(?)");
        $query->execute(array($email, $password));
        if ($query->rowCount() != 1) {
            // if no match found
            throw new Exception("Invalid email or password");
        }

        // remember authenticated acccount_id
        list($this->account_id) = $query->fetch(PDO::FETCH_NUM);
        
        return $this->account_id;
    }

    public function create_session() {
        if (is_null( $this->account_id) ) {
            throw new Exception("cannot create new session: not authenticated");
        }
        $this->session_id= sha1(uniqid('', true));
        $query=$this->db->prepare("insert into sessions (session_id, ip, account_id) VALUES (?,?,?)");
        $query->execute(array(
            $this->session_id,
            $_SERVER['REMOTE_ADDR'],
            $this->account_id
        ));
        return $this->session_id;    
    }

    public function authenticate_via_session_id($session_id) {
        $query= $this->db->prepare(
            "SELECT account_id FROM `sessions` WHERE session_id=? AND ip=? AND end_ts IS NULL"
        );
        $query->execute(array($session_id, $_SERVER['REMOTE_ADDR']));
        if ($query->rowCount() != 1) {
            // if no match found
            throw new Exception("Invalid session_id");
        }

        // remember authenticated acccount_id
        list($this->account_id) = $query->fetch(PDO::FETCH_NUM);
        $this->session_id = $session_id;

        return $this->account_id;   
    }  
    
}

$log_array = array();
function log_string($message) {
    global $log_array;
    $log_array[microtime()] = $message;
}
?>