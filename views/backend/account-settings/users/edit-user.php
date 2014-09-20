<br/>
<form class="normosa-ui-form" method="post" action="" >
    <h4>Edit User</h4>
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
        <label class="label w150" for="account">Account</label>
        <select inputtype="_default" id="account" name="account" class="textbox w200" autocomplete="off" style="width:205px">
            <option value="-">- Select -</option>
            <?php
            $accounts = $_GET['account-names'];
            for ($i = 0; $i < $accounts->count(); $i++) {
                ?>
                <option value="<?php echo $accounts[$i]->getValue('no') ?>" <?php if ($_GET['account-user']->getValue('account') == $accounts[$i]->getValue('no')) { ?>selected <?php } ?>><?php echo $accounts[$i]->getValue('name') ?></option>
                <?php
            }
            ?>
        </select>
        <span class="error"><?php echo $_GET['account-user']->validationReport('account') ?></span>
    </div>
    <div class="row">
        <label class="label w150" for="username">Username</label>
        <input inputtype="textbox" id="username" name="username" class="textbox w200" style="text-transform: lowercase" value="<?php echo $_GET['user']->getValue('username') ?>" autocomplete="off"/>
        <span class="error"><?php echo $_GET['user']->validationReport('username') ?></span>
    </div>
    <div class="row">
        <label class="label w150" for="password2">Password</label>
        <input inputtype="textbox" id="password2" name="password2" type="password" class="textbox w200" value="<?php echo $_GET['user']->getValue('password2') ?>" autocomplete="off"/>
        <span class="error"><?php echo $_GET['user']->validationReport('password2') ?></span>
    </div>
    <div class="row">
        <label class="label w150" for="status">Status</label>
        <select inputtype="_default" id="status" name="status" class="textbox w140" style="width: 200px" autocomplete="off">
            <option value="-">- Select -</option>
            <option <?php if (strcasecmp($_GET['user']->getValue('status'), '1') == 0) { ?>selected <?php } ?> value="1">Enabled</option>
            <option <?php if (strcasecmp($_GET['user']->getValue('status'), '0') == 0) { ?>selected <?php } ?> value="0">Disabled</option>
        </select>
        <span class="error"><?php echo $_GET['user']->validationReport('status') ?></span>
    </div>
    <div class="row">
        <label class="label w150"></label>
        <button class="submit">Save</button>
    </div>
</form>