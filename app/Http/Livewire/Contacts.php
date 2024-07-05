<?php

namespace App\Http\Livewire;

use App\Models\Chat;
use App\Models\Friends;
use App\Models\User;
use Livewire\Component;

class Contacts extends Component
{
    public function render()
    {
        return view('livewire.contacts',[
            'contacts' => Friends::where("friend_id",auth()->id())->latest()->get()
        ])->layout('layouts.main');
    }
}
