<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <title><?php echo $_GET['title'] ?></title>
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
        <script language="javascript" type="text/javascript" src="<?php echo CDN_CONTEXT_PATH ?>/js/tiny_mce/tiny_mce.js"></script>
    </head>
    <body id="body">
        <div id="content">
            <?php require 'mailing-list-menu.php' ?>
            <div id="right">
                <?php
                require BASE_PATH . '/views/includes/header.php';
                ?><div class="r-content"><?php
                require BASE_PATH . '/views/mailing-list/' . $_GET['view'];
                ?></div><?php
                require BASE_PATH . '/views/includes/footer.php';
                ?>
            </div>
            <div class="clear"></div>
        </div>
        <iframe id="iframe_fix" style="display: none"></iframe>
        <script language="javascript" type="text/javascript">
            var _base = '<?php echo CONTEXT_PATH ?>/';
            $(document).ready(function() {
                _plugins = new NT.Core.hashTable();
                var _history = $n(document.body).history({});
                $('#message').each(function() {
                    tinyMCE.init({
                        // General options
                        elements: "message",
                        mode: "exact",
                        theme: "advanced",
                        plugins: "pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount,advlist,autosave",
                        // Theme options
                        theme_advanced_buttons1: "newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
                        theme_advanced_buttons2: "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
                        theme_advanced_buttons3: "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
                        theme_advanced_buttons4: "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak,restoredraft",
                        theme_advanced_toolbar_location: "top",
                        theme_advanced_toolbar_align: "left",
                        theme_advanced_statusbar_location: "bottom",
                        theme_advanced_resizing: true,
                        // Example content CSS (should be your site CSS)
                        content_css: "css/content.css",
                        // Drop lists for link/image/media/template dialogs
                        template_external_list_url: "lists/template_list.js",
                        external_link_list_url: "lists/link_list.js",
                        external_image_list_url: "lists/image_list.js",
                        media_external_list_url: "lists/media_list.js",
                        // Style formats
                        style_formats: [
                            {
                                title: 'Bold text',
                                inline: 'b'
                            },
                            {
                                title: 'Red text',
                                inline: 'span',
                                styles: {
                                    color: '#ff0000'
                                }
                            },
                            {
                                title: 'Red header',
                                block: 'h1',
                                styles: {
                                    color: '#ff0000'
                                }
                            },
                            {
                                title: 'Example 1',
                                inline: 'span',
                                classes: 'example1'
                            },
                            {
                                title: 'Example 2',
                                inline: 'span',
                                classes: 'example2'
                            },
                            {
                                title: 'Table styles'
                            },
                            {
                                title: 'Table row 1',
                                selector: 'tr',
                                classes: 'tablerow1'
                            }
                        ],
                        // Replace values for the template plugin
                        template_replace_values: {
                            username: "Some User",
                            staffid: "991234"
                        },
                        extended_valid_elements: "iframe[src|width|height|name|align]"
                    });
                });
            });
        </script>
    </body>
</html>