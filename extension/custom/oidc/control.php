<?php
require __DIR__ . '/vendor/autoload.php';

use Jumbojett\OpenIDConnectClient;

class oidc extends control
{
    public function login()
    {
        // OpenIDConnectClient will parse $_REQUEST, but $_REQUEST is unset by framework. So we need to rebuild it.
        parse_str(parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY), $params);
        foreach ($params as $key => $value) {
            // error_log($key . "=>" . $value);
            $_REQUEST[$key] = $value;
        }

        $referer = $this->server->http_referer;
        if(empty($referer)) {
            $referer = $_REQUEST['referer'];
        }

        $last = $this->server->request_time;

        $oidc = new OpenIDConnectClient(
            $this->config->oidc->provider_url,
            $this->config->oidc->clientId,
            $this->config->oidc->clientSecret
        );

        if ($this->config->oidc->disableSSLVerify) {
            $oidc->setVerifyHost(false);
            $oidc->setVerifyPeer(false);
            $oidc->setHttpUpgradeInsecureRequests(false);
        }

        $uriPath = '';
        if (!empty($referer)) {
            $uriPath = parse_url($referer, PHP_URL_PATH);
        }
        if(empty($uriPath)) {
            $oidc->setRedirectURL(common::getSysURL() . inlink('login'));
        } else {
            $oidc->setRedirectURL(common::getSysURL() . inlink('login') . "?referer=" . urlencode($uriPath));
        }

        try {
            $scopes=array("profile", "email");
            $oidc->addScope($scopes);
            // $token = $oidc->requestClientCredentialsToken()->access_token;
            $oidc->authenticate();

            $accessToken = $oidc->getAccessTokenPayload();
            $roles = $accessToken->realm_access->roles;

            $user = $oidc->requestUserInfo();
        } catch (Exception $e) {
            die("oidc error:" . $e->getMessage());
        }
        $this->view->name = $user->name;
        $this->view->email = $user->email;
        $this->view->account = $user->preferred_username;
        $user->account = $user->preferred_username;

        if (in_array($this->config->oidc->adminRoleName, $roles)) {
            $user->isAdmin = true;
        }

        $dbuser = $this->oidc->getBindUser($user->account);
        if (!$dbuser) {
            $this->view->error = "user not exist!";
            $this->oidc->createUser($user);
            $dbuser = $this->oidc->getBindUser($user->account);
        }

        $this->user = $this->loadModel('user');

        //设置登录信息
        $this->user->cleanLocked($dbuser->account);
        /* Authorize him and save to session. */
        $dbuser->admin = strpos($this->app->company->admins, ",{$dbuser->account},") !== false;
        $dbuser->rights = $this->user->authorize($dbuser->account);
        $dbuser->groups = $this->user->getGroups($dbuser->account);
        $dbuser->view = $this->user->grantUserView($dbuser->account, $dbuser->rights['acls']);
        $dbuser->last = date(DT_DATETIME1, $last);
        $dbuser->lastTime = $dbuser->last;
        $dbuser->modifyPassword = ($dbuser->visits == 0 and !empty($this->config->safe->modifyPasswordFirstLogin));
        if ($dbuser->modifyPassword) $dbuser->modifyPasswordReason = 'modifyPasswordFirstLogin';
        if (!$dbuser->modifyPassword and !empty($this->config->safe->changeWeak)) {
            $dbuser->modifyPassword = $this->loadModel('admin')->checkWeak($user);
            if ($dbuser->modifyPassword) $user->modifyPasswordReason = 'weak';
        }

        $userIP = helper::getRemoteIp();

        // 检测 last 字段的类型，兼容不同版本（int时间戳 或 datetime）
        // 通过查询表结构来判断字段类型
        $sql = "SHOW COLUMNS FROM " . TABLE_USER . " LIKE 'last'";
        $columnInfo = $this->dao->query($sql)->fetch();
        $isDatetimeField = (strpos($columnInfo->Type, 'datetime') !== false || strpos($columnInfo->Type, 'timestamp') !== false);

        $lastValue = $isDatetimeField ? date(DT_DATETIME1, $last) : $last;

        $this->dao->update(TABLE_USER)
            ->set('visits = visits + 1')
            ->set('ip')->eq($userIP)
            ->set('last')->eq($lastValue)
            ->where('account')->eq($dbuser->account)
            ->exec();

        $this->session->set('user', $dbuser);
        $this->app->user = $this->session->user;
        $this->loadModel('action')->create('user', $dbuser->id, 'login');

        if (empty($referer)) {
            echo js::locate($this->createLink($this->config->default->module), 'parent');
        } else {
            $this->locate($referer);
        }
    }
}
