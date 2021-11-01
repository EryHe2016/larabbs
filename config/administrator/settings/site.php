<?php

return [
    'title' => '站点设置',

    //访问权限判断
    'permission' => function ()
    {
        return Auth::user()->hasRole('Founder');
    },

    //站点配置的表单
    'edit_fields' => [
        'site_name' => [
            //表单标题
            'title' => '站点名称',
            //表单条目类型
            'type' => 'text',
            //字数限制
            'limit' => 50,
        ],
        'contact_email' => [
            'title' => '联系人邮箱',
            'type' => 'text',
            'limit' => 50,
        ],
        ''
    ],
];
