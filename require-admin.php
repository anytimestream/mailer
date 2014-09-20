<?php
if(!UserService::IsAdmin()){
    header("location: ".CONTEXT_PATH.'/');
}

