 {{-- Llamamos al componente de mi card --}}
 <x-card cardTitle="Detalles categoría">
    <x-slot:cardTools>
        <a href="{{ route('categories') }}" class="btn btn-primary">
            <i class="fas fa-arrow-circle-left">
                Regresar a la Categoría
            </i>
        </a>
    </x-slot:cardTools>

    {{-- @dump($category) --}}

    <div class="row">
        <div class="col-md-4">
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <h2 class="profile-username text-center">
                        {{ $category->name }}
                    </h2>
                    <ul class="list-group mb-3">
                        <li class="list-group-item">
                            <b>Productos</b> <a class="float-right">{{ count($category->products) }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Artículos</b> <a class="float-right">{{ $products->sum('stock') }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Producto</th>
                        <th>Precio venta</th>
                        <th>Stock</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($category->products as $product)
                        <tr>
                            <td> {{ $product->id }} </td>
                            <td> {{ $product->name }} </td>
                            <td> {{ $product->purchase_price }} </td>
                            <td> {!! $product->stockLabel !!} </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-card>
