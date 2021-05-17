<?php

/** Ochrana roti SQL injection
 * @param string $text text s poteciálně nebezpečnými znaky
 * @return string text s bezpečnými znaky */
function gpc_addslashes($text) {
	return (get_magic_quotes_gpc() ? $text : addslashes($text));
}

/** Převedení na bezpečné znaky
 * @param string $text text s poteciálně HTML nebezpečnými znaky
 * @param bool $pouze_return nevrací se pomocí funkce echo
 * @return string text s HTML bezpečnými znaky */
function html_entities($text, $pouze_return = false) {
	if ($pouze_return) {
		if ($text == null || $text == "")
			return("");
		//else return htmlentities($text, null, "utf-8");
		else
			return htmlspecialchars($text, ENT_QUOTES, "utf-8");
	} else {
		if ($text == null || $text == "")
			echo("");
		//else echo(htmlentities($text, null, "utf-8"));
		else
			echo(htmlspecialchars($text, ENT_QUOTES, "utf-8"));
	}
}

/** Převedení HTML entit na původní význam
 * @param string $text text s HTML entitami
 * @return string text s odstraněnými entitami */
function unhtml_entities($text) {
	$trans_tbl = get_html_translation_table(HTML_ENTITIES);
	$trans_tbl = array_flip($trans_tbl);
	return strtr($text, $trans_tbl);
}

/** Kontrola e-mailové adresy
 * @param string $email e-mailová adresa
 * @return bool syntaktická správnost adresy */
function check_email($email) {
	/* $atom = '[-a-z0-9!#$%&\'*+/=?^_`{|}~]'; // znaky tvořící uživatelské jméno
	  $domain = '[a-z0-9]([-a-z0-9]{0,61}[a-z0-9])'; // jedna komponenta domény
	  return eregi("^$atom+(\\.$atom+)*@($domain?\\.)+$domain\$", $email); */
	//return eregi('^[a-z0-9_]{1}[a-z0-9\-_]*(\.[a-z0-9\-_]+)*@[a-z0-9]{1}[a-z0-9\-_]*(\.[a-z0-9\-_]+)*\.[a-z]{2,4}$', $email);
	return preg_match('/^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+.[A-Za-z]{2,5}$/', $email);
}


/** Vrácení aktuální URL
 * @return string adresa */
function get_page_url($pouze_soubor = false) {
	if ($pouze_soubor)
		return $_SERVER["PHP_SELF"] . (IsSet($_SERVER["QUERY_STRING"]) ? ("?" . $_SERVER["QUERY_STRING"]) : "");
	else
		return "http" . (IsSet($_SERVER["HTTPS"]) ? "s" : "") . "://" . $_SERVER["HTTP_HOST"] . $_SERVER["PHP_SELF"] . (IsSet($_SERVER["QUERY_STRING"]) ? ("?" . $_SERVER["QUERY_STRING"]) : "");
}

/** Odebrání parametru z URL
 * @param string $url adresa, s níž se bude pracovat
 * @param string $param parametr, jež se bude odebírat
 * @return string adresa bez parametru */
function remove_from_url($url, $param, $amp = false) {
	if ($amp)
		return str_replace("&", "&amp;", (preg_replace("~(\\?)($param)=[^&]*&|[&?]($param)=[^&]*~", '\\1', $url)));
	else
		return preg_replace("~(\\?)($param)=[^&]*&|[&?]($param)=[^&]*~", '\\1', $url);
}

/** Přidání parametru do URL
 * @param string $url adresa, s níž se bude pracovat
 * @param string $param parametr, jež se bude přidávat
 * @param string $hotnota hodnota parametru, jež se bude přidávat
 * @return string adresa s novým parametrem */
function add_to_url($url, $param, $hodnota, $amp = false) {
	if ($amp)
		return str_replace("&", "&amp;", (remove_from_url($url, $param) . ((strrpos($url, "?") === false) ? "?" : "&") . $param . "=" . $hodnota));
	else
		return remove_from_url($url, $param) . ((strrpos($url, "?") === false) ? "?" : "&") . $param . "=" . $hodnota;
}


/** Doplnění http protokolu do případné adresy
 * @param string $adresa adresa
 * @param string $schema schéma, které bude přičteno, pokud adresa žádné neobsahuje
 * @return string adresa */
function doplni_www($adresa, $schema = "http://") {
	$scheme = array("http", "https", "ftp");
	for ($i = 0; $i < count($scheme); $i++) {
		if (strtolower(substr($adresa, 0, strlen($scheme[$i]))) == $scheme[$i])
			return $adresa;
	}
	return $schema . $adresa;
}

/** Kontrola www adresy
 * @param string $url_addres adresa ku kontrole
 * @param string $check_domain parametr, zda-li se má kontrolovat MX záznam
 * @return bool platnost adresy */
function kontrola_www($url_addres, $check_domain = 0) {
	if (trim($url_addres) == null)
		return false;
	$scheme = array("http", "https", "ftp");
	$url = parse_url(trim($url_addres));
	if (in_array($url["scheme"], $scheme) == 0)
		return false;
	if ($url["host"] == null)
		return false;
	if ($check_domain == 0)
		return true;
	if (getmxrr($url["host"], $MX))
		return $MX;
	else
		return false;
}


/** Kontrola platnosti čísla
 * @param string $str číslo ku kontrole
 * @param bool $cele parametr, zda se má kontrolovat jen integer
 * @return bool platnost čísla */
function kontrola_cisla($str, $cele = false) {
	if ($cele)
		return preg_match("/^([0-9-]+)$/", $str);
	else
		return preg_match("/^([0-9.,-]+)$/", $str);
}


/** Převedení čísla pro vkládání do textu
 * @param string $str číslo ku převodu
 * @return string číslo se zaměněou tečkou za čárku */
function cislo_do_textu($str) {
	return str_replace(".", ",", $str);
}


/** Odstranění diakritiky v UTF-8 textu
 * @param string $text text ku odstranění
 * @return string text bez diakritiky */
function cs_utf2ascii($text) {
	static $tbl = array("\xc3\xa1" => "a", "\xc3\xa4" => "a", "\xc4\x8d" => "c", "\xc4\x8f" => "d", "\xc3\xa9" => "e", "\xc4\x9b" => "e", "\xc3\xad" => "i", "\xc4\xbe" => "l", "\xc4\xba" => "l", "\xc5\x88" => "n", "\xc3\xb3" => "o", "\xc3\xb6" => "o", "\xc5\x91" => "o", "\xc3\xb4" => "o", "\xc5\x99" => "r", "\xc5\x95" => "r", "\xc5\xa1" => "s", "\xc5\xa5" => "t", "\xc3\xba" => "u", "\xc5\xaf" => "u", "\xc3\xbc" => "u", "\xc5\xb1" => "u", "\xc3\xbd" => "y", "\xc5\xbe" => "z", "\xc3\x81" => "A", "\xc3\x84" => "A", "\xc4\x8c" => "C", "\xc4\x8e" => "D", "\xc3\x89" => "E", "\xc4\x9a" => "E", "\xc3\x8d" => "I", "\xc4\xbd" => "L", "\xc4\xb9" => "L", "\xc5\x87" => "N", "\xc3\x93" => "O", "\xc3\x96" => "O", "\xc5\x90" => "O", "\xc3\x94" => "O", "\xc5\x98" => "R", "\xc5\x94" => "R", "\xc5\xa0" => "S", "\xc5\xa4" => "T", "\xc3\x9a" => "U", "\xc5\xae" => "U", "\xc3\x9c" => "U", "\xc5\xb0" => "U", "\xc3\x9d" => "Y", "\xc5\xbd" => "Z");
	return strtr($text, $tbl);
}

/** Odstranění diakritiky v UTF-8 textu
 * @param string $text text ku odstranění
 * @return string text bez diakritiky */
function xx_utf2ascii($text) {
	setlocale(LC_CTYPE, "cs_CZ.utf-8");
	$vysledek = iconv('UTF-8', 'ASCII//TRANSLIT', $text);
	$odstranit = array("'", "\"", "^");
	$vysledek = str_replace($odstranit, "", $vysledek);
	return($vysledek);
}

/** Vygenerovani pratelske URL adresy
 * @param string $title retezec, ze ktereho vygenerujeme url adresu
 * @return string $address vraceny retezec obsahujici friendly url
 */
function seo_url($text) {
	$address = $text;
	//$address = strtolower (cs_utf2ascii($address));
	$address = strtolower(xx_utf2ascii($address));
	$re = "/[^[:alpha:][:digit:]]/";
	$replacement = "-";
	$address = preg_replace($re, $replacement, $address);
	$address = trim($address, "-");
	$re = "/[-]+/";
	$replacement = "-";
	$address = preg_replace($re, $replacement, $address);
	return $address;
}



/** Vrácení mime typu dokumentu
 * @param string $contenttype typ ku nastavení
 * @param bool $no_header vrátí string pro META TAG
 * @param string $charset znaková sada
 * @return header hlavička */
function sendContentType($contenttype, $no_header = false, $charset = "utf-8") {
	$accept = false;
	if ($contenttype == "application/xhtml+xml") {
		if (stristr($_SERVER["HTTP_ACCEPT"], "application/xhtml+xml")) {
			if (preg_match("/application\/xhtml\+xml;q=0(\.[1-9]+)/i", $_SERVER["HTTP_ACCEPT"], $matches)) {
				$xhtml_q = $matches[1];
				if (preg_match("/text\/html;q=0(\.[1-9]+)/i", $_SERVER["HTTP_ACCEPT"], $matches)) {
					$html_q = $matches[1];
					if ($xhtml_q >= $html_q)
						$accept = true;
				}
			} else
				$accept = true;
		}
		if (stristr($_SERVER["HTTP_USER_AGENT"], "W3C_Validator"))
			$accept = true;
		if (!IsSet($_SERVER["HTTP_ACCEPT"]))
			$accept = true;
	}
	if (!$accept && ($contenttype == "application/xhtml+xml"))
		$contenttype = "text/html";
	if ($no_header)
		return "Content-Type: " . $contenttype . "; charset=" . $charset;
	else
		header("Content-Type: " . $contenttype . "; charset=" . $charset);
}



/** Odeslání e-mailu v HTML formátu
 * @param string $adresat adresát
 * @param string $telo tělo e-mailu */
function odesli_email($adresat, $predmet, $telo) {
	$hlavicka = "From: " . EMAIL_ODESILATELE . "\r\n";
	if (EMAIL_PRIJEMCE_SKRYTY != null)
		$hlavicka.= "Bcc: " . EMAIL_PRIJEMCE_SKRYTY . "\r\n";
	$hlavicka.= "X-Mailer: X-Mailer: PHP/" . phpversion() . "\r\n";
	$hlavicka.= "Content-Type: text/html; charset=utf-8\r\n";
	return mail($adresat, $predmet, $telo, $hlavicka);
}



function OrizniText($text, $pocetZnaku) {
	$text = strip_tags($text);
	if ($pocetZnaku > mb_strlen($text))
		$pocetZnaku = mb_strlen($text) - 20;
	$mezeraPredKoncem = mb_strpos($text, ' ', $pocetZnaku);
	if ($mezeraPredKoncem == false)
		$mezeraPredKoncem = $pocetZnaku;
	$text = mb_substr($text, 0, $mezeraPredKoncem);
	$text .= "&hellip;";
	return($text);
}


?>
