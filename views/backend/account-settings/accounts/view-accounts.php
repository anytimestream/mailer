<div class="normosa-ui-datatable" id="datatable">
    <div class="toolbar">
        <a class="btn" href="<?php echo CONTEXT_PATH ?>/backend/account-settings/accounts/new" title="New Account">New</a>
    </div>
    <div class="datasource">
        <script language="javascript" type="text/javascript">
            var contextmenuurl = new Array();
            contextmenuurl["edit"] = {window: "same", url: "<?php echo CONTEXT_PATH; ?>/backend/account-settings/accounts/edit/"};
            contextmenuurl["delete"] = {window: "same", url: "<?php echo CONTEXT_PATH; ?>/backend/account-settings/accounts/delete/"};

            var contextmenu = [
                {title: 'Edit', cmd: 'edit'},
                {title: 'Delete', cmd: 'delete'}
            ];
        </script>
    </div>
    <div class="scrollpane">
        <div class=content style="min-width:1000px;">
            <div class="th">
                <span class="td w150" style="text-align:center">N0</span>
                <span class="td w300" style="text-align:center">Name</span>
                <span class="td w120" style="text-align:center">Retention Period</span>
                <span class="td w120" style="text-align:center">Creation Date</span>
                <span class="td w120" style="text-align:center;border:0">Last Changed</span>
            </div>
            <div class=th_fix></div>
            <?php
            $accounts = $_GET['accounts'];
            for ($i = 0; $i < $accounts->count(); $i++) {
                if ($i == ($_GET['pagination']->getSize() - 1)) {
                    $style = "style=\"border:0;\"";
                } else {
                    $style = "";
                }
                ?>
                <div class="tr" <?php echo $style ?> id="<?php echo $accounts[$i]->getValue('id') ?>" contextmenu="contextmenu">
                    <div class="content">
                        <span class="td w150"><?php echo $accounts[$i]->getValue('no') ?></span>
                        <span class="td w300" title="<?php echo ucwords($accounts[$i]->getValue('name')) ?>"><?php echo ucwords($accounts[$i]->getValue('name')) ?></span>
                        <span class="td w120" style="text-align:center">
                            <?php
                            if($accounts[$i]->getValue('retention') > 1){
                                echo $accounts[$i]->getValue('retention') . " Days";
                            }
                            else{
                                echo $accounts[$i]->getValue('retention') . " Day";
                            }
                            ?>
                        </span>
                        <span class="td w120" style="text-align:center"><?php echo Date::convertFromMySqlDate($accounts[$i]->getValue('creation_date')) ?></span>
                        <span class="td w120" style="text-align:center;border:0"><?php echo Date::convertFromMySqlDate($accounts[$i]->getValue('last_changed')) ?></span>
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