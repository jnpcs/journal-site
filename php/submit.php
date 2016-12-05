<?php

include 'config.php';

// принимаем данные из формы
$author_name = $_POST['author_name'];
$org_name = $_POST['org_name'];
$org_address = $_POST['org_address'];
$email = $_POST['email'];
$correspondence = 0;
$paper_name = $_POST['paper_name'];
$pacs_numbers = $_POST['pacs_numbers'];
$keywords = $_POST['keywords'];
$abstract = $_POST['abstract'];
$file_name = $_FILES['file']['name'];

$file_name = date('U').'.zip'; // количество секунд от начала эпохи

if (copy($_FILES['file']['tmp_name'], $uploaddir . $file_name)) {
	echo "Файл успешно загружен на сервер<br />";
}else {
	echo "Ошибка! Не удалось загрузить файл на сервер!<br />";
	exit; 
}

if(isset($_POST['correspondence'])) $correspondence = 1; // если поставлена галочка

// подключение к серверу
$db = mysql_connect($server, $username, $password);
if(!$db){ 
	echo "couldn't connect<br/>";
}else {
	echo "connected to server<br/>";
}

mysql_select_db($dbname, $db);  // с какой базой данных будем работать
mysql_query("SET NAMES utf8");

// добавляем автора, если ещё нет; для обновления данных автора нужен личный кабинет
if(mysql_result(mysql_query("SELECT COUNT(`author_id`) FROM `authors` WHERE `authors`.`author_name`='$author_name'"), 0) == 0){
	$send_authors = "INSERT INTO authors (author_name, author_email, correspondence, org_name, org_address)
					VALUES ('".mysql_real_escape_string($author_name)."',
							'".mysql_real_escape_string($email)."',
							'$correspondence',
							'".mysql_real_escape_string($org_name)."',
							'".mysql_real_escape_string($org_address)."')";
	if(!mysql_query($send_authors, $db)){
		echo "error sending 'author'<br />";
	} else {
		echo "success sending 'author'<br />";
	}
} else echo "This author already exists<br />";
	
$send_papers = "INSERT INTO papers (received)
				VALUES (NOW())";
if(!mysql_query($send_papers, $db)){
	echo "error sending 'papers'<br />";
} else {
	echo "success sending 'papers'<br />";
}
// будет ещё отдельная штука "обновить статью", это "initial commit" статьи
// возможно, есть лучший вариант запроса в строчках 64 и 78
$send_papvar = "INSERT INTO paper_variant (paper_id, paper_name, pacs_numbers, received, abstract, keywords, file)
				VALUES ('".mysql_result(mysql_query("SELECT MAX(`paper_id`) FROM `papers`"), 0)."',
						'".mysql_real_escape_string($paper_name)."',
						'".mysql_real_escape_string($pacs_numbers)."',
						NOW(),
						'".mysql_real_escape_string($abstract)."',
						'".mysql_real_escape_string($keywords)."',
						'$file_name')";
if(!mysql_query($send_papvar, $db)){
	echo "error sending 'paper_variant'<br />";
} else {
	echo "success sending 'paper_variant'<br />";
}

$send_papauth = "INSERT INTO papers_authors (paper_id, author_id)
				VALUES ('".mysql_result(mysql_query("SELECT MAX(`paper_id`) FROM `papers`"), 0)."',
						'".mysql_result(mysql_query("SELECT `author_id` FROM `authors` WHERE `authors`.`author_name`='$author_name'"), 0)."')";
if(!mysql_query($send_papauth, $db)){
	echo "error sending 'papers_authors'<br />";
} else {
	echo "success sending 'papers_authors'<br />";
}

echo "Sending completed";

?>