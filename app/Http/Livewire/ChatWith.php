<?php

namespace App\Http\Livewire;

use App\Models\Chat;
use App\Models\Friends;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Str;

class ChatWith extends Component
{

    public $uuid;
    public $user;
    public $message;


    public function send_message()
    {
        $this->validate(['message' => "required"]);


        chat::create([
            'user_id' => auth()->id(),
            'message' => $this->message,
            'chat_id' => Friends::where(['user_id'=>auth()->id(), 'friend_id' =>$this->user->id])->first()->chat_id,
            'friend_id' => $this->user->id
        ]);

        $this->message='';
        $this->render();
    }

    public function mount($uuid)
    {
        $this->uuid = $uuid;
        $this->user = User::where('uuid',$uuid)->first();


        if (Friends::where(['user_id' => auth()->id(), 'friend_id' => $this->user->id])->count() === 0 || Friends::where(['user_id' => $this->user->id, 'friend_id' => auth()->id()])->count() === 0) {
            $uuid = Str::uuid();
            Friends::create([
                'user_id' => auth()->id(),
                'chat_id' => $uuid,
                'friend_id' => $this->user->id
            ]);

            Friends::create([
                'user_id' => $this->user->id,
                'chat_id' => $uuid,
                'friend_id' => auth()->id()
            ]);
        }
    }
    public function render()
    {
        return view('livewire.chat-with',[
            'messages' => chat::where('chat_id',Friends::where(['user_id'=>auth()->id(), 'friend_id' =>$this->user->id])->first()->chat_id)->get()
                    ])->layout('layouts.main');

    }
}
