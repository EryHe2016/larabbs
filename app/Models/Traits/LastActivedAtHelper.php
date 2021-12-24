<?php
namespace App\Models\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\Redis;

trait LastActivedAtHelper
{
    //缓存相关
    protected $hash_prefix = 'larabbs_last_actived_at_';
    protected $field_prefix = 'user_';

    public function recordLastActivedAt()
    {
        //获取今天的日期
        $date = Carbon::now()->toDateString();

        //Redis 哈希表的命名，如：larabbs_last_actived_at_2021-12-24
        $hash = $this->getHashFromDateString($date);

        //字段名称如：user_1
        $field = $this->getHashField();

        //当前时间如：2021-12-24 14:49:53
        $now = Carbon::now()->toDateTimeString();

        //数据写入Redis，字段已存在会被更新
        Redis::hSet($hash, $field, $now);
    }

    public function syncUserActivedAt()
    {
        // 获取昨天的日期，格式如：2021-12-23
        $yesterday_date = Carbon::yesterday()->toDateString();

        //Redis哈希表命名
        $hash = $this->getHashFromDateString($yesterday_date);

        //从Redis中获取多有哈希表数据
        $dates = Redis::hGetAll($hash);

        //遍历并同步到数据库
        foreach($dates as $user_id => $actived_at){
            //将user_1转换成1
            $user_id = str_replace($this->field_prefix, '', $user_id);

            //只有当用户存在时才更新到用户表
            if($user = $this->find($user_id)){
                $user->last_actived_at = $actived_at;
                $user->save();
            }
        }

        //以数据库为中心的存储，既已同步可直接删除
        Redis::del($hash);
    }

    public function getLastActivedAtAttribute($value)
    {
        //获取今天的日期
        $date = Carbon::now()->toDateString();

        //reids哈希表的命名
        $hash = $this->getHashFromDateString($date);

        //字段名字
        $field = $this->getHashField();

        //优先选择Redis数据，否则使用数据库
        $datetime = Redis::hGet($hash, $field) ? : $value;

        //如果存在的话返回时间
        if($datetime){
            return new Carbon($datetime);
        }else{
            //否则使用注册时间
            return $this->created_at;
        }
    }

    public function getHashFromDateString($date)
    {
        // Redis 哈希表的命名，如：larabbs_last_actived_at_2021-12-24
        return $this->hash_prefix . $date;
    }

    public function getHashField()
    {
        // 字段名称，如：user_1
        return $this->field_prefix . $this->id;
    }
}
