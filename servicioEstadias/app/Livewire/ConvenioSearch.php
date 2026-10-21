<?php

namespace App\Livewire;

use App\Models\Convenio;
use Livewire\Component;

class ConvenioSearch extends Component
{
    public $search = '';

    public function updatedSearch($value)
    {
        logger('Search updated: '.$value);
    }

    public function render()
    {
        $convenios = Convenio::query()
            ->when($this->search, function ($query) {
                $query->where('nombre', 'like', '%'.$this->search.'%');
            })
            ->get();

        return view('livewire.convenio-search', compact('convenios'));
    }
}
