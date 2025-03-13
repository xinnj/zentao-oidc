<?php
$module = $this->app->getModuleName();
$method = $this->app->getMethodName();

if($module == 'oidc' and $method == 'login') return true;
