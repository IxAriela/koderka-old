<?php

//...ANTISPAM lest
if ($_POST["name"] != "" || $_POST["email"] != "" || $_POST["web"] != "" || $_POST["text"] != "") {
    $zprava = "Zpráva byla chycena do ANTISPAM PASTI.<br />";
    $zprava .= "Byla vyplněna políčka, která by měla být běžnému uživateli skryta (CSS).<br />";
    $zprava .= "Pokud máte v prohlížeči vypnuté styly a vidíte všechna políčka, prosím vyplňte pouze ta, jejichž popisky nejsou celé velkými písmeny.";
    Stav($zprava, 0);
} else {
    //...Uložení do SESSION
    $_SESSION["NABIDKA"] = array();
    $_SESSION["NABIDKA"]["Jmeno"] = $_POST["txtJmeno"];
    $_SESSION["NABIDKA"]["Telefon"] = $_POST["txtTelefon"];
    $_SESSION["NABIDKA"]["Mail"] = $_POST["txtMail"];
    $_SESSION["NABIDKA"]["Lokalita"] = $_POST["txtLokalita"];
    $_SESSION["NABIDKA"]["Typ"] = $_POST["txtTyp"];
    $_SESSION["NABIDKA"]["Vzkaz"] = $_POST["txtVzkaz"];

    //...Kontrola dat
    $Jmeno = IsSet($_POST["txtJmeno"]) && trim($_POST["txtJmeno"]) != "" ? $_POST["txtJmeno"] : null;
    $Telefon = IsSet($_POST["txtTelefon"]) && trim($_POST["txtTelefon"]) != "" ? $_POST["txtTelefon"] : null;
    $Mail = IsSet($_POST["txtMail"]) && trim($_POST["txtMail"]) != "" ? $_POST["txtMail"] : null;
    $Lokalita = IsSet($_POST["txtLokalita"]) && trim($_POST["txtLokalita"]) != "" ? $_POST["txtLokalita"] : null;
    $Typ = IsSet($_POST["txtTyp"]) && trim($_POST["txtTyp"]) != "" ? $_POST["txtTyp"] : null;
    $Vzkaz = IsSet($_POST["txtVzkaz"]) && trim($_POST["txtVzkaz"]) != "" ? $_POST["txtVzkaz"] : null;

    $stav = "";
    if ($Jmeno == null)
	$stav .= "&nbsp;&ndash;&nbsp;jméno<br />";
    if ($Telefon == null)
	$stav .= "&nbsp;&ndash;&nbsp;telefon<br />";
    if ($Mail == null)
	$stav .= "&nbsp;&ndash;&nbsp;e-mail<br />";
    if (!check_email($Mail))
	$stav .= "&nbsp;&ndash;&nbsp;e-mail není v platném formátu<br />";
    if ($Lokalita == null)
	$stav .= "&nbsp;&ndash;&nbsp;lokalita<br />";
    if ($Typ == null)
	$stav .= "&nbsp;&ndash;&nbsp;typ stavby<br />";
    if ($Vzkaz == null)
	$stav .= "&nbsp;&ndash;&nbsp;vzkaz<br />";
    if ($stav != ""){
	Stav("Některé údaje nebyly správně zadány:<br />" . $stav, 0);
	    $location = PREFIX . "#formNabidka";
	    header("Location: " . $location . "");
	    die();
    } else {
	$hlavicka = "From: " . EMAIL_ODESILATELE . " \n";
	$hlavicka .= "X-Mailer: X-Mailer: PHP/" . phpversion() . " \n";
	if (EMAIL_PRIJEMCE_SKRYTY != null)
	    $hlavicka .= "Bcc: " . EMAIL_PRIJEMCE_SKRYTY . "\n";
	$hlavicka .= "Content-Type: text/plain; charset=utf-8";

	$text = "Zákazník/ce " . $Jmeno . " chce prodat " . $Typ . "\r\n\r\n\r\n\r\n";
	$text .= "Jméno: " . $Jmeno . "\r\n\r\n";
	$text .= "Telefon: " . $Telefon . "\r\n\r\n";
	$text .= "E-mail: " . $Mail . "\r\n\r\n";
	$text .= "Lokalita: " . $Lokalita . "\r\n\r\n";
	$text .= "Typ stavby: " . $Typ . "\r\n\r\n";
	$text .= "Vzkaz: " . $Vzkaz . "\r\n\r\n\r\n\r\n";
	$text .= $presmerovani . "\r\n\r\n";
	$text .= Date("j. n. Y H:i:s") . ", IP: " . $_SERVER["REMOTE_ADDR"];

	//echo "<pre>".$text."</pre>";
	//exit;
	if (mail(EMAIL_PRIJEMCE, "Uspesnereality.cz - nabidka", $text, $hlavicka)) {
	    Stav("Vzkaz byl úspěšně odeslán.", 1);
	    unset($_SESSION["NABIDKA"]);
	    $_SESSION['ODESLAN_MAIL'] = 1;
	    $location = PREFIX . "dekujeme-nabidka.html";
	    header("Location: " . $location . "");
	    die();
	} else {
	    Stav("Omlouváme se, ale vzkaz se nepodařilo odeslat.", 0);
	    $location = PREFIX . "#formNabidka";
	    header("Location: " . $location . "");
	    die();
	}
    }
}
//echo($stav);       die;
?>
