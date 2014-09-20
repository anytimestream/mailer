<?php
UserService::RequireLogin(CONTEXT_PATH.'/login');

if(UserService::IsAdmin()){
    header("location: ".CONTEXT_PATH.'/backend');
}
