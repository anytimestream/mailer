<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-53360660-1', 'auto');
  ga('send', 'pageview');

</script>
<div id="header">
    <ul>
        <li><a class="<?php echo getCurrentTab('b', 'mailing-list', true) ?>" href="<?php echo CONTEXT_PATH; ?>/backend/mailing-list">Mailing List</a></li>
        <li><a class="<?php echo getCurrentTab('b', 'subscribers', false) ?>" href="<?php echo CONTEXT_PATH; ?>/backend/subscribers">Subscribers</a></li>
        <li><a class="<?php echo getCurrentTab('b', 'send-mail-errors', false) ?>" href="<?php echo CONTEXT_PATH; ?>/backend/send-mail-errors">Send Mail Errors</a></li>
        <li><a class="<?php echo getCurrentTab('b', 'account-settings', false) ?>" href="<?php echo CONTEXT_PATH; ?>/backend/account-settings/accounts">Account Settings</a></li>
        <li><a href="<?php echo CONTEXT_PATH; ?>/change-password">Change Password</a></li>
        <li><a href="<?php echo CONTEXT_PATH; ?>/logout">Log off</a></li>
    </ul>
</div>