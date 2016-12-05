<?php 

include 'config.php';

// подключение к серверу
$db = mysql_connect($server, $username, $password);
if(!$db){ 
	echo "couldn't connect<br/>";
}else {
	echo "connected to server<br/>";
}

// создание базы данных
if(!mysql_query("CREATE DATABASE $dbname CHARACTER SET utf8 COLLATE utf8_general_ci")) { // кодировка
	echo "couldn't create database<br/>";
}else {
	echo "database $dbname has been created<br/>";
}

// создание таблиц
mysql_select_db($dbname, $db);  // с какой базой данных будем работать

$authors = "CREATE TABLE authors (	author_id INT(11) AUTO_INCREMENT PRIMARY KEY NOT NULL,
									author_name VARCHAR(50),
									author_email VARCHAR(50) NULL,
									correspondence TINYINT(1) DEFAULT 0,
									org_name VARCHAR(255),
									org_address VARCHAR(255))";
if(!mysql_query($authors, $db)) {
	echo "error 'authors'<br/>";
}else {
	echo "table 'authors' has been created<br/>";
}

$papers = "CREATE TABLE papers (	paper_id INT(11) AUTO_INCREMENT PRIMARY KEY NOT NULL,
									received DATETIME)"; 
if(!mysql_query($papers, $db)) {
	echo "error 'papers'<br/>";
}else {
	echo "table 'papers' has been created<br/>";
}

$papers_authors = "CREATE TABLE papers_authors (	pa_num INT(11) AUTO_INCREMENT PRIMARY KEY NOT NULL,
													paper_id INT(11),
													author_id INT(11))";
if(!mysql_query($papers_authors, $db)) {
	echo "error 'papers_authors'<br/>";
}else {
	echo "table 'papers_authors' has been created<br/>";
}

$paper_variant = "CREATE TABLE paper_variant (	pv_num INT(11) AUTO_INCREMENT PRIMARY KEY NOT NULL,
												paper_id INT(11),
												paper_name VARCHAR(255),
												pacs_numbers VARCHAR(50) NULL,
												received DATETIME,
												abstract TEXT,
												keywords TEXT,								
												file VARCHAR(50) NULL)"; 
if(!mysql_query($paper_variant, $db)) {
	echo "error 'paper_variant'<br/>";
}else {
	echo "table 'paper_variant' has been created<br/>";
}

// связи между таблицами
$papers_auth = "ALTER TABLE papers_authors ADD (	FOREIGN KEY (author_id) REFERENCES authors (author_id),
													FOREIGN KEY (paper_id) REFERENCES papers (paper_id))";												
if(!mysql_query($papers_auth, $db)) {
	echo "error keys for 'papers_authors'<br/>";
}else {
	echo "keys for 'papers_authors' have been created<br/>";
}

$paper_var = "ALTER TABLE paper_variant ADD FOREIGN KEY (paper_id) REFERENCES papers (paper_id)";
if(!mysql_query($paper_var, $db)) {
	echo "error keys for 'paper_variant'<br/>";
}else {
	echo "keys for 'paper_variant' have been created<br/>";
}

echo "Installation ended<br/>";

?>