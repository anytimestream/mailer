<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <title>Change Password</title>
        <link href="<?php echo CDN_CONTEXT_PATH ?>/css/login.css" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <div id="content">
            <h4>Change Password</h4>
            <form id="login" action="" method="post">
                <div class="row error">
                    <center><?php if (isset($_GET['msg'])) {echo $_GET['msg'];} ?></center>
                </div>
                <div class="row">
                    <label>New Password:</label>
                    <input type="password" name="password" id="password"/>
                </div>
                <div class="row">
                    <label>Re-Password:</label>
                    <input type="password" name="password2" id="password2"/>
                </div>
                <div><center><button class="btn">Update</button></center></div>
            </form>
        </div>
    </body>
</html>
