@include('shared._error')

<div class="reply-box">
    <form action="{{ route('replies.store') }}" method="post" accept-charset="UTF-8">
        {{ csrf_field() }}
        <input type="hidden" name="topic_id" value="{{ $topic->id }}">
        <div class="form-group">
            <textarea name="content" class="form-control" rows="3" placeholder="分享你的见解"></textarea>
        </div>
        <button type="submit" class="btn btn-primary btn-sm"><i></i>回复</button>
    </form>
</div>
<hr>
