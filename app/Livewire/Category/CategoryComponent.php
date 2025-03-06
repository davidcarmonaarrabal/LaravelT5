<?php

namespace App\Livewire\Category;

use App\Models\Categories;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Categorías')]

class CategoryComponent extends Component
{
    public $totalRegistros=0;
    public $name;
    use WithPagination;
    public $search="";
    public $cant=5;
    public $Id;


    public function render(){
        if($this->search != '') {
            $this->resetPage();
        }

        $this->totalRegistros = Categories::count();

        $categories = Categories::where('name','like','%'.$this->search.'%')->orderBy('id', 'desc')->paginate($this->cant);

        return view('livewire.category.category-component',
        [
            'categories' => $categories
        ]);
    }

    public function mount(){
        $this->totalRegistros = Categories::count();
    }

    public function store() {
        $rules = [
            'name' => 'required | min:5 | max:255 | unique:categories',
        ];
        $messages = [
            'name.required' => 'El nombre es requerido',
            'name.min' => 'El nombre debe tener mínimo 5 caracteres',
            'name.max' => 'El nombre no debe superar 255 caracteres',
            'name.unique' => 'El nombre de la categoría ya está en uso'
        ];

        $this->validate($rules, $messages);

        $category = new Categories();
        $category->name= $this->name;
        $category->save();

        $this->dispatch('close-modal', 'modalCategory');
        $this->dispatch('msg', 'Categoria creada correctamente');

        $this->reset(['name']);
    }

    public function edit(Categories $category) {
        $this->Id = $category->id;

        $this->name = $category->name;

        $this->dispatch('open-modal', 'modalCategory');
    }

    public function update(Categories $category){

        $rules = [
            'name' => 'required|min:5|max:255|unique:categories'
        ];

        $messages = [
            'name.required' => 'El nombre es requerido',
            'name.min' => 'El nombre debe tener minimo 5 caracteres',   
            'name.max' => 'El nombre no debe superar los 255 caracteres', 
            'name.unique' => 'El nombre de la categoria ya esta en uso'   
        ];

        $this->validate($rules,$messages);

        $category->name= $this->name;

        $category->update();

        $this->dispatch('close-modal', 'modalCategory');
        $this->dispatch('msg', 'Categoría editada correctamente');

        $this->reset(['name']);
    }

    public function create() {
        $this->Id = 0;

        $this->reset(['name']);

        $this->resetErrorBag();

        $this->dispatch('open-modal', 'modalCategory');
    }

    #[On('destroyCategory')]
    public function destroy($id){
            /*Si no encuentra la categoría, muestra página NOT FOUND */
            $category = Categories::findOrfail($id);
            
            /* Eliminamos categoria */
            $category->delete();

            /* Emitir evento para ceruse App\Models\Categories;rar modal */
            $this->dispatch('msg', 'Categoría borrada correctamente');
    }
}
