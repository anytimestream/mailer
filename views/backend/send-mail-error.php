<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <title><?php echo $_GET['title']?></title>
        <link href="<?php echo CDN_CONTEXT_PATH ?>/css/backend.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo CDN_CONTEXT_PATH ?>/css/data.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo CDN_CONTEXT_PATH ?>/css/datagridview.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo CDN_CONTEXT_PATH ?>/js/ext/jquery-ui-1.8.5.custom.css" rel="stylesheet" type="text/css" />
        <link rel="shortcut icon" href="<?php echo CDN_CONTEXT_PATH ?>/favicon.ico" type="image/ico" />
        <script type="text/javascript" src="<?php echo CDN_CONTEXT_PATH ?>/js/ext/jquery-1.7.1.min.js" ></script>
        <script type="text/javascript" src="<?php echo CDN_CONTEXT_PATH ?>/js/ext/jquery-ui-1.10.4.custom.min.js" ></script>
        <script type="text/javascript" src="<?php echo CDN_CONTEXT_PATH ?>/js/ext/jquery.ui-contextmenu.min.js" ></script>
        <script type="text/javascript" src="<?php echo CDN_CONTEXT_PATH ?>/js/ext/json.js" ></script>
        <script type="text/javascript" src="<?php echo CDN_CONTEXT_PATH ?>/js/core.js" ></script>
        <script type="text/javascript" src="<?php echo CDN_CONTEXT_PATH ?>/js/datatable.js" ></script>
        <script type="text/javascript" src="<?php echo CDN_CONTEXT_PATH ?>/js/form.js" ></script>
        <script type="text/javascript" src="<?php echo CDN_CONTEXT_PATH ?>/js/history.js" ></script>
        <script type="text/javascript" src="<?php echo CDN_CONTEXT_PATH ?>/js/anchor.js" ></script>
        <script type="text/javascript" src="<?php echo CDN_CONTEXT_PATH ?>/js/validation.js" ></script>
        <script type="text/javascript" src="<?php echo CDN_CONTEXT_PATH ?>/js/dialog.js" ></script>
    </head>
    <body id="body">
        <div id="content">
            <?php require 'send-mail-error-menu.php' ?>
            <div id="right">
                <?php
                require BASE_PATH . '/views/backend/includes/header.php';
                ?><div class="r-content"><?php
                require BASE_PATH . '/views/backend/send-mail-errors/' . $_GET['view'];
                ?></div><?php
                require BASE_PATH . '/views/backend/includes/footer.php';
                ?>
            </div>
            <div class="clear"></div>
        </div>
        <iframe id="iframe_fix" style="display: none"></iframe>
        <script language="javascript" type="text/javascript">
            var _base = '<?php echo CONTEXT_PATH ?>/';
            $(document).ready(function(){
                _plugins = new NT.Core.hashTable();
                var _history = $n(document.body).history({});
            });
        </script>
    </body>
</html>