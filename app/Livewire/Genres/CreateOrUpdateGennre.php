<?php

namespace App\Livewire\Genres;

use App\Models\Genre;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class CreateOrUpdate extends Component
{
    use WireUiActions;

    public Genre $form;
    public $visible = false;

    public function render()
    {
        return view('livewire.genres.create-or-update-genre');
    }

    #[On('open-genre-form')]
    public function showModal(?int $id = null)
    {
        $this->form = $resetForm;

        if($id !== null) {
            $this->form = $setGenre(
                Genre::findOrFail($id)    
            );
        }
        $this->visible = true;
    }
    
    public function save()
    {
        if($this->form->genre !== null){
            $this->authorize('update', $this->form->genre);
            $this->form->update();
            $this->dispatch('genre-updated-event');
            $this->notification()->send([
                'icon' => 'success',
                'title' => __('genres.messages.success.updated', [
                    'name' => $this->form->genre->name,
                ]),
            ]);
        } else {
            $this->authorize('create', Genre::class);
            $this->form->store();
            $genre = Genre::create([
                'name' =>$this->name,
            ]);
            $this->dispatch('genre-created-event'); 
            $this->notification()->send([
                'icon' => 'success',
                'title' => __('genres.messages.success.created', [
                    'name' => $this->form->name,
                ]),
            ]);
        }
        $this->visible = false;
    }
}