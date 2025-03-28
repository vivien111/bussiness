<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Announcement;

class FormulaireAnnonce extends Component
{
    public $title, $content, $price;

    protected $rules = [
        'title' => 'required|min:5',
        'content' => 'required|min:10',
        'price' => 'required|numeric|min:1'
    ];

    public function submit()
    {
        $this->validate();

        Announcement::create([
            'title' => $this->title,
            'content' => $this->content,
            'price' => $this->price,
            'user_id' => auth()->id()
        ]);

        session()->flash('message', 'Annonce ajoutée avec succès !');
        $this->reset();
    }

    public function render()
    {
        return view('livewire.formulaire-annonce');
    }
}

