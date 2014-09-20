<br/>
<form class="normosa-ui-form" method="post" action="" style="width: 900px" enctype="multipart/form-data">
    <h4>Compose</h4>
    <div class="row">
        <?php
        if (isset($_GET['msg'])) {
            ?><p class="msg"><b>Error</b><?php
            echo $_GET['msg']
            ?></p><div class="clear"></div><?php
            }
            if (isset($_GET['status'])) {
                ?><p class="msg2"><?php echo $_GET['status']; ?></p><div class="clear"></div><?php
            }
            ?>
    </div>
    <div class="row">
        <label class="label w150" for="name">Name</label>
        <input inputtype="textbox" id="name" name="name" class="textbox w220" value="<?php echo $_GET['mailing-list']->getValue('name') ?>" autocomplete="off"/>
        <span class="error"><?php echo $_GET['mailing-list']->validationReport('name') ?></span>
    </div>
    <div class="row">
        <label class="label w150" for="sender">From</label>
        <input inputtype="textbox" id="sender" name="sender" class="textbox w220" value="<?php echo $_GET['mailing-list']->getValue('sender') ?>" autocomplete="off"/>
        <span class="error"><?php echo $_GET['mailing-list']->validationReport('sender') ?></span>
    </div>
    <div class="row">
        <label class="label w150" for="subject">Subject</label>
        <input inputtype="textbox" id="subject" name="subject" class="textbox w220" value="<?php echo $_GET['mailing-list']->getValue('subject') ?>" autocomplete="off"/>
        <span class="error"><?php echo $_GET['mailing-list']->validationReport('subject') ?></span>
    </div>
    <div class="row">
        <label class="label w150" for="to" style="float: left; padding-right: 5px;">Recipients</label>
        <textarea inputtype="textbox" id="recipients" name="recipients" class="textbox w220" style="height: 200px"><?php echo $_GET['mailing-list']->getValue('recipients') ?></textarea>
        <span class="error"><?php echo $_GET['mailing-list']->validationReport('recipients') ?></span>
    </div>
    <div class="row">
        <label class="label w150" for="file1">Attachment</label>
        <input type="file" name="file1" class="textbox w220"/>
    </div>
    <div class="row">
        <label class="label w150" for="file2">Attachment</label>
        <input type="file" name="file2" class="textbox w220"/>
    </div>
    <div class="row">
        <label class="label w150" for="file3">Attachment</label>
        <input type="file" name="file3" class="textbox w220"/>
    </div>
    <div class="row" style="margin-left: 150px;">
        <textarea inputtype="textbox" id="message" name="body" class="textbox w400" style="height: 428px"><?php echo $_GET['mailing-list']->getValue('body') ?></textarea>
        <span class="error"><?php echo $_GET['mailing-list']->validationReport('body') ?></span>
    </div>
    <div class="clear"></div>
    <div class="row">
        <label class="label w150"></label>
        <button class="submit">Save</button>
    </div>
</form>