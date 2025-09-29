<?php

function new_connection()
{
	include("ip.php");

	$link = new mysqli($ip, "root", "", "control_maestro");
	$link->set_charset("utf8") or die();

	return $link;
}
