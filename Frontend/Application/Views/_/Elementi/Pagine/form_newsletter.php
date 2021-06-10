<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<div id="newsletter-form" class="uk-container uk-container-small uk-margin-medium-bottom uk-background-muted uk-padding-large">
    <h2 class="uk-margin-remove uk-text-large uk-text-bold uk-text-uppercase uk-text-center"><?php echo gtext("Iscriviti alla newsletter");?></h2>
    <h3 class="uk-text-center uk-margin-remove-top uk-text-meta"><?php echo gtext("ti aggiorniamo sulle novità, offerte e articoli sul blog");?></h3>
	<?php echo Form::$notice;?>
    <form action="<?php echo Domain::$currentUrl;?>#newsletter-form" class="uk-container uk-container-xsmall" method="post" novalidate="novalidate">
        <div uk-grid>
			<div class="uk-width-1-3@m uk-width-1-3@s"><?php echo Html_Form::input("email",Form::$values["email"],"uk-width-expand uk-input uk-flex-first class_email","email","placeholder='".gtext("Il tuo indirizzo email*")."'");?></div>
			
			<div class="uk-width-1-3@m uk-width-1-3@s">
				<button class="uk-button uk-button-secondary uk-text-bold uk-width-expand"><?php echo gtext("Iscriviti");?></button>
			</div>
        </div>
        <?php echo Html_Form::hidden("invia","newsletter");?>
        <?php $idPrivacy = PagineModel::gTipoPagina("PRIVACY"); ?>
        <br />
        <div class="class_accetto">
			<?php echo Html_Form::checkbox('accetto',Form::$values["accetto"],'1','uk-checkbox');?><span class="uk-text-small uk-margin-left"><?php echo gtext("Ho letto e accetto le condizioni della");?> <a href="<?php echo $this->baseUrl."/".getUrlAlias($idPrivacy);?>"><?php echo gtext("privacy policy");?></a></span>
		</div>
    </form>
</div>
