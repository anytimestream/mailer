<?php
$pagination = $_GET['pagination'];
if($pagination->getTotal() == 0){
    ?>
<div class="pagination">
    <div class="record-count">
        No Records Found
    </div>
    <div class="clear"></div>
</div>
    <?php
    return;
}
$current_view = ($pagination->getIndex() - 1) * $pagination->getSize() + 1;
?>
<div class="pagination">
    <div class="record-count">
        Viewing <?php echo number_format($current_view) ?> to <?php echo number_format($current_view + $pagination->getPageCount() - 1) ?> of <?php echo number_format($pagination->getTotal()) ?> Records
    </div>
    <div class="records">
        <?php
        if ($pagination->getNext() > $pagination->getPrevious()) {
            if($pagination->getPrevious() < $pagination->getIndex()){
                ?><a class="btn" action="back" href="<?php echo CONTEXT_PATH . $pagination->getUrl() ?>page=<?php echo $pagination->getPrevious() ?>">Back</a><?php
            }
            ?>
            <select class="goto" url="<?php echo CONTEXT_PATH . $pagination->getUrl() ?>page=">
                <?php
                for ($i = $pagination->getMinPage(); $i <= $pagination->getMaxPage(); $i++) {
                    if ($i == $pagination->getIndex()) {
                        ?><option selected value="<?php echo $i ?>">Page <?php echo $i ?></option><?php
        } else {
                        ?><option value="<?php echo $i ?>">Page <?php echo $i ?></option><?php
        }
    }
                ?>
            </select>
            <?php
            if($pagination->getNext() > $pagination->getIndex()){
                ?><a class="btn" action="next" max="<?php echo $pagination->getMaxPage() ?>" href="<?php echo CONTEXT_PATH . $pagination->getUrl() ?>page=<?php echo $pagination->getNext() ?>">Next</a><?php
            }
        }
        ?>
        <div class="clear"></div>
    </div>
    <div class="clear"></div>
</div>