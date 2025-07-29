<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<div class="uk-container uk-container-small uk-margin-medium-bottom box_form_evidenzia">
	<div class="box_notice">
		<?php
		Form::$tipo = "N";
		echo Form::gNotice();
		?>
	</div>
    <form action="<?php echo Domain::$currentUrl ? Domain::$currentUrl : Url::getRoot();?>#<?php echo v("fragment_form_newsletter");?>" class="uk-container uk-container-xsmall" method="post" novalidate="novalidate">
        <div class="uk-grid" uk-grid>
			<div class="uk-width-3-4@m uk-width-3-4@s"><?php echo Html_Form::input("email",Form::gValue("email"),"uk-width-expand uk-input uk-flex-first class_email","email","placeholder='".gtext("Il tuo indirizzo email*")."'");?></div>
			
			<div class="uk-width-1-4@m uk-width-1-4@s">
				<div class="uk-button uk-button-secondary uk-text-bold uk-width-expandy spinner uk-hidden" uk-spinner="ratio: .70"></div>
				<button type='submit' class="uk-button uk-button-secondary uk-text-bold uk-width-expand btn_submit_form"><?php echo gtext("Iscriviti");?></button>
			</div>
        </div>
        
        <?php include (tpf("Elementi/Pagine/campo-captcha.php"));?>
        
        <?php echo Html_Form::hidden("invia","newsletter");?>
        <?php
		if (v("newsletter_ajax_submit"))
			echo Html_Form::hidden("ajaxsubmit","ajaxsubmit");?>
		
        <?php $idPrivacy = PagineModel::gTipoPagina("PRIVACY"); ?>
        <br />
        <div class="class_accetto">
			<?php echo Html_Form::checkbox('accetto',Form::gValue("accetto"),'1','uk-checkbox');?><span class="uk-text-small uk-margin-left"><?php echo gtext("Ho letto e accetto le condizioni della");?> <a target="_blank" href="<?php echo $this->baseUrl."/".getUrlAlias($idPrivacy);?>"><?php echo gtext("privacy policy");?></a></span>
		</div>
    </form>
</div> 
