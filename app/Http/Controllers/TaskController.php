<?php

namespace App\Http\Controllers;

use Auth;
use App\Task;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\TaskRepository;

class TaskController extends Controller
{
	protected $tasks;
    /**
     * Create a new controller instance.
     *
     * @return void
     */

	//protected $redirectPath = '/tasks';
    public function __construct(TaskRepository $tasks)
    {
        //$this->middleware('auth');
	$this->tasks = $tasks;
	if(is_null(\Request::session()->get('user'))){
		\View::share('loggedin_user', null);
	} else {
		\View::share('loggedin_user', \Request::session()->get('user')->user);
	}
    }
	
	public function index(Request $request)
	{
		if(is_null($request->session()->get('user')))
			return redirect('/auth/login');
		//$tasks = $this->tasks->forUser($request->user());
		$tasks = $this->tasks->userTasks($request->session()->get('user')->user->id);
		return view('tasks.index',[
			'tasks' => $tasks
		]);
	}
	
	public function store(Request $request)
	{
		if(is_null($request->session()->get('user')))
			return redirect('/');
		$this->validate($request, [
			'name' => 'required|max:255',
		]);

		$data = [
				'name' => $request->name,
				'user_id' => $request->session()->get('user')->user->id
			];

		$this->tasks->saveTask($data);

		return redirect('/tasks');
	}
	
	public function destroy(Request $request, Task $task)
	{
		//$this->authorize('destroy', $task);
		$task->delete();
		return redirect('/tasks');
	}
}
