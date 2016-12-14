<?php

include 'config.php';

// принимаем данные из формы
$authors = $_POST['authors'];
//$correspondence = 0;

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

//if(isset($_POST['correspondence'])) $correspondence = 1; // если поставлена галочка

// подключение к серверу
$db = mysql_connect($server, $username, $password);
if(!$db){ 
	echo "couldn't connect<br/>";
}else {
	echo "connected to server<br/>";
}

mysql_select_db($dbname, $db);  // с какой базой данных будем работать
mysql_query("SET NAMES utf8");

$send_papers = "INSERT INTO papers (received)
				VALUES (NOW())";
if(!mysql_query($send_papers, $db)){
	echo "error sending 'papers'<br />";
} else {
	echo "success sending 'papers'<br />";
}

// будет ещё отдельная штука "обновить статью", это "initial commit" статьи
// возможно, есть лучший вариант запроса в строчках 48 и 77
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

// добавляем автора, если ещё нет; для обновления данных автора нужен личный кабинет
//CORRESPONDENCE
foreach($authors as $i){
	if(mysql_result(mysql_query("SELECT COUNT(`author_id`) FROM `authors` WHERE `authors`.`author_name`='$i[name]'"), 0) == 0){
		$send_authors = "INSERT INTO authors (author_name, affiliation, author_email)
						VALUES ('".mysql_real_escape_string($i['name'])."',
						'".mysql_real_escape_string($i['affiliation'])."',
						'".mysql_real_escape_string($i['email'])."')";
		if(!mysql_query($send_authors, $db)){
			echo "error sending author $i[name]<br />";
		} else {
			echo "success sending author $i[name]<br />";
		}
	} else echo "Author $i[name] already exists<br />";
	
	$send_papauth = "INSERT INTO papers_authors (paper_id, author_id)
					VALUES ('".mysql_result(mysql_query("SELECT MAX(`paper_id`) FROM `papers`"), 0)."',
					'".mysql_result(mysql_query("SELECT `author_id` FROM `authors` WHERE `authors`.`author_name`='$i[name]'"), 0)."')";
	if(!mysql_query($send_papauth, $db)){
		echo "error sending 'papers_authors' $i[name]<br />";
	} else {
		echo "success sending 'papers_authors' $i[name]<br />";
	}
}
echo "Sending completed";

?>