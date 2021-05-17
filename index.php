<?php
	date_default_timezone_set("Europe/Prague");

	include("system/source.php");
	include("system/function.php");
	include("system/session.php");


	$pageIndex = false;
	$cesta = str_replace("index.php", "", $_SERVER["SCRIPT_FILENAME"]);

	if (!IsSet($_GET["page"]) or !file_exists("pages/page_".basename($_GET["page"]).".php")) $page = "404";
	else $page = basename($_GET["page"]);

	$action = isset($_GET["action"]) ? basename($_GET["action"]) : "";   //ternární operátor  $hodnota = podminka ? pokud ano : pokud ne;

	//...Default
	if (!IsSet($_GET["page"])) $page = "index";

	
	if (IsSet($_GET["php-info"])) {
		sendContentType("text/html"); //...PHP info
		require_once("php-info.php");
	} else if (IsSet($_GET["sitemap-xml"])) {
		sendContentType("application/xml"); //...Mapa stránek
		require_once("sitemap-xml.php");
	} else {
		//...Provádění akcí nad daty
		if ($action) {
			$location = IsSet($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : "index.html";

			if ($action == "writeMe") {include("pages/action/writeMe.php");}

			header("Location: ".$location);
			exit;

		//...Zobrazování ostatních stránek
		} else require_once("pages/page_".$page.".php");
	}

?>
