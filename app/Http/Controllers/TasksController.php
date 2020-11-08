<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use App\Task;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $data = [];
        if (\Auth::check()) { // 認証済みの場合
            // 認証済みユーザを取得
            $user = \Auth::user();
            $task =$user->tasklists()->orderby('created_at','desc')->paginate(10);
            
            
        
            
            $data = [
                'user' => $user,
                'tasks' => $tasks,
            ];
             return view('tasks.index',
        ['tasks' => $tasks
        ]);
        }
            
       // return view('tasks.index',
        //['tasks' => $tasks
        //]);
        //未ログインの場合ウェルカム場面
        return view('welcome',
        //$date
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $task = new Task;

        // メッセージ作成ビューを表示
        return view('tasks.create', [
            'task' => $task,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        $request->validate([
            'status' => 'required|max:10',
            'content'=> 'required'// 追加
            
        ]);
        
        // ☆認証済みユーザ（閲覧者）の投稿として作成（リクエストされた値をもとに作成）
        $request->user()->tasklists()->create([
            'content' => $request->content,
           ]);
        
        //ログイン中のユーザーを取得
        $user = \Auth::user();
        
        // メッセージを作成
        $task = new Task;
        $task->user_id = $user->id;
        $task->status = $request->status;
        $task->content = $request->content;
        $task->save();

        // トップページへリダイレクトさせる
        return redirect('/');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
         // idの値でメッセージを検索して取得
        $task = Task::findOrFail($id);

        // メッセージ詳細ビューでそれを表示
        return view('tasks.show', [
            'task' => $task,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // idの値でメッセージを検索して取得
        $task = Task::findOrFail($id);

        // メッセージ編集ビューでそれを表示
        return view('tasks.edit', [
            'task' => $task,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {   
      
        $request->validate([
            'status' => 'required|max:10',
            'content' => 'required'
        ]);
       
        $task = Task::findOrFail($id);
        $task->content = $request->content;
        $task->status = $request->status;
        $task->save();

      
        return redirect('/');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
          // idの値でメッセージを検索して取得
        $task = Task::findOrFail($id);
        
         // 認証済みユーザ（閲覧者）がその投稿の所有者である場合は、投稿を削除
        if (\Auth::id() === $tasklist->user_id) {
            $tasklist->delete();
        }

        // トップページへリダイレクトさせる
        return redirect('/');
    }
}
