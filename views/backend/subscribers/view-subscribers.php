<div class="normosa-ui-datatable" id="datatable">
    <div class="toolbar">
        <?php
        require BASE_PATH . '/views/backend/includes/name-search.php';
        ?>
    </div>
    <div class="datasource">
        <script language="javascript" type="text/javascript">
            var contextmenuurl = new Array();
            contextmenuurl["edit"] = {window:"same", url:"<?php echo CONTEXT_PATH; ?>/backend/subscribers/edit/"};
            contextmenuurl["delete"] = {window:"same", url:"<?php echo CONTEXT_PATH; ?>/backend/subscribers/delete/"};
            
            var contextmenu = [
                {title:'Edit', cmd:'edit'},
                {title:'Delete', cmd:'delete'}
            ];
        </script>
    </div>
    <div class="scrollpane">
        <div class=content style="min-width:1000px;">
            <div class="th">
                <span class="td w120" style="text-align:center">Account</span>
                <span class="td w200" style="text-align:center">Email</span>
                <span class="td w80" style="text-align:center">Sent</span>
                <span class="td w80" style="text-align:center">Blacklist</span>
                <span class="td w120" style="text-align:center">Verification Status</span>
                <span class="td w120" style="text-align:center">Creation Date</span>
                <span class="td w120" style="text-align:center;border:0">Last Changed</span>
            </div>
            <div class=th_fix></div>
            <?php
            $subscribers = $_GET['subscribers'];
            for ($i = 0; $i < $subscribers->count(); $i++) {
                if ($i == ($_GET['pagination']->getSize() - 1)) {
                    $style = "style=\"border:0;\"";
                } else {
                    $style = "";
                }
                ?>
                <div class="tr" <?php echo $style ?> id="<?php echo $subscribers[$i]->getValue('id') ?>" contextmenu="contextmenu">
                    <div class="content">
                        <span class="td w120"><?php echo $subscribers[$i]->getValue('account') ?></span>
                        <span class="td w200"><?php echo strtolower($subscribers[$i]->getValue('email')) ?></span>
                        <span class="td w80" style="text-align:center">
                            <?php
                            if($subscribers[$i]->getValue('sent')){
                                echo "Yes";
                            }
                            else{
                                echo "No";
                            }
                            ?>
                        </span>
                        <span class="td w80" style="text-align:center">
                            <?php
                            if($subscribers[$i]->getValue('backlist')){
                                echo "Yes";
                            }
                            else{
                                echo "No";
                            }
                            ?>
                        </span>
                        <span class="td w120" style="text-align:center">
                            <?php
                            switch($subscribers[$i]->getValue('verified')){
                                case "0":
                                    echo "Pending";
                                    break;
                                case "1":
                                    echo "Waiting";
                                    break;
                                case "2":
                                    echo "Valid";
                                    break;
                                case "3":
                                    echo "Invalid";
                                    break;
                            }
                            ?>
                        </span>
                        <span class="td w120" style="text-align:center"><?php echo Date::convertFromMySqlDate($subscribers[$i]->getValue('creation_date')) ?></span>
                        <span class="td w120" style="text-align:center;border:0"><?php echo Date::convertFromMySqlDate($subscribers[$i]->getValue('last_changed')) ?></span>
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