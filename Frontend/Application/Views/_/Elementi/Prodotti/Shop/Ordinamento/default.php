<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<span class="uk-text-small uk-text-mute"><?php echo gtext("Ordina per");?>:</span>
<select name="o" class="select_follow_url uk-select uk-form-width-medium uk-form-small">
	<option <?php if (isset($_GET["o"]) && $_GET["o"] == "tutti") echo "selected";?> value="<?php echo $url_ordinamento.$this->getViewStatusUsingVariables(array("o"=>"tutti", "p"=>1));?>"><?php echo gtext("Predefinito");?></option>
	<option <?php if (isset($_GET["o"]) && $_GET["o"] == "crescente") echo "selected";?> value="<?php echo $url_ordinamento . $this->getViewStatusUsingVariables(array("o"=>"crescente", "p"=>1));?>"><?php echo gtext("Prezzo crescente");?></option>
	<option <?php if (isset($_GET["o"]) && $_GET["o"] == "decrescente") echo "selected";?> value="<?php echo $url_ordinamento . $this->getViewStatusUsingVariables(array("o"=>"decrescente", "p"=>1));?>"><?php echo gtext("Prezzo descrescente");?></option>
</select> 
