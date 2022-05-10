<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\Participant;

class ParticipantController extends Controller
{
    public function addExpense(Request $request)
    {
        //update participants
        $room = $request->room;
        $current_participant = Participant::where('user_id', $request->user_id)->where('room_id', $request->room['id'])->first();
        $participants = Participant::where('room_id', $request->room['id'])->get();
        $split_expense = $request->expense / count($room['participants']);

        foreach($participants as $participant) {
            if($participant->user_id !== $request->user_id) {
                $participant->payback = $participant->payback + $split_expense;
                $participant->update();
            }
        }

        $current_participant->money_spent = $current_participant->money_spent + $request->expense;
        $current_participant->money_owed = $current_participant->money_owed + ($request->expense - $split_expense);
        if($current_participant->money_owed > $current_participant->payback && $current_participant->payback > 0) {
            $current_participant->money_owed = $current_participant->money_owed - $current_participant->payback;
        }
        $current_participant->transactions()->create(['amount' => $request->expense]);
        $current_participant->update();
    }

    public function removeExpense(Request $request)
    {
        $room = Room::where('id', $request->room_id)->first();
        $current_participant = Participant::where('id', $request->id)->first();
        $participants = Participant::where('room_id', $request->room_id)->get();
        $split_transaction = $request->transaction / count($room->participants);

        foreach($participants as $participant) {
            if($participant->user_id !== $request->id) {
                $participant->payback = $participant->payback - $split_transaction;
                $participant->update();
            }
        }

        $current_participant->money_spent = $current_participant->money_spent - $request->$split_transaction;
        $current_participant->money_owed = $current_participant->money_owed - ($request->transaction - $split_transaction);
        if($current_participant->money_owed > $current_participant->payback && $current_participant->payback > 0) {
            $current_participant->money_owed = $current_participant->money_owed + $current_participant->payback;
        }
        $current_participant->transactions()->find($request->transaction_id)->delete();
        $current_participant->update();
    }
}
