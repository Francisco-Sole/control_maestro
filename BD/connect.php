<?php

function new_connection()
{
	include("ip.php");

	$link = new mysqli($ip, "root", "", "controlMaestro");
	$link->set_charset("utf8") or die();

	return $link;
}
