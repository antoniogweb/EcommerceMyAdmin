<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php
if (v("attiva_tag_hreflang") && count($arrayLingue) > 0)
{
	$default = "";
	
	if (count(Params::$frontEndCountries) > 0)
	{
		foreach ($arrayLingue as $lingua => $url)
		{
			foreach (Params::$frontEndCountries as $country)
			{
				if ($url != $lingua || $this->controller == "home")
				{
					$url = $this->controller == "home" ? "" : str_replace("$lingua/","",$url);
					
					if ($lingua == Params::$defaultFrontEndLanguage)
						$default = $url;
				?>
					<link rel="alternate" href="<?php echo Domain::$publicUrl;?>/<?php echo $lingua;?>_<?php echo $country;?>/<?php echo $url;?>" hreflang="<?php echo $lingua;?>-<?php echo $country;?>" />
				<?php
				}
			}
		}
		?>
		<link rel="alternate" href="<?php echo Domain::$publicUrl;?>/<?php echo Params::$defaultFrontEndLanguage;?>_<?php echo Params::$defaultFrontEndCountry;?>/<?php echo $default;?>" hreflang="x-default" />
	<?php
	}
	else
	{
		foreach ($arrayLingue as $lingua => $url)
		{
			if ($url != $lingua || $this->controller == "home")
			{
				$url = $this->controller == "home" ? "" : str_replace("$lingua/","",$url);
				
				if ($lingua == Params::$defaultFrontEndLanguage)
					$default = $url;
			?>
				<link rel="alternate" href="<?php echo Domain::$publicUrl;?>/<?php echo $lingua;?>/<?php echo $url;?>" hreflang="<?php echo $lingua;?>" />
			<?php
			}
		}
		?>
		<link rel="alternate" href="<?php echo Domain::$publicUrl;?>/<?php echo Params::$defaultFrontEndLanguage;?>/<?php echo $default;?>" hreflang="x-default" />
	<?php
	}
	?>
	
<?php } ?> 
