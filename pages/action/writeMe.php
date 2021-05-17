<?php
//...ANTISPAM lest
if ($_POST["name"] != "" || $_POST["email"] != "" || $_POST["web"] != "" || $_POST["text"] != "") {

    $zprava = "Zpráva byla chycena do ANTISPAM LSTI.<br />";
    $zprava .= "Byla vyplněna políčka, která by měla být běžnému uživateli skryta (CSS).<br />";
    $zprava .= "Pokud máte v prohlížeči vypnuté styly a vidíte všechna políčka, prosím vyplňte pouze ta, jejichž popisky nejsou celé velkými písmeny.";

    Stav($zprava, 0);
} else {
//...Uložení do SESSION
    $_SESSION["WRITE_ME"] = array();
    $_SESSION["WRITE_ME"]["Name"] = $_POST["txtName"];
    $_SESSION["WRITE_ME"]["Email"] = $_POST["txtEmail"];
    $_SESSION["WRITE_ME"]["Message"] = $_POST["txtMessage"];
    $_SESSION["WRITE_ME"]["Url"] = $_POST["url"];

    //...Kontrola dat
    $Name = IsSet($_POST["txtName"]) && trim($_POST["txtName"]) != "" ? $_POST["txtName"] : null;
    $Email = IsSet($_POST["txtEmail"]) && trim($_POST["txtEmail"]) != "" ? $_POST["txtEmail"] : null;
    $Message = IsSet($_POST["txtMessage"]) && trim($_POST["txtMessage"]) != "" ? $_POST["txtMessage"] : null;
    $Url = IsSet($_POST["url"]) && trim($_POST["url"]) != "" ? $_POST["url"] : null;

    $cond = "";
    if ($Name == null)
        $cond .= "&nbsp;&ndash;&nbsp;jméno<br />";  //$cond.= "abc"; znamena na konec promenne $cond doplni dalsi text, promenna neexistovala. Dlouhy zapis by byl $cond = $cond . "abc";
    if ($Email == null)
        $cond .= "&nbsp;&ndash;&nbsp;e-mail<br />";
    if ($Message == null)
        $cond .= "&nbsp;&ndash;&nbsp;odkaz<br />";


    if ($cond != "") {
        Stav("Některé údaje nebyly správně zadány:<br />" . $cond, 0);
    } else {
        $header = "From: " . EMAIL_ODESILATELE . " \r\n";
        $header .= "X-Mailer: X-Mailer: PHP/" . phpversion() . " \r\n";
        $header .= "Content-Type: text/plain; charset=utf-8";

        $text = "Jméno: " . $Name . "\n\n";
        $text .= "E-mail: " . $Email . "\n\n";
        $text .= "Vzkaz: " . $Message . "\n\n\n\n";
        $text .= "Odesláno z adresy: " . $Url . "\n\n\n\n";
        $text .= Date("j. n. Y H:i:s") . ", IP: " . $_SERVER["REMOTE_ADDR"];

        if (mail(EMAIL_PRIJEMCE, "Zpráva z webu koderka.net", $text, $header)) {

            Stav("Vzkaz byl úspěšně odeslán.", 1);
            unset($_SESSION["WRITE_ME"]);
            $_SESSION['ODESLAN_MAIL'] = 1;
            $location = PREFIX . "dekuji.html";
            //header("Location: " . $location . "");
            //die();    //to probublá zpět do indexu kde je header taky
        } else {
            Stav("Omlouváme se, ale vzkaz se nepodařilo odeslat.", 0);
        }
    }
}
