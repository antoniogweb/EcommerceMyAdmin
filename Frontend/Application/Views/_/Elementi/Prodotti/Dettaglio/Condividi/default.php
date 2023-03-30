<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php echo gtext("Condividi")?>:
<a class="bo-social-facebook" href="http://www.facebook.com/sharer.php?u=<?php echo $this->baseUrl."/$urlAlias";?>&title=<?php echo field($p, "title");?>" target="_blank" title="<?php echo gtext("Condividi su facebook");?>">
	<span uk-icon="icon: facebook"></span>
</a>
<a class="bo-social-twitter" href="https://twitter.com/share?url=<?php echo $this->baseUrl."/$urlAlias";?>&amp;text=<?php echo field($p, "title");?>" target="_blank" title="<?php echo gtext("Condividi su Twitter");?>">
	<span uk-icon="icon: twitter"></span>
</a>
<!--<a class="bo-social-tumblr" href="http://www.tumblr.com/share/link?url=<?php echo $this->baseUrl."/$urlAlias";?>&name=<?php echo field($p, "title");?>" target="_blank" title="<?php echo gtext("Condividi su Tumblr");?>">
	<span uk-icon="icon: tumblr"></span>
</a>-->
