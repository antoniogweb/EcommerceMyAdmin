<?php if (!defined('EG')) die('Direct access not allowed!');

if (v("codice_gtm_analytics"))
{
	if (isset($pages) && count($pages) > 0)
	{
		$stringaCacheAnalytics = '';
		
		if (isset($tagCorrente) && $tagCorrente)
		{
			$item_list_id = 'TAG_'.(int)$tagCorrente["tag"]["id_tag"];
			$item_list_name = F::alt($tagCorrente["tag"]["titolo"]);
		}
		else if (isset($datiCategoria))
		{
			$item_list_id = 'CAT_'.(int)$datiCategoria["categories"]["id_c"];
			$item_list_name = F::alt($datiCategoria["categories"]["title"]);
		}
		
		if (isset($item_list_id) && isset($item_list_name))
		{
			$stringaCacheAnalytics .= '$item_list_id = "'.$item_list_id.'";';
			$stringaCacheAnalytics .= '$item_list_name = "'.$item_list_name.'";';
		}
		
		$arrayIdsPages = [];
		
		foreach ($pages as $page)
		{
			$arrayIdsPages[] = (int)$page["pages"]["id_page"];
		}
		
		$stringaCacheAnalytics .= '$arrayIdsPages = '.json_encode($arrayIdsPages).';';
		
		include(tpf("/Elementi/Analytics/Cache/analytics_impressioni_lista".v("versione_google_analytics").".php", false, false, $stringaCacheAnalytics));
	}
}
