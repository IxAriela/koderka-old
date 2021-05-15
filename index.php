<?php
	date_default_timezone_set("Europe/Prague");

	include("system/source.php");
	include("system/function.php");
	include("system/session.php");


	$pageIndex = false;
	$cesta = str_replace("index.php", "", $_SERVER["SCRIPT_FILENAME"]);

	if (!IsSet($_GET["page"]) or !file_exists("pages/page_".basename($_GET["page"]).".php")) $page = "404";
	else $page = basename($_GET["page"]);

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
		if ($page == "akce") {
			$presmerovani = IsSet($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : "index.html";

			if (IsSet($_GET["writeMe"])) {include("pages/action/writeMe.php");}

			header("Location: ".$presmerovani);
			exit;

		//...Zobrazování ostatních stránek
		} else require_once("pages/page_".$page.".php");
	}

?>
