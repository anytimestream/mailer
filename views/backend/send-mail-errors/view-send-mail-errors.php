<div class="normosa-ui-datatable" id="datatable">
    <div class="toolbar">
        <!--<a class="btn" href="<?php echo CONTEXT_PATH ?>/backend/$sendMailErrors/new" title="New Account">New</a>-->
    </div>
    <div class="datasource">
        <script language="javascript" type="text/javascript">
            var contextmenuurl = new Array();
            contextmenuurl["delete"] = {window:"same", url:"<?php echo CONTEXT_PATH; ?>/backend/send-mail-errors/delete/"};
            
            var contextmenu = [
                {title:'Delete', cmd:'delete'}
            ];
        </script>
    </div>
    <div class="scrollpane">
        <div class=content style="min-width:1000px;">
            <div class="th">
                <span class="td w120" style="text-align:center">Type</span>
                <span class="td w200" style="text-align:center">Email</span>
                <span class="td w500" style="text-align:center">Error</span>
                <span class="td w120" style="text-align:center;border:0">Creation Date</span>
            </div>
            <div class=th_fix></div>
            <?php
            $sendMailErrors = $_GET['send-mail-errors'];
            for ($i = 0; $i < $sendMailErrors->count(); $i++) {
                if ($i == ($_GET['pagination']->getSize() - 1)) {
                    $style = "style=\"border:0;\"";
                } else {
                    $style = "";
                }
                ?>
                <div class="tr" <?php echo $style ?> id="<?php echo $sendMailErrors[$i]->getValue('id') ?>" contextmenu="contextmenu">
                    <div class="content">
                        <span class="td w120"><?php echo $sendMailErrors[$i]->getValue('type') ?></span>
                        <span class="td w200" title="<?php echo strtolower($sendMailErrors[$i]->getValue('email')) ?>"><?php echo strtolower($sendMailErrors[$i]->getValue('email')) ?></span>
                        <span class="td w500" title="<?php echo strip_tags($sendMailErrors[$i]->getValue('error')) ?>"><?php echo strip_tags($sendMailErrors[$i]->getValue('error')) ?></span>
                        <span class="td w120" style="text-align:center;border:0"><?php echo Date::convertFromMySqlDate($sendMailErrors[$i]->getValue('creation_date')) ?></span>
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