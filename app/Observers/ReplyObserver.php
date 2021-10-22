<?php

namespace App\Observers;

use App\Models\Reply;
use Auth;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class ReplyObserver
{
    public function creating(Reply $reply)
    {
        $reply->content = clean($reply->content, 'user_topic_body');
    }

    public function created(Reply $reply)
    {
        $reply->topic->reply_count = $reply->topic->replies->count();
        $reply->topic->last_reply_user_id = Auth::id();
        $reply->topic->save();
    }

    public function updating(Reply $reply)
    {
        //
    }
}
