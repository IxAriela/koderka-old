<?php include("design/layout/header.php"); ?>

<div class="dekujemeNabidka">

    <br><br>
    <h1 class="jakoH3"> Děkuji za Váš zájem o mé služby.</h1>
    <p>V nejbližší době Vás budu kontaktovat.</p>

    <br><br>

    <?php
    /*
    ?>
    <h2 class="jakoH3">Kam chcete jít dál?</h2>
    <ul class="dekujeme_ul">
	<?php $nemovitosti = VratDetailHierarchie(HIERARCHIE_NEMOVITOSTI); ?>
	<li><a href="<?php echo($nemovitosti["cesta"]); ?>"><?php echo($nemovitosti["hi_nazev"]); ?></a></li>
	<?php $sluzby = VratDetailProduktu(CLANEK_SLUZBY); ?>
	<li><a href="<?php echo($sluzby["cesta"]); ?>"><?php echo($sluzby["pr_nazev"]); ?></a></li>
	<?php $reference = VratDetailProduktu(CLANEK_REFERENCE); ?>
	<li><a href="<?php echo($reference["cesta"]); ?>"><?php echo($reference["pr_nazev"]); ?></a></li>
	<?php $kontakt = VratDetailProduktu(CLANEK_KONTAKTY); ?>
	<li><a href="<?php echo($kontakt["cesta"]); ?>"><?php echo($kontakt["pr_nazev"]); ?></a></li>
    </ul>
    <?php */ ?>
</div>
<?php include("design/layout/footer.php"); ?>