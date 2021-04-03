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

/** Převedení data z výstupu z MySQL do rozumné podoby pro zobrazení na webu
 * @param string $datum výstup z MySQL
 * @param bool $cas zobrazení času
 * @return string ve formátu D. M. RRRR */
function sql_date_to_html($datum, $cas = false, $separator = " ") {
	if ($cas)
		return date("j." . $separator . "n." . $separator . "Y H:i", strtotime($datum));
	else
		return date("j." . $separator . "n." . $separator . "Y", strtotime($datum));
}

/** Převedení data z výstupu z MySQL do podoby DD. MM. RRRR HH:mm pro zobrazení na webu
 * @param string $datum výstup z MySQL
 * @param bool $cas zobrazení času
 * @return string ve formátu D. M. RRRR */
function sql_date_to_html2($datum, $cas = false, $separator = " ") {
	if ($cas)
		return date("d." . $separator . "m." . $separator . "Y H:i", strtotime($datum));
	else
		return date("d." . $separator . "m." . $separator . "Y", strtotime($datum));
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

/*
  1. Základ pro kontrolu rozsahů je jednoduchý – den může být 1-31, měsíc 1-12 a rok povolíme jakýkoliv čtyřciferný:
  ^([1-9]|[12][0-9]|3[01])\.([1-9]|1[012])\.[0-9]{4}$
  2. Pro kontrolu počtu dní v měsíci použijeme aserce – za třicítkou nesmí následovat únor, za jednatřicítkou smí být pouze odpovídající měsíce:
  ^([1-9]|[12][0-9]|30(?=\\.[^2])|31(?=\.([13578][02]?\.)))\.([1-9]|1[012])\.[0-9]{4}$
  3. Roky dělitelné čtyřmi vyřešíme další asercí – pokud je za devětadvacítkou únor, tak musí být rok dělitelný čtyřmi (číslo je dělitelné čtyřmi, pokud je předposlední číslice sudá a poslední 0, 4, 8 nebo pokud je lichá a poslední 2, 6):
  ^([1-9]|19|[12][0-8]|29(?=\.([^2]|2\.([0-9]{2}([02468][048]|[13579][26]))))|30(?=\\.[^2])|31(?=\.([13578][02]?\.)))\.([1-9]|1[012])\.[0-9]{4}$
  4. A konečně kontrola století zajistí finální řešení – v celé století má únor 29 dní, jen pokud je dělitelné čtyřmi:
  ^([1-9]|19|[12][0-8]|29(?=\.([^2]|2\.(([02468][048]|[13579][26])00|[0-9]{2}(0[48]|[2468][048]|[13579][26]))))|30(?=\.[^2])|31(?=\.([13578][02]?\.)))\.([1-9]|1[012])\.[0-9]{4}$
  Výsledkem je tato funkce:
 */

/** Kontrola data
 * @param string $datum datum ve formátu d.m.rrrr
 * @return bool platnost data
 * @copyright Jakub Vrána, http://php.vrana.cz */
function platne_datum($datum) {
	return preg_match('~^([1-9]|19|[12][0-8]|29(?=\\.([^2]|2\\.(([02468][048]|[13579][26])00|[0-9]{2}(0[48]|[2468][048]|[13579][26]))))|30(?=\\.[^2])|31(?=\\.([13578][02]?\\.)))\\.([1-9]|1[012])\\.[0-9]{4}$~D', $datum);
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

/** Kontrola hesla
 * @param string $str heslo ku kontrole
 * @return bool platnost hesla
 * Heslo pro musí být alespoň 6 znaků dlouhé, musí v něm být alespoň jedna číslice, jedno velké a jedno malé písmeno (bez diakritiky). */
function kontrola_hesla($str) {
	if (strlen($str) < 6)
		return false;
	if (!preg_match('*[0-9]*', $str))
		return false;
	if (!preg_match('*[a-z]*', $str))
		return false;
	if (!preg_match('*[A-Z]*', $str))
		return false;
	return true;
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

/** Převedení čísla pro vkládání do SQL
 * @param string $str číslo ku převodu
 * @return string číslo se zaměněou čárkou za tečku */
function cislo_do_sql($str) {
	return str_replace(",", ".", $str);
}

/** Převedení čísla pro vkládání do textu
 * @param string $str číslo ku převodu
 * @return string číslo se zaměněou tečkou za čárku */
function cislo_do_textu($str) {
	return str_replace(".", ",", $str);
}

/** Převedení čísla pro výpis měny
 * @param string $str číslo ku převodu
 * @return string číslo s mezerami mezi tisíci a zvoleným počtem desetinných míst */
function mena($cislo, $pocet_mist = 2) {
	return number_format($cislo, $pocet_mist, ",", " ");
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

/** Drobečková navigace
 * @param unknown $IdHierarchie
 * @param string $odkaz
 */
function breadcrumbs($IdHierarchie, $odkaz = '') {
	global $conn, $IdFirmy, $stranka;
	echo('<a href="' . PREFIX . '" title="Úvod">Úvod</a>');
	if (in_array($IdHierarchie, explode(',', $IdFirmy))) {
		$cestafirmy = "";

		$hiFirmy = $conn->query("SELECT `id_hierarchie`, `hierarchie_id_hierarchie`, `hi_nazev`, `hi_seo` FROM `hierarchie` WHERE `id_hierarchie` = " . gpc_addslashes($IdHierarchie));
		if ($hiFirmy && $hiFirmy->num_rows > 0) {
			$vetev = $hiFirmy->fetch_array(MYSQLI_ASSOC);
			if ($odkaz != '')
				$cestafirmy = ("&nbsp;> <a href=\"" . $odkaz . $vetev["id_hierarchie"] . "\">" . html_entities($vetev["hi_nazev"], true) . "</a>");
			else
				$cestafirmy = ("&nbsp;> <a href=\"" . seo_v_seznamu($vetev["id_hierarchie"]) . "\">" . html_entities($vetev["hi_nazev"], true) . "</a>");
			$hiFirmy->close();
		}

		$NazevFirmy = (IsSet($_GET["upravit"]) ? gpc_addslashes(trim($_GET["upravit"])) : (IsSet($_GET["zobrazit"]) ? gpc_addslashes(trim($_GET["zobrazit"])) : false));
		$strankaIdFirmy = IsSet($_GET["vyrobce"]) ? gpc_addslashes($_GET["vyrobce"]) : false;
		$sql = "SELECT `id_firma`,`fa_nazev` FROM `firmy` WHERE `fa_nazev` = '" . $NazevFirmy . "' \n" .
				($strankaIdFirmy ? "AND `id_firma` = '" . $strankaIdFirmy . "' \n" : "");
		$entFirmy = $conn->query($sql);
		$adr = '';
		if ($entFirmy && $entFirmy->num_rows > 0) {
			$firma = $entFirmy->fetch_array(MYSQLI_ASSOC);
			$cestafirmy .= ("&nbsp;> <a href=\"" . seo_url($firma['fa_nazev']) . '.php?vyrobce=' . $firma['id_firma'] . "\">" . html_entities($firma['fa_nazev'], true) . "</a>");
			$entFirmy->close();
		}
		echo($cestafirmy);
	} else if (!empty($IdHierarchie))
		echo(cesta_v_seznamu($IdHierarchie, $odkaz));
	else {
		switch ($stranka) {
			case "osobni-udaje":
				$nazevCesty = "Osobní údaje";
				break;
			case "objednavky":
				$nazevCesty = "Objednávky";
				break;
			case "objednavka":
				$nazevCesty = "Objednávka";
				break;
			case "prihlasit":
				$nazevCesty = "Přihlášení";
				break;
			case "registrace":
				$nazevCesty = "Registrace";
				break;
			case "vysledky-vyhledavani":
				$nazevCesty = "Výsledky vyhledávání";
				break;
		}
		if (isset($nazevCesty))
			echo("&nbsp;> <a href=\"" . ($stranka) . ".php\">" . $nazevCesty . "</a>");
	}
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

/*
  // WINDOWS-1250 to ASCII for diacritic chars
  function cs_win2ascii($s) {
  return strtr($s, "\xe1\xe4\xe8\xef\xe9\xec\xed\xbe\xe5\xf2\xf3\xf6\xf5\xf4\xf8\xe0\x9a\x9d\xfa\xf9\xfc\xfb\xfd\x9e\xc1\xc4\xc8\xcf\xc9\xcc\xcd\xbc\xc5\xd2\xd3\xd6\xd5\xd4\xd8\xc0\x8a\x8d\xda\xd9\xdc\xdb\xdd\x8e", "aacdeeillnoooorrstuuuuyzAACDEEILLNOOOORRSTUUUUYZ");
  }
  // ISO-8859-2 to ASCII for diacritic chars
  function cs_iso2ascii($s) {
  return strtr($s, "\xe1\xe4\xe8\xef\xe9\xec\xed\xb5\xe5\xf2\xf3\xf6\xf5\xf4\xf8\xe0\xb9\xbb\xfa\xf9\xfc\xfb\xfd\xbe\xc1\xc4\xc8\xcf\xc9\xcc\xcd\xa5\xc5\xd2\xd3\xd6\xd5\xd4\xd8\xc0\xa9\xab\xda\xd9\xdc\xdb\xdd\xae", "aacdeeillnoooorrstuuuuyzAACDEEILLNOOOORRSTUUUUYZ");
  }
 */

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

/**
 * vrati blok HTML s vygenerovanymi odkazy pro prechod na dalsi stranku
 *
 * strankovani je ve tvaru <code>zobrazit vse | 1 2 3 4.. 8 | > | >></code>
 * @access public
 * @param integer $recordCount pocet zaznamu celkem
 * @param integer $recordStart od jakeho zaznamu se aktualne zobrazuje
 * @param string $url URL ktere se doplni k vygenerovanym odkazum
 * @param integer $recordShow maximalni pocet zaznamu zobrazenych na jedne strance
 * @param integer $pageLimit pocet generovanych odkazu cisel (pak se zobrazi ..)
 * @return string vrati HTML blok nebo null, pokud $recordCount <= $recordShow
 * */
function PageControl($recordCount, $recordStart, $url, $recordShow, $pageLimit = 5) {
	if ($url == null)
		$url = get_page_url();
	$out = null;
	$separator = '|';
	$separator = '';
	if (($recordCount > $recordShow) and ( $recordStart < $recordCount)):
		$pageLimit -= 2; // protoze pridavam vzdy jeden odkaz PRED a druhy ZA vygenerovanou radu cisel, odectu tyto dva z celk. poctu
		if ($pageLimit <= 0):
			$pageLimit = 1;
		endif;
		$pageThis = Ceil(($recordStart / $recordShow) + 1);
		$pageSum = Ceil($recordCount / $recordShow);

		$out .= "<span class=\"sStrankovac\" >";

		if ($recordStart <= 0):
			/* -----------------------------------------------------------------------
			  jestlize stojis na zacatku
			  ----------------------------------------------------------------------- */
			$gotoOlders = $recordStart + $recordShow;
			$gotoLast = $recordCount - $recordShow;

			// vykreslim odkazy 1 2 3 4 ...
			for ($smycka = 1; $smycka <= $pageSum and $smycka <= ($pageLimit + 1); $smycka ++):
				if ($smycka != $pageThis):
					$out .= '<a class="strankovacCislo" href="' . add_to_url($url, "recordStart", ($recordShow * ($smycka - 1)), true) . '">' . $smycka . '</a>';
				else:
					$out .= '<span class="strankovacAktivni">' . $smycka . '</span>';
				endif;
			endfor;
			if (($pageLimit + 1) < $pageSum):
				$out .= '<span class="strankovacDveTecky">..</span><a class="strankovacCislo" href="' . add_to_url($url, "recordStart", $gotoLast, true) . '">' . $pageSum . '</a>';
			endif;
			$out .= $separator;
			$out .= '<a class="strankovacP" href="' . add_to_url($url, "recordStart", $gotoOlders, true) . '"><span>&raquo;</span></a>';
			$out .= $separator;
			$out .= '<a class="strankovacPP" href="' . add_to_url($url, "recordStart", $gotoLast, true) . '"><span>&raquo;&raquo;</span></a>';
		elseif ($recordStart + $recordShow >= $recordCount):
			/* -----------------------------------------------------------------------
			  jestlize jsi pred koncem a nezobrazilo by se vsech "recordShow" zaznamu
			  ----------------------------------------------------------------------- */
			$gotoLatest = $recordStart - $recordShow;
			$podmineny_pageStart = 1;
			if ($pageLimit < $pageSum):
				$podmineny_pageStart = $pageSum - $pageLimit;
			endif;

			$out .= '<a class="strankovacLL" href="' . add_to_url($url, "recordStart", 0, true) . '"><span>&laquo;&laquo;</span></a>';
			$out .= $separator;
			$out .= '<a class="strankovacL" href="' . add_to_url($url, "recordStart", $gotoLatest, true) . '"><span>&laquo;</span></a>';
			$out .= $separator;
			if ($pageLimit + 1 < $pageSum):
				$out .= '<a class="strankovacCislo" href="' . add_to_url($url, "recordStart", 0, true) . '">1</a><span class="strankovacDveTecky">..</span>';
			endif;

			// vykreslim odkazy 1 2 3 4 ...
			for ($smycka = $podmineny_pageStart; $smycka <= $pageSum; $smycka ++):
				if ($smycka != $pageThis):
					$out .= '<a class="strankovacCislo" href="' . add_to_url($url, "recordStart", ($recordShow * ($smycka - 1)), true) . '">' . $smycka . '</a>';
				else:
					$out .= '<span class="strankovacAktivni">' . $smycka . '</span>';
				endif;
			endfor;
		else:
			/* -----------------------------------------------------------------------
			  jestlize jsi nekde uprostred
			  ----------------------------------------------------------------------- */
			$gotoLatest = $recordStart - $recordShow;
			$gotoOlders = $recordStart + $recordShow;
			$gotoLast = $recordCount - $recordShow;
			$podmineny_pageEnd = ( ( (Ceil($pageThis / $pageLimit)) * $pageLimit) + 1) > $pageSum ? $pageSum : ( (Ceil($pageThis / $pageLimit)) * $pageLimit) + 1;
			$podmineny_pageStart = ( ($podmineny_pageEnd - 1) - $pageLimit <= 0) ? 1 : ($podmineny_pageEnd - 1) - $pageLimit;

			$out .= '<a class="strankovacLL" href="' . add_to_url($url, "recordStart", 0, true) . '"><span>&laquo;&laquo;</span></a>';
			$out .= $separator;
			$out .= '<a class="strankovacL" href="' . add_to_url($url, "recordStart", $gotoLatest, true) . '"><span>&laquo;</span></a>';
			$out .= $separator;

			if ($pageLimit + 1 < $pageSum and $podmineny_pageStart > 1):
				$out .= '<a class="strankovacCislo" href="' . add_to_url($url, "recordStart", 0, true) . '">1</a><span class="strankovacDveTecky">..</span>';
			endif;

			// vykreslim odkazy 1 2 3 4 ...
			for ($smycka = $podmineny_pageStart; $smycka <= $podmineny_pageEnd; $smycka ++):
				if ($smycka != $pageThis):
					$out .= '<a class="strankovacCislo" href="' . add_to_url($url, "recordStart", ($recordShow * ($smycka - 1)), true) . '">' . $smycka . '</a>';
				else:
					$out .= '<span class="strankovacAktivni">' . $smycka . '</span>';
				endif;
			endfor;

			if ($pageLimit + 1 < $pageSum and $podmineny_pageEnd < $pageSum):
				$out .= '<span class="strankovacDveTecky">..</span><a class="strankovacCislo" href="' . add_to_url($url, "recordStart", $gotoLast, true) . '">' . $pageSum . '</a>';
			//$out .= '..<a href="'. $url. '&amp;recordStart='. $gotoLast. '">'. $pageSum. '</a>';
			endif;
			$out .= $separator;
			$out .= '<a class="strankovacP" href="' . add_to_url($url, "recordStart", $gotoOlders, true) . '"><span>&raquo;</span></a>';
			$out .= $separator;
			$out .= '<a class="strankovacPP" href="' . add_to_url($url, "recordStart", $gotoLast, true) . '"><span>&raquo;&raquo;</span></a>';
		endif;

		$out .= "</span><br class=\"clearRight\" />";
	endif;

	return $out;
}

/** Vrácení měny ve formátu pro české koruny
 * @param float $cislo hodnota ku převodu
 * @param bool $bez_haleru používat haléře?
 * @return string formát Kč */
function penize($cislo, $bez_haleru = false) {
	if ($bez_haleru)
		return str_replace(" ", "&nbsp;", number_format($cislo, 0, ",", " "));
	return str_replace(" ", "&nbsp;", number_format($cislo, 2, ",", " "));
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

/** Vrácení ceny podle hladiny přihlášeného uživatele
 * @param float $cena cena ku převodu
 * @param int $ignoruj_hladinu přiznak, jestli se má hladina ignorovat
 * @return string formát Kč */
function cenova_hladina($cena, $ignoruj_hladinu) {
	if (!APLIKUJ_SYSTEM_CENOVYCH_HLADIN || $ignoruj_hladinu || $ignoruj_hladinu == 1)
		return $cena;
	else
		return (int) ($cena * ((100 - CENOVA_HLADINA) / 100));
}

/* function seo_v_seznamu($IdHierarchie, $BezPrefixu = false) {
  $vysledek = ($BezPrefixu ? "" : PREFIX).(JE_ESHOP ? "e-shop/" : "");
  global $conn;
  if ($IdHierarchie != null) {
  $vetev_arr = $conn->query("select id_hierarchie, hierarchie_id_hierarchie, hi_seo from hierarchie where id_hierarchie = ".gpc_addslashes($IdHierarchie));
  if ($vetev_arr && $vetev_arr->num_rows > 0) {
  $vetev = $vetev_arr->fetch_array(MYSQLI_ASSOC);
  $vetev_arr->close();
  $vysledek = seo_v_seznamu($vetev["hierarchie_id_hierarchie"], $BezPrefixu).$vetev["hi_seo"]."/";
  }
  }
  return($vysledek);
  } */

/** Převod klíčových slov s identifikátorem na odkazy
 * @param array $vstup výsledek regulárního výrazu
 * @return string převedený odkaz */
function OsetriSouboryCallback($vstup) {
	global $conn;
	$odkaz = "";
	$sql = "select id_produkt_fotografie, produkty_id_produkt, pr_jmeno_souboru, pr_poradi is null as pr_poradi_isnull, pr_poradi from produkty_binarni_data where id_produkt_fotografie = " . (int) $vstup[1] . " and typy_dat_id_typ_dat = 2 \n";
	$soubory = $conn->query($sql);
	if ($soubory && $soubory->num_rows > 0) {
		while ($soubor = $soubory->fetch_array(MYSQLI_ASSOC)) {
			$odkaz = PREFIX_SOUBORY . "katalog-obrazku/clanek-" . $soubor["produkty_id_produkt"] . "/" . html_entities($soubor["id_produkt_fotografie"] . "-" . $soubor["pr_jmeno_souboru"], true) . "";
		}
		$soubory->close();
	}

	return($odkaz);
}

/** Převod klíčových slov s identifikátorem na odkazy
 * @param array $vstup výsledek regulárního výrazu
 * @return string převedený odkaz */
function OsetriYoutubeCallback($vstup) {
	$odkaz = "";
	$ytId = $vstup[1];
	if (strlen($ytId) > 0) {
		$odkaz = "<iframe class=\"ytVideo\" src=\"https://www.youtube.com/embed/" . $ytId . "?rel=0&amp;showinfo=0\" frameborder=\"0\" allowfullscreen></iframe>";
	}
	return($odkaz);
}

/** Převod klíčových slov s identifikátorem na obrázky
	* @param array $vstup výsledek regulárního výrazu
	* @return string převedený obrázek */
	function OsetriObrazkyCallback($vstup) {
		global $conn;
		$navrat = "";
		$chyba = 0;
		$texy = new Texy();
		$tridaObrazku = "";
    //print_r($vstup);
		$origKlik = isset($vstup[1]) ? $vstup[1] : null;
		$zobrazeni = (isset($vstup[2]) && strlen($vstup[2]) > 0) ? strtolower($vstup[2]) : null;
		$id = isset($vstup[3]) && (int)$vstup[3] > 0 ? (int)$vstup[3] : null;
		$odkaz = isset($vstup[6]) && trim($vstup[6]) != "" ? $vstup[6] : null;
		$popis = isset($vstup[8]) && trim($vstup[8]) != "" ? $vstup[8] : "";
		$zarovnani = isset($vstup[9]) ? $vstup[9] : null;
		$marginLeva = 0;
		$marginPrava= 0;
		$sirkaObrazku = 245;
		$vyskaObrazku = 0;
		//178
		unset($texy);
		if ($id == null) {$navrat= "[NEPLATNÝ IDENTIFIKÁTOR OBRÁZKU]"; $chyba = 1;}
		else
		{
			$tridaObrazku = "";
			$tridaObrazku = " obrazekStred ";
			if ($zarovnani == "<") {$tridaObrazku = " obrazekLevy "; }
			else if ($zarovnani == ">") {$tridaObrazku = " obrazekPravy "; }

			if($origKlik == 'ORIG')
			{
        //pokud je odkaz tak se to nastavi jako klikaci odkaz
        if(!is_null($odkaz))
				  $navrat = BoxVratObrazek($id, $zobrazeni, null, $tridaObrazku, $odkaz, $popis, 'cover');
        else
          $navrat = BoxVratObrazek($id, $zobrazeni, null, $tridaObrazku, null, $popis, 'contain');
			}
			else if($origKlik == 'KLIK')
			{
				$navrat = BoxVratObrazek($id, $zobrazeni, null, $tridaObrazku, BoxVratAdresuObrazku($id, 'detail'), $popis, null, '50%', '50%', 'lightbox[]');
			}

		}
		return($navrat);
	}

/** Převod klíčových slov s identifikátorem na odkazy
 * @param array $vstup výsledek regulárního výrazu
 * @return string převedený odkaz */
function OsetriOdkazyCallback($vstup) {
	global $conn;
	$prod = new produkt($conn);
	$prod->_id = (int) $vstup[1];
	//return $prod->UrlProduktu($prod->HierarchieHledanehoProduktu())."".$prod->_produkt_seo.".html";
	$cesta = VratCestuKProduktu($prod->_id);
	return($cesta);
}

	/** Převod klíčových slov s identifikátorem na odkazy / obrázky
	* @param string $vstup zpracováváný text
	* @return string zpracovaný text */
	function OsetriOdkazyObrazky($vstup) {
		global $FOTOGRAFIE_ROZMERY;

		//dle zdroje dame moznost vsech obrazku
		$stringTypyRozmeru = "";
		foreach($FOTOGRAFIE_ROZMERY as $typBoxu=>$hodnota)
		{
			$stringTypyRozmeru.=strtoupper($typBoxu)."|";
		}
		$stringTypyRozmeru = substr($stringTypyRozmeru,0,-1); //echo($stringTypyRozmeru);die;

    $vstup = preg_replace_callback('/[\(\[]eTABULKA id=([0-9]+) ?(\(([^\]]*)\))? ?([<>])?[\)\]]/i', 'OsetriETabulkyCallback', $vstup);

		$vstup = preg_replace_callback('/\[CLANEK id=([0-9]+)\]/i', 'OsetriOdkazyCallback', $vstup);
		$vstup = preg_replace_callback('/\[HIERARCHIE id=([0-9]+)\]/i', 'OsetriHierarchiiCallback', $vstup);
		//$vstup = preg_replace_callback('/[\(\[]eTABULKA id=([0-9]+) ?(\(([^\]]*)\))? ?([<>])?[\)\]]/i', 'OsetriETabulkyCallback', $vstup);
		$vstup = preg_replace_callback('/\[SOUBOR id=([0-9]+)\]/i', 'OsetriSouboryCallback', $vstup);
    //echo("<br/>".$vstup."<br/>");
    $vstup = preg_replace_callback('/[\(\[]OBRAZ_(ORIG|KLIK)_?('.$stringTypyRozmeru.')? id=([0-9]+) ?(odkaz=(\(([^\)]*)\)))? ?(\(([^\]]*)\))? ?([<>])?[\)\]]/i', 'OsetriObrazkyCallback', $vstup);
		return $vstup;
	}

/** Převod klíčových slov s identifikátorem na odkazy
 * @param array $vstup výsledek regulárního výrazu
 * @return string převedený odkaz */
function OsetriMapyCallback($vstup) {
	global $conn, $jazyk;
	$vystup = "mapa";

	if ($vstup[1] == 'POBOCKY') {
		$vystup = '
      <div id="dMapa1Obal">
			<img id="iMapa1" src="' . PREFIX_SOUBORY . 'katalog-obrazku/mapa/mapa-730.png" width="730" height="511" border="0" usemap="#mapa1" />

			<map name="mapa1">
			<!-- #$-:Image map file created by GIMP Image Map plug-in -->
			<!-- #$-:GIMP Image Map plug-in by Maurits Rijk -->
			<!-- #$-:Please do not edit lines starting with "#$" -->
			<!-- #$VERSION:2.3 -->
			<!-- #$AUTHOR:mantic  -->
			<area shape="circle" coords="615,203,15" class="mapaKlik mapaKlikKraj" target="ostrava" nohref="nohref" />
			<area shape="circle" coords="453,310,15" class="mapaKlik mapaKlikKraj" target="brno"  nohref="nohref" />
			<area shape="circle" coords="234,137,15" class="mapaKlik mapaKlikKraj" target="praha"  nohref="nohref" />
			<area shape="circle" coords="483,418,15" class="mapaKlik mapaKlikKraj" target="bratislava"  nohref="nohref" />
			<area shape="circle" coords="706,137,15" class="mapaKlik mapaKlikKraj" target="katowice"  nohref="nohref" />
			<area shape="poly" class="mapaKlik mapaKlikKraj" coords="524,129,537,129,545,126,553,122,553,131,551,140,544,145,552,153,556,162,559,169,567,171,575,168,579,159,589,165,597,171,607,175,626,179,634,184,636,196,636,207,646,211,659,219,661,237,648,240,630,239,625,248,617,255,612,252,607,245,596,241,587,236,577,239,569,236,565,235,559,224,551,219,544,216,541,202,535,203,530,201,525,191,520,190,512,196,510,188,501,192,490,187,495,173,493,163,497,156,498,146,507,138,521,132" target="moravskoslezsky"  nohref="nohref" />
			<area shape="poly" class="mapaKlik mapaKlikKraj" coords="474,101,485,109,501,111,518,123,524,129,517,135,507,139,499,145,498,154,493,162,495,173,491,187,501,192,510,188,513,196,519,190,525,192,530,202,541,202,544,215,560,225,564,234,557,243,550,250,544,246,538,254,530,253,526,259,513,257,507,266,491,270,478,257,476,250,469,239,467,248,467,253,454,243,459,237,452,231,453,226,458,219,457,202,452,190,450,181,452,177,450,168,454,163,459,151,461,143,476,138,484,134,472,113,471,107" target="olomoucky"  nohref="nohref" />
			<area shape="poly" class="mapaKlik mapaKlikKraj" coords="564,235,576,238,587,237,601,241,609,246,616,256,615,265,606,268,594,271,591,279,586,294,578,308,566,308,563,318,553,323,544,331,538,332,533,324,530,320,518,319,509,312,499,308,498,301,488,301,489,297,494,291,489,284,489,276,491,271,510,265,512,257,526,257,531,254,538,254,543,246,551,249" target="zlinsky"  nohref="nohref" />
			<area shape="poly" class="mapaKlik mapaKlikKraj" coords="329,321,343,317,359,311,370,303,392,303,401,298,403,268,406,262,403,254,415,241,420,234,419,223,440,220,455,224,452,230,459,237,455,244,467,253,470,240,478,254,481,261,490,270,489,283,494,291,488,301,498,302,499,309,510,312,520,319,531,321,538,332,531,337,521,333,516,337,502,326,496,326,488,332,478,344,472,352,471,359,455,354,443,348,432,343,420,344,411,353,396,352,381,345,366,334,356,329,343,330" target="jihomoravsky"  nohref="nohref" />
			<area shape="poly" class="mapaKlik mapaKlikKraj" coords="331,183,338,190,344,189,360,199,365,205,370,209,377,202,392,211,408,218,420,224,419,234,404,254,405,263,403,268,401,299,394,302,370,304,360,311,330,321,331,309,337,297,334,291,313,289,311,276,306,279,301,276,293,275,285,266,269,265,270,256,269,250,270,238,272,224,278,220,290,220,300,219,303,214,300,210,303,198,314,194,323,193,323,183" target="vysocina"  nohref="nohref" />
			<area shape="poly" class="mapaKlik mapaKlikKraj" coords="331,183,332,175,333,169,328,161,322,159,321,149,331,144,340,141,351,144,359,137,361,142,370,143,373,138,381,142,382,146,387,152,401,159,409,160,418,147,427,139,437,141,443,145,449,152,455,150,461,148,456,162,450,167,452,177,451,183,457,202,458,220,454,225,439,220,420,223,388,210,377,202,370,209,359,198,343,190,337,190" target="pardubicky"  nohref="nohref" />
			<area shape="poly" class="mapaKlik mapaKlikKraj" coords="341,51,353,53,363,58,371,65,383,65,386,74,397,71,406,71,411,66,423,73,429,81,426,90,415,99,406,104,414,113,423,115,435,131,441,144,427,139,420,147,409,160,385,151,382,145,380,140,373,138,370,143,361,141,359,137,351,143,340,142,329,145,319,142,323,135,319,119,309,120,307,117,296,108,295,103,299,97,296,90,297,86,307,88,312,84,321,89,325,94,328,83,333,87,344,85,340,79,339,71,339,57" target="kralovehradecky"  nohref="nohref" />
			<area shape="poly" class="mapaKlik mapaKlikKraj" coords="293,15,305,18,315,23,318,32,321,40,325,46,327,52,341,52,339,55,340,78,344,85,333,87,328,83,325,93,321,89,312,84,306,87,297,86,292,82,288,72,279,71,271,76,267,81,261,79,258,86,248,88,243,86,233,83,224,69,224,58,227,46,231,41,239,40,244,42,245,36,253,34,257,43,266,39,274,38,284,37,286,29,286,18,289,14" target="liberecky"  nohref="nohref" />
			<area shape="poly" class="mapaKlik mapaKlikKraj" coords="220,7,228,12,240,14,249,19,251,30,249,35,244,37,244,43,235,41,231,42,226,51,224,60,225,71,233,85,236,85,231,95,220,108,198,107,188,105,177,118,167,120,155,123,137,127,133,136,125,138,113,137,110,132,114,124,107,115,110,102,102,100,94,95,88,88,94,80,104,80,109,72,125,65,131,61,139,58,145,49,161,49,176,46,184,42,192,42,208,38,221,33,225,28,217,21,216,14" target="ustecky"  nohref="nohref" />
			<area shape="poly" class="mapaKlik mapaKlikKraj" coords="89,87,94,96,99,101,110,102,108,114,114,126,110,133,114,138,121,139,110,144,106,150,94,151,87,155,79,164,70,161,63,160,57,162,54,165,41,164,23,141,14,136,2,109,8,97,16,102,21,111,22,118,30,108,34,100,44,90,58,90,71,85,85,91,88,92" target="karlovarsky"  nohref="nohref" />
			<area shape="poly" class="mapaKlik mapaKlikKraj" coords="121,139,127,142,126,148,134,151,141,156,148,155,154,163,161,165,167,171,165,176,160,183,160,196,151,201,156,212,157,226,157,239,152,263,147,275,145,285,139,284,135,296,130,305,126,308,118,303,109,287,103,283,91,282,87,268,79,253,68,248,59,245,50,235,44,225,40,213,38,199,29,188,33,175,41,164,52,165,58,163,65,159,80,164,90,153,97,150,107,149,109,145,122,139" target="plzensky"  nohref="nohref" />
			<area shape="poly" class="mapaKlik mapaKlikKraj" coords="157,223,165,229,178,229,184,226,188,223,190,227,196,229,204,227,208,222,213,226,225,227,235,228,246,230,254,231,256,226,256,216,264,222,272,228,270,237,269,254,270,263,286,266,295,275,303,278,307,278,312,276,313,288,334,292,337,296,335,303,332,309,330,323,320,318,310,312,296,315,293,315,286,309,276,308,274,319,270,336,266,344,257,340,251,346,241,363,231,363,215,362,203,366,181,361,174,350,166,340,158,335,151,329,148,319,140,315,133,309,130,305,134,294,141,284,144,285,148,273,154,262,158,239" target="jihocesky"  nohref="nohref" />
			<area shape="poly" class="mapaKlik mapaKlikKraj" coords="123,139,132,135,138,127,157,122,178,118,188,107,189,106,211,108,222,108,234,91,236,84,247,88,256,88,261,81,264,79,269,79,275,74,282,72,289,73,292,81,297,86,296,91,299,97,295,102,296,108,309,120,319,120,324,135,318,143,326,146,322,149,323,159,328,161,333,167,329,183,321,184,322,193,302,197,300,209,303,213,300,219,278,221,271,225,270,226,256,215,256,227,253,230,239,229,214,226,207,222,205,225,193,228,187,223,179,229,172,229,164,229,157,221,155,209,151,200,160,198,160,181,166,172,162,165,154,164,148,155,141,157,133,150,126,149,127,141" target="stredocesky"  nohref="nohref" />
			<area shape="poly" class="mapaKlik mapaKlikKraj" coords="729,236,725,234,721,224,710,231,700,234,695,243,692,252,686,254,677,253,673,254,671,243,669,239,662,238,649,241,629,239,627,246,619,252,616,256,616,261,611,266,601,269,595,270,590,278,589,290,584,299,579,308,568,311,565,311,562,319,556,324,548,328,540,332,533,337,527,336,521,333,518,335,513,336,506,328,497,324,489,330,484,336,478,344,470,350,470,362,468,369,467,377,462,381,459,390,459,399,459,403,463,407,462,411,466,418,467,418,467,428,472,433,475,436,479,439,479,448,481,454,487,455,496,455,504,462,512,466,513,475,519,478,526,483,531,490,539,496,552,500,566,499,579,498,595,502,607,500,627,500,639,496,647,493,653,488,648,481,646,473,647,463,651,457,666,455,674,450,686,453,697,452,701,449,710,451,717,446,724,439,730,432" target="slovensko"  nohref="nohref" />
			</map>
      <span class="sMapa1Polozka sMapa1Praha">' . $jazyk->Text("Praha", "Praha", "Praga") . '</span>
			<span class="sMapa1Polozka sMapa1Brno">' . $jazyk->Text("Brno", "Brno", "Brno") . '</span>
			<span class="sMapa1Polozka sMapa1Ostrava">' . $jazyk->Text("Ostrava", "Ostrava", "Ostrawa") . '</span>
			<span class="sMapa1Polozka sMapa1Bratislava">' . $jazyk->Text("Bratislava", "Bratislava", "Bratysława") . '</span>
			<span class="sMapa1Polozka sMapa1Katovice">' . $jazyk->Text("Katovice", "Katovice", "Katowice") . '</span>
			</div>
			';
	}

	if ($vstup[1] == 'ZASTOUPENI') {
		$vystup = '
      <div id="dMapa2Obal">
			<img id="iMapa2" src="' . PREFIX_SOUBORY . 'katalog-obrazku/mapa/mapa2-730.png" width="730" height="358" border="0" usemap="#mapa2" />

			<map name="mapa2">
			<!-- #$-:Image map file created by GIMP Image Map plug-in -->
			<!-- #$-:GIMP Image Map plug-in by Maurits Rijk -->
			<!-- #$-:Please do not edit lines starting with "#$" -->
			<!-- #$VERSION:2.3 -->
			<!-- #$AUTHOR:mantic  -->
			<area shape="poly" class="mapaKlik mapaKlikKraj" coords="373,90,381,90,388,88,393,84,395,88,394,95,390,98,387,101,392,105,396,109,397,114,399,117,402,120,409,117,412,112,415,112,420,117,430,121,437,123,446,125,452,128,452,139,454,146,459,148,467,152,472,166,464,167,458,169,450,166,447,171,442,177,440,179,438,185,437,177,433,171,426,168,419,165,416,165,410,167,405,164,397,156,389,150,385,141,379,140,375,133,370,131,365,136,363,130,358,133,350,129,353,120,352,114,354,108,356,101,362,94,368,91" target="moravskoslezsky2" nohref="nohref" />
			<area shape="poly" class="mapaKlik mapaKlikKraj" coords="438,185,436,175,431,170,419,165,410,166,404,165,400,165,397,168,392,173,389,172,386,171,383,176,379,176,376,177,375,181,364,179,362,185,355,187,350,188,348,192,349,199,350,203,348,209,352,211,354,212,355,217,364,218,367,223,376,224,384,235,396,228,402,225,403,218,414,215,419,205,421,194,427,189,435,188" target="zlinsky2" nohref="nohref" />
			<area shape="poly" class="mapaKlik mapaKlikKraj" coords="338,71,345,74,352,75,365,81,372,86,374,89,367,91,359,97,355,104,353,110,352,115,351,129,358,133,363,131,365,135,371,131,375,132,378,139,384,141,389,152,404,164,401,166,393,173,388,171,384,175,377,176,374,181,364,178,362,184,350,189,341,179,335,168,332,177,326,172,324,169,328,165,323,162,322,156,326,155,327,149,327,142,322,132,321,129,322,124,320,115,325,111,330,100,337,95,343,94,345,91,340,83,335,76,336,73" target="olomoucky2" nohref="nohref" />
			<area shape="poly" class="mapaKlik mapaKlikKraj" coords="302,156,312,155,319,155,322,155,323,162,328,165,324,167,332,177,335,169,341,179,350,188,348,191,348,198,350,202,348,209,352,212,354,212,355,217,363,218,367,223,377,224,384,235,378,236,373,235,367,238,358,230,354,230,346,234,347,238,342,240,337,248,336,253,326,251,316,246,307,242,297,246,290,249,273,245,263,238,251,231,244,231,232,223,244,223,258,217,266,213,283,213,285,210,287,194,289,182,287,177,294,171,298,165,299,156" target="jihomoravsky2" nohref="nohref" />
			<area shape="poly" class="mapaKlik mapaKlikKraj" coords="235,127,241,133,245,131,258,140,260,145,265,145,268,141,279,147,295,155,299,157,298,164,293,171,287,177,289,183,284,211,283,212,265,213,247,221,243,222,233,223,237,213,240,209,239,202,222,202,220,194,217,195,211,193,208,192,200,187,191,186,189,172,193,164,193,157,196,155,213,153,216,151,213,146,214,140,218,137,227,134,230,133,230,128" target="vysocina2" nohref="nohref" />
			<area shape="poly" class="mapaKlik mapaKlikKraj" coords="231,101,242,97,247,98,253,95,257,99,266,95,271,97,272,102,288,111,293,108,299,100,306,96,315,98,319,104,326,105,325,109,320,114,322,124,321,128,327,141,326,155,321,155,299,155,295,156,269,140,265,145,261,146,258,138,245,132,242,133,236,127,235,125,237,116,228,110,227,104" target="pardubicky2" nohref="nohref" />
			<area shape="poly" class="mapaKlik mapaKlikKraj" coords="211,57,218,58,224,58,230,63,233,56,238,57,244,57,242,51,241,33,255,36,262,41,267,43,273,44,276,49,283,47,292,45,307,55,302,63,290,71,294,76,303,81,309,89,312,95,308,96,303,97,299,100,293,109,289,110,272,100,268,95,264,95,257,98,252,94,246,98,243,97,231,101,225,97,229,93,226,81,221,82,216,80,212,76,209,73,212,66,209,57" target="kralovehradecky2" nohref="nohref" />
			<area shape="poly" class="mapaKlik mapaKlikKraj" coords="179,20,182,25,188,26,192,23,199,24,204,21,204,10,208,8,219,10,225,15,227,21,230,28,237,33,242,33,241,47,244,57,233,56,230,63,224,58,210,57,201,47,196,49,190,51,189,54,185,52,181,58,174,61,168,56,162,52,158,45,160,34,166,24,173,28,174,23" target="liberecky2" nohref="nohref" />
			<area shape="poly" class="mapaKlik mapaKlikKraj" coords="63,60,68,54,76,51,81,46,89,43,98,41,102,35,111,33,125,31,131,27,145,25,160,19,156,13,155,5,157,2,164,8,172,7,178,13,179,20,174,22,172,27,166,25,160,34,158,43,161,50,166,56,165,63,161,68,158,72,147,74,141,73,134,71,128,77,123,81,115,82,109,86,97,88,93,94,85,97,79,94,80,86,76,78,79,71,73,70,66,65" target="ustecky2" nohref="nohref" />
			<area shape="poly" class="mapaKlik mapaKlikKraj" coords="28,114,20,102,13,97,7,84,2,76,4,70,8,67,12,74,15,79,19,75,24,69,31,63,41,61,51,58,59,61,64,61,71,69,79,71,76,78,80,86,79,92,82,97,77,102,75,103,68,103,62,106,57,113,53,111,44,110,38,115" target="karlovarsky2" nohref="nohref" />
			<area shape="poly" class="mapaKlik mapaKlikKraj" coords="93,217,85,211,75,202,70,199,64,194,58,182,50,173,41,173,34,164,33,155,28,150,26,140,21,133,21,125,24,122,29,114,38,114,44,111,54,111,57,114,62,105,70,102,75,103,82,97,89,97,89,102,94,103,100,107,108,108,109,112,114,113,117,117,117,121,114,126,113,135,108,141,112,151,111,158,112,173,106,186,103,198,98,199,97,206,94,211" target="plzensky2" nohref="nohref" />
			<area shape="poly" class="mapaKlik mapaKlikKraj" coords="93,216,97,208,99,199,104,197,106,185,112,172,112,155,114,158,117,160,126,161,131,155,136,159,143,159,146,154,150,158,165,160,181,161,183,153,192,157,193,158,193,166,190,170,191,186,202,187,217,195,221,194,223,202,239,202,239,209,233,223,223,219,214,221,208,223,205,216,200,215,194,217,194,221,191,241,188,241,185,239,181,242,170,258,167,257,157,254,145,260,126,253,121,242,115,237,110,236,107,228,100,224" target="jihocesky2" nohref="nohref" />
			<area shape="poly" class="mapaKlik mapaKlikKraj" coords="235,127,230,127,229,133,216,137,213,141,214,151,210,153,195,156,192,157,183,153,180,161,149,159,146,154,143,159,133,159,130,155,124,160,116,159,112,154,111,149,108,142,112,135,115,124,118,119,117,115,113,112,109,112,107,108,96,105,93,102,89,102,89,97,95,92,97,88,112,85,117,82,125,81,135,72,148,74,158,73,166,63,167,55,176,60,182,56,186,53,191,54,191,51,203,47,210,56,212,66,209,72,219,81,226,82,229,93,226,96,231,100,227,103,227,110,237,114,236,123" target="stredocesky2" nohref="nohref" />
			<area shape="poly" class="mapaKlik mapaKlikKraj" coords="409,216,412,223,426,228,434,235,440,244,435,249,437,254,441,261,443,272,446,275,446,283,445,289,441,294,447,293,456,288,460,279,462,287,470,290,466,299,464,307,463,312,478,310,488,306,480,321,469,321,464,323,461,331,460,339,465,346,464,348,456,349,452,353,433,352,422,357,410,354,391,354,375,344,365,336,364,329,354,323,343,320,341,315,342,310,334,304,333,295,329,290,329,287,327,284,329,269,334,265,335,252,342,239,347,238,347,234,356,230,367,238,376,235,382,236,402,225,403,218,407,218" target="zapadoslovensky2" nohref="nohref" />
			<area shape="poly" class="mapaKlik mapaKlikKraj" coords="481,320,488,306,479,310,464,311,469,291,462,285,459,278,456,287,444,294,442,294,444,286,446,274,443,271,441,259,436,248,440,242,427,228,412,222,410,217,415,214,419,205,421,195,426,189,437,188,439,182,450,167,458,168,471,164,478,166,479,175,482,179,488,177,495,175,496,165,504,166,511,158,517,164,520,173,523,174,525,179,534,180,537,186,536,194,532,202,538,203,540,206,549,209,549,220,547,223,549,230,563,234,564,238,561,244,555,242,548,242,544,244,544,248,547,252,554,252,553,258,553,264,554,267,563,267,566,271,573,276,582,278,576,290,573,296,570,300,564,299,559,301,555,307,548,311,541,314,540,312,533,311,531,304,526,306,524,301,516,305,513,312,511,317,504,317,493,320" target="stredoslovensky2" nohref="nohref" />
			<area shape="poly" class="mapaKlik mapaKlikKraj" coords="581,278,572,276,565,269,562,266,554,266,554,252,547,250,544,247,545,243,552,242,558,243,563,244,564,236,563,233,548,229,547,223,549,219,548,208,542,206,539,202,543,198,547,197,553,202,556,200,555,193,563,186,570,184,572,180,578,179,581,175,585,180,591,181,596,178,601,179,603,183,608,186,612,187,613,191,619,183,626,175,629,177,631,180,634,174,642,174,646,176,649,177,656,174,666,177,670,180,670,181,672,182,674,179,679,183,687,185,689,193,692,195,692,198,699,199,709,205,714,206,719,210,728,211,725,220,720,221,716,229,716,233,715,237,714,243,712,245,712,250,708,255,704,262,700,266,697,273,699,280,696,288,690,285,682,287,675,288,671,289,663,285,662,277,653,271,647,266,642,269,634,272,632,271,626,274,621,270,610,271,605,265,589,269,584,271" target="vychodoslovensky2" nohref="nohref" />

			</map>
      <span class="sMapa2Polozka sMapa2Praha">' . $jazyk->Text("Praha", "Praha", "Praga") . '</span>
			<span class="sMapa2Polozka sMapa2Brno">' . $jazyk->Text("Brno", "Brno", "Brno") . '</span>
			<span class="sMapa2Polozka sMapa2Ostrava">' . $jazyk->Text("Ostrava", "Ostrava", "Ostrawa") . '</span>
			<span class="sMapa2Polozka sMapa2Bratislava">' . $jazyk->Text("Bratislava", "Bratislava", "Bratysława") . '</span>
			</div>
			';
	}

	return($vystup);
}

/** Převod klíčových slov s identifikátorem na odkazy
 * @param array $vstup výsledek regulárního výrazu
 * @return string převedený odkaz */
function OsetriHierarchiiCallback($vstup) {
	global $conn;
	$odkaz = "";
	//echo("++".seo_v_seznamu2((int)$vstup[1])."++");//die;
	return(seo_v_seznamu2((int) $vstup[1]));
	return($odkaz);
}

function OsetriOdkazyBlankCallback($vstup) {
	$blank = false;
	$scheme = array("http", "https");
	for ($i = 0; $i < count($scheme); $i++) {
		if (!$blank && strtolower(substr($vstup[1], 0, strlen($scheme[$i]))) == $scheme[$i])
			$blank = true;
	}
	return "href=\"" . $vstup[1] . "\"" . ($blank ? " onclick=\"return!window.open(this.href);\"" : "") . ">";
}

/** Převod klíčových slov s identifikátorem na tabulky
	 * @param array $vstup výsledek regulárního výrazu
	 * @return string převedený na tabulku */
	function OsetriETabulkyCallback($vstup) {
		return(SablonaETabulka($vstup[1],true));
	}

function OsetriOdkazyBlank($vstup) {
	$zpracovat = array(
		'#href="(.*)">#' => "href=\"$1\" title=\"a\">"
	);

	$zpracovat_co = array_keys($zpracovat);
	$zpracovat_cim = array_values($zpracovat);
	//return preg_replace_callback('#href="(.*)">#', 'OsetriOdkazyBlankCallback', $vstup);
	$vstup = preg_replace_callback('/href="(.*)">/i', 'OsetriOdkazyBlankCallback', $vstup);
	$vstup = preg_replace_callback('/\[MAPA_(POBOCKY|ZASTOUPENI)\]/i', 'OsetriMapyCallback', $vstup);
	return($vstup);
}

/** Převod klíčových slov s identifikátorem na obrázky
 * @param array $vstup výsledek regulárního výrazu
 * @return string převedený obrázek */
function OsetriObrazkyCallbackProdukt($vstup) {
	global $conn;
	global $pocetObrazku;
	$texy = new Texy();
	$zobrazeni = isset($vstup[1]) ? $vstup[1] : null;
	$id = isset($vstup[2]) && (int) $vstup[2] > 0 ? (int) $vstup[2] : null;
	$popis = isset($vstup[3]) && trim($vstup[3]) != "" ? $texy->process(rtrim(ltrim(trim($vstup[3]), "("), ")"), true) : "";
	$zarovnani = isset($vstup[5]) ? $vstup[5] : null;
	unset($texy);
	if ($zobrazeni == null || $id == null)
		return "[NEPLATNÝ IDENTIFIKÁTOR OBRÁZKU]";
	else {
		$sql = "select id_produkt_fotografie, pr_jmeno_souboru, id_produkt, pr_obrazek_sirka, pr_obrazek_vyska, pr_obrazek_original_sirka, pr_obrazek_original_vyska "
				. "from produkty_binarni_data as pbd "
				. "inner join produkty as p on p.id_produkt = pbd.produkty_id_produkt "
				. "where id_produkt_fotografie = " . ((int) $vstup[2]);
		$obrazky = $conn->query($sql);
		if ($obrazky && $obrazky->num_rows > 0) {
			$obrazek = $obrazky->fetch_array(MYSQLI_ASSOC);
			$obrazky->close();
			$htmlvelikosti = "";
			if ($obrazek["pr_obrazek_sirka"] != null && $obrazek["pr_obrazek_vyska"] != null)
				$htmlvelikosti = " width=\"" . $obrazek["pr_obrazek_sirka"] . "\" height=\"" . $obrazek["pr_obrazek_vyska"] . "\"";
			$htmlvelikosti_orig = "";
			if ($obrazek["pr_obrazek_original_sirka"] != null && $obrazek["pr_obrazek_original_vyska"] != null)
				$htmlvelikosti_orig = " width=\"" . $obrazek["pr_obrazek_original_sirka"] . "\" height=\"" . $obrazek["pr_obrazek_original_vyska"] . "\"";
			if (($pocetObrazku % 4 == 0))
				$tridaObrazku = " class=\"obrazekIlustrace bezMarginu\"";
			else
				$tridaObrazku = " class=\"obrazekIlustrace\"";

			if ($zarovnani == "<")
				$tridaObrazku = " class=\"obrazekIlustrace obrazekLevy\"";
			else if ($zarovnani == ">")
				$tridaObrazku = " class=\"obrazekIlustrace obrazekPravy\"";

			if ($zobrazeni == "ORIG") {
				$pocetObrazku++;
				return "<img src=\"" . PREFIX_SOUBORY . "katalog-obrazku/produkt-" . $obrazek["id_produkt"] . "/" . html_entities($obrazek["id_produkt_fotografie"] . "-" . $obrazek["pr_jmeno_souboru"], true) . "\"" . $htmlvelikosti_orig . " alt=\"" . $popis . "\"" . $tridaObrazku . " />";
			} else if ($zobrazeni == "KLIK") {
				$pocetObrazku++;
				return "<a href=\"" . PREFIX_SOUBORY . "katalog-obrazku/produkt-" . $obrazek["id_produkt"] . "/detail-" . html_entities($obrazek["id_produkt_fotografie"] . "-" . $obrazek["pr_jmeno_souboru"], true) . "\" rel=\"lightbox[clanek]\" title=\"" . $popis . "\"><img src=\"" . PREFIX_SOUBORY . "katalog-obrazku/produkt-" . $obrazek["id_produkt"] . "/nahled-" . html_entities($obrazek["id_produkt_fotografie"] . "-" . $obrazek["pr_jmeno_souboru"], true) . "\"" . $htmlvelikosti . " alt=\"" . $popis . "\"" . $tridaObrazku . " /></a>";
			} else if ($zobrazeni == "KLIK_ORIG") {
				$pocetObrazku++;
				return "<a href=\"" . PREFIX_SOUBORY . "katalog-obrazku/produkt-" . $obrazek["id_produkt"] . "/" . html_entities($obrazek["id_produkt_fotografie"] . "-" . $obrazek["pr_jmeno_souboru"], true) . "\" rel=\"lightbox[clanek]\" title=\"" . $popis . "\"><img src=\"" . PREFIX_SOUBORY . "katalog-obrazku/produkt-" . $obrazek["id_produkt"] . "/nahled-" . html_entities($obrazek["id_produkt_fotografie"] . "-" . $obrazek["pr_jmeno_souboru"], true) . "\"" . $htmlvelikosti . " alt=\"" . $popis . "\"" . $tridaObrazku . " /></a>";
			} else
				return "[NEPLATNÝ IDENTIFIKÁTOR OBRÁZKU]";
		} else
			return "[OBRÁZEK NEBYL NALEZEN]";
	}
}

/** Převod klíčových slov s identifikátorem na odkazy
 * @param array $vstup výsledek regulárního výrazu
 * @return string převedený odkaz */
function OsetriOdkazyCallbackProdukt($vstup) {
	global $conn;
	$prod = new produkt($conn);
	$prod->_id = (int) $vstup[1];
	return $prod->UrlProduktu($prod->HierarchieHledanehoProduktu()) . "" . $prod->_produkt_seo;
}

/** Převod klíčových slov s identifikátorem na odkazy / obrázky
 * @param string $vstup zpracováváný text
 * @return string zpracovaný text */
function OsetriOdkazyObrazkyProdukt($vstup) {
	$vstup = preg_replace_callback('/[\(\[]OBRAZ_(ORIG|KLIK|KLIK_ORIG) id=([0-9]+) ?(\(([^\]]*)\))? ?([<>])?[\)\]]/i', 'OsetriObrazkyCallbackProdukt', $vstup);
	$vstup = preg_replace_callback('/\[CLANEK id=([0-9]+)\]/i', 'OsetriOdkazyCallback', $vstup);
	$vstup = preg_replace_callback('/\[HIERARCHIE id=([0-9]+)\]/i', 'OsetriHierarchiiCallback', $vstup);
	$vstup = preg_replace_callback('/\[SOUBOR id=([0-9]+)\]/i', 'OsetriSouboryCallback', $vstup);
	return $vstup;
}

/*
  Funkce vrátí v poli všechny id hierarchii které tvoří cestu
 */

function VratPoleAktivnichHierarchii($IdHierarchie, &$pole) {
	global $conn;
	if ($IdHierarchie != null) {
		$pole[] = $IdHierarchie;
		$vetev_arr = $conn->query("select id_hierarchie, hierarchie_id_hierarchie, hi_seo from hierarchie where id_hierarchie = " . gpc_addslashes($IdHierarchie));
		//echo("select id_hierarchie, hierarchie_id_hierarchie, hi_seo from hierarchie where id_hierarchie = ".gpc_addslashes($IdHierarchie));
		if ($vetev_arr && $vetev_arr->num_rows > 0) {
			$vetev = $vetev_arr->fetch_array(MYSQLI_ASSOC);
			$vetev_arr->close();
			VratPoleAktivnichHierarchii($vetev["hierarchie_id_hierarchie"], $pole);
		}
	}
}

function MaHierachiePodhierarchii($IdHierarchie) {
	global $conn;
	if ($IdHierarchie != null) {
		$vetev_arr = $conn->query("select id_hierarchie, hierarchie_id_hierarchie, hi_seo from hierarchie where hierarchie_id_hierarchie = " . gpc_addslashes($IdHierarchie));
		if ($vetev_arr && $vetev_arr->num_rows > 0) {
			return(true);
		}
		return(false);
	} else
		return(false);
}

function VratDetailHierarchie($IdHierarchie) {
	global $conn, $jazyk;
	$sql = "select *, " . $jazyk->Sql2Jaz('hi_nazev') . ", " . $jazyk->Sql2Jaz('hi_popis') . ", " . $jazyk->Sql2Jaz('hi_keywords') . ", " . $jazyk->Sql2Jaz('hi_description') . ", " . $jazyk->Sql2Jaz('hi_seo') . ", " . $jazyk->Sql2JazVratPolickoAs('hi_seo_podstrcene', 'hi_seo_podstrcene_jazyk') . " from hierarchie where id_hierarchie = " . $IdHierarchie . " \n";
	$hierarchie = $conn->query($sql);
	if ($hierarchie && $hierarchie->num_rows > 0) {
		if ($jednaHierarchie = $hierarchie->fetch_array(MYSQLI_ASSOC)) {
			$jednaHierarchie['cesta'] = seo_v_seznamu($IdHierarchie);
			return($jednaHierarchie);
		}
		$hierarchie->close();
	}
	return(null);
}

/*pro vzchozi clanek  okopirovatr s ibosu function VratDetailHierarchie($IdHierarchie) {
	global $conn, $jazyk;
	$sql = "select *, " . $jazyk->Sql2Jaz('hi_nazev') . ", " . $jazyk->Sql2Jaz('hi_popis') . ", " . $jazyk->Sql2Jaz('hi_keywords') . ", " . $jazyk->Sql2Jaz('hi_description') . ", " . $jazyk->Sql2Jaz('hi_seo') . ", " . $jazyk->Sql2JazVratPolickoAs('hi_seo_podstrcene', 'hi_seo_podstrcene_jazyk') . " from hierarchie where id_hierarchie = " . $IdHierarchie . " \n";
	$hierarchie = $conn->query($sql);
	if ($hierarchie && $hierarchie->num_rows > 0) {
		if ($jednaHierarchie = $hierarchie->fetch_array(MYSQLI_ASSOC)) {

      $idVychozihoClanku = vratVychoziClanekHierarchie($IdHierarchie);
      if(is_null($idVychozihoClanku))
      {
          $jednaHierarchie['cesta'] = seo_v_seznamu($IdHierarchie);
          $jednaHierarchie['cesta_hierarchie'] = $jednaHierarchie['cesta'];
      }
      else
      {
          $jednaHierarchie['cesta_hierarchie'] = seo_v_seznamu($IdHierarchie);
          $detailProduktu = VratDetailProduktu($idVychozihoClanku);
          $jednaHierarchie['cesta'] = $detailProduktu['cesta'];
      }

			return($jednaHierarchie);
		}
		$hierarchie->close();
	}
	return(null);
}  */

function seo_v_seznamu2($IdHierarchie, $bezPrefixu = false) {
	global $jazyk, $conn;
	if ($bezPrefixu)
		$cesta = "";
	else
		$cesta = PREFIX . "";
	if ($IdHierarchie != null) {
		$vetev_arr = $conn->query("select id_hierarchie, hierarchie_id_hierarchie, " . $jazyk->Sql2Jaz('hi_seo') . ", " . $jazyk->Sql2JazVratPolickoAs('hi_seo_podstrcene', 'hi_seo_podstrcene_jazyk') . " from hierarchie where id_hierarchie = " . gpc_addslashes($IdHierarchie));
		if ($vetev_arr && $vetev_arr->num_rows > 0) {
			$vetev = $vetev_arr->fetch_array(MYSQLI_ASSOC);
			$vetev_arr->close();
			$hiSeo = $vetev["hi_seo"];
			if (strlen($vetev["hi_seo_podstrcene_jazyk"]) > 0)
				$hiSeo = $vetev["hi_seo_podstrcene_jazyk"];
			$cesta = seo_v_seznamu2($vetev["hierarchie_id_hierarchie"], $bezPrefixu) . $hiSeo . "/";
		}
	}
	return($cesta);
}

//...Vytvoření adresářové struktury
function seo_v_seznamu($IdHierarchie, $zvolenyJazyk = null) {
	global $jazyk, $conn;
	//$cesta = PREFIX.KATALOG."/";
	if (is_null($zvolenyJazyk))
		$cesta = PREFIX . "";
	else
		$cesta = PREFIX_SOUBORY . strtolower($zvolenyJazyk) . "/";

	if ($IdHierarchie != null) {
		if (is_null($zvolenyJazyk))
			$sql = "select id_hierarchie, hierarchie_id_hierarchie, " . $jazyk->Sql2Jaz('hi_seo') . ", " . $jazyk->Sql2JazVratPolickoAs('hi_seo_podstrcene', 'hi_seo_podstrcene_jazyk') . " from hierarchie where id_hierarchie = " . gpc_addslashes($IdHierarchie);
		else
			$sql = "select id_hierarchie, hierarchie_id_hierarchie, " . $jazyk->Sql2JazZadanyJazyk('hi_seo', $zvolenyJazyk) . ", " . $jazyk->Sql2JazVratPolickoJazykAs('hi_seo_podstrcene', $zvolenyJazyk, 'hi_seo_podstrcene_jazyk') . " from hierarchie where id_hierarchie = " . gpc_addslashes($IdHierarchie);

		$vetev_arr = mysqli_query($conn, $sql);
		if ($vetev_arr && mysqli_num_rows($vetev_arr) > 0) {
			$vetev = $vetev_arr->fetch_array(MYSQLI_ASSOC);
			mysqli_free_result($vetev_arr);
			$hiSeo = $vetev["hi_seo"];
			if (strlen($vetev["hi_seo_podstrcene_jazyk"]) > 0)
				$hiSeo = $vetev["hi_seo_podstrcene_jazyk"];
			$cesta = seo_v_seznamu($vetev["hierarchie_id_hierarchie"], $zvolenyJazyk) . $hiSeo . "/";
		}
	}
	return($cesta);
}

function OsetriVstup($vstup) {
	$vstup = OsetriMapu($vstup);
	$vstup = OsetriOdkazyBlank($vstup);
	$vstup = OsetriOdkazyObrazky($vstup);
	$vstup = OsetriVideo($vstup);
	$vstup = OsetriOstatni($vstup);
	return($vstup);
}

function OsetriMapu($vstup) {
	$vstup = preg_replace_callback('/\[MAPA_KONTAKT sirka=([0-9]+) vyska=([0-9]+) ?([<>])?\]/i', 'OsetriMapuCallback', $vstup);
	return($vstup);
}

function OsetriVideo($vstup) {
	$vstup = preg_replace_callback('/\[YOUTUBE id=([^\\\ ]+) ?(img=([0-9]+))? ?(sirka=([0-9]+) vyska=([0-9]+))? ?([<>])?\]/i', 'OsetriVideoCallback', $vstup);
	return $vstup;
}

function OsetriOstatni($vstup) {
	$vstup = preg_replace('/\m2\]/i', 'm<sup>2</sup>', $vstup);
	$vstup = preg_replace('/\m3\]/i', 'm<sup>3</sup>', $vstup);
	return $vstup;
}

function OsetriVideoCallback($vstup) {
		$navrat = "";
		$chyba = 0;
		$id = isset($vstup[1]) ? $vstup[1] : null;
		$obrazek = isset($vstup[3]) && (int)$vstup[3] > 0 ? (int)$vstup[3] : null;
		$sirka = isset($vstup[4]) && (int)$vstup[4] > 0 ? (int)$vstup[4] : null;
		$vyska = isset($vstup[5]) && (int)$vstup[5] > 0 ? (int)$vstup[5] : null;
		$zarovnani = isset($vstup[6]) ? $vstup[6] : null;

		if(is_null($sirka)) $sirka = 482;
		if(is_null($vyska)) $vyska = 271;

		if ($id == null) {$navrat= "[NEPLATNÝ IDENTIFIKÁTOR VIDEA]"; $chyba = 1;}

		$tridaObrazku = " class=\"obrazekStred obrazekVideo \"";
		$marginPrava=10;
		if ($zarovnani == "<") {$tridaObrazku = " class=\"obrazekLevy obrazekVideo\""; $marginPrava=10;}
		else if ($zarovnani == ">") {$tridaObrazku = " class=\"obrazekPravy obrazekVideo\""; $marginLeva=10;}

		if($chyba == 0)
		{
			$navrat = "<iframe ".$tridaObrazku." ".((!is_null($sirka)?"width=\"$sirka\"":""))." ".((!is_null($vyska)?"height=\"$vyska\"":""))." src='//www.youtube.com/embed/".$id."?rel=0' frameborder='0' allowfullscreen></iframe>";
		}
		return($navrat);
	}

function OsetriMapuCallback($vstup)
	{
		return("");
		$sirka = isset($vstup[1]) ? $vstup[1] : null;
		$vyska = isset($vstup[2]) ? $vstup[2] : null;
		$zarovnani = isset($vstup[3]) ? $vstup[3] : null;
		if(is_null($sirka) || is_null($vyska)) return("Nezadané rozměry");

		$tridaObrazku = " class=\"nepodstatne\"";
		if ($zarovnani == "L") {$tridaObrazku = " class=\"obrazekLevy\""; $marginPrava=10;}
		else if ($zarovnani == "P") {$tridaObrazku = " class=\"obrazekPravy\""; $marginLeva=10;}

		// Mapy.cz -->
		//http://mapy.cz/#!x=18.238623&y=49.791089&z=17&t=s&d=addr_9113178_1
		$navrat = '
			<div id="m" '.$tridaObrazku.' style="width:'.$sirka.'px; height:'.$vyska.'px;"></div>
		<script type="text/javascript">

			var center = SMap.Coords.fromWGS84(18.243623,49.791089);

			var m = new SMap(JAK.gel("m"), center, 13);
			m.addDefaultLayer(SMap.DEF_OPHOTO);
			m.addDefaultLayer(SMap.DEF_TURIST);
			m.addDefaultLayer(SMap.DEF_BASE).enable();

			var layerSwitch = new SMap.Control.Layer();
			layerSwitch.addDefaultLayer(SMap.DEF_BASE);
			layerSwitch.addDefaultLayer(SMap.DEF_OPHOTO);
			layerSwitch.addDefaultLayer(SMap.DEF_TURIST);

			m.addControl(layerSwitch, {left:"8px", top:"9px"});

			m.addDefaultControls();

			var layer = new SMap.Layer.Marker();
			m.addLayer(layer);
			layer.enable();


			var pozice1 = SMap.Coords.fromWGS84(18.238493,49.791189);
			var marker = new SMap.Marker(pozice1, "",{title: "PPPlaček", url: \''.PREFIX.'system/design/obrazky/mapy-akord.png\', anchor: {left:0, top:0}});
			layer.addMarker(marker);

			var mini4 = new SMap.Control.Minimap(150, 100, {diff: -4, layer: SMap.DEF_BASE, color: "black", opacity: 0.3});
			m.addControl(mini4, {right:"3px", bottom:"3px"});
			</script>
			';
			return($navrat);
	}

function je_produkt_sprazen_se_sekci($idProdukt, $poleSprazeni, $poleAktivnichHierarchii) {
	foreach ($poleSprazeni as $sprazenyProdukt => $sprazeneHierarchie) {
		if ($sprazenyProdukt == $idProdukt) {
			//print_r($sprazeneHierarchie);
			//foreach($sprazeneHierarchie as $k=>$jednaSprazenaHierarchie)
			{
				//if(in_array($jednaSprazenaHierarchie, $poleAktivnichHierarchii))
				if (in_array($sprazeneHierarchie, $poleAktivnichHierarchii)) {
					return(true);
				}
			}
		}
	}

	return(false);
}

function VratPocetProduktuVHierarchii($IdHierarchie) {
	global $conn;
	$sql = "SELECT count(*) AS pocet FROM produkty_hierarchie as ph INNER JOIN produkty AS p ON p.id_produkt = ph.produkty_id_produkt AND p.pr_datum_neaktivni IS NULL WHERE ph.hierarchie_id_hierarchie = " . gpc_addslashes($IdHierarchie) . " \n";
	$poctyProduktu = $conn->query($sql);
	$pocet = 0;
	if ($poctyProduktu && $poctyProduktu->num_rows > 0) {
		while ($pocetProduktu = $poctyProduktu->fetch_array(MYSQLI_ASSOC)) {
			$pocet = $pocetProduktu['pocet'];
		}
		$poctyProduktu->close();
	}
	//return(10);
	return($pocet);
}

function VratProduktyVHierarchii($IdHierarchie, $vratITitulniClanek = true) {
	global $conn, $POLE_HIERARCHII_RAZENYCH_SESTUPNE;
	$sql = "SELECT p.*, p.pr_poradi IS NULL AS pr_poradi_isnull FROM produkty_hierarchie as ph INNER JOIN produkty AS p ON p.id_produkt = ph.produkty_id_produkt AND p.pr_datum_neaktivni IS NULL WHERE ";
	$sql.= "ph.hierarchie_id_hierarchie = " . gpc_addslashes($IdHierarchie) . " ";
	if(!$vratITitulniClanek) $sql.= "AND p.pr_titulni = 0 ";
	if(in_array($IdHierarchie, $POLE_HIERARCHII_RAZENYCH_SESTUPNE))
		$sql.= "ORDER BY pr_poradi_isnull, pr_poradi DESC, pr_zobrazovane_datum DESC, id_produkt DESC \n";
	else
		$sql.= "ORDER BY pr_poradi_isnull, pr_poradi \n";
	$poleProduktu = array();
	$produkty = $conn->query($sql);
	if ($produkty && $produkty->num_rows > 0) {
		while ($produkt = $produkty->fetch_array(MYSQLI_ASSOC)) {
			$poleProduktu[] = VratDetailProduktu($produkt['id_produkt']);
		}
		$produkty->close();
	}
	return($poleProduktu);
}

function VratHierarchieVHierarchii($IdHierarchie) {
	global $conn;
	if (is_null($IdHierarchie))
		$sql = "SELECT hi.*, hi.hi_poradi IS NULL AS hi_poradi_isnull FROM hierarchie as hi WHERE hi.hierarchie_id_hierarchie IS NULL ORDER BY hi_poradi_isnull, hi_poradi \n";
	else
		$sql = "SELECT hi.*, hi.hi_poradi IS NULL AS hi_poradi_isnull FROM hierarchie as hi WHERE hi.hierarchie_id_hierarchie = " . gpc_addslashes($IdHierarchie) . " ORDER BY hi_poradi_isnull, hi_poradi \n";
	$poleHierarchii = array();
	$hierarchie = $conn->query($sql);
	if ($hierarchie && $hierarchie->num_rows > 0) {
		while ($jednaHierarchie = $hierarchie->fetch_array(MYSQLI_ASSOC)) {
			$poleHierarchii[] = VratDetailHierarchie($jednaHierarchie['id_hierarchie']);
		}
		$hierarchie->close();
	}
	return($poleHierarchii);
}

function VratCestuKProduktu($IdProdukt, $zvolenyJazyk = null, $odkazProEditaci = false) {
	global $conn, $jazyk;
	$vysledek = null;
	if (is_null($zvolenyJazyk))
		$sql = "select *, " . $jazyk->Sql2Jaz('pr_seo') . ", " . $jazyk->Sql2JazVratPolickoAs('pr_seo_podstrcene', 'pr_seo_podstrcene_jazyk') . " from produkty_hierarchie as ph inner join produkty as p on p.id_produkt = ph.produkty_id_produkt where p.id_produkt = " . $IdProdukt . " limit 0,1\n";
	else
		$sql = "select *, " . $jazyk->Sql2JazZadanyJazyk('pr_seo', $zvolenyJazyk) . ", " . $jazyk->Sql2JazVratPolickoJazykAs('pr_seo_podstrcene', $zvolenyJazyk, 'pr_seo_podstrcene_jazyk') . " from produkty_hierarchie as ph inner join produkty as p on p.id_produkt = ph.produkty_id_produkt where p.id_produkt = " . $IdProdukt . " limit 0,1\n";

	//echo($sql);
	$cesty = $conn->query($sql);
	if ($cesty && $cesty->num_rows > 0) {
		if ($cesta = $cesty->fetch_array(MYSQLI_ASSOC)) {
			if (JeProduktVEshopu($IdProdukt)) {
				$prSeo = $cesta['pr_seo'];
				if (strlen($cesta['pr_seo_podstrcene_jazyk']) > 0)
					$prSeo = $cesta['pr_seo_podstrcene_jazyk'];
				$vysledek = seo_v_seznamu($cesta['hierarchie_id_hierarchie'], $zvolenyJazyk) . (PRODUKTY_POUZIVEJ_ID_V_URL ? $cesta['id_produkt'] . "/" : "") . $prSeo. ($odkazProEditaci?".php":".html");
			}
			else {
				if(PRAVA != ADMINISTRATOR &&  strlen($cesta['pr_proklik']) > 0)
				{
					$vysledek = OsetriVstup($cesta['pr_proklik']);
				}
				else
				{
					$prSeo = $cesta['pr_seo'];
					if (strlen($cesta['pr_seo_podstrcene_jazyk']) > 0)
						$prSeo = $cesta['pr_seo_podstrcene_jazyk'];
					$vysledek = seo_v_seznamu($cesta['hierarchie_id_hierarchie'], $zvolenyJazyk) . (CLANKY_POUZIVEJ_ID_V_URL ? $cesta['id_produkt'] . "/" : "") . $prSeo. ($odkazProEditaci?".php":".html");
				}
			}
		}
		$cesty->close();
	}
	return($vysledek);
}

function VratDetailProduktu($IdProduktu) {
	global $conn, $jazyk;
	$sql = "select *, " . $jazyk->Sql2Jaz('pr_nazev') . ", " . $jazyk->Sql2Jaz('pr_nahled') . ", " . $jazyk->Sql2Jaz('pr_popis') . ", " . $jazyk->Sql2Jaz('pr_seo') . ", " . $jazyk->Sql2Jaz('pr_popis_stranky') . ", " . $jazyk->Sql2Jaz('pr_klicova_slova') . " from produkty as p \n";
	$sql.= "left join produkty_ceny as pc on pc.produkty_id_produkt = p.id_produkt and typy_cen_id_typ_ceny = 1 and pc_datum_neaktivni is null ";
	$sql.= "left join firmy as f on f.id_firma = p.firmy_vyrobce_id_firma ";
	$sql.= "left join dph as dph on pc.dph_id_dph = dph.id_dph ";
	$sql.= "where p.id_produkt = " . $IdProduktu . " ";
	$produkty = $conn->query($sql);
	if ($produkty && $produkty->num_rows > 0) {
		if ($jedenProdukt = $produkty->fetch_array(MYSQLI_ASSOC)) {
			$jedenProdukt['cesta'] = VratCestuKProduktu($IdProduktu);
			return($jedenProdukt);
		}
		$produkty->close();
	}
	return(null);
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

/* Pokud je produkt zařazen v takové větvi, že její kořenova hierarchie je E-SHOP tak vrací true */

function JeProduktVEshopu($idProdukt) {
	global $conn;
	$sql = "SELECT * FROM produkty_hierarchie WHERE produkty_id_produkt = " . (int) gpc_addslashes($idProdukt) . " ";
	$hierarchieKdeJeProdukt = $conn->query($sql);
	if ($hierarchieKdeJeProdukt && $hierarchieKdeJeProdukt->num_rows > 0) {
		while ($jednaHierarchieKdeJeProdukt = $hierarchieKdeJeProdukt->fetch_array(MYSQLI_ASSOC)) {
			$pomPole = array();
			VratPoleAktivnichHierarchii($jednaHierarchieKdeJeProdukt['hierarchie_id_hierarchie'], $pomPole);
			if (in_array(HIERARCHIE_ESHOP, $pomPole))
				return(true);
		}
		$hierarchieKdeJeProdukt->close();
	}
	return(false);
}

/* Funkce vrátí id všech hierarchii v hierarchii */

function VratVsechnyHierarchieVHiearchiiRekurzivne($IdHierarchie, &$pole) {
	global $conn;
	$sql = "SELECT id_hierarchie FROM hierarchie WHERE hierarchie_id_hierarchie = " . $IdHierarchie . " AND hi_neaktivni = 0 \n";
	$viceHierarchii = $conn->query($sql);
	if ($viceHierarchii && $viceHierarchii->num_rows > 0) {
		while ($jednaHierarchie = $viceHierarchii->fetch_array(MYSQLI_ASSOC)) {
			$pole[] = $jednaHierarchie['id_hierarchie'];
			VratVsechnyHierarchieVHiearchiiRekurzivne($jednaHierarchie['id_hierarchie'], $pole);
		}
		$viceHierarchii->close();
	}
}

/* Funkce vrátí obrázky k produktu */

function VratVsechnyObrazkyKProduktu($IdProdukt, &$pole) {
	global $conn;
	$sql = "SELECT *, pr_poradi IS NULL AS pr_poradi_isnull FROM produkty_binarni_data WHERE produkty_id_produkt = " . gpc_addslashes($IdProdukt) . " AND pr_nezobrazovat = 0 AND typy_dat_id_typ_dat = 1 ORDER BY pr_poradi_isnull, pr_poradi, produkty_id_produkt DESC  \n";
	$obrazky = $conn->query($sql);
	if ($obrazky && $obrazky->num_rows > 0) {
		while ($jedenObrazek = $obrazky->fetch_array(MYSQLI_ASSOC)) {
			$obrazek = $jedenObrazek;
			$obrazek['cesta'] = ADRESA_E_SHOPU . PREFIX_SOUBORY . "katalog-obrazku/clanek-" . $jedenObrazek['produkty_id_produkt'] . "/" . $jedenObrazek['id_produkt_fotografie'] . "-" . $jedenObrazek['pr_jmeno_souboru'];
			$pole[] = $obrazek;
		}
		$obrazky->close();
	}
}

/*
  Funkce vrati jednoduchou strukturu hierarchie vcetne produktu. Vrac9 pouye id produktu/hierarchie, typ (produkt/hierarchie), u hierarchii s podhierarchiemi vraci rekurzivne obsah
  korenovaHierarchie - idHierarchie odkud se zacina se zpracovanim (null pro zpracovani od korene)
  vratiTakeNeaktivni - pokud je true vraci i neaktivni polozky
 */

function vratStrukturuHierarchieProMenu($korenovaHierarchie = null, $vratitTakeNeaktivni = false) {
	global $conn;
	$poleStruktury = array();

	$korenovaHierarchieSQL = 0;
	if (!is_null($korenovaHierarchie))
		$korenovaHierarchieSQL = $korenovaHierarchie;
	$sql = "select 'hierarchie' as union_typ, h.id_hierarchie, 0 as id_produkt, h.hierarchie_id_hierarchie, h.hi_poradi AS union_poradi, h.hi_poradi is null as union_poradi_isnull \n";
	$sql.= "from hierarchie as h \n";
	if (!is_null($korenovaHierarchie))
		$sql.= "where h.hierarchie_id_hierarchie = " . gpc_addslashes($korenovaHierarchieSQL) . " \n";
	else
		$sql.= "where h.hierarchie_id_hierarchie IS NULL \n";
	if (!$vratitTakeNeaktivni)
		$sql.= "AND h.hi_neaktivni = 0 \n";
	if (strlen($korenovaHierarchie) == 0)
		$korenovaHierarchieSQL = 0;
	$sql.= "union ";
	$sql.= "select 'produkt' as union_typ, 0 as id_hierarchie, p.id_produkt, 0 as hierarchie_id_hierarchie, p.pr_poradi AS union_poradi, p.pr_poradi is null as union_poradi_isnull \n";
	$sql.= "from produkty_hierarchie as ph inner join produkty as p on p.id_produkt = ph.produkty_id_produkt \n";
	//$sql.= "where ph.hierarchie_id_hierarchie  = ".$korenovaHierarchie." and p.pr_datum_neaktivni is null and p.pr_tip = 1  \n";
	$sql.= "where ph.hierarchie_id_hierarchie  = " . $korenovaHierarchieSQL . " \n";
	if (!$vratitTakeNeaktivni) {
		$sql.= " AND p.pr_datum_neaktivni IS NULL ";
		//$sql.= " AND (DATE(NOW()) BETWEEN IFNULL(pr_datum_zobrazovat_od, '1971-01-01') AND IFNULL(pr_datum_zobrazovat_do, '2040-12-31')) ";
	}
	$sql.= "ORDER BY union_poradi_isnull, union_poradi \n";

	//echo($sql);
	$struktura = $conn->query($sql);
	if ($struktura && $struktura->num_rows > 0) {
		while ($jednaStruktura = $struktura->fetch_array(MYSQLI_ASSOC)) {
			if ($jednaStruktura['union_typ'] == 'produkt') {
				$polozkaStruktura = array();
				$polozkaStruktura['typ'] = $jednaStruktura['union_typ'];
				$polozkaStruktura['idPolozky'] = $jednaStruktura['id_produkt'];
				$poleStruktury[] = $polozkaStruktura;
			} else if ($jednaStruktura['union_typ'] == 'hierarchie') {
				$polozkaStruktura = array();
				$polozkaStruktura['typ'] = $jednaStruktura['union_typ'];
				$polozkaStruktura['idPolozky'] = $jednaStruktura['id_hierarchie'];
				$podhierarchie = vratStrukturuHierarchieProMenu($jednaStruktura['id_hierarchie'], $vratitTakeNeaktivni);
				if (sizeof($podhierarchie) > 0)
					$polozkaStruktura['podMenu'] = $podhierarchie;
				$poleStruktury[] = $polozkaStruktura;
			}
		}
		$struktura->close();
	}

	return($poleStruktury);
}

/*
  Funkce naplní pole hodnotami - u každého produktu mohou být funkce jiné takže v tato fnkce se bude modifikovat
  strukturaHierarchie - pole hodnot které vrací funkce vratStrukturuHierarchieProMenu
 */

function vratNaplnenouStrukturu($strukturaHierarchie) {
	global $conn;
	$naplnenePole = array();
	foreach ($strukturaHierarchie as $k => $polozkaStruktury) {
		$polozkaNaplnenehoPole = array();
		$polozkaNaplnenehoPole = $polozkaStruktury;
		if ($polozkaStruktury['typ'] == 'produkt') {
			$detailProduktu = VratDetailProduktu($polozkaStruktury['idPolozky']);
			//zde se pripojuji pole vlastnosti pro produkt
			$polozkaNaplnenehoPole = array_merge($polozkaNaplnenehoPole, $detailProduktu);

			$polozkaNaplnenehoPole['url'] = $detailProduktu['cesta'];
			$polozkaNaplnenehoPole['nazev'] = $detailProduktu['pr_nazev'];
			$polozkaNaplnenehoPole['popis'] = $detailProduktu['pr_popis'];
			$polozkaNaplnenehoPole['nahled'] = $detailProduktu['pr_nahled'];
			$polozkaNaplnenehoPole['seo'] = $detailProduktu['pr_seo'];
      $polozkaNaplnenehoPole['proklik'] = $detailProduktu['pr_proklik'];
		} else if ($polozkaStruktury['typ'] == 'hierarchie') {
			$detailHierarchie = VratDetailHierarchie($polozkaStruktury['idPolozky']);
			//zde se pripojuji pole vlastnosti pro hierarchii
			$polozkaNaplnenehoPole = array_merge($polozkaNaplnenehoPole, $detailHierarchie);

			$polozkaNaplnenehoPole['url'] = $detailHierarchie['cesta'];
			$idVychoziClanek = vratVychoziClanekHierarchie($polozkaStruktury['idPolozky']);
			if(!is_null($idVychoziClanek))
			{
				$vychoziClanek = VratDetailProduktu($idVychoziClanek);
				$polozkaNaplnenehoPole['url'] = $vychoziClanek['cesta'];
			}
			$polozkaNaplnenehoPole['nazev'] = $detailHierarchie['hi_nazev'];
			$polozkaNaplnenehoPole['popis'] = $detailHierarchie['hi_popis'];
			$polozkaNaplnenehoPole['seo'] = $detailHierarchie['hi_seo'];
			if (isset($polozkaStruktury['podMenu']) && sizeof($polozkaStruktury['podMenu']) > 0) {
				$podmenu = vratNaplnenouStrukturu($polozkaStruktury['podMenu']);
				if (sizeof($podmenu) > 0)
					$polozkaNaplnenehoPole['podMenu'] = $podmenu;
			}
		}

		if (sizeof($polozkaNaplnenehoPole) > 0)
			$naplnenePole[] = $polozkaNaplnenehoPole;
	}
	return($naplnenePole);
}

/*
  Funkce vytvoří podmenu dle zadane struktury a vrací html
  struktura - pole struktury hierarchie
  pocetZanoreni - pocet zanoreni ktere chceme vykreslovat
  class - trida ktera se prida do UL
  cisloZanoreni - promenna pro predavani iteraci
 */

function vratPodmenu($struktura, $pocetZanoreni = null, $class = "menu", $cisloZanoreni = 0) {
	global $IdProdukt, $poleAktivnichHierarchii;
	if (is_null($poleAktivnichHierarchii))
		$lokalniPoleAktivnichHierarchii = array();
	else
		$lokalniPoleAktivnichHierarchii = $poleAktivnichHierarchii;
	if (is_null($IdProdukt))
		$lokalniIdProdukt = 0;
	else
		$lokalniIdProdukt = $IdProdukt;

	$cisloZanoreni++;
	$vysledek = "";
	if (sizeof($struktura) > 0) {
		$vysledek.="\n<ul class=\"" . $class . " zanoreni" . $cisloZanoreni . " rozbalene\">\n";
		foreach ($struktura as $k => $polozkaStruktury) {
			if ($polozkaStruktury['typ'] == 'produkt') {
				$neaktivni = (strlen($polozkaStruktury['pr_datum_neaktivni']) > 0) ? " neaktivni" : "";
				$aktivniPolozka = ($polozkaStruktury['idPolozky'] == $lokalniIdProdukt) ? " aktivni" : "";
        //zda neudelat aktivni i odkazujici prokliky
        if(!empty($polozkaStruktury['proklik']))
        {
          //pokud proklik ukazuje na produkt a ten je aktivni je io proklikavaci odkay aktivni
          preg_match('/PRODUKT ID=([0-9]+)/i', $polozkaStruktury['proklik'], $shody);
          if(!empty($shody[1]))
          {
            $aktivniPolozka = ($shody[1] == $lokalniIdProdukt) ? " aktivni" : "";
          }
          //pokud proklik ukazuje na hierarchii a ta a jeji obsah je aktivni je i proklikavaci odkaz aktivni
          preg_match('/HIERARCHIE id=([0-9]+)/i', $polozkaStruktury['proklik'], $shody);
          if(!empty($shody[1]))
          {
            $aktivniPolozka = (in_array($shody[1], $lokalniPoleAktivnichHierarchii)) ? " aktivni" : "";
          }
        }

				$vysledek.= "<li class=\"" . $class . "LiPr" . $polozkaStruktury['idPolozky'] . $neaktivni . $aktivniPolozka . "\">\n";
				$vysledek.= "<span class=\"" . $class . "Rozbalovaci nerozbalovaci\"></span>\n";
				$vysledek.= "<a href=\"" . $polozkaStruktury['url'] . "\">\n";
				$vysledek.= $polozkaStruktury['nazev'] . "\n";
				$vysledek.= "</a>\n";
				$vysledek.= "</li>\n";
			}
			if ($polozkaStruktury['typ'] == 'hierarchie') {
				$neaktivni = ($polozkaStruktury['hi_neaktivni'] == 1) ? " neaktivni" : "";
				$aktivniPolozka = (in_array($polozkaStruktury['idPolozky'], $lokalniPoleAktivnichHierarchii)) ? " aktivni" : "";
				$maPodmenu = (isset($polozkaStruktury['podMenu']) && sizeof($polozkaStruktury['podMenu']) > 0) ? " maPodmenu " : "";
				$vysledek.= "<li class=\"" . $class . "LiHi" . $polozkaStruktury['idPolozky'] . $neaktivni . $aktivniPolozka . $maPodmenu . "\">\n";
				$vysledek.= "<span class=\"" . $class . "Rozbalovaci " . ($maPodmenu ? "nerozbaleny" : "nezobrazovat") . "\"></span>\n";
				$vysledek.= "<a href=\"" . $polozkaStruktury['url'] . "\">\n";
				$vysledek.= $polozkaStruktury['nazev'] . "\n";
				$vysledek.= "</a>\n";
				if (is_null($pocetZanoreni) || $pocetZanoreni > $cisloZanoreni)
					if (isset($polozkaStruktury['podMenu']) && sizeof($polozkaStruktury['podMenu']) > 0) {
						$dalsiZanoreni = null;
						if (!is_null($pocetZanoreni))
							$dalsiZanoreni = $pocetZanoreni - 1;
						$podmenu = vratPodmenu($polozkaStruktury['podMenu'], $dalsiZanoreni, $class, $cisloZanoreni);
						if (sizeof($podmenu) > 0)
							$vysledek.=$podmenu;
					}
				$vysledek.= "</li>\n";
			}
		}
		$vysledek.="<br class='clearBoth' /></ul>\n";
	}
	return($vysledek);
}

/*
  Funkce vrátí kompletní menu
  idKorenoveHierarchie - id pro pocatecni koren, null pro cely strom
  pocetZanoreni - kolik se ma vykreslit zanoreni
  class - pojmenovani tridy
 */

function vratMenu($idKorenoveHierarchie, $pocetZanoreni = null, $class = "menu") {
	$struktura = vratStrukturuHierarchieProMenu($idKorenoveHierarchie, PRAVA == ADMINISTRATOR);
	$naplnenaStruktura = vratNaplnenouStrukturu($struktura);
	$menu = vratPodmenu($naplnenaStruktura, $pocetZanoreni, $class, 0);
	return($menu);
}



/**
	 *funkce odstrani jeden radek tabulky, updatuje pocty radku vraci true/false dle uspechu operace
	 **/
	function OdstranRadekTabulky($idTabulky, $radekCislo)
	{
		global $conn;

		//zjistime pocet radku
		$sql = "SELECT MAX(tabulky_radky_id_radek_informaci) AS max FROM e_tabulky_hodnoty WHERE id_e_tabulky = ".(int)gpc_addslashes($idTabulky)." ";
		$pocetRadku = 0;
		$pocet = $conn->query($sql);
		if ($pocet && $pocet->num_rows > 0)
		{
			if ($jedenPocet = $pocet->fetch_array(MYSQLI_ASSOC))
			{
				$pocetRadku = $jedenPocet['max'];
			}
			$pocet->close();
		}

		$sql = "start transaction; \n";
		if(!$conn->query($sql)) return(false); //...Start transakce
		//smazu radek
		$sql = "DELETE FROM e_tabulky_hodnoty WHERE ";
		$sql.= "id_e_tabulky = ".(int)gpc_addslashes($idTabulky)." ";
		$sql.= "AND tabulky_radky_id_radek_informaci = ".(int)gpc_addslashes($radekCislo)." ";
		if(!$conn->query($sql)) return(false);

		//snizime radky zaznamum nize
		$sql = "SELECT * FROM e_tabulky_hodnoty WHERE ";
		$sql.= "id_e_tabulky = ".(int)gpc_addslashes($idTabulky)." ";
		$sql.= "AND tabulky_radky_id_radek_informaci > ".(int)gpc_addslashes($radekCislo)." ORDER BY tabulky_radky_id_radek_informaci ASC ";
		$radky = $conn->query($sql);
		if ($radky && $radky->num_rows > 0)
		{
			while ($radek = $radky->fetch_array(MYSQLI_ASSOC))
			{
				$sql2 = "UPDATE e_tabulky_hodnoty SET ";
				$sql2.= "id_tabulky_hodnoty = '".$radek['id_e_tabulky']."-".($radek['tabulky_radky_id_radek_informaci']-1)."-".$radek['tabulky_sloupce_id_sloupec_informaci']."', ";
				$sql2.= "tabulky_radky_id_radek_informaci = ".($radek['tabulky_radky_id_radek_informaci']-1)." ";
				$sql2.= "WHERE id_tabulky_hodnoty = '".$radek['id_tabulky_hodnoty']."' ";
				if(!$conn->query($sql2)) return(false);
			}
			$radky->close();
		}

		//updatneme v tabulkach pocet radku
		$sql = "UPDATE e_tabulky SET tabulky_radky = tabulky_radky - 1 WHERE id_tabulka_informaci = ".(int)gpc_addslashes($idTabulky)."";
		if(!$conn->query($sql)) return(false);

		$sql = "commit;";
		if(!$conn->query($sql)) return(false);
		return(true);
	}

	/**
	 *funkce odstrani jeden sloupec tabulky, updatuje pocty sloupcu vraci true/false dle uspechu operace
	 **/
	function OdstranSloupecTabulky($idTabulky, $sloupecCislo)
	{
		global $conn;

		//zjistime pocet sloupcu
		$sql = "SELECT MAX(tabulky_sloupce_id_sloupec_informaci) AS max FROM e_tabulky_hodnoty WHERE id_e_tabulky = ".(int)gpc_addslashes($idTabulky)." ";
		$pocetSloupcu = 0;
		$pocet = $conn->query($sql);
		if ($pocet && $pocet->num_rows > 0)
		{
			if ($jedenPocet = $pocet->fetch_array(MYSQLI_ASSOC))
			{
				$pocetSloupcu = $jedenPocet['max'];
			}
			$pocet->close();
		}
		if($pocetSloupcu <= 1) return(false);

		$sql = "start transaction; \n";
		if(!$conn->query($sql)) return(false); //...Start transakce

		//smazu sloupec
		$sql = "DELETE FROM e_tabulky_hodnoty WHERE ";
		$sql.= "id_e_tabulky = ".(int)gpc_addslashes($idTabulky)." ";
		$sql.= "AND tabulky_sloupce_id_sloupec_informaci = ".(int)gpc_addslashes($sloupecCislo)." ";
		if(!$conn->query($sql)) return(false);

		//snizime sloupce zaznamum dale
		$sql = "SELECT * FROM e_tabulky_hodnoty WHERE ";
		$sql.= "id_e_tabulky = ".(int)gpc_addslashes($idTabulky)." ";
		$sql.= "AND tabulky_sloupce_id_sloupec_informaci > ".(int)gpc_addslashes($sloupecCislo)." ORDER BY tabulky_sloupce_id_sloupec_informaci ASC ";
		$sloupce = $conn->query($sql);
		if ($sloupce && $sloupce->num_rows > 0)
		{
			while ($sloupec = $sloupce->fetch_array(MYSQLI_ASSOC))
			{
				$sql2 = "UPDATE e_tabulky_hodnoty SET ";
				$sql2.= "id_tabulky_hodnoty = '".$sloupec['id_e_tabulky']."-".$sloupec['tabulky_radky_id_radek_informaci']."-".($sloupec['tabulky_sloupce_id_sloupec_informaci']-1)."', ";
				$sql2.= "tabulky_sloupce_id_sloupec_informaci = ".($sloupec['tabulky_sloupce_id_sloupec_informaci']-1)." ";
				$sql2.= "WHERE id_tabulky_hodnoty = '".$sloupec['id_tabulky_hodnoty']."' ";
				if(!$conn->query($sql2)) return(false);
			}
			$sloupce->close();
		}

		//updatneme v tabulkach pocet sloupcu
		$sql = "UPDATE e_tabulky SET tabulky_sloupce = tabulky_sloupce - 1 WHERE id_tabulka_informaci = ".(int)gpc_addslashes($idTabulky)."";
		if(!$conn->query($sql)) return(false);

		$sql = "commit;";
		if(!$conn->query($sql)) return(false);

		return(true);
	}

	/**
	 *funkce přidá řádek do zvolené tabulky na zadané místo, ostatní řádky posune dál. Při nezadané hodnotě řádku nebo vyšší hodnotě než má tabulka řádků přidává na konec. Updatuje tabulku. Vraci true/false dle úspěchu operace
	 **/
	function PridejRadekTabulky($idTabulky, $radekNaMisto = null)
	{
		global $conn;

		$sql = "start transaction; \n";
		if(!$conn->query($sql)) return(false); //...Start transakce

		//zjistime pocet radku
		$sql = "SELECT MAX(tabulky_radky_id_radek_informaci) AS max FROM e_tabulky_hodnoty WHERE id_e_tabulky = ".(int)gpc_addslashes($idTabulky)." ";
		$pocetRadku = 0;
		$pocet = $conn->query($sql);
		if ($pocet && $pocet->num_rows > 0)
		{
			if ($jedenPocet = $pocet->fetch_array(MYSQLI_ASSOC))
			{
				$pocetRadku = $jedenPocet['max'];
			}
			$pocet->close();
		}

		//zjistime pocet sloupcu
		$sql = "SELECT MAX(tabulky_sloupce_id_sloupec_informaci) AS max FROM e_tabulky_hodnoty WHERE id_e_tabulky = ".(int)gpc_addslashes($idTabulky)." ";
		$pocetSloupcu = 0;
		$pocet = $conn->query($sql);
		if ($pocet && $pocet->num_rows > 0)
		{
			if ($jedenPocet = $pocet->fetch_array(MYSQLI_ASSOC))
			{
				$pocetSloupcu = $jedenPocet['max'];
			}
			$pocet->close();
		}

		if(is_null($radekNaMisto)) $radekNaMisto = $pocetRadku + 1;
		else if($radekNaMisto >= $pocetRadku) $radekNaMisto = $pocetRadku + 1;

		//vsechny radky vetsi nez pridavany posuneme o 1
		$sql = "SELECT * FROM e_tabulky_hodnoty WHERE id_e_tabulky = ".(int)gpc_addslashes($idTabulky)." AND tabulky_radky_id_radek_informaci >= ".(int)gpc_addslashes($radekNaMisto)." ORDER BY tabulky_radky_id_radek_informaci DESC ";
		$radky = $conn->query($sql);
		if ($radky && $radky->num_rows > 0)
		{
			while ($radek = $radky->fetch_array(MYSQLI_ASSOC))
			{
				$sql2 = "UPDATE e_tabulky_hodnoty SET ";
				$sql2.= "id_tabulky_hodnoty = '".$radek['id_e_tabulky']."-".($radek['tabulky_radky_id_radek_informaci']+1)."-".$radek['tabulky_sloupce_id_sloupec_informaci']."', ";
				$sql2.= "tabulky_radky_id_radek_informaci = ".($radek['tabulky_radky_id_radek_informaci']+1)." ";
				$sql2.= "WHERE id_tabulky_hodnoty = '".$radek['id_tabulky_hodnoty']."' ";
				if(!$conn->query($sql2)) return(false);
			}
			$radky->close();
		}

		//insertujeme novy radek
		for($i = 1; $i <= $pocetSloupcu; $i++ )
		{
			$sql = "INSERT INTO e_tabulky_hodnoty(id_tabulky_hodnoty, id_e_tabulky, tabulky_radky_id_radek_informaci, tabulky_sloupce_id_sloupec_informaci)";
			$sql.= "VALUES('".(int)gpc_addslashes($idTabulky)."-".(int)gpc_addslashes($radekNaMisto)."-".$i."', ".(int)gpc_addslashes($idTabulky).", ".(int)gpc_addslashes($radekNaMisto).", ".$i.")";
			if(!$conn->query($sql)) return(false);
		}

		//updatneme v tabulkach pocet radku
		$sql = "UPDATE e_tabulky SET tabulky_radky = tabulky_radky + 1 WHERE id_tabulka_informaci = ".(int)gpc_addslashes($idTabulky)."";
		if(!$conn->query($sql)) return(false);

		$sql = "commit;";
		if(!$conn->query($sql)) return(false);

		return(true);
	}

	/**
	 *funkce přidá sloupec do zvolené tabulky na zadané místo, ostatní sloupce posune dál. Při nezadané hodnotě sloupce nebo vyšší hodnotě než má tabulka sloupcu přidává na konec. Updatuje tabulku. Vraci true/false dle úspěchu operace
	 **/
	function PridejSloupecTabulky($idTabulky, $sloupecNaMisto = null)
	{
		global $conn;

		$sql = "start transaction; \n";
		if(!$conn->query($sql)) return(false); //...Start transakce

		//zjistime pocet radku
		$sql = "SELECT MAX(tabulky_radky_id_radek_informaci) AS max FROM e_tabulky_hodnoty WHERE id_e_tabulky = ".(int)gpc_addslashes($idTabulky)." ";
		$pocetRadku = 0;
		$pocet = $conn->query($sql);
		if ($pocet && $pocet->num_rows > 0)
		{
			if ($jedenPocet = $pocet->fetch_array(MYSQLI_ASSOC))
			{
				$pocetRadku = $jedenPocet['max'];
			}
			$pocet->close();
		}

		//zjistime pocet sloupcu
		$sql = "SELECT MAX(tabulky_sloupce_id_sloupec_informaci) AS max FROM e_tabulky_hodnoty WHERE id_e_tabulky = ".(int)gpc_addslashes($idTabulky)." ";
		$pocetSloupcu = 0;
		$pocet = $conn->query($sql);
		if ($pocet && $pocet->num_rows > 0)
		{
			if ($jedenPocet = $pocet->fetch_array(MYSQLI_ASSOC))
			{
				$pocetSloupcu = $jedenPocet['max'];
			}
			$pocet->close();
		}

		if(is_null($sloupecNaMisto)) $sloupecNaMisto = $pocetSloupcu + 1;
		else if($sloupecNaMisto >= $pocetSloupcu) $sloupecNaMisto = $pocetSloupcu + 1;

		//vsechny sloupce vetsi nez pridavany posuneme o 1
		$sql = "SELECT * FROM e_tabulky_hodnoty WHERE id_e_tabulky = ".(int)gpc_addslashes($idTabulky)." AND tabulky_sloupce_id_sloupec_informaci >= ".(int)gpc_addslashes($sloupecNaMisto)." ORDER BY tabulky_sloupce_id_sloupec_informaci DESC ";
		$sloupce = $conn->query($sql);
		if ($sloupce && $sloupce->num_rows > 0)
		{
			while ($sloupec = $sloupce->fetch_array(MYSQLI_ASSOC))
			{
				$sql2 = "UPDATE e_tabulky_hodnoty SET ";
				$sql2.= "id_tabulky_hodnoty = '".$sloupec['id_e_tabulky']."-".$sloupec['tabulky_radky_id_radek_informaci']."-".($sloupec['tabulky_sloupce_id_sloupec_informaci']+1)."', ";
				$sql2.= "tabulky_sloupce_id_sloupec_informaci = ".($sloupec['tabulky_sloupce_id_sloupec_informaci']+1)." ";
				$sql2.= "WHERE id_tabulky_hodnoty = '".$sloupec['id_tabulky_hodnoty']."' ";
				if(!$conn->query($sql2)) return(false);
			}
			$sloupce->close();
		}

		//insertujeme novy sloupec
		for($i = 1; $i <= $pocetRadku; $i++ )
		{
			$sql = "INSERT INTO e_tabulky_hodnoty(id_tabulky_hodnoty, id_e_tabulky, tabulky_radky_id_radek_informaci, tabulky_sloupce_id_sloupec_informaci)";
			$sql.= "VALUES('".(int)gpc_addslashes($idTabulky)."-".$i."-".(int)gpc_addslashes($sloupecNaMisto)."', ".(int)gpc_addslashes($idTabulky).", ".$i.", ".(int)gpc_addslashes($sloupecNaMisto).")";
			if(!$conn->query($sql)) return(false);
		}

		//updatneme v tabulkach pocet sloupcu
		$sql = "UPDATE e_tabulky SET tabulky_sloupce = tabulky_sloupce + 1 WHERE id_tabulka_informaci = ".(int)gpc_addslashes($idTabulky)."";
		if(!$conn->query($sql)) return(false);

		$sql = "commit;";
		if(!$conn->query($sql)) return(false);

		return(true);
	}

	function PripravRowspanColspan($idTabulky)
	{
		global $conn;


		$sql = "start transaction; \n";
		if(!$conn->query($sql)) return(false); //...Start transakce

		//zjistime pocet radku
		$sql = "SELECT MAX(tabulky_radky_id_radek_informaci) AS max FROM e_tabulky_hodnoty WHERE id_e_tabulky = ".(int)gpc_addslashes($idTabulky)." ";
		$pocetRadku = 0;
		$pocet = $conn->query($sql);
		if ($pocet && $pocet->num_rows > 0)
		{
			if ($jedenPocet = $pocet->fetch_array(MYSQLI_ASSOC))
			{
				$pocetRadku = $jedenPocet['max'];
			}
			$pocet->close();
		}

		//zjistime pocet sloupcu
		$sql = "SELECT MAX(tabulky_sloupce_id_sloupec_informaci) AS max FROM e_tabulky_hodnoty WHERE id_e_tabulky = ".(int)gpc_addslashes($idTabulky)." ";
		$pocetSloupcu = 0;
		$pocet = $conn->query($sql);
		if ($pocet && $pocet->num_rows > 0)
		{
			if ($jedenPocet = $pocet->fetch_array(MYSQLI_ASSOC))
			{
				$pocetSloupcu = $jedenPocet['max'];
			}
			$pocet->close();
		}

		//pripravime si mustr jak to bude vypadat
		$virtualniTabulkaRowspan = array();
		$virtualniTabulkaColspan = array();
		for($x = 1; $x <= $pocetSloupcu; $x++)
			for($y = 1; $y <= $pocetRadku; $y++)
			{
				$virtualniTabulkaRowspan[$x][$y] = 1;
				$virtualniTabulkaColspan[$x][$y] = 1;
			}

		for($x = 1; $x <= $pocetSloupcu; $x++)
			for($y = 1; $y <= $pocetRadku; $y++)
			{
				$sql = "SELECT * FROM e_tabulky_hodnoty WHERE id_tabulky_hodnoty = '".$idTabulky."-".$y."-".$x."' ";
				$bunky = $conn->query($sql);
				if ($bunky && $bunky->num_rows > 0)
				{
					if ($bunka = $bunky->fetch_array(MYSQLI_ASSOC))
					{
						//echo($x."-".$y."=");
						$bunkaColspan = $bunka['th_colspan'];
						$bunkaRowspan = $bunka['th_rowspan'];

						if(strlen($bunkaColspan) == 0) $bunkaColspan = 1;
						if(strlen($bunkaRowspan) == 0) $bunkaRowspan = 1;
						if($bunkaColspan == 0) $bunkaColspan = 1;
						if($bunkaRowspan == 0) $bunkaRowspan = 1;

						//echo($bunkaColspan." x ".$bunkaRowspan."<br />");

						for($rx = $x; $rx <= ($x+$bunkaColspan-1); $rx++)
							for($ry = $y; $ry <= ($y+$bunkaRowspan-1); $ry++)
							if($rx != $x || $ry != $y)
							{
								$virtualniTabulkaRowspan[$rx][$ry] = 0;
								$virtualniTabulkaColspan[$rx][$ry] = 0;
								//echo("n");
							}
						if($virtualniTabulkaRowspan[$x][$y] > 0) $virtualniTabulkaRowspan[$x][$y] = $bunkaRowspan;
						if($virtualniTabulkaColspan[$x][$y] > 0) $virtualniTabulkaColspan[$x][$y] = $bunkaColspan;
					}
					$bunky->close();
				}
			}

		/*	echo("<pre>");
		print_r($virtualniTabulkaColspan);
		//print_r($virtualniTabulkaRowspan);
		echo("</pre>");die;*/

		//ulozeni mustru do db
		for($x = 1; $x <= $pocetSloupcu; $x++)
			for($y = 1; $y <= $pocetRadku; $y++)
			{
				$bunkaColspan = (isset($virtualniTabulkaColspan[$x][$y]) && strlen($virtualniTabulkaColspan[$x][$y]) > 0)?(int)$virtualniTabulkaColspan[$x][$y]:null;
				$bunkaRowspan = (isset($virtualniTabulkaRowspan[$x][$y]) && strlen($virtualniTabulkaRowspan[$x][$y]) > 0)?(int)$virtualniTabulkaRowspan[$x][$y]:null;

				if($bunkaColspan == 1) $bunkaColspan = null;
				if($bunkaRowspan == 1) $bunkaRowspan = null;
				//echo($bunkaColspan." x ".$bunkaRowspan." ");
				$sql = "UPDATE e_tabulky_hodnoty SET ";
				$sql.= "th_rowspan = ".(!is_null($bunkaRowspan) ? (int)$bunkaRowspan : "null").", \n";
				$sql.= "th_colspan = ".(!is_null($bunkaColspan) ? (int)$bunkaColspan : "null")." \n";
				$sql.= "WHERE id_tabulky_hodnoty = '".$idTabulky."-".$y."-".$x."' ";
				//echo($sql."<br />");
				if(!$conn->query($sql)) return(false);
			}

		$sql = "commit;";
		if(!$conn->query($sql)) return(false);

		return(true);
	}

	/*Pokud je odkaz null tak se vraci span misto a
	zacatekKonec - null = normalne, zacatek = starovaci tag, konec = koncovy tag
	*/
	function BoxVratObrazek($idObrazek, $typBoxu = null, $zacatekKonec = null, $predanaClass = null, $odkaz = null, $title = null, $containCover = null, $zarovnaniX = "50%", $zarovnaniY = "50%", $rel = null )
	{
		global $conn, $FOTOGRAFIE_ROZMERY, $FOTOGRAFIE_VYCHOZI_OBRAZEK;
    $typBoxu = strtolower($typBoxu);
    if(!is_null($typBoxu) && !isset($FOTOGRAFIE_ROZMERY[$typBoxu])) return(false);
		//pokud je zadan box zkontrolujeme
		if(!is_null($typBoxu))
		{
			$sirka = $FOTOGRAFIE_ROZMERY[$typBoxu]['sirka'];
			$vyska = $FOTOGRAFIE_ROZMERY[$typBoxu]['vyska'];

			$vraciSpan = (is_null($odkaz)?true:false);
			if($zacatekKonec == 'konec') return("</".($vraciSpan?"span":"a")."> ");

			if(!is_null($idObrazek))
      if(!BoxExistujeObrazek($idObrazek, $typBoxu))
			{
				if(!BoxVygenerujObrazek($idObrazek, $typBoxu))
				{
					//return(false);
          $idObrazek = null;
				}
			}
		}
		else
		{
			$sirka = null;
			$vyska = null;
		}
    $informace = array();
    $cestaKObrazku = (ADRESA_E_SHOPU . PREFIX_SOUBORY. $FOTOGRAFIE_VYCHOZI_OBRAZEK);
    $informace = getimagesize($cestaKObrazku);
    if(is_null($idObrazek)) $containCover = "contain";

    if(!is_null($idObrazek))
    {
		  $cestaKObrazku = BoxVratAdresuObrazku($idObrazek, $typBoxu);
      $informace = getimagesize(BoxVratAdresuObrazku($idObrazek, $typBoxu, true));
    }
		$sirkaNahradni = $informace[0];
		$vyskaNahradni = $informace[1];

		if(strlen($sirka) == 0) $sirka = $sirkaNahradni;
		if(strlen($vyska) == 0) $vyska = $vyskaNahradni;

		$vraciSpan = (is_null($odkaz)?true:false);
		$htmlSirka = "";
		if(strlen($sirka) > 0) $htmlSirka.= " width=\"".$sirka."\" ";
		$htmlVyska = "";
		if(strlen($vyska) > 0) $htmlVyska.= " width=\"".$vyska."\" ";
		$html = "";
		$html.= "<".($vraciSpan?"span":"a")." class=\"aObrazekBox aObrazekBoxTyp".ucfirst($typBoxu)." ".(!is_null($predanaClass)?$predanaClass."":"")." \"";
		if(!is_null($odkaz) && !$vraciSpan) $html.= " href=\"".$odkaz."\" ";
		if(!is_null($title)) $html.= " title=\"".$title."\" ";
		if(!is_null($title))
		{
			$informace = BoxVratInformace($idObrazek);
			if(isset($informace['pr_popis_obrazku']) && strlen($informace['pr_popis_obrazku']) > 0)
				$html.= " title=\"".$informace['pr_popis_obrazku']."\" ";
		}
		if(!is_null($rel) && !$vraciSpan) $html.= " rel=\"".$rel."\" ";
		if(!isset($FOTOGRAFIE_ROZMERY[$typBoxu]['nepouzivatBox']) || $FOTOGRAFIE_ROZMERY[$typBoxu]['nepouzivatBox'] != 1)
		{
			$html.= " style=\" ";
			if(strlen($sirka) > 0) $html.= " width: ".$sirka."px; ";
			if(strlen($vyska) > 0) $html.= " height: ".$vyska."px; max-width: 100%; ";
			$html.= " background-image: url('".$cestaKObrazku."'); ";
			$html.= " background-position: ".$zarovnaniX." ".$zarovnaniY."; ";
			$html.= " background-repeat: no-repeat; ";
			if(!is_null($containCover)) $html.= " background-size: ".$containCover."; ";
			$html.= " \" ";
		}
		$html.= " > ";
			if(!is_null($cestaKObrazku)) $html.= "<img alt=\"".$title."\" ".$htmlSirka." ".$htmlVyska." src=\"".$cestaKObrazku."\" Xstyle=\"display: none;\" /> ";
		if(is_null($zacatekKonec)) $html.= "</".($vraciSpan?"span":"a")."> ";
		return($html);
	}

	/*
	Funkce vygeneruje obrazek dle pole zadanych velikosti, vraci true pokud vygenerovala, jinak vraci false
	*/
	function BoxVygenerujObrazek($idObrazek, $typBoxu)
	{
		global $conn, $FOTOGRAFIE_ROZMERY;
    $typBoxu = strtolower($typBoxu);
		if(!isset($FOTOGRAFIE_ROZMERY[$typBoxu])) return(false);


		$sql = "SELECT * FROM produkty_binarni_data WHERE id_produkt_fotografie = ".(int)gpc_addslashes($idObrazek)." ";
		$obrazky = $conn->query($sql);
		if ($obrazky && $obrazky->num_rows > 0)
		{
			if ($obrazek = $obrazky->fetch_array(MYSQLI_ASSOC))
			{
				$jmenoOriginalu = $obrazek['id_produkt_fotografie']."-".$obrazek['pr_jmeno_souboru'];
				/*zjistime aktualni sirku vysku*/
				$informace = @getimagesize(CESTA_KATALOG_OBRAZKU."clanek-".$obrazek['produkty_id_produkt']."/".$jmenoOriginalu);
				$sirka = $informace[0];
				$vyska = $informace[1];

				//pokud je obrazek moc velky neresime ho aby to nespadlo
				if($sirka > NASTAVENI_MAX_ROZMERY_NAHRAVANEHO_OBRAZKU_SIRKA || $vyska > NASTAVENI_MAX_ROZMERY_NAHRAVANEHO_OBRAZKU_VYSKA) return(false);

				$novaSirka = 0;
				$novaVyska = 0;

				/*BOX*/
				/*pokud je zadana sirka i vyska*/
				if(!is_null($FOTOGRAFIE_ROZMERY[$typBoxu]['sirka']) && !is_null($FOTOGRAFIE_ROZMERY[$typBoxu]['vyska']))
				{
					$novaSirka = $FOTOGRAFIE_ROZMERY[$typBoxu]['sirka'];
					$novaVyska = round($vyska*($FOTOGRAFIE_ROZMERY[$typBoxu]['sirka']/$sirka));
					if($novaVyska < $FOTOGRAFIE_ROZMERY[$typBoxu]['vyska'])
					{
						$novaVyska = $FOTOGRAFIE_ROZMERY[$typBoxu]['vyska'];
						$novaSirka = round($sirka*($FOTOGRAFIE_ROZMERY[$typBoxu]['vyska']/$vyska));
					}
				}

				/*pokud je zadana jen sirka*/
				else if(!is_null($FOTOGRAFIE_ROZMERY[$typBoxu]['sirka']) && is_null($FOTOGRAFIE_ROZMERY[$typBoxu]['vyska']))
				{
					$novaSirka = $FOTOGRAFIE_ROZMERY[$typBoxu]['sirka'];
					$novaVyska = round($vyska*($FOTOGRAFIE_ROZMERY[$typBoxu]['sirka']/$sirka));
				}

				/*pokud je zadana jen vyska*/
				else if(is_null($FOTOGRAFIE_ROZMERY[$typBoxu]['sirka']) && !is_null($FOTOGRAFIE_ROZMERY[$typBoxu]['vyska']))
				{
					$novaVyska = $FOTOGRAFIE_ROZMERY[$typBoxu]['vyska'];
					$novaSirka = round($sirka*($FOTOGRAFIE_ROZMERY[$typBoxu]['vyska']/$vyska));
				}

				/*pokud je zadana max sirka zajistime aby nebyla prekrocena na ukor vysky*/
				if(isset($FOTOGRAFIE_ROZMERY[$typBoxu]['maxSirka']) && $novaSirka > $FOTOGRAFIE_ROZMERY[$typBoxu]['maxSirka'])
				{
					$novaSirkaMax = $FOTOGRAFIE_ROZMERY[$typBoxu]['maxSirka'];
					$novaVyskaMax = round($novaVyska*($FOTOGRAFIE_ROZMERY[$typBoxu]['maxSirka']/$novaSirka));
					$novaSirka = $novaSirkaMax;
					$novaVyska = $novaVyskaMax;
				}

				/*pokud je zadana max vyska zajistime aby nebyla prekrocena na ukor sirky*/
				if(isset($FOTOGRAFIE_ROZMERY[$typBoxu]['maxVyska']) && $novaVyska > $FOTOGRAFIE_ROZMERY[$typBoxu]['maxVyska'])
				{
					$novaVyskaMax = $FOTOGRAFIE_ROZMERY[$typBoxu]['maxVyska'];
					$novaSirkaMax = round($novaSirka*($FOTOGRAFIE_ROZMERY[$typBoxu]['maxVyska']/$novaVyska));
					$novaSirka = $novaSirkaMax;
					$novaVyska = $novaVyskaMax;
				}

				resizeImage(CESTA_KATALOG_OBRAZKU."clanek-".$obrazek['produkty_id_produkt']."/".$jmenoOriginalu, $novaSirka, $novaVyska, false, false, $typBoxu.'-', CESTA_KATALOG_OBRAZKU."clanek-".$obrazek['produkty_id_produkt']."/");
				chmod(CESTA_KATALOG_OBRAZKU."clanek-".$obrazek['produkty_id_produkt']."/".$typBoxu."-".$jmenoOriginalu, octdec("0666"));
				/*/BOX*/
				return(true);
			}
			$obrazky->close();
		}
		return(false);
	}

	/*
	Funkce zjisti zda existuje obrazek nebo ne vraci true/false
	*/
	function BoxExistujeObrazek($idObrazek, $typBoxu)
	{
		global $conn, $FOTOGRAFIE_ROZMERY;
    $typBoxu = strtolower($typBoxu);
		if(!isset($FOTOGRAFIE_ROZMERY[$typBoxu])) return(false);

		$sql = "SELECT * FROM produkty_binarni_data WHERE id_produkt_fotografie = ".(int)gpc_addslashes($idObrazek)." ";
		$obrazky = $conn->query($sql);
		if ($obrazky && $obrazky->num_rows > 0)
		{
			if ($obrazek = $obrazky->fetch_array(MYSQLI_ASSOC))
			{
				$jmenoOriginalu = $obrazek['id_produkt_fotografie']."-".$obrazek['pr_jmeno_souboru'];
				$cestaKObrazku = CESTA_KATALOG_OBRAZKU."clanek-".$obrazek['produkty_id_produkt']."/".$typBoxu."-".$jmenoOriginalu;
				if(file_exists($cestaKObrazku)) return(true);
				else return(false);
			}
			$obrazky->close();
		}
		return(false);
	}

	/*
	Funkce vrátí informace o obrázku vraci info/false
	*/
	function BoxVratInformace($idObrazek)
	{
		global $conn;

		$sql = "SELECT * FROM produkty_binarni_data WHERE id_produkt_fotografie = ".(int)gpc_addslashes($idObrazek)." ";
		$obrazky = $conn->query($sql);
		if ($obrazky && $obrazky->num_rows > 0)
		{
			if ($obrazek = $obrazky->fetch_array(MYSQLI_ASSOC))
			{
				return($obrazek);
			}
			$obrazky->close();
		}
		return(false);
	}

	/*
	Funkce vráti cestu k obrazku vrat cestu vrati cestu na disku
	*/
	function BoxVratAdresuObrazku($idObrazek, $typBoxu = null, $vratCestuNaDisku = false)
	{
		global $conn, $FOTOGRAFIE_ROZMERY;
    $typBoxu = strtolower($typBoxu);
		if(!is_null($typBoxu) && !isset($FOTOGRAFIE_ROZMERY[$typBoxu])) return(false);

		$sql = "SELECT * FROM produkty_binarni_data WHERE id_produkt_fotografie = ".(int)gpc_addslashes($idObrazek)." ";
		$obrazky = $conn->query($sql);
		if ($obrazky && $obrazky->num_rows > 0)
		{
			if ($obrazek = $obrazky->fetch_array(MYSQLI_ASSOC))
			{
				if(!is_null($typBoxu))
					if(!BoxExistujeObrazek($idObrazek, $typBoxu))
					{
						if(!BoxVygenerujObrazek($idObrazek, $typBoxu))
						{
							return(false);
						}
					}

				$jmenoOriginalu = $obrazek['id_produkt_fotografie']."-".$obrazek['pr_jmeno_souboru'];

				if(is_null($typBoxu)) $cestaKObrazku = CESTA_KATALOG_OBRAZKU."clanek-".$obrazek['produkty_id_produkt']."/".$jmenoOriginalu;
				else $cestaKObrazku = CESTA_KATALOG_OBRAZKU."clanek-".$obrazek['produkty_id_produkt']."/".$typBoxu."-".$jmenoOriginalu;

				if(!file_exists($cestaKObrazku)) return(false);

				if(is_null($typBoxu) && $vratCestuNaDisku == false)
					return(ADRESA_E_SHOPU . PREFIX_SOUBORY."katalog-obrazku/"."clanek-".$obrazek['produkty_id_produkt']."/".$jmenoOriginalu);
				else if(is_null($typBoxu) && $vratCestuNaDisku == true)
					return(CESTA_KATALOG_OBRAZKU."clanek-".$obrazek['produkty_id_produkt']."/".$jmenoOriginalu);
				else if(!is_null($typBoxu) && $vratCestuNaDisku == false)
					return(ADRESA_E_SHOPU . PREFIX_SOUBORY."katalog-obrazku/"."clanek-".$obrazek['produkty_id_produkt']."/".$typBoxu."-".$jmenoOriginalu);
				else if(!is_null($typBoxu) && $vratCestuNaDisku == true)
					return(CESTA_KATALOG_OBRAZKU."clanek-".$obrazek['produkty_id_produkt']."/".$typBoxu."-".$jmenoOriginalu);
				else
					return(false);
			}
			$obrazky->close();
		}
		return(false);
	}



	/*
	Funkce zjisti zda existuje obrazek nebo ne vraci true/false
	Při nevyplněném typu smaže všechny
	Pokud smazOriginal = true smaze i jeho
	*/
	function BoxSmazObrazek($idObrazek, $typBoxu = null, $smazOriginal = false)
	{
		global $conn, $FOTOGRAFIE_ROZMERY;

    $typBoxu = strtolower($typBoxu);
		$sql = "SELECT * FROM produkty_binarni_data WHERE id_produkt_fotografie = ".(int)gpc_addslashes($idObrazek)." ";
		$obrazky = $conn->query($sql);
		if ($obrazky && $obrazky->num_rows > 0)
		{
			if ($obrazek = $obrazky->fetch_array(MYSQLI_ASSOC))
			{
				$jmenoOriginalu = $obrazek['id_produkt_fotografie']."-".$obrazek['pr_jmeno_souboru'];
				$cestaKObrazku = CESTA_KATALOG_OBRAZKU."clanek-".$obrazek['produkty_id_produkt']."/".$typBoxu."-".$jmenoOriginalu;
				$chyba = false;

				//pokud je zadan typ
				if(!is_null($typBoxu))
				{
					$cestaKObrazku = CESTA_KATALOG_OBRAZKU."clanek-".$obrazek['produkty_id_produkt']."/".$typBoxu."-".$jmenoOriginalu;
					if(file_exists($cestaKObrazku))
					{
						if(!unlink($cestaKObrazku)) $chyba = true;
					}
				}
				else
				{
					foreach($FOTOGRAFIE_ROZMERY as $typBoxu=>$hodnota)
					{
						$cestaKObrazku = CESTA_KATALOG_OBRAZKU."clanek-".$obrazek['produkty_id_produkt']."/".$typBoxu."-".$jmenoOriginalu;
						if(file_exists($cestaKObrazku))
						{
							if(!unlink($cestaKObrazku)) $chyba = true;
						}
					}
					if($smazOriginal)
					{
						$cestaKObrazku = CESTA_KATALOG_OBRAZKU."clanek-".$obrazek['produkty_id_produkt']."/".$jmenoOriginalu;
						if(file_exists($cestaKObrazku))
						{
							if(!unlink($cestaKObrazku)) $chyba = true;
						}
					}
				}

				return($chyba);
			}
			$obrazky->close();
		}
		return(false);
	}

	/*funkce vrátí poměr stran*/
	function VratPomerStran($sirka, $vyska)
	{
		if(!kontrola_cisla($sirka, true)) return(null);
		if(!kontrola_cisla($vyska, true)) return(null);
		if($sirka <= 0) return(null);
		if($vyska <= 0) return(null);

		//$vysoka = ($sirka >= $vyska?false:true);
		$vetsiCislo = ($sirka >= $vyska?$sirka:$vyska);
		$mensiCislo = ($sirka < $vyska?$sirka:$vyska);

		for($i = ($mensiCislo+1); $i--; $i > 0)
		{
			if($vetsiCislo%$i == 0 && $mensiCislo%$i == 0)
			{
				return(round($sirka/$i).":".round($vyska/$i));
			}
		}
		return($sirka.":".$vyska);
	}

	//funkce zda m8 hierarchie podhierarchii nebo produkty vraci true/false
	function maHierarchieObsah($idHierarchie)
	{
		global $conn;
		$sql = "SELECT * FROM hierarchie WHERE hierarchie_id_hierarchie = ".gpc_addslashes($idHierarchie)." ";
        $hierarchie = $conn->query($sql);
        if ($hierarchie && $hierarchie->num_rows > 0)
        {
            return(true);
        }

		$sql = "SELECT * FROM produkty_hierarchie WHERE hierarchie_id_hierarchie = ".gpc_addslashes($idHierarchie)." ";
        $hierarchie = $conn->query($sql);
        if ($hierarchie && $hierarchie->num_rows > 0)
        {
            return(true);
        }

		return(false);
	}

	//funkce vrátí výchozí článek pro hierarchii
	// vraci id produktu nebo null pokud takovy neexistuje
	function vratVychoziClanekHierarchie($idHierarchie)
	{
		global $conn;
		$sql = "SELECT p.id_produkt FROM produkty AS p INNER JOIN produkty_hierarchie AS ph ON p.id_produkt = ph.produkty_id_produkt WHERE ph.hierarchie_id_hierarchie = ".gpc_addslashes($idHierarchie)." ";
		$sql.= "AND p.pr_titulni = 1 ";
		$sql.= "ORDER BY p.pr_poradi IS NULL, p.pr_poradi LIMIT 0,1 ";
        $produkty = $conn->query($sql);
        if ($produkty && $produkty->num_rows > 0)
        {
            if ($produkt = $produkty->fetch_array(MYSQLI_ASSOC))
            {
                return($produkt['id_produkt']);
            }
            $produkty->close();
        }
		return(null);
	}

	/*
	Funkce vrátí true/false zda je id souboru binarnich dat pouze pro prihlasene
	idSouboru - id souboru z tabulky produkty binarni data
	*/
	function jeSouborProPrihlasene($idSouboru)
	{
		global $conn;
		$vysledek = false;
		$sql = "SELECT id_produkt_fotografie FROM produkty_binarni_data WHERE id_produkt_fotografie = ".gpc_addslashes($idSouboru)." AND pr_pro_prihlasene = 1 ";
		$soubory = $conn->query($sql);
		if ($soubory && $soubory->num_rows > 0)
			$vysledek = true;
		return($vysledek);
	}

  function jeProduktVHierarchii($idProdukt, $idHierarchie, $rekurzivne = false)
  {
    global $conn;

    $sql = "SELECT hierarchie_id_hierarchie FROM produkty_hierarchie WHERE produkty_id_produkt = ".gpc_addslashes($idProdukt)." AND hierarchie_id_hierarchie = ".gpc_addslashes($idHierarchie)."";
    $produkty = $conn->query($sql);
    if ($produkty && $produkty->num_rows > 0)
      return(true);
    else
    {
      if($rekurzivne)
      {
        $poleHierarchii = array();
        VratVsechnyHierarchieVHiearchiiRekurzivne($idHierarchie, $poleHierarchii);
        foreach($poleHierarchii as $jednaHierarchie)
        {
          if(jeProduktVHierarchii($idProdukt, $jednaHierarchie, false)) return(true);
        }
      }
      return(false);
    }
  }


?>
