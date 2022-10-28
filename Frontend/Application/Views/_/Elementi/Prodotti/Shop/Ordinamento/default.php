<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<span class="uk-text-small uk-text-mute"><?php echo gtext("Ordina per");?>:</span>
<select name="o" class="select_follow_url uk-select uk-form-width-medium uk-form-small">
	<option <?php if (isset($this->viewArgs["o"]) && $this->viewArgs["o"] == "tutti") echo "selected";?> value="<?php echo $url_ordinamento.$this->getViewStatusUsingVariables(array("o"=>"tutti", "p"=>1));?>"><?php echo gtext("Predefinito");?></option>
	<option <?php if (isset($this->viewArgs["o"]) && $this->viewArgs["o"] == "az") echo "selected";?> value="<?php echo $url_ordinamento . $this->getViewStatusUsingVariables(array("o"=>"az", "p"=>1));?>"><?php echo gtext("A -> Z");?></option>
	<option <?php if (isset($this->viewArgs["o"]) && $this->viewArgs["o"] == "za") echo "selected";?> value="<?php echo $url_ordinamento . $this->getViewStatusUsingVariables(array("o"=>"za", "p"=>1));?>"><?php echo gtext("Z -> A");?></option>
	<option <?php if (isset($this->viewArgs["o"]) && $this->viewArgs["o"] == "crescente") echo "selected";?> value="<?php echo $url_ordinamento . $this->getViewStatusUsingVariables(array("o"=>"crescente", "p"=>1));?>"><?php echo gtext("Prezzo crescente");?></option>
	<option <?php if (isset($this->viewArgs["o"]) && $this->viewArgs["o"] == "decrescente") echo "selected";?> value="<?php echo $url_ordinamento . $this->getViewStatusUsingVariables(array("o"=>"decrescente", "p"=>1));?>"><?php echo gtext("Prezzo descrescente");?></option>
	<option <?php if (isset($this->viewArgs["o"]) && $this->viewArgs["o"] == "piuvenduto") echo "selected";?> value="<?php echo $url_ordinamento . $this->getViewStatusUsingVariables(array("o"=>"piuvenduto", "p"=>1));?>"><?php echo gtext("Più venduti");?></option>
</select> 
