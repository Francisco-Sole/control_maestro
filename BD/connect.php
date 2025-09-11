<?php

function new_connection(){
	include ("ip.php");

	$link = new mysqli($ip, "fsole", "fsole", "modulocliente");
	$link->set_charset("utf8") or die();

	return $link;
}
