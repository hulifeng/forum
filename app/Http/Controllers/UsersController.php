<?php

namespace App\Http\Controllers;

use App\Handlers\ImageUploaderHandler;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Models\User;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['show']]);
    }

    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $this->authorize('update', $user);

        return view('users.edit', compact('user'));
    }

    public function update(UserRequest $request, User $user, ImageUploaderHandler $uploaderHandler)
    {
        $this->authorize('update', $user);

        $data = $request->all();

        // 如果有图片上传
        if ($request->avator) {
            $result = $uploaderHandler->save($request->avator, 'avators', $user->id, 362);
            if ($result) {
                $data['avator'] = $result['path'];
            }
        }

        $user->update($data);

        return redirect()->route('users.show', $user->id)->with('success', '个人数据更新成功！');
    }
}
