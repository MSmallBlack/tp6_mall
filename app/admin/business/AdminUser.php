<?php
/**
 * Created by PhpStorm
 * author :gogochen
 * date   :2020/5/8
 * time   :0:43
 */
declare(strict_types=1);

namespace app\admin\business;


use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\Exception;
use app\common\model\mysql\AdminUser as AdminUserModel;
use function request;

class AdminUser
{
    public $adminUserObj = null;

    public function __construct()
    {
        $this->adminUserObj = new AdminUserModel();
    }

    /**
     * 登录
     * @param $data
     * @return bool
     * @throws Exception
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function login(array $data): bool
    {
        $adminUser = $this->adminUserObj->getAdminUserByUsername($data['username']);
        if (empty($adminUser) || $adminUser->status != config('status.mysql.table_normal')) {
            throw new Exception('不存在该用户');
        }
        if ($adminUser->password != md5(config('status.md5_str') . $data['password'])) {
            throw new Exception('密码错误');
        }
        //更新数据表
        $updateData = [
            'update_time' => time(),
            'last_login_time' => time(),
            'last_login_ip' => request()->ip()
        ];
        $result = $this->adminUserObj->updateAdminUserById($adminUser->id, $updateData);
        if (!$result) {
            throw new Exception('登录失败');
        }

        //记录session
        session(config('admin.session_admin'), $adminUser);
        return true;
    }
}