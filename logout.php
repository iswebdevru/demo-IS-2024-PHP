<?php

require_once(realpath(dirname(__FILE__)) . '/lib/auth.php');
logout_user();
header('Location:./login.php');