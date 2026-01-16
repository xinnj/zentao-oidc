<?php

class oidcModel extends model
{
    /**
     * Get bind user.
     *
     * @param string $account
     * @access public
     * @return object
     */
    public function getBindUser($account)
    {
        return $this->dao->select('*')->from(TABLE_USER)
            ->where('account')->eq($account)
            ->andWhere('deleted')->eq('0')
            ->fetch();
    }

    /*
     *生成随机密码 ,pw_length 密码长度
     */
    function create_password($pw_length = 8): string
    {
        $randpwd = '';
        for ($i = 0; $i < $pw_length; $i++) {
            $randpwd .= chr(mt_rand(33, 126));
        }
        return $randpwd;
    }

    /**
     * Create a user
     *
     * @access public
     * @return void
     */
    public function createUser($newUser)
    {
        $user = $this->dao->select('*')->from(TABLE_USER)
            ->where('account')->eq($newUser->account)
            ->andWhere('deleted')->eq('1')
            ->fetch();
        if ($user) {
            error_log("A deleted user exist, remove it.");
            $this->dao->delete()->from(TABLE_USER)->where('account')->eq($newUser->account)->exec();
            $this->dao->delete()->from(TABLE_USERGROUP)->where('account')->eq($newUser->account)->exec();
        }

        $user = new stdclass();
        $user->account = $newUser->account;
        $user->realname = $newUser->chinesename;
        $user->email = $newUser->email;
        $user->password = md5($this->create_password(12)); //随机生成12位密码长度
        $user->deleted = '0';
        $this->dao->insert(TABLE_USER)->data($user)->autoCheck()->exec();
        if (dao::isError()) {
            error_log(dao::getError());
            return;
        }

        //将用户加到默认组
        $group = new stdClass();
        $group->account = $user->account;
        if ($newUser->isAdmin) {
            $group->group = 1; //加到admin组
        } else {
            $group->group = 10; //默认加到Others组
        }

        $this->dao->replace(TABLE_USERGROUP)->data($group)->exec();
        if (dao::isError()) {
            error_log(dao::getError());
            return;
        }
    }
}
