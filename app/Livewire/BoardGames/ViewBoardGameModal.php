<?php

namespace App\Livewire\BoardGames;

use App\Models\BoardGame;
use Livewire\Component;

class ViewBoardGameModal extends Component
{
    public $isOpen = false;
    public $boardGame = null;
    public $allGameIds = [];
    public $currentIndex = 0;

    protected $listeners = ['openViewModal'];

    public function openViewModal($rowId)
    {
        // Отримуємо всі ID записів
        $this->allGameIds = BoardGame::pluck('id')->toArray();
        
        // Знаходимо індекс поточного запису
        $this->currentIndex = array_search($rowId, $this->allGameIds);
        
        $this->boardGame = BoardGame::with('genres')->findOrFail($rowId);
        $this->isOpen = true;
    }

    public function nextGame()
    {
        if ($this->currentIndex < count($this->allGameIds) - 1) {
            $this->currentIndex++;
            $this->boardGame = BoardGame::with('genres')->findOrFail($this->allGameIds[$this->currentIndex]);
        }
    }

    public function previousGame()
    {
        if ($this->currentIndex > 0) {
            $this->currentIndex--;
            $this->boardGame = BoardGame::with('genres')->findOrFail($this->allGameIds[$this->currentIndex]);
        }
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->boardGame = null;
        $this->allGameIds = [];
        $this->currentIndex = 0;
    }

    public function render()
    {
        return view('livewire.board-games.view-board-game-modal');
    }
}
