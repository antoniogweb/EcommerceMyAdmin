<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<script src="<?php echo $this->baseUrlSrc.'/admin/Frontend/Public/Js/';?>jquery-3.5.1.min.js"></script>
<script src="<?php echo $this->baseUrlSrc.'/admin/Frontend/Public/Js/';?>ajaxQueue.js"></script>
<script src="<?php echo $this->baseUrlSrc.'/admin/Frontend/Public/Js/';?>functions.js?v=<?php echo rand(1,10000);?>"></script>
<script src="<?php echo $this->baseUrlSrc.'/admin/Frontend/Public/Js/';?>cart.js?v=<?php echo rand(1,10000);?>"></script>

<script src="<?php echo $this->baseUrlSrc."/admin/Frontend/Public/Js/uikit/"?>uikit-icons.min.js"></script>

<script type='text/javascript' src='<?php echo $this->baseUrlSrc;?>/admin/Frontend/Public/Js/icheck.min.js'></script>
<script type='text/javascript' src='<?php echo $this->baseUrlSrc;?>/admin/Frontend/Public/Js/image-picker/image-picker.min.js'></script>

<?php if ($this->controller == "home" && count($modali_frontend) > 0) { ?>
<?php include(tpf("/Elementi/modali.php"));?>
<?php } ?>
