<?php
var_dump($_REQUEST);
include('config.php');
include('functions.php');

// принимаем данные из формы
$author_name = $_POST['Author_Name']; 
$affiliation=$_POST['Affiliation']; 
$email = $_POST['email'];
$paper_name = $_POST['paper_name'];

//if(isset($_POST['correspondence'])) $correspondence = 1; // если поставлена галочка

//подключение к серверу
try {
	$db = connect_db();
	$query=$db->prepare("Select count(*) from accounts where email=?");
	$query->execute(array($email));
	if($query->fetch(PDO::FETCH_NUM)[0]==0){
		// new user registration
		$query=$db->prepare("Insert into accounts (email,name,affiliation) values (?,?,?)");
		$query->execute(array($email,$author_name,$affiliation));
		$account_id=$db->lastInsertId();
		$db -> exec("Insert into papers (account_id,status) values ($account_id,$status_new)");
		$paper_id=$db->lastInsertId();
		$file_name = date('U').'.pdf'; // количество секунд от начала эпохи
		if (copy($_FILES['file']['tmp_name'], $uploaddir . $file_name)) {
			echo "Файл успешно загружен на сервер<br />";
		}else {
			echo "Ошибка! Не удалось загрузить файл на сервер!<br />";
			exit; 
		}
		$query=$db->prepare("Insert into paper_variants (paper_id,title,paper_filename) values (?,?,?)");
		$query->execute(array($paper_id,$paper_name,$file_name));
		die();
	}else{
		//user exists
		die("user exist, login first!");
	};
	
}
catch(PDOException $err) {
	echo $err -> getMessage();
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