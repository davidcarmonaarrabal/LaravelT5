<div>
    {{-- Llamo al componente de mi card --}}
    <x-card cardTitle="Listado productos ({{ $this->totalRegistros }})">
        <x-slot:cardTools>
            <a href="#" class="btn btn-primary" wire:click='create'>
                <i class="fas fa-plus-circle"></i> Crear Producto
            </a>
        </x-slot>

        <x-table>
            <x-slot:thead>
                <th>ID</th>
                <th>Nombre</th>
                <th>Precio venta</th>
                <th>Stock</th>
                <th>Categoría</th>
                <th>Estado</th>
                <th width="3%">...</th>
                <th width="3%">...</th>
                <th width="3%">...</th>
            </x-slot:thead>

            @forelse ($products as $product)
                <tr>
                    <td> {{ $product->id }} </td>
                    <td> {{ $product->name }} </td>
                    <td> {!! $product->priceLabel !!} </td>
                    <td> {!! $product->stockLabel !!} </td>
                    <td>
                        <a class="badge badge-secondary" href="{{ route('categories.show', $product->categories->id) }}">
                            {{ $product->categories->name }}
                        </a>
                    </td>
                    <td> {!! $product->activeLabel !!} </td>
                    <td>
                        <a href="{{ route('products.show', $product) }}" title="ver" class="btn btn-success btn-sm">
                            <i class="far fa-eye"></i>
                        </a>
                    </td>

                    <td>
                        <a href="#" wire:click='edit( {{ $product->id }})' title="editar"
                            class="btn btn-primary btn-sm">
                            <i class="far fa-edit"></i>
                        </a>
                    </td>

                    <td>
                        {{-- Emito evento delete==sweetAlert --}}
                        <a wire:click="$dispatch('delete', {id: {{ $product->id }}, 
                                       eventname:'destroyProduct'})"
                            title="eliminar" class="btn btn-danger btn-sm">
                            <i class="far fa-trash-alt"></i>
                        </a>
                    </td>
                </tr>
            @empty
                <tr class="text-center">
                    <td colspan="10">No hay registros que mostrar</td>
                </tr>
            @endforelse
        </x-table>

        {{-- Añadimos links de paginación --}}
        <x-slot:cardFooter>
            {{ $products->links() }}
        </x-slot>

    </x-card>

    {{-- Llamada al modal --}}
    @include('products.modal')
</div>
