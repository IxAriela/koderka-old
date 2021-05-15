<?php

define("EMAIL_ODESILATELE", "info@koderka.net");
define("EMAIL_PRIJEMCE", " ivet@koderka.net");
define("CESTA_KOREN", str_replace("index.php", "", $_SERVER["SCRIPT_FILENAME"]));
define("CESTA_KATALOG_OBRAZKU", CESTA_KOREN . "katalog-obrazku/");



//...Nastavení "hladiny" chyb
//error_reporting(E_ERROR);
error_reporting(E_ALL);

session_start();


	define("PREFIX", "/nespor-levova/");
	define("ADRESA_E_SHOPU", "http://localhost/");


//}
?>
