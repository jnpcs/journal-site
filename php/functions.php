<?php
function authenticate(){
    return 4;
}  

function connect_db(){
	//подключение к серверу
	try {
		$db = new PDO("mysql:host=$host", $user, $pass);
		$db -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$db -> exec("set names utf8");
		echo "connection established</br>";
		$db -> exec("use $dbname");
		return $db;
	}
	catch(PDOException $err) {
		echo $err -> getMessage();
	}
}; //end connect_db

?>