<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php echo gtext("Condividi")?>:
<a class="bo-social-facebook" href="http://www.facebook.com/sharer.php?u=<?php echo $this->baseUrl."/$urlAlias";?>&title=<?php echo $p["pages"]["title"];?>" target="_blank" title="<?php echo gtext("Condividi su facebook");?>">
	<span uk-icon="icon: facebook"></span>
</a>
<a class="bo-social-twitter" href="http://twitter.com/home?status=<?php echo $p["pages"]["title"];?> <?php echo $this->baseUrl."/$urlAlias";?>" target="_blank" title="<?php echo gtext("Condividi su on Twitter");?>">
	<span uk-icon="icon: twitter"></span>
</a>
<!--<a class="bo-social-tumblr" href="http://www.tumblr.com/share/link?url=<?php echo $this->baseUrl."/$urlAlias";?>&name=<?php echo $p["pages"]["title"];?>" target="_blank" title="<?php echo gtext("Condividi su Tumblr");?>">
	<span uk-icon="icon: tumblr"></span>
</a>-->
