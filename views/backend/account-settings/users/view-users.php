<div class="normosa-ui-datatable" id="datatable">
    <div class="toolbar">
        <a class="btn" href="<?php echo CONTEXT_PATH ?>/backend/account-settings/users/new" title="New User">New</a>
    </div>
    <div class="datasource">
        <script language="javascript" type="text/javascript">
            var contextmenuurl = new Array();
            contextmenuurl["edit"] = {window: "same", url: "<?php echo CONTEXT_PATH; ?>/backend/account-settings/users/edit/"};
            contextmenuurl["delete"] = {window: "same", url: "<?php echo CONTEXT_PATH; ?>/backend/account-settings/users/delete/"};

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
                <span class="td w200" style="text-align:center">Username</span>
                <span class="td w80" style="text-align:center">Status</span>
                <span class="td w120" style="text-align:center">Creation Date</span>
                <span class="td w120" style="text-align:center;border:0">Last Changed</span>
            </div>
            <div class=th_fix></div>
            <?php
            $users = $_GET['users'];
            for ($i = 0; $i < $users->count(); $i++) {
                if ($i == ($_GET['pagination']->getSize() - 1)) {
                    $style = "style=\"border:0;\"";
                } else {
                    $style = "";
                }
                ?>
                <div class="tr" <?php echo $style ?> id="<?php echo $users[$i]->getValue('id') ?>" contextmenu="contextmenu">
                    <div class="content">
                        <span class="td w200" title="<?php echo ucwords($users[$i]->getValue('account')) ?>"><?php echo ucwords($users[$i]->getValue('account')) ?></span>
                        <span class="td w200" title="<?php echo strtolower($users[$i]->getValue('username')) ?>"><?php echo strtolower($users[$i]->getValue('username')) ?></span>
                        <span class="td w80" style="text-align:center">
                            <?php
                            if($users[$i]->getValue('status')){
                                echo "Enabled";
                            }
                            else{
                                echo "Disabled";
                            }
                            ?>
                        </span>
                        <span class="td w120" style="text-align:center"><?php echo Date::convertFromMySqlDate($users[$i]->getValue('creation_date')) ?></span>
                        <span class="td w120" style="text-align:center;border:0"><?php echo Date::convertFromMySqlDate($users[$i]->getValue('last_changed')) ?></span>
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