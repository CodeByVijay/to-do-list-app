<?php

namespace App\Http\Controllers;

use App\Models\ToDoList;
use Illuminate\Http\Request;

class ToDoListController extends Controller
{
    public function getAllTask(){
        $tasks = ToDoList::orderBy('id', 'DESC')->get();
        return response()->json(['task'=>$tasks]);
    }

    public function getNotCompleteTask(){
        $tasks = ToDoList::where('is_complete',0)->orderBy('id', 'DESC')->get();
        return response()->json(['task'=>$tasks]);
    }

    public function doneTask(Request $req){
        $tasks = ToDoList::find($req->id)->update(
            [
                'is_complete'=>1
            ]
        );
        return response()->json(['task'=>'Task Done.']);
    }
    public function removeDoneTask(Request $req){
        $tasks = ToDoList::find($req->id)->update(
            [
                'is_complete'=>0
            ]
        );
        return response()->json(['task'=>'Task Not Done.']);
    }

    public function checkTask(Request $req){
        $tasks = ToDoList::where('task',$req->task)->first();
        // return response()->json($tasks);
        if($tasks != null){
            return response()->json(['result'=>'exist']);
        }else{
            return response()->json(['result'=>'notexist']);
        }

    }

    public function addTask(Request $req){
        $task = new ToDoList();
        $task->task = $req->task;
        $task->save();
        return response()->json(['result'=>'Task Added.','task'=>$task]);
    }

    public function deleteTask(Request $req){
        ToDoList::find($req->id)->delete();
        return response()->json(['result'=>'success']);
    }
}
