<?php
for ($i = $_GET['pagination']->getPageCount(); $i < $_GET['pagination']->getSize(); $i++) {
    if ($i == ($_GET['pagination']->getSize() - 1)) {
        $style = "style=\"border:0;\"";
    } else {
        $style = "";
    }
    ?>
    <div class="tr" <?php echo $style ?>><div class="content"></div></div>
    <?php
}
?>