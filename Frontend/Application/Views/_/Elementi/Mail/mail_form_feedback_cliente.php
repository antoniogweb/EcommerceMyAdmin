<?php if (!defined('EG')) die('Direct access not allowed!'); ?>

<p><?php echo gtext("Gentile cliente, la sua valutazione del prodotto è stata correttamente inserita, ma non è ancora stata pubblicata nel nostro sito web",false);?></p>

<p><?php echo gtext("Sarà valutata quanto prima da un membro del nostro staff. Riceverà una mail non appena la sua valutazione verrà approvata e pubblicata nel nostro sito web.",false);?></p>

<?php $idCondizioniFeedback = PagineModel::gTipoPagina("CONDIZIONI_FEEDBACK"); ?>
<?php if ($idCondizioniFeedback) { ?>
<p><?php echo gtext("La sua valutazione potrebbe essere rifiutata nel caso non rispetti le condizioni di pubblicazione della valutazione da lei approvate, che può trovare al",false);?>
 <a href="<?php echo Domain::$publicUrl."/".Params::$lang."/".getUrlAlias($idCondizioniFeedback);?>"><?php echo gtext("seguente link");?></a>
</p>
<?php } ?>

<p><?php echo gtext("Cordiali saluti", false);?>.</p>
