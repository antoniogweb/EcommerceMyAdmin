<?php if (!defined('EG')) die('Direct access not allowed!');

if (isset($breadcrumb))
{
	if (is_array($breadcrumb))
	{
		foreach ($breadcrumb as $text => $link)
		{
			echo "<li>";
			
			if ($link)
				echo "<a href='$link'>";
			
			echo "<span class='uk-text-small'>".$text."</span>";
			
			if ($link)
				echo "</a>";
			
			echo "</li>";
		}
	}
	else
		echo "<li><a href='".$this->baseUrl."'>".gtext("Home")."</a></li>" .  $breadcrumb;
}
