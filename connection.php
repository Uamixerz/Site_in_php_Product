<!--Код для підключення БД-->
<?php
$user="root";//імя користувача для бд
$pass="";//пароль користувача
try{
    $dbh = new PDO("mysql:host=localhost;dbname=pv121",$user,$pass);//Строка для спроби входу в бд
}catch (Exception $ex){//Ловимо помилку
    print ("Error ".$ex->getMessage()."<br/>");
    exit();//зупиняємо скріпт
}