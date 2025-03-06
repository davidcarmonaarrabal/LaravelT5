<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;

class Messages extends Component
{
    public function render()
    {
        return view('livewire.messages');
    }

    //Cuando se emita el evento (On) se ejecutará la función
    #[On('msg')]
    public function msgs($msg) {
        session()->flash('msg', $msg);
    }
}
