<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::all();

        return response()->json(['data'=> $tasks]);
    }

    public function show($id)
    {
        $task = Task::find($id);

        if(empty($task)){
            return response()->json(['message'=> 'Essa tarefa não existe.', 'status'=> 'error'], 409);
        }

        return response()->json(['data'=> $task]);
    }

    public function store(Request $request)
    {
        $task = new Task();

        $task->user_id = auth()->user()->id;
        $task->code = $request->code;
        $task->title = $request->title;
        $task->description = $request->description;
        $task->status = $request->status ?? 1;

        if($task->save()){
            return response()->json(['message'=> 'Criado com sucesso!', 'status'=>'success']);
        }

        return response()->json(['message'=> 'Não foi possível cadastrar tente novamente mais tarde!', 'status'=> 'error'], 500);
    }

    public function edit(Request $request, $id)
    {
        $task = Task::find($id);

        if(empty($task)){
            return response()->json(['message'=> 'Essa tarefa não existe.', 'status'=> 'error'], 409);
        }

        $task->user_id = auth()->user()->id;
        $task->title = $request->title;
        $task->description = $request->description;
        $task->status = $request->status ?? 1;

        if($task->save()){
            return response()->json(['message'=> 'Atualizado com sucesso!', 'status'=>'success']);
        }

        return response()->json(['message'=> 'Não foi possível cadastrar tente novamente mais tarde!', 'status'=> 'error'], 500);
    }

    public function delete($id)
    {
        $task = Task::find($id);

        if(empty($task)){
            return response()->json(['message'=> 'Essa tarefa não existe.', 'status'=> 'error'], 409);
        }

        $task->delete();
        return response()->json(['message'=> 'Deletado com sucesso!', 'status'=> 'success']);
    }
}
