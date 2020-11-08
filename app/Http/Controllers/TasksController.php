<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Task;

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
        if (\Auth::check()) { 
            // 認証済みの場合
            // 認証済みユーザを取得
            $user = \Auth::user();
            $tasks =$user->tasks()->orderby('created_at','desc')->paginate(10);
            
            
        
            
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
        
        //ログインしているユーザーの情報を取得
        $user = \Auth::user();
        //もし、ログインしているユーザのIDと対象のタスクのuser_idが一致していたら表示する
        
        if($user->id==$task->user_id){
        // メッセージ詳細ビューでそれを表示
            return view('tasks.show', [
            'task' => $task,
        ]);
         }
        //それ以外なら、一覧画面にリダイレクトする。
        else{
            return redirect('/');
        }
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
        
         $user = \Auth::user();
        //もし、ログインしているユーザのIDと対象のタスクのuser_idが一致していたら表示する
        
        if($user->id==$task->user_id){
        // メッセージ詳細ビューでそれを表示
            return view('tasks.show', [
            'task' => $task,
        ]);
         }
        //それ以外なら、一覧画面にリダイレクトする。
        else{
            return redirect('/');
        
    }

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
        
        $user = \Auth::user();
        //もし、ログインしているユーザのIDと対象のタスクのuser_idが一致していたら表示する
        
        if($user->id==$task->user_id){
        // メッセージ詳細ビューでそれを表示
        //     return view('tasks.show', [
        //     'task' => $task,
        // ]);
        
        //対象のタスクを削除する。
            $task->delete();
         }
        // //それ以外なら、一覧画面にリダイレクトする。
        // else{
        //     return redirect('/');
        // }
    

        // トップページへリダイレクトさせる
        return redirect('/');
    }
}
