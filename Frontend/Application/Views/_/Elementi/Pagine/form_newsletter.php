<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<div class="uk-container uk-container-small uk-margin-medium-bottom uk-background-green uk-padding-large">
    <h2 class="uk-margin-remove uk-text-large uk-text-bold uk-text-uppercase uk-text-center"><?php echo gtext("Iscriviti alla newsletter");?></h2>
    <h3 class="uk-text-center uk-margin-remove-top uk-text-meta"><?php echo gtext("ti aggiorniamo sulle novità, offerte e articoli sul blog");?></h3>

    <form class="uk-container uk-container-xsmall">
        <div uk-grid>
            <div class="uk-width-2-3@m uk-width-2-3@s"><input class="uk-width-expand uk-input uk-flex-first" type="text" placeholder="Your mail address"></div>
            <div class="uk-width-1-3@m uk-width-1-3@s"> <button class="uk-button uk-button-secondary uk-text-bold uk-width-expand"><?php echo gtext("Iscriviti");?></button></div>
        </div>
        <?php $idPrivacy = PagineModel::gTipoPagina("PRIVACY"); ?>
        <br /><input class="uk-checkbox" type="checkbox"><span class="uk-text-small uk-margin-left"><?php echo gtext("Ho letto e accetto le condizioni della");?> <a href="<?php echo $this->baseUrl."/".getUrlAlias($idPrivacy);?>"><?php echo gtext("privacy policy");?></a></span>
    </form>
</div>
