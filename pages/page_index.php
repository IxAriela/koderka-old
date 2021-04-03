<?php
$pageIndex = true;
include("layout/header.php");
?>


<div class="row part">
    <img class="img-responsive" id="IL" alt="Iveta Nešpor Levová, fotil Marek Mantič - www.mantic.cz" title="Iveta Nešpor Levová, fotil Marek Mantič - www.mantic.cz" src="images/ivet.jpg">
    <div class="intro">
        <h1>Tvořím web</h1>
        <p>
            Jmenuji se Iveta Nešpor Levová a dělám weby. Že chci dělat něco s weby jsem věděla už na vysoké škole, kde jsem se poprvé 
            více začala dozvídat o internetu, webech a různých webových technologiích. Jen tehdy jsem ještě nevěděla přesně co, protože 
            to nebyl IT obor a HTML jsme probírali pouze okrajově, CSS vůbec. A přitom až CSS je ta pravá zábava, dodává tomu ten vzhled :) 
        </p> 
        <p> 
            Protože s IT jsem začala až relativně pozdě, troufám si říct, že jsem neztratila nic
            z "běžného" jazyka a dokážu i techničtější webové věci vysvětlit laickým způsobem.
        </p>
    </div>
</div>

<div class="">
    <h2>Moje nabídka</h2>
    <ul>
        <li><a href="<?php echo(PREFIX); ?>wordpress.html">Weby na systému Wordpress</a></li>
        <li><a href="<?php echo(PREFIX); ?>staticke-weby.html">Statické weby (bez administrace)</a></li>
        <li><a href="<?php echo(PREFIX); ?>ostatni-prace.html">Ostatní HTML/CSS práce</a></li>
    </ul>
</div>

<div class="row row-eq-height part" id="ja">
    <div class="col-sm-4">
        <a href="portfolio.html"><h3>Vzdělání a praxe</h3></a>
        <div class="col-sm-12">
            <p class="pics_hover"><a href="portfolio.html"><img class="img-responsive" alt="Vzdělání a praxe" title="Vzdělání a praxe" src="images/Zivotopis.jpg"></a></p>
        </div>
    </div>
    <div class="col-sm-4"><a href="pocitace.html"><h3>Moje cesta k počítačům</h3></a>
        <div class="col-sm-12">
            <p class="pics_hover"><a href="pocitace.html"><img class="img-responsive" alt="Moje cesta k počítačům" title="Moje cesta k počítačům" src="images/Portfolio.jpg"></a></p>
        </div>
    </div>
    <div class="col-sm-4"><a href="konicky.html"><h3>Koníčky</h3></a>
        <div class="col-sm-12">
            <p class="pics_hover"><a href="konicky.html"><img class="img-responsive" alt="Koníčky" title="Koníčky" src="images/Konicky.jpg"></a></p>
        </div>
    </div>
</div>

<h3 class="part" id="projekty">Projekty</h3>
<div class="row">
    <div class="col-sm-4">
        <img class="img-responsive obr" src="images/gug.png" alt="Google User Group" title="Google User Group"></a>
    </div>
    <div class="col-sm-8">
        <a href="http://www.gug.cz/cs"><h4>Google User Group</h4></a>
        <p>Google User Group alias GUG.cz je&nbsp;skupina nadšenců do&nbsp;(Google) technologií. Dobrovolníci z&nbsp;celé republiky pořádají akce, přednášky, workshopy a&nbsp;další, kde většinou kombinují vzdělávání a&nbsp;zábavu.</p>
        <p>Ke&nbsp;GUGu jsem se dostala díky školnímu projektu (předmět Aplikační seminář - skupinový projekt na&nbsp;2&nbsp;semestry, tedy celý rok) a&nbsp;pokračovala jsem v&nbsp;něm s&nbsp;radostí i&nbsp;po&nbsp;škole, v&nbsp;podstatě až&nbsp;do&nbsp;teď. Nyní mám na&nbsp;starosti hlavně administraci této neziskovky a&nbsp;kvůli tomu už&nbsp;moc nestíhám samotné organizování akcí.</p>
    </div>
</div>
<br/>
<div class="row">
    <div class="col-sm-4">
        <img class="img-responsive obr" alt="Iveta Levová Photography" title="Iveta Levová Photography" src="images/photoil.png">
    </div>
    <div class="col-sm-8">
        <a href="https://www.facebook.com/ivetalevovaphoto/"><h4>Iveta Levová Photography</h4></a>
        <p>Jedním z&nbsp;mých velkých koníčků je&nbsp;focení. Fotím od&nbsp;svých 20&nbsp;let, kdy jsem za maturitu dostala svůj první fotoaparát. Vyhrává u mne hlavně příroda, zvířata a&nbsp;detaily.</p>
        <p>Dříve jsem na&nbsp;své fotografie měla vlastní web vytvořený na&nbsp;Wordpressu, když jsem se jednou rozhodla se s&nbsp;ním naučit. Už ale zastarával a&nbsp;potřeboval obnovit. 
            Myslela jsem, že bych ho vytvořila opět na Wordpressu, ale protože jsem se už něco v&nbsp;kódění naučila, chtěla bych si jej napsat sama. Ale na to se musím ještě nějakou chvíli učit, proto 
            mám na&nbsp;své fotky v současnosti jen Facebookovou stránku.
    </div>
</div>

<h3 class="part" id="kontakt">Kontakt</h3>
<form action="mail.php" method="post" align="center">
    <div class="row col-sm-12"><input type="text" name="jmeno" maxlength="25" placeholder="Jméno"></div>
    <div class="row col-sm-12"><input type="email" name="email" placeholder="E-mail"></div>
    <div class="row col-sm-12"><input type="subject" name="predmet" placeholder="Předmět"></div>
    <div class="row col-sm-12"><input class="text" type="text" name="zprava" placeholder="Vaše zpráva"></div>
    <div class="row col-sm-12"><input type="submit"></div>
</form>

<?php
include("layout/footer.php");
?>