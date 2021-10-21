<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Topic extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'body', 'category_id', 'excerpt', 'slug'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeRecent($query)
    {
        //按照创建的时间排序
        return $query->orderBy('created_at', 'desc');
    }

    public function scopeRecentReplied($query)
    {
        return $query->orderBy('updated_at', 'desc');
    }

    public function scopeWithOrder($query, $order)
    {
        switch($order){
            case 'recent':
                $query->recent();
                break;
            default:
                $query->recentReplied();
                break;
        }
    }

    public function link($param = [])
    {
        return route('topics.show', array_merge([$this->id, $this->slug], $param));
    }
}