<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;

class DocenteSearch extends Component
{
    public $search = '';

    public function render()
    {
        $docentes = User::where('name', 'like', '%'.$this->search.'%')->paginate(10);

        return view('livewire.docente-search', compact('docentes'));
    }
}
