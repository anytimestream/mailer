<br/>
<form class="normosa-ui-form" method="post" action="">
    <h4>Confirm Deletion of User "<b><?php echo $_GET['user']->getValue('username') ?></b>"</h4>
    <input type="hidden" name="id" id="id" value="<?php echo $_GET['user']->getValue('id') ?>"/>
    <input type="hidden" name="confirmed" id="confirmed"/>
    <div class="row">
        <label class="label w150"></label>
        <button class="submit">Confirm</button>
    </div>
</form>