<?php

namespace App\Livewire\Genres\Forms;

use App\Models\Genre;
use Illuminate\Support\Str;
use Livewire\Form;
use WireUi\Traits\WireUiActions;

class GenreForm extends Form
{
    public ?Genre $genre = null;
    public string $name = '';
    public function setGenre(Genre $genre)
    {
        $this->genre = $genre;
        $this->name = $genre->name;
    }

    public function resetForm()
    {
        $this->reset();
        $this->resetErrorBag();
    }

    public function rules()
    {
        return[
            'name' => [
                'required',
                Rule::unique('genres')->withoutTrashed()->ignore(optional($this->genre)->id), 
            ],
        ];
    }
    
    public function validationAttributes()
    {
        return [
            'name' => Str::lower(__('genres.attributes.name')),
        ];
    }
    public function store()
    {
        $this->validate();
        $this->genre = Genre::create($this->all());
    }

    public function update()
    {
        $this->validate();
        $this->genre->fill(
            $this->all()
        )->save();
    }
}
