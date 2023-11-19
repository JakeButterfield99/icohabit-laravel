<?php

namespace App\Http\Controllers\Rota;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Requests\RotaCreateRequest;
use App\Http\Requests\TaskAddRequest;
use App\Http\Requests\TaskAssignRequest;
use App\Http\Requests\TaskCreateRequest;
use App\Http\Requests\TaskJoinRequest;
use App\Http\Traits\UtilTrait;

use App\Models\Rota\Rota;
use App\Models\Rota\Task;
use App\Models\Rota\TaskManager;
use App\Models\Auth\User;

class RotaController extends Controller
{
    use UtilTrait;

    public function create_rota( RotaCreateRequest $request ) {

        $user = auth()->user();
        $home = $user->home;

        $rota = Rota::create([
            'name' => $request['name'],
            'visible' => $request['visible'],
        ]);

        if( $request['type'] == 'home' ){
            $rota->update([
                'home_id' => $home->id,
            ]);
        } else {
            $rota->update([
                'user_id' => $user->id,
            ]);
        }


        return response($rota, 201); // Created
    }

    public function get_rotas( Request $request ) {

        $user = auth()->user();
        $home = $user->home;

        $rotas = Rota::where('visible', 'public')
                    ->orWhere('home_id', $home->id)
                    ->orWhere('user_id', $user->id)
                    ->get();

        return response($rotas, 200); // OK
    }

    public function get_rota_tasks( Request $request, $rota_id ) {

        $user = auth()->user();

        $rota = Rota::where('id', $rota_id)->first();

        if( !$this->access_model( $user, $rota ) ) {
            return response([
                'message' => 'User cannot access that rota'
            ], 403); // Forbidden
        }

        $tasks = TaskManager::where('rota_id', $rota_id)->get();

        return response($tasks, 200); // OK
    }

    public function get_tasks( Request $request ) {

        $user = auth()->user();

        $match = ['user_id' => $user->id, 'visible' => 'private'];
        $tasks = Task::where($match)->orWhere('visible', 'public')->get();

        return response($tasks, 200); // OK

    }

    public function create_task( TaskCreateRequest $request ) {

        $user = auth()->user();

        $task = Task::create([
            'name' => $request['name'],
            'visible' => $request['visible'],
            'time' => $request['time'],
            'user_id' => $user->id,
        ]);

        return response($task, 201); // Created

    }

    public function add_task( TaskAddRequest $request ) {

        $user = auth()->user();
        $rota = Rota::where('id', $request['rota_id'])->first();

        if( !$this->access_model( $user, $rota ) ) {
            return response([
                'message' => 'User cannot access this rota'
            ], 403); // Forbidden
        }

        $taskManager = TaskManager::create([
            'rota_id' => $rota->id,
            'task_id' => $request['task_id'],
            'user_id' => $user->id,
            'time' => $request['time'],
            'due_by' => $request['due_by'],
        ]);

        return response($taskManager, 201); // Created

    }

    public function join_task( TaskJoinRequest $request ) {

        $user = auth()->user();
        $manager = TaskManager::where('id', $request['task_manager_id'])->first();
        $rota = Rota::where('id', $manager->rota_id)->first();

        if( !$this->access_model( $user, $rota ) ) {
            return response([
                'message' => 'User cannot access this rota'
            ], 403); // Forbidden
        }

        $manager->update([
            'user_id' => $user->id,
        ]);

        return response($manager, 200); // OK

    }

    public function assign_task( TaskAssignRequest $request ) {

        $user = auth()->user();
        $newUser = User::where('id', $request['user_id'])->first();
        $manager = TaskManager::where('id', $request['task_manager_id'])->first();
        $rota = Rota::where('id', $manager->rota_id)->first();

        if( !$this->access_model( $user, $rota ) ) {
            return response([
                'message' => 'You cannot access this rota'
            ], 403); // Forbidden
        }

        if( !$this->access_model( $newUser, $rota ) ) {
            return response([
                'message' => 'That User cannot access this rota'
            ], 403); // Forbidden
        }

        $manager->update([
            'user_id' => $newUser->id,
        ]);

        return response($manager, 200); // OK
    }

}
