<?php
	function Stav($zprava, $typ) {
		$_SESSION["stav"] = array();
		$_SESSION["stav"]["zprava"] = $zprava;
		$_SESSION["stav"]["typ"] = $typ;
	}
	
	//...showHTML
	//...showPOPUP
	function ZobrazStav() {
		if (IsSet($_SESSION["stav"]) && IsSet($_SESSION["stav"]["zprava"]) && IsSet($_SESSION["stav"]["typ"])) {
			$zprava = $_SESSION["stav"]["zprava"];
			//...OK
			if ($_SESSION["stav"]["typ"] == 1) {
				if (STAV_OK == "showPOPUP") {
					echo ("<script type=\"text/javascript\">\n");
					echo ("	//<![CDATA[\n");
					echo ("		stavZprava = \"".$zprava."\";\n");
					echo ("		stavTyp = 1;\n");
					echo ("	//]]>\n");
					echo ("</script>\n");
					echo ("<noscript>\n");
					echo ("	<div id=\"stavOk\">".$zprava."</div>\n");	
					echo ("</noscript>\n");
				} else echo ("<div id=\"stavOk\">".$zprava."</div>");
			}
			//...Chyba
			if ($_SESSION["stav"]["typ"] == 0) {
				if (STAV_ERR == "showPOPUP") {
					echo ("<script type=\"text/javascript\">\n");
					echo ("	//<![CDATA[\n");
					echo ("		stavZprava = \"".$zprava."\";\n");
					echo ("		stavTyp = 0;\n");
					echo ("	//]]>\n");
					echo ("</script>\n");
					echo ("<noscript>\n");
					echo ("	<div id=\"stavChyba\">".$zprava."</div>\n");	
					echo ("</noscript>\n");
				} else echo ("<div id=\"stavChyba\">".$zprava."</div>");
			}
			$_SESSION["stav"] = null;
		}
	}
?>