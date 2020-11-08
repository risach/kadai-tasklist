<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;

class UsersController extends Controller
{
    //
  public function show($id)
    {
        // idの値でユーザを検索して取得
        $user = User::findOrFail($id);
        
         // ユーザの投稿一覧を作成日時の降順で取得
        $tasklistss = $user->tasklists()->orderBy('created_at', 'desc')->paginate(10);

    }
}