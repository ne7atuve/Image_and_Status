<?php
session_start();
require "func.php";

$name = $_POST["name"];
$work = $_POST["work"];
$telephone = $_POST["telephone"];
$address = $_POST["address"];
$email = $_POST["email"];
$password = $_POST["password"];
$vk = $_POST["vk"];
$telegram = $_POST["telegram"];
$instagram = $_POST["instagram"];
$status = $_POST["status"];
$image = $_POST["image"];


$user = get_user_by_email($email);

if(!empty($user))
{
	set_flash_message("danger", "Этот email уже занят другим пользователем");
	redirect_to("/Учебный проект/create_user.php");
}

add_user($email, $password);

add_info($id, $name, $work, $telephone, $address);
add_social_networks($id, $vk, $telegram, $instagram);
set_status($id, $status);
add_image($id, $image);


set_flash_message("success", "Пользователь успешно добавлен!");
redirect_to("/Учебный проект/users.php");



?>