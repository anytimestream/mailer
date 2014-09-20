<?php

require BASE_PATH .'/require-login.php';

if(UserService::IsAdmin()){
    header("location: ".CONTEXT_PATH."/backend");
}
else{
    header("location: ".CONTEXT_PATH."/");
}