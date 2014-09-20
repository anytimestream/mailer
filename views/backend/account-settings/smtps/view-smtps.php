<div class="normosa-ui-datatable" id="datatable">
    <div class="toolbar">
        <a class="btn" href="<?php echo CONTEXT_PATH ?>/backend/account-settings/smtps/new" title="New SMTP">New</a>
    </div>
    <div class="datasource">
        <script language="javascript" type="text/javascript">
            var contextmenuurl = new Array();
            contextmenuurl["edit"] = {window: "same", url: "<?php echo CONTEXT_PATH; ?>/backend/account-settings/smtps/edit/"};
            contextmenuurl["delete"] = {window: "same", url: "<?php echo CONTEXT_PATH; ?>/backend/account-settings/smtps/delete/"};

            var contextmenu = [
                {title: 'Edit', cmd: 'edit'},
                {title: 'Delete', cmd: 'delete'}
            ];
        </script>
    </div>
    <div class="scrollpane">
        <div class=content style="min-width:1000px;">
            <div class="th">
                <span class="td w200" style="text-align:center">Account</span>
                <span class="td w80" style="text-align:center">Provider</span>
                <span class="td w150" style="text-align:center">Host</span>
                <span class="td w150" style="text-align:center">Username</span>
                <span class="td w150" style="text-align:center">Password</span>
                <span class="td w80" style="text-align:center">Status</span>
                <span class="td w120" style="text-align:center">Creation Date</span>
                <span class="td w120" style="text-align:center;border:0">Last Changed</span>
            </div>
            <div class=th_fix></div>
            <?php
            $smtps = $_GET['smtps'];
            for ($i = 0; $i < $smtps->count(); $i++) {
                if ($i == ($_GET['pagination']->getSize() - 1)) {
                    $style = "style=\"border:0;\"";
                } else {
                    $style = "";
                }
                ?>
                <div class="tr" <?php echo $style ?> id="<?php echo $smtps[$i]->getValue('id') ?>" contextmenu="contextmenu">
                    <div class="content">
                        <span class="td w200" title="<?php echo ucwords($smtps[$i]->getValue('account')) ?>"><?php echo ucwords($smtps[$i]->getValue('account')) ?></span>
                        <span class="td w80" style="text-align:center" title="<?php echo ucwords($smtps[$i]->getValue('provider')) ?>"><?php echo ucwords($smtps[$i]->getValue('provider')) ?></span>
                        <span class="td w150" title="<?php echo strtolower($smtps[$i]->getValue('host')) ?>"><?php echo strtolower($smtps[$i]->getValue('host')) ?></span>
                        <span class="td w150" title="<?php echo strtolower($smtps[$i]->getValue('username')) ?>"><?php echo strtolower($smtps[$i]->getValue('username')) ?></span>
                        <span class="td w150" title="<?php echo $smtps[$i]->getValue('password') ?>"><?php echo $smtps[$i]->getValue('password') ?></span>
                        <span class="td w80" style="text-align:center">
                            <?php
                            if($smtps[$i]->getValue('status')){
                                echo "Enabled";
                            }
                            else{
                                echo "Disabled";
                            }
                            ?>
                        </span>
                        <span class="td w120" style="text-align:center"><?php echo Date::convertFromMySqlDate($smtps[$i]->getValue('creation_date')) ?></span>
                        <span class="td w120" style="text-align:center;border:0"><?php echo Date::convertFromMySqlDate($smtps[$i]->getValue('last_changed')) ?></span>
                    </div>
                </div>
                <?php
            }
            require BASE_PATH . '/views/backend/includes/datatable-default-rows.php';
            ?>
        </div>
    </div>
    <?php require BASE_PATH . '/views/backend/includes/pagination.php'; ?>
</div>