<?php

namespace App\Http\Controllers\Api;

use App\Http\Queries\TopicQuery;
use App\Http\Requests\Api\TopicRequest;
use App\Http\Resources\TopicResource;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class TopicsController extends Controller
{
    /**
     * 创建话题
     *
     * @param TopicRequest $request
     * @param Topic $topic
     * @return TopicResource
     */
    public function store(TopicRequest $request, Topic $topic)
    {
        $topic->fill($request->all());
        $topic->user_id = $request->user()->id;
        $topic->save();

        return new TopicResource($topic);
    }

    /**
     * 编辑话题
     *
     * @param TopicRequest $request
     * @param Topic $topic
     * @return TopicResource
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(TopicRequest $request, Topic $topic)
    {
        $this->authorize('update', $topic);

        $topic->update($request->all());
        return new TopicResource($topic);
    }

    public function destroy(Topic $topic)
    {
        $this->authorize('destroy', $topic);
        $topic->delete();
        return response(null,204);
    }

    /**
     * 话题列表
     *
     * @param Request $request
     * @param User $user
     * @param $query
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request, User $user, TopicQuery $query)
    {
        /*$query = $topic->query();

        if($categoryId = $request->category_id){
            $query->where('category_id', $categoryId);
        }

        $topics = $query
            ->with('user', 'category')
            ->withOrder($request->order)->paginate();*/
        /*$topics = QueryBuilder::for(Topic::class)
            ->allowedIncludes('user', 'category')
            ->allowedFilters([
                'title',
                AllowedFilter::exact('category_id'),
                AllowedFilter::scope('withOrder')->default('recentReplied'),
            ])
            ->paginate();*/
        $topics = $query->paginate();
        $active_users = $user->getActiveUsers();dd($active_users);
        return TopicResource::collection($topics);
    }

    /**
     * 指定用户的话题列表
     *
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function userIndex(Request $request, User $user, TopicQuery $query)
    {
        /*$query = $user->topics()->getQuery();

        $topics = QueryBuilder::for($query)
            ->allowedIncludes('user', 'category')
            ->allowedFilters([
                'title',
                AllowedFilter::exact('category_id'),
                AllowedFilter::scope('withOrder')->default('recentReplied'),
            ])
            ->paginate();*/

        $topics = $query->where('user_id', $user->id)->paginate();

        return TopicResource::collection($topics);
    }

    public function show($topicId, TopicQuery $query)
    {
        /* 使用include机制 方法一 不使用路由模型绑定 直接接收$topicId
         *
         * $topic = QueryBuilder::for(Topic::class)
            ->allowedIncludes('user', 'category')
            ->findOrFail($topicId);*/

        //方法二 model重写 resolveRouteBinding()
        $topic = $query->findOrFail($topicId);
        return new TopicResource($topic);
    }
}
