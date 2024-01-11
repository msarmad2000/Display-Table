<?php

namespace App\Livewire;
use Livewire\Component;
use App\Models\User;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

class UsersTable extends Component
{
    use WithPagination;

    public $perPage = 5;

    #[Url(history:true)]
    public $search= '';

    #[Url(history:true)]
    public $admin = '';

    #[Url(history:true)]
    public $sortby='created_at';

    #[Url(history:true)]
    public $sortDir = 'DESC';

    public function updatedSearch(){
        $this->resetPage();
    }
    public function setSortby($sortbyField){
      if($this->sortby === $sortbyField) {
           $this->sortDir = ($this->sortDir == 'ASC') ? 'DESC' : "ASC";
           return;
      }
      $this->sortby = $sortbyField;
      $this->sortDir = 'DESC';
    }
    public function delete(User $user){
        $user->delete();
    }
    public function render()
    {
        return view('livewire.users-table',
        [ 'users' => User::Search($this->search)
        ->when($this->admin !== '',function($query){
            $query->where('is_admin', $this->admin);
        })->orderBy($this->sortby,$this->sortDir)
        ->paginate($this->perPage),
        ]);
    }
}
