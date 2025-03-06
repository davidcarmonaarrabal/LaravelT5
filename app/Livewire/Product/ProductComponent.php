<?php

namespace App\Livewire\Product;

use App\Models\Categories;
use App\Models\Category;
use App\Models\Products;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Productos')]
class ProductComponent extends Component
{
    //Para poder usar la paginación
    use WithPagination;

    //Propiedades clase
    public $search = '';
    public $totalRegistros = 0;
    public $cant = 5;

    //Propiedades modelo
    public $Id = 0;
    public $name;
    public $categories_id;
    public $description;
    public $purchase_price;
    public $selling_price;
    public $bar_code;
    public $stock = 10;
    public $min_stock;
    public $expiration_date;
    public $active = 1;


    public function render()
    {
        //Contamos el nº productos
        $this->totalRegistros = Products::count();

        //Obtenemos los productos para nuestro listado
        /* Mostramos de forma descendente (reverse) 
         con el where filtramos todos los nombres de los productos
         que contenga lo que busco en el buscador*/
        $products = Products::where('name', 'like', '%' . $this->search . '%')
            ->orderBy('id', 'desc')
            ->paginate($this->cant);


        return view('livewire.product.product-component', [
            'products' => $products
        ]);
    }

    //Funciona limpiar modal en crearProducts
    public function create()
    {
        /*Si pulsamos en crear, cancelamos, editamos, cancelamos
        y volvemos a crear, me guarda el botón con editar. Para
        obviar este error, se soluciona de la siguiente forma: */
        $this->Id = 0;

        //Limpiamos los valores
        $this->clean();

        //Emitir evento para abrir el modal
        $this->dispatch('open-modal', 'modalProducts');
    }

    //Función para crear producto
    public function store()
    {
        $rules = [
            'name' => 'required|min:5|max:255|unique:products',
            'description' => 'max:255',
            'purchase_price' => 'numeric|nullable',
            'selling_price' => 'required|nullable',
            'stock' => 'required|numeric',
            'min_stock' => 'numeric|nullable',
            'categories_id' => 'required|numeric'
        ];

        $this->validate($rules);

        //Obtenemos valores del form para dar de alta
        $product = new Products();
        $product->name = $this->name;
        $product->description = $this->description;
        $product->purchase_price = $this->purchase_price;
        $product->selling_price = $this->selling_price;
        $product->stock = $this->stock;
        $product->min_stock = $this->min_stock;
        $product->bar_code = $this->bar_code;
        $product->expiration_date = $this->expiration_date;
        $product->active = $this->active;
        $product->categories_id = $this->categories_id;

        $product->save();

        //Emitir evento para cerrar modal
        $this->dispatch('close-modal', 'modalProducts');
        $this->dispatch('msg', 'Producto creado correctamente');

        //Propiedades de los input a reiniciar
        $this->clean();
    }

    //Función para edición de producto
    public function edit(Products $product)
    {
        //Propiedades de los input a reiniciar
        $this->clean();

        // Cargamos los datos a mostrar
        $this->Id = $product->id;
        $this->name =  $product->name;
        $this->description =  $product->description;
        $this->purchase_price =  $product->purchase_price;
        $this->selling_price =  $product->selling_price;
        $this->stock =  $product->stock;
        $this->min_stock =  $product->min_stock;
        $this->bar_code =  $product->bar_code;
        $this->expiration_date =  $product->expiration_date;
        $this->active =  $product->active;
        $this->categories_id =  $product->categories_id;

        //Emitir evento para abrir el modal
        $this->dispatch('open-modal', 'modalProducts');
    }

    //Función para actualizar producto
    public function update(Products $product)
    {

        $rules = [
            'name' => 'required|min:5|max:255|unique:products,id,'.$this->Id,
            'description' => 'max:255',
            'purchase_price' => 'numeric|nullable',
            'selling_price' => 'required|nullable',
            'stock' => 'required|numeric',
            'min_stock' => 'numeric|nullable',
            'categories_id' => 'required|numeric'
        ];

        //Validación del formulario
        $this->validate($rules);

        //Actualizamos los datos a modificar
        $product->name = $this->name;
        $product->description =  $this->description;
        $product->purchase_price = $this->purchase_price;
        $product->selling_price = $this->selling_price;
        $product->stock = $this->stock;
        $product->min_stock = $this->min_stock;
        $product->bar_code = $this->bar_code;
        $product->expiration_date = $this->expiration_date;
        $product->active = $this->active;
        $product->categories_id = $this->categories_id;


        //Guardamos el producto a modificar
        $product->update();

        //Emitir evento para cerrar modal
        $this->dispatch('close-modal', 'modalProducts');
        $this->dispatch('msg', 'Producto editado correctamente');

        //Restrablecemos el input con el nuevo valor
        $this->clean();
    }


    // Metodo encargado de la limpieza
    public function clean()
    {
        $this->reset([
            'Id',
            'name',
            'description',
            'purchase_price',
            'selling_price',
            'stock',
            'min_stock',
            'bar_code',
            'expiration_date',
            'active',
            'categories_id'
        ]);

        $this->resetErrorBag();
    }

    // Metodo para el select de categorias en creación producto
    #[Computed()]
    public function categories()
    {
        return Categories::all();
    }

    // Función para eliminar producto
        /*Evento que estoy escuchando para eliminar en caso necesario */
        #[On('destroyProduct')]
        public function destroy($id)
        {
            /*Si no encuentra el producto, muestra página NOT FOUND */
            $product = Products::findOrfail($id);

            /* Eliminamos categoria */
            $product->delete();

            /* Emitir evento para cerrar modal */
            $this->dispatch('msg', 'Producto borrado correctamente');
        }
}
