<?php

namespace App\Http\Controllers;

use App\Handlers\ImageUploaderHandler;
use App\Models\Category;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\TopicRequest;

class TopicsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

	public function index(Request $request, Topic $topic, User $user)
	{
		$topics = $topic->withOrder($request->order)->paginate(30);

		$active_users = $user->getActiveUsers();

		return view('topics.index', compact('topics', 'active_users'));
	}

    public function show(Topic $topic, Request $request)
    {
        // URL 矫正
        if ( ! empty($topic->slug) && $topic->slug != $request->slug) {
            return redirect($topic->link(), 301);
        }

        return view('topics.show', compact('topic'));
    }

	public function create(Topic $topic)
	{
	    $categories = Category::all();

		return view('topics.create_and_edit', compact('topic', 'categories'));
	}

	public function store(TopicRequest $request, Topic $topic)
	{
	    $topic->fill($request->all());

	    $topic->user_id = \Auth::id();

        $topic->save();

        return redirect()->to($topic->link())->with('success', '成功创建主题！');
	}

	public function edit(Topic $topic)
	{
        $this->authorize('update', $topic);

        $categories = Category::all();

		return view('topics.create_and_edit', compact('topic', 'categories'));
	}

	public function update(TopicRequest $request, Topic $topic)
	{
		$this->authorize('update', $topic);

		$topic->update($request->all());

		return redirect()->to($topic->link())->with('success', '更新话题成功！');
	}

	public function destroy(Topic $topic)
	{
		$this->authorize('destroy', $topic);

		$topic->delete();

		return redirect()->route('topics.index')->with('success', '删除话题成功！');
	}

    public function uploadImage(Request $request, ImageUploaderHandler $uploader)
    {
        // 默认是失败的
        $data = [
            'success'   => false,
            'msg'       => '上传失败!',
            'file_path' => ''
        ];

        // 判断是否有图片上传
        if ($file = $request->upload_file) {
            // 保存图片到本地
            $result = $uploader->save($request->upload_file, 'topics', \Auth::id(), 1024);
            if ($request) {
                $data = [
                    'success' => true,
                    'msg' => '上传成功',
                    'file_path' => $result['path']
                ];
            }
        }

        return $data;
    }
}