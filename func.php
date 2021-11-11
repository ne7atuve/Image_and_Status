<?php 

function get_user_by_email($email)
{
	$pdo = new PDO("mysql:host=array;dbname=my_project;", "root", "");
	$sql = "SELECT * FROM users WHERE email=:email";
	$statement = $pdo->prepare($sql);
	$statement->execute(["email" => $email]);
	$user = $statement->fetch(PDO::FETCH_ASSOC);
	return $user;
}

function add_user($email, $password)
{
	$pdo = new PDO("mysql:host=array;dbname=my_project;", "root", "");
	$sql = "INSERT INTO users (email, password, role, status, image) VALUES (:email, :password, :role, '', '')";
	$passwd = password_hash($password, PASSWORD_DEFAULT);
	$statement = $pdo->prepare($sql);
	$final = $statement->execute(["email" => $email, "password" => $passwd, "role" => "user"]);
	return $pdo->lastInsertId();
}

function set_flash_message($name, $message)
{
	$_SESSION[$name] = $message;
}

function redirect_to($path)
{
	header("Location: {$path}");
	exit;
}

function display_flash_message($name)
{
	if(isset($_SESSION[$name]))
	{
		echo "<div class=\"alert alert-{$name} text-dark\" role=\"alert\"> {$_SESSION[$name]}</div>";
		unset($_SESSION[$name]);
	}
}

function login($email, $password)
{
	$user = get_user_by_email($email);

	if($user)
	{
		if(password_verify($password, $user["password"]) === true)
		{
			$_SESSION["login"] = true;
			$_SESSION["user"] = $user;
			$_SESSION["current_user"] = $email;
			$_SESSION["role"] = $user["role"];
			redirect_to("/Учебный проект/users.php");
		}
		else
		{
			set_flash_message("danger", "Неправильно введен пароль!");
			redirect_to("/Учебный проект/page_login.php");
		}
	}
	else
	{
		set_flash_message("danger", "Такого пользователя не существует!");
		redirect_to("/Учебный проект/page_login.php");
	}
	return true;
}

function add_info($id, $name, $work, $telephone, $address)
{
	$pdo = new PDO("mysql:host=array;dbname=my_project;", "root", "");
	$sql = "INSERT INTO info (name, work, telephone, address, user_id) VALUES (:name, :work, :telephone, :address, :user_id)";
	$statement = $pdo->prepare($sql);
	$statement->execute(["name" => $name, "work" => $work, "telephone" => $telephone, "address" => $address]);
	$info = $statement->fetch(PDO::FETCH_ASSOC);
	return $info;
}

function add_social_networks($id, $vk, $telegram, $instagram)
{
	$pdo = new PDO("mysql:host=array;dbname=my_project;", "root", "");
	$sql = "INSERT INTO social_networks (vk, telegram, instagram, user_id) VALUES (:vk, :telegram, :instagram, :user_id)";
	$statement = $pdo->prepare($sql);
	$statement->execute(["vk" => $vk, "telegram" => $telegram, "instagram" => $instagram]);
	$networks = $statement->fetch(PDO::FETCH_ASSOC);
	return $networks;

}

function set_status($id, $status) 
{
	$pdo = new PDO("mysql:host=array;dbname=my_project;", "root", "");
	$sql = "UPDATE users SET status = :status WHERE id = :id";
	$statement = $pdo->prepare($sql);
	$result = $statement->execute(["status" => $status, "id" => $id]);
}

function add_image($id, $image)
{

	$image = "img/demo/avatars/" . time() . $_FILES["image"]["name"];
	move_uploaded_file($_FILES["image"]["tmp_name"], "../" . $image);

	$pdo = new PDO("mysql:host=array;dbname=my_project;", "root", "");
	$sql = "UPDATE users SET image = :image WHERE id = :id";
	$statement = $pdo->prepare($sql);
	$result = $statement->execute(["image" => $image, "id" => $id]);
}


?>