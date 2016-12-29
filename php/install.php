<?php

	include 'config.php';
	
	//подключение к серверу
	try {
		$db = new PDO("mysql:host=$host", $user, $pass);
		$db -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$db -> exec("set names utf8");
		echo "connection established</br>";
	}
	catch(PDOException $err) {
		echo $err -> getMessage() . "<br />";
	}
	
	//создание базы данных
	try {
		$db -> exec("CREATE DATABASE $dbname CHARACTER SET utf8 COLLATE utf8_general_ci");
		echo "database succesful created</br>";
	}
	catch(PDOException $err) {
		echo $err -> getMessage() . "<br />";
	}
	
	//выбор базы данных
	$db -> exec("use $dbname");
	
	//создание таблицы "authors"
	try {
		$db -> exec("CREATE TABLE authors (	author_id INT(11) AUTO_INCREMENT PRIMARY KEY NOT NULL,
											author_name VARCHAR(50),
											author_email VARCHAR(50) NULL,
											correspondence TINYINT(1) DEFAULT 0,
											affiliation VARCHAR(255))");
		echo "success creating table 'authors'</br>";
	}
	catch(PDOException $err) {
		echo $err -> getMessage() . "<br />";
	}
	
	//создание таблицы "papers"
	try {
		$db -> exec("CREATE TABLE papers (	paper_id INT(11) AUTO_INCREMENT PRIMARY KEY NOT NULL,
											received DATETIME)");
		echo "success creating table 'papers'</br>";
	}
	catch(PDOException $err) {
		echo $err -> getMessage() . "<br />";
	}
	
	//создание таблицы "papers_authors"
	try {
		$db -> exec("CREATE TABLE papers_authors (	pap_auth_id INT(11) AUTO_INCREMENT PRIMARY KEY NOT NULL,
													paper_id INT(11),
													author_id INT(11))");
		echo "success creating table 'papers_authors'</br>";
	}
	catch(PDOException $err) {
		echo $err -> getMessage() . "<br />";
	}
	
	//создание таблицы "paper_variant"
	try {
		$db -> exec("CREATE TABLE paper_variant (	pap_var_id INT(11) AUTO_INCREMENT PRIMARY KEY NOT NULL,
													paper_id INT(11),
													paper_name VARCHAR(255),
													pacs_numbers VARCHAR(50) NULL,
													received DATETIME,
													abstract TEXT,
													keywords TEXT,								
													file VARCHAR(50) NULL)");
		echo "success creating table 'paper_variant'</br>";
	}
	catch(PDOException $err) {
		echo $err -> getMessage() . "<br />";
	}
	
	//создание связей для таблицы "paper_authors"
	try {
		$db -> exec("ALTER TABLE papers_authors ADD (	FOREIGN KEY (author_id) REFERENCES authors (author_id),
														FOREIGN KEY (paper_id) REFERENCES papers (paper_id))");
		echo "success creating keys for table 'paper_authors'</br>";
	}
	catch(PDOException $err) {
		echo $err -> getMessage() . "<br />";
	}
	
	//создание связей для таблицы "paper_variant"
	try {
		$db -> exec("ALTER TABLE paper_variant ADD FOREIGN KEY (paper_id) REFERENCES papers (paper_id)");
		echo "success creating keys for table 'paper_variant'</br>";
	}
	catch(PDOException $err) {
		echo $err -> getMessage() . "<br />";
	}
	
	//закрытие подключения
	$db = null;
?>