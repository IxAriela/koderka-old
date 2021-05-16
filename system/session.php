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
        ?>
        <div class="alert
                             <?php
        if ($_SESSION["stav"]["typ"] == 1) echo "alert-success alert-dismissible ";
        else if ($_SESSION["stav"]["typ"] == 0) echo "alert-danger alert-dismissible  ";
        ?>
                             fade show" role="alert">
            <?php
            echo $zprava;
            ?>
            <!--button type="button" class="btn-close" aria-label="Close"></button-->
        </div>
        <?php

        unset($_SESSION["stav"]);
    }
}
?>