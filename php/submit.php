<?php
var_dump($_REQUEST);
	include 'config.php';

	// принимаем данные из формы
	$authors = $_POST['authors']; 
	
	foreach($authors as $auth) {
		foreach($auth as $elem) {
			if($elem == "") {
				exit("Не все данные для автора введены!");
			}
		}
	}
	//$correspondence = 0;
	
	if($_POST['paper_name'] != "") {
		$paper_name = $_POST['paper_name'];
	} else exit("Необходимо ввести название статьи!");
	
	if($_POST['pacs_numbers'] != "") {
		$pacs_numbers = $_POST['pacs_numbers'];
	} else exit("Необходимо ввести PACS numbers!");
	
	if($_POST['keywords'] != "") {
		$keywords = $_POST['keywords'];
	} else exit("Необходимо ввести ключевые слова!");
	
	if($_POST['abstract'] != "") {
		$abstract = $_POST['abstract'];
	} else exit("Необходимо ввести abstract!");
	
	$file_name = $_FILES['file']['name'];
	
	$file_name = date('U').'.zip'; // количество секунд от начала эпохи

	if (copy($_FILES['file']['tmp_name'], $uploaddir . $file_name)) {
		echo "Файл успешно загружен на сервер<br />";
	}else {
		echo "Ошибка! Не удалось загрузить файл на сервер!<br />";
		exit; 
	}

	//if(isset($_POST['correspondence'])) $correspondence = 1; // если поставлена галочка

	//подключение к серверу
	try {
		$db = new PDO("mysql:host=$host", $user, $pass);
		$db -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$db -> exec("set names utf8");
		echo "connection established</br>";
	}
	catch(PDOException $err) {
		echo $err -> getMessage();
	}
	
	//выбор базы данных
	try{
		$db -> exec("use $dbname");
	}
	catch(PDOException $err) {
		echo $err -> getMessage() . "<br />";
	}

	//papers
	try {
		$db -> exec("INSERT INTO papers (received) VALUES (NOW())");
		echo "success sending in table 'papers'</br>";
	}
	catch(PDOException $err) {
		echo $err -> getMessage() . "<br />";
	}
	
	$lastid = $db -> lastInsertId(); //чтобы потом вставлять в другие колонки
	
// будет ещё отдельная штука "обновить статью", это "initial commit" статьи
	//paper_variant
	try {
		$db -> exec("INSERT INTO paper_variant (paper_id, paper_name, pacs_numbers, received, abstract, keywords, file)
				VALUES (".$lastid.", "
						.$db -> quote($paper_name).", "
						.$db -> quote($pacs_numbers).", 
						NOW(), "
						.$db -> quote($abstract).",	"
						.$db -> quote($keywords).",
						'$file_name')");
		echo "success sending in table 'paper_variant'</br>";
	}
	catch(PDOException $err) {
		echo $err -> getMessage() . "<br />";
	}
	
// добавляем автора, если ещё нет; для обновления данных автора нужен личный кабинет
	//CORRESPONDENCE
	foreach($authors as $i) {
		if(($db -> query("SELECT COUNT(`author_id`) FROM `authors` WHERE `authors`.`author_name`='$i[name]'") -> fetchColumn()) == 0){
			try {
				$db -> exec("INSERT INTO authors (author_name, affiliation, author_email)
								VALUES (".$db -> quote($i['name']).", "
										.$db -> quote($i['affiliation']).", "
										.$db -> quote($i['email']).")");
				echo "success sending '$i[name]' in table 'authors'</br>";
			}
			catch(PDOException $err) {
				echo $err -> getMessage() . "<br />";
			}
		} else echo "Author $i[name] already exists<br />";
		
		try {
			$db -> exec("INSERT INTO papers_authors (paper_id, author_id)
							VALUES (".$lastid.", '"
									 .$db -> query("SELECT `author_id` FROM `authors` WHERE `authors`.`author_name`='$i[name]'") -> fetchColumn()."')");
			echo "success sending in table 'papers_authors'</br>";
		}
		catch(PDOException $err) {
				echo $err -> getMessage() . "<br />";
		}
	}
	$db = null;
	echo "Sending completed";

?>