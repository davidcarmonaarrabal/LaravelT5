{{-- Llamamos al componente de mi card --}}
<x-card cardTitle="Detalles producto">
    <x-slot:cardTools>
        <a href="{{ route('products') }}" class="btn btn-primary">
            <i class="fas fa-arrow-circle-left">
                Regresar al Producto
            </i>
        </a>
    </x-slot>

    <!-- Default box -->
    <div class="card card-solid">
        <div class="card-body">
            <div class="col-12 ">
                <h3 class="my-3">{{ $product->name }}</h3>
                <p>{{ $product->description }}</p>

                <hr>

                <div class="row">
                    <!-- Caja stock -->
                    <div class="col-md-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-info">
                                <i class="fas fa-box-open"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text">Stock</span>
                                <span class="info-box-number">{!! $product->stockLabel !!}</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                    </div>

                    <!-- Caja stock minimo-->
                    <div class="col-md-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-info">
                                <i class="fas fa-box-open"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text">Stock minimo</span>
                                <span class="info-box-number">
                                    <span class="badge badge-success">
                                        {{ $product->min_stock }}
                                    </span>
                                </span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                    </div>

                    <!-- Caja categoria -->
                    <div class="col-md-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-info">
                                <i class="fas fa-th-large"></i>
                            </span>
                            <div class="info-box-content">

                                <span class="info-box-text">Categoria</span>
                                <span class="info-box-number">{{ $product->category_id }}</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                    </div>

                    <!-- Caja estado -->
                    <div class="col-md-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-info">
                                <i class="fas fa-toggle-on"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text">Estado</span>
                                <span class="info-box-number">{!! $product->activeLabel !!}</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                    </div>

                    <!-- Caja codigo barras -->
                    <div class="col-md-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-info">
                                <i class="fas fa-barcode"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text">Codigo barras</span>
                                <span class="info-box-number">{{ $product->bar_code }}</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                    </div>

                    <!-- Caja fecha vencimiento -->
                    <div class="col-md-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-info">
                                <i class="fas fa-calendar-times"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text">Fecha vencimiento</span>
                                <span class="info-box-number">{{ $product->expiration_date }}</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="bg-lightblue py-2 px-3 mt-4 col-md-6">
                        <h2 class="mb-0">
                            {!! $product->priceLabel !!}
                        </h2>
                        <h4 class="mt-0">
                            <small>Precio venta </small>
                        </h4>
                    </div>
                    <div class="bg-gray py-2 px-3 mt-4 col-md-6">
                        <h2 class="mb-0">
                            {{ $product->purchase_price }}
                        </h2>
                        <h4 class="mt-0">
                            <small>Precio compra</small>
                        </h4>
                    </div>
                </div>

            </div>
        </div>

    </div>


</x-card>
