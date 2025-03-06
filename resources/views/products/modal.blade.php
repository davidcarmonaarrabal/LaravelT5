{{-- Llamamos a nuestro modal --}}
<x-modal modalId="modalProducts" modalTitle="Productos" modalSize="modal-lg">

    {{-- Formulario para el modal --}}
    <form wire:submit={{ $Id == 0 ? 'store' : "update($Id)" }}>
        <div class="form-row">

            {{-- Input name --}}
            <div class="form-group col-md-7">
                <label for="name">Nombre:</label>
                <input wire:model='name' id="name" type="text" class="form-control" placeholder="Nombre Producto">

                {{-- Directiva de error en caso de validación errónea --}}
                @error('name')
                    <div class="alert alert-danger w-100 mt-2">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- Select Category --}}
            <div class="form-group col-md-5">
                <label for="category_id">Categoría:</label>
                <select wire:model='category_id' id="category_id" class="form-control">
                    @foreach ($this->categories as $category)
                        <option value="{{ $category->id}}"> {{$category->name}} </option>
                    @endforeach
                </select>

                {{-- Directiva de error en caso de validación errónea --}}
                @error('category_id')
                    <div class="alert alert-danger w-100 mt-2">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- Textarea description --}}
            <div class="form-group col-md-12">
                <label for="description">Descripción:</label>
                <textarea wire:model='description' id="description" class="form-control" rows="3"></textarea>

                {{-- Directiva de error en caso de validación errónea --}}
                @error('description')
                    <div class="alert alert-danger w-100 mt-2">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- Input precio compra --}}
            <div class="form-group col-md-4">
                <label for="purchase_price">Precio Compra:</label>
                <input wire:model='purchase_price' id="purchase_price" type="number" class="form-control"
                    min="0" step="any" placeholder="Precio compra">

                {{-- Directiva de error en caso de validación errónea --}}
                @error('purchase_price')
                    <div class="alert alert-danger w-100 mt-2">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- Input precio venta --}}
            <div class="form-group col-md-4">
                <label for="selling_price">Precio Venta:</label>
                <input wire:model='selling_price' id="selling_price" type="number" class="form-control"
                     min="0" step="any" placeholder="Precio venta">

                {{-- Directiva de error en caso de validación errónea --}}
                @error('selling_price')
                    <div class="alert alert-danger w-100 mt-2">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- Input código barras --}}
            <div class="form-group col-md-4">
                <label for="bar_code">Código de barras:</label>
                <input wire:model='bar_code' id="bar_code" type="number" class="form-control"
                    placeholder="Código de barras">

                {{-- Directiva de error en caso de validación errónea --}}
                @error('bar_code')
                    <div class="alert alert-danger w-100 mt-2">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- Input stock --}}
            <div class="form-group col-md-4">
                <label for="stock">Stock:</label>
                <input wire:model='stock' id="stock" type="number" class="form-control" 
                    min="0" step="any" placeholder="Stock">

                {{-- Directiva de error en caso de validación errónea --}}
                @error('stock')
                    <div class="alert alert-danger w-100 mt-2">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- Input stock minimo --}}
            <div class="form-group col-md-4">
                <label for="min_stock">Stock mínimo:</label>
                <input wire:model='min_stock' id="min_stock" type="number" class="form-control"
                    placeholder="Stock mínimo">

                {{-- Directiva de error en caso de validación errónea --}}
                @error('min_stock')
                    <div class="alert alert-danger w-100 mt-2">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- Input Fecha vencimiento --}}
            <div class="form-group col-md-4">
                <label for="expiration_date">Fecha vencimiento:</label>
                <input wire:model='expiration_date' id="expiration_date" type="date" class="form-control"
                    placeholder="Fecha vencimiento">

                {{-- Directiva de error en caso de validación errónea --}}
                @error('expiration_date')
                    <div class="alert alert-danger w-100 mt-2">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- checkbox Active --}}
            <div class="form-group col-md-3">
                <div class="icheck-primary">
                    <input wire:model='active' type="checkbox" id="active" checked>
                    <label for="active">
                        ¿Está activo?
                    </label>
                </div>

                {{-- Directiva de error en caso de validación errónea --}}
                @error('active')
                    <div class="alert alert-danger w-100 mt-2">
                        {{ $message }}
                    </div>
                @enderror
            </div>

        </div>
        <hr>
        <button class="btn btn-primary float-right">
            {{ $Id == 0 ? 'Guardar' : 'Editar' }}
        </button>
    </form>

</x-modal>
