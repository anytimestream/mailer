<div class="normosa-ui-datatable" id="datatable">
    <div class="toolbar">
        <a class="btn" href="<?php echo CONTEXT_PATH ?>/mailing-list/new" title="New">Compose</a>
    </div>
    <div class="datasource">
        
    </div>
    <div class="scrollpane">
        <div class=content style="min-width:1000px;">
            <div class="th">
                <span class="td w150" style="text-align:center">Subject</span>
                <span class="td w260" style="text-align:center">From</span>
                <span class="td w200" style="text-align:center">Name</span>
                <span class="td w60" style="text-align:center">Pending</span>
                <span class="td w60" style="text-align:center">Sent</span>
                <span class="td w60" style="text-align:center">Valid</span>
                <span class="td w60" style="text-align:center">Invalid</span>
                <span class="td w60" style="text-align:center">Blacklist</span>
                <span class="td w120" style="text-align:center; border-right: 0">Creation Date</span>
            </div>
            <div class=th_fix></div>
            <?php
            $mailingList = $_GET['mailing-list'];
            for ($i = 0; $i < $mailingList->count(); $i++) {
                if ($i == ($_GET['pagination']->getSize() - 1)) {
                    $style = "style=\"border:0;\"";
                } else {
                    $style = "";
                }
                ?>
                <div class="tr" <?php echo $style ?> id="<?php echo $mailingList[$i]->getValue('id') ?>">
                    <div class="content">
                        <span class="td w150" title="<?php echo ucwords($mailingList[$i]->getValue('subject')) ?>"><?php echo ucwords($mailingList[$i]->getValue('subject')) ?></span>
                        <span class="td w260" title="<?php echo strtolower($mailingList[$i]->getValue('sender')) ?>"><?php echo strtolower($mailingList[$i]->getValue('sender')) ?></span>
                        <span class="td w200" title="<?php echo ucwords($mailingList[$i]->getValue('name')) ?>"><?php echo ucwords($mailingList[$i]->getValue('name')) ?></span>
                        <span class="td w60" style="text-align:center">
                            <?php
                            if($mailingList[$i]->getValue('logged')){
                                echo $mailingList[$i]->getValue('pending');
                            }
                            else{
                                echo "calculating...";
                            }
                            ?>
                        </span>
                        <span class="td w60" style="text-align:center"><?php echo $mailingList[$i]->getValue('sent') ?></span>
                        <span class="td w60" style="text-align:center"><?php echo $mailingList[$i]->getValue('valid') ?></span>
                        <span class="td w60" style="text-align:center"><?php echo $mailingList[$i]->getValue('invalid') ?></span>
                        <span class="td w60" style="text-align:center"><?php echo $mailingList[$i]->getValue('blacklist') ?></span>
                        <span class="td w120" style="text-align:center; border-right: 0"><?php echo Date::convertFromMySqlDate($mailingList[$i]->getValue('creation_date')) ?></span>
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