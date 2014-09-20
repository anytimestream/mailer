<br/>
<form class="normosa-ui-form" method="post" action="" >
    <h4>Edit SMTP</h4>
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
                <option value="<?php echo $accounts[$i]->getValue('no') ?>" <?php if ($_GET['smtp-account']->getValue('account') == $accounts[$i]->getValue('no')) { ?>selected <?php } ?>><?php echo $accounts[$i]->getValue('name') ?></option>
                <?php
            }
            ?>
        </select>
        <span class="error"><?php echo $_GET['smtp-account']->validationReport('account') ?></span>
    </div>
    <div class="row">
        <label class="label w150" for="provider">Provider</label>
        <select inputtype="_default" id="provider" name="provider" class="textbox w140" style="width: 200px" autocomplete="off">
            <option value="-">- Select -</option>
            <option <?php if (strcasecmp($_GET['smtp-account']->getValue('provider'), 'Gmail') == 0) { ?>selected <?php } ?>>Gmail</option>
            <option <?php if (strcasecmp($_GET['smtp-account']->getValue('provider'), 'Others') == 0) { ?>selected <?php } ?>>Others</option>
        </select>
        <span class="error"><?php echo $_GET['smtp-account']->validationReport('provider') ?></span>
    </div>
    <div class="row">
        <label class="label w150" for="host">Host</label>
        <input inputtype="textbox" id="host" name="host" class="textbox w200" style="text-transform: lowercase" value="<?php echo $_GET['smtp-account']->getValue('host') ?>" autocomplete="off"/>
        <span class="error"><?php echo $_GET['smtp-account']->validationReport('host') ?></span>
    </div>
    <div class="row">
        <label class="label w150" for="username">Username</label>
        <input inputtype="textbox" id="username" name="username" class="textbox w200" style="text-transform: lowercase" value="<?php echo $_GET['smtp-account']->getValue('username') ?>" autocomplete="off"/>
        <span class="error"><?php echo $_GET['smtp-account']->validationReport('username') ?></span>
    </div>
    <div class="row">
        <label class="label w150" for="password">Password</label>
        <input inputtype="textbox" id="password" name="password" class="textbox w200" value="<?php echo $_GET['smtp-account']->getValue('password') ?>" autocomplete="off"/>
        <span class="error"><?php echo $_GET['smtp-account']->validationReport('password') ?></span>
    </div>
    <div class="row">
        <label class="label w150" for="status">Status</label>
        <select inputtype="_default" id="status" name="status" class="textbox w140" style="width: 200px" autocomplete="off">
            <option value="-">- Select -</option>
            <option <?php if (strcasecmp($_GET['smtp-account']->getValue('status'), '1') == 0) { ?>selected <?php } ?> value="1">Enabled</option>
            <option <?php if (strcasecmp($_GET['smtp-account']->getValue('status'), '0') == 0) { ?>selected <?php } ?> value="0">Disabled</option>
        </select>
        <span class="error"><?php echo $_GET['smtp-account']->validationReport('status') ?></span>
    </div>
    <div class="row">
        <label class="label w150"></label>
        <button class="submit">Save</button>
    </div>
</form>