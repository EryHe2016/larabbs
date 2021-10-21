<?php
function route_class()
{
    return str_replace('.','-',Route::currentRouteName());
}

function category_nav_active($category_id)
{
    return active_class(if_route('categories.show') && if_route_param('category',$category_id));
}

function make_excerpt($content, $length=200)
{
    $excerpt = trim(preg_replace('/\r\n|\r|\n+/', '', strip_tags($content)));
    return Str::limit($excerpt, $length);
}