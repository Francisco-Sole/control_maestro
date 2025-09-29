<?php

function new_connection()
{
	include("ip.php");

	$link = new mysqli($ip, "fsole", "fsole", "controlMaestro");
	$link->set_charset("utf8") or die();

	return $link;
}
