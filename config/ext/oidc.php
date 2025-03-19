<?php
$filter->oidc = new stdclass();
$filter->oidc->login = new stdclass();
$filter->oidc->login->paramValue['scope'] = 'reg::any';

$config->oidc = new stdclass();
$config->oidc->provider_url = "";
$config->oidc->clientId = "";
$config->oidc->clientSecret = "";
// Role name for administrator users
$config->oidc->adminRoleName = "zentao_admin";

// Disable auth server SSL verification. Set to true only for test purpose.
$config->oidc->disableSSLVerify = false;