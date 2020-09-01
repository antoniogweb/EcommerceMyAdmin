<?php if (!defined('EG')) die('Direct access not allowed!'); ?>
<?php echo "<?xml version=\"1.0\" encoding=\"utf-8\" ?>\n";?>
<selects>
    <lista>
        <option value="0">-- seleziona --</option>
        <?php foreach ($lista as $id => $nome) { ?>
        <option value="<?php echo $id;?>"><![CDATA[<?php echo $nome;?>]]></option>
        <?php } ?>
    </lista>
</selects>