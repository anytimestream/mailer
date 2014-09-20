<br/>
<form class="normosa-ui-form" method="post" action="" >
    <h4>New Account</h4>
    <div class="row">
        <?php
        if (isset($_GET['msg'])) {
            ?><p class="msg"><b>Error</b><?php
            echo $_GET['msg']
            ?></p><div class="clear"></div><?php
            }
            if (isset($_GET['status'])) {
                ?><p class="msg2"><?php echo $_GET['status']; ?></p><div class="clear"></div><?php
            }
            ?>
    </div>
    <div class="row">
        <label class="label w150" for="name">Name</label>
        <input inputtype="textbox" id="name" name="name" class="textbox w200" value="<?php echo $_GET['account']->getValue('name') ?>" autocomplete="off"/>
        <span class="error"><?php echo $_GET['account']->validationReport('name') ?></span>
    </div>
    <div class="row">
        <label class="label w150"></label>
        <button class="submit">Save</button>
    </div>
</form>