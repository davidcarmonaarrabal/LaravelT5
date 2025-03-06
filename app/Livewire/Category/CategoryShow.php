<?php

namespace App\Livewire\Category;

use App\Models\Categories;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Ver Categoría')]

class CategoryShow extends Component
{
    use WithPagination;

    public Categories $category;

    public function render()
    {
        $products = $this->category->products()->paginate(5);
        
        return view('livewire.category.category-show',compact('products'));
    }
}
