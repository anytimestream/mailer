<br/>
<form class="normosa-ui-form" method="post" action="" >
    <h4>New User</h4>
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
        <label class="label w150" for="password">Password</label>
        <input inputtype="textbox" type="password" id="password" name="password" class="textbox w200" value="<?php echo $_GET['user']->getValue('password') ?>" autocomplete="off"/>
        <span class="error"><?php echo $_GET['user']->validationReport('password') ?></span>
    </div>
    <div class="row">
        <label class="label w150"></label>
        <button class="submit">Save</button>
    </div>
</form>