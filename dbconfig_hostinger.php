<?php

	$DB_HOST = 'localhost';
	$DB_USER = 'u211841485_root';
	$DB_PASS = 'root321';
	$DB_NAME = 'u211841485_max';
	
	try{
		$DB_con = new PDO("mysql:host={$DB_HOST};dbname={$DB_NAME}",$DB_USER,$DB_PASS);
		$DB_con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	catch(PDOException $e){
		echo $e->getMessage();
	}