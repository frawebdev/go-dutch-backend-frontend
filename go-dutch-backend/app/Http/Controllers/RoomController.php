<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Room;
use App\Models\Participant;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index($id)
    {
        $rooms = Room::whereHas('participants', function($q) use ($id) {
            $q->where('user_id', $id);
        })->get();

        return $rooms;
    }

    public function show($id)
    {
        $room = Room::where('id', $id)->with('participants')->first();

        return $room;
    }

    public function store(Request $request)
    {
        $users = User::whereIn('id', $request->users)->get();
        
        $room = new Room();
        $room->title = $request->title;
        $room->room_admin = $request->admin['id'];
        $room->save();

        foreach ($users as $user) {
            $room->participants()->create([ 
                'name' => $user->name,
                'user_id' => $user->id 
                ]);
        }

        return json_encode([ 'response' => 200 ]);
    }
}
