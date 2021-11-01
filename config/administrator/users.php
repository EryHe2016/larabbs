<?php

use App\Models\User;

return [
    //页面标题
    'title' =>  '用户',
    'heading' =>  '用户管理',
    //模型单数，用作页面 新建$single
    'single' => '用户',
    //数据模型，用作数据的CRUD
    'model' => User::class,

    /**
     * 设置当前页面的访问权限 返回布尔值
     *
     * true 通过权限验证  false 无权访问并从Menu中隐藏
     */
    'premission' => function()
    {
        return Auth::user()->can('manage_contents');
    },

    //字段负责渲染 数据表格 由无数的 列 组成
    'columns' => [
        'id' => [
            'title' => '用户ID'
        ],
        'avatar' => [
            'title' => '头像',
            //默认情况会直接输出数据，也可以使用output选项来定制输出内容
            'output' => function($avatar, $model){
                return empty($avatar) ? 'N/A' : '<img src="'.$avatar.'" width="40">';
            },
            //是否允许排序
            'sortable' => false,
        ],
        'name' => [
            'title' => '用户名',
            'sortable' => false,
            'output' => function($name, $model){
                return '<a href="/users/'.$model->id.'" target="_blank">'.$name.'</a>';
            },
        ],
        'email' => [
            'title' => '邮箱',
        ],
        'created_at',

        'operation' => [
            'title'  => '管理',
            'output' => function ($value, $model) {
                return $value;
            },
            'sortable' => false,
        ],
    ],

    //模型表单 设置项
    'edit_fields' => [
        'name' => [
            'title' => '用户名',
            'type' => 'text'
        ],
        'email' => [
            'title' => '邮箱',
        ],
        'password' => [
            'title' => '密码',
            'type' => 'password', //表单使用input类型password
        ],
        'avatar' => [
            'title' => '用户头像',
            'type' => 'image',
            //图片上传必须设置图片存放路径
            'location' => public_path().'/uploads/images/avatars/',
        ],
        'roles' => [
            'title' => '用户角色',
            //指定数据的类型为关联模型
            'type' => 'relationship',
            //关联模型的字段，用来做关联显示
            'name_field' => 'name'
        ],
    ],
    //数据过滤设置
    'filters' => [
        'id' => [
            // 过滤表单条目显示名称
            'title' => '用户ID',
        ],
        'name' => [
            'title' => '用户名',
        ],
        'email' => [
            'title' => 'Email',
        ],
    ],
];
