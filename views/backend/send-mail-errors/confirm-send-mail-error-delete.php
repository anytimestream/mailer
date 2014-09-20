<br/>
<form class="normosa-ui-form" method="post" action="">
    <h4>Confirm Deletion of Error "<b><?php echo strip_tags($_GET['send-mail-error']->getValue('error')) ?></b>"</h4>
    <input type="hidden" name="id" id="id" value="<?php echo $_GET['send-mail-error']->getValue('id') ?>"/>
    <input type="hidden" name="confirmed" id="confirmed"/>
    <div class="row">
        <label class="label w150"></label>
        <button class="submit">Confirm</button>
    </div>
</form>