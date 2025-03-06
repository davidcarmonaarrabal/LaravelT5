# Índice de la Guía de Laravel Tema 5

## 1. [Cómo crear el proyecto](#1-cómo-crear-el-proyecto)
- Creación del proyecto
- Configuración de la base de datos

## 2. [Creando la Base de Datos](#2-creando-la-base-de-datos)
- Ejecución de migraciones
- 2.1. [Categorías y Productos](#21-categorías-y-productos)
  - Creación de modelos y migraciones
  - Seeder para categorías y productos
  - Relaciones en los modelos
  - Factorías de datos
- 2.2. [Clientes, Sales y Users](#22-clientes-sales-y-users)
  - Creación de modelos y migraciones
  - Seeder para clientes, ventas y usuarios
  - Relaciones en los modelos
  - Factorías de datos
- 2.3. [Items](#23-items)
  - Creación de modelos y migraciones
  - Relaciones entre Items y Productos
  - Factorías de datos
- 2.4. [ItemsSales](#24-itemssales)
  - Creación de modelos y migraciones
  - Relaciones entre ItemsSales y otros modelos
  - Factorías de datos

## 3. [Descargamos e importamos la plantilla](#3-descargamos-e-importamos-la-plantilla)
- Configuración de autenticación
- Integración de Bootstrap
- Uso de la plantilla AdminLTE

## 4. [Empezamos a crear componentes para hacer que la plantilla funcione con nuestros propios datos](#4-empezamos-a-crear-componentes-para-hacer-que-la-plantilla-funcione-con-nuestros-propios-datos)
- 4.1. [Crear la ruta a categorías y su vista inicial](#41-crear-la-ruta-a-categorías-y-su-vista-inicial)
- 4.2. [Creamos una categoría](#42-creamos-una-categoría)
- 4.3. [Mostrando las categorías](#43-mostrando-las-categorías)
- 4.4. [Editando una categoría](#44-editando-una-categoría)
- 4.5. [Viendo las características de las categorías](#45-viendo-las-características-de-las-categorías)
- 4.6. [Eliminando categorías](#46-eliminando-categorías)

## 5. [Productos](#5-productos)

## 1. Cómo crear el proyecto.
Empezamos creando el proyecto con `sudo composer create-project laravel/laravel ventas` y `cd ventas` `sudo composer require livewire/livewire`, `composer require laravel/jetstream` y `php artisan jetstream:install livewire`. Abrimos en visual y configuramos la BBDD:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ventas
DB_USERNAME=root
DB_PASSWORD=
```

## 2 Creando la Base de Datos.
Y la creamos con `php artisan migrate`. Tras esto procedemos a crear todas sus **tablas y relaciones**.

### 2.1. Categorías y Productos.

Empezamos con `php artisan make:model categories -m` y `php artisan make:seeder CategoriesSeeder`, y editamos la migración:

```
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id()->primary();
            $table->string('name');
            $table->timestamps();
        });
    }
```

Y añadimos el seeder en el `DatabaseSeeder`:

```
    public function run(): void
    {
        User::factory()->create([
            CategoriesSeeder::class,
        ]);
    }
}
```

Y ahora hacemos lo mismo con `php artisan make:model products -m` y `php artisan make:seeder ProductsSeeder`:

```
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id()->primary();
            $table->string('name');
            $table->string('description')->nullable();
            $table->decimal('purchase_price', 10, 2);
            $table->decimal('selling_price', 10, 2);
            $table->integer('stock');
            $table->integer('min_stock');
            $table->date('expiration_date');
            $table->boolean('active');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->timestamps();
        });
    }
```

```
    public function run(): void
    {
        \App\Models\User::factory()->create([
            'name' => 'Abbigail Jerde',
            'email' => 'jeanne.cummerata@example.com',
            'email_verified_at' => now(),
            'password' => '$2y$12$u4nxfyG6ItBBlEnASHZkSuVTAGz6PkFg9MEbv1wBAD/sN2r77UaZm',
            'remember_token' => 'f9Z6FtZG3a',
        ]);

        $this->call([
            CategoriesSeeder::class,
            ProductsSeeder::class,
        ]);
    }
}
```

Y ahora escribimos las **relaciones en los Modelos**:

***app/Models/Categories.php***:

```
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Categories extends Model
{
    use HasFactory;
    protected $fillable = ['name'];

    public function products(): HasMany
    {
        return $this->hasMany(products::class);
    }
}
```

***app/Models/Products.php***:

```
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Products extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'categoria_id'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Categories::class);
    }
}
```

Y comprobamos *si todo funciona* con una **Factoría de datos** con `php artisan make:factory ProductsFactory --model=Products` y `php artisan make:factory CategoriesFactory --model=Categories` y escribimos:

***database/factories/CategoriesFactory.php***:

```
<?php

namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoriesFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(), 
        ];
    }
}
```

***database/factories/ProductsFactory.php***:
```
<?php

namespace Database\Factories;

use App\Models\Categories;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Products>
 */
class ProductsFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence(),
            'description' => $this->faker->text(100),
            'purchase_price' => $this->faker->randomFloat(2, 1, 100),
            'selling_price' => $this->faker->randomFloat(2, 1, 100),
            'stock' => $this->faker->numberBetween(1, 1000),
            'min_stock' => $this->faker->numberBetween(1, 1000),
            'expiration_date' => $this->faker->date(),
            'active' => $this->faker->boolean(),
            'category_id' => Categories::factory(),
        ];
    }
}
```

Y nos vamos a los **seeders** para llamar a las factorías:

***database/seeders/CategoriesSeeder.php***:

```
    public function run(): void
    {
        Categories::factory(10)->create();
    }
}
```

***database/seeders/ProductsSeeder.php***:

```
    public function run(): void
    {
        Products::factory(10)->create();
    }
}
```

### 2.2. Clientes, Sales y Users.

Y ejecutamos ahora `php artisan migrate:fresh --seed` y comprobámos en la **BBDD** que todo exista. Tras esto, procedemos a crear el modelo **Clientes** con `php artisan make:model clients -m` y  `php artisan make:seeder ClientSeeder` y modificamos la migración y ***añadimos el seeder al DatabaseSeeder***:

```
public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('identify');
            $table->string('telephone', 9);
            $table->string('email');
            $table->string('company');
            $table->timestamps();
        });
    }
```

Y pasamos a crear el **seeder** de **Users** con `php artisan make:seeder UsersSeeder` y editamos la **migración de Users**, para posteriormente añadir el seeder al padre:

```
Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->boolean('admin');
            $table->boolean('active');
            $table->timestamps();
        });
```

Ahora creamos la tabla ventas con `php artisan make:model Sales -m` y con `php artisan make:seeder SalesSeeder` y añadimos al seeder al padre después de modificar la migración:

```
public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->decimal('total', 10, 2);
            $table->decimal('payment');
            $table->date('date');
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }
```

Y ahora modificamos el modelo ***app/Models/Clients.php***:

```
class Clients extends Model
{
    protected $fillable = ['name',
                            'identify',
                            'telephone',
                            'email',
                            'company'];
    use HasFactory;

    public function sales(): HasMany
    {
        return $this->hasMany(Sales::class);
    }
}
```

Y el ***app/Models/Sales.php***:

```
class Sales extends Model
{
    protected $fillable = ['total',
                            'payment'];
    
    use HasFactory;

    public function client(): BelongsTo
    {
        return $this->belongsTo(Clients::class);
    }
}
```

Y ahora hacemos las **factorías de los 3**:

`php artisan make:factory ClientsFactory`:
```
    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence(),
            'identify' => $this->faker->sentence(),
            'telephone' => $this->faker->numerify('#########'),
            'email' => $this->faker->safeEmail,
            'company' => $this->faker->sentence(),
        ];
    }
}
```

`php artisan make:factory UsersFactory`:
```
    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence(),
            'email' => $this->faker->safeEmail,
            'password' => Hash::make('password'),
            'admin' => $this->faker->boolean(),
            'active' => $this->faker->boolean(),
        ];
    }
}
```

`php artisan make:factory SalesFactory`:
```
    public function definition(): array
    {
        return [
            'total' => $this->faker->randomFloat(2, 1, 100),
            'payment' => $this->faker->randomFloat(2, 1, 100),
            'date' => $this->faker->date(),
            'client_id' => Clients::factory(),
            'user_id' => User::factory(),
        ];
    }
}
```

Y ahora nos vamos a los **3 seeder para llamar a las factorías**:

```
    public function run(): void
    {
        User::factory(10)->create();
    }
}
```

```
    public function run(): void
    {
        Clients::factory(10)->create();
    }
}
```

```
    public function run(): void
    {
        Sales::factory(10)->create();
    }
}
```

Nuestro **DatabaseSeeder**:

```
    public function run(): void
    {
        $this->call([
            UsersSeeder::class,
            CategoriesSeeder::class,
            ProductsSeeder::class,
            ClientsSeeder::class,
            SalesSeeder::class,
        ]);
    }
}
```

### 2.3. Items.

Y ejecutamos `php artisan migrate:fresh --seed` y comprobamos la **BBDD**. Pasamos ahora a crear **Items** con `php artisan make:model Items -m` y `php artisan make:seeder ItemsSeeder`, editando la migración, añadiendo las **FK** y lo añadimos al seeder padre:

```
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('image');
            $table->decimal('price', 10, 2);
            $table->integer('qty');
            $table->date('date');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }
```

Ahora procedemos a hacer la relación ***Items - Productos***

***app/Models/Items.php***:
```
class Items extends Model
{
    protected $fillable = ['name', 'product_id'];
    use HasFactory;

    public function item(): BelongsTo
    {
        return $this->belongsTo(Items::class);
    }
}
```

***app/Models/Products.php***:
```
class Products extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'categoria_id'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Categories::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(Items::class);
    }
}
```

Y ahora le hacemos la factoría con `php artisan make:factory ItemsFactory` y llamamos a esta en el seeder:

```
    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence(),
            'image' => $this->faker->sentence(),
            'price' => $this->faker->randomFloat(2, 1, 100),
            'qty' => $this->faker->randomNumber(1),
            'date' => $this->faker->date(),
            'product_id' => Products::factory(),
        ];
    }
}
```

### 2.4. ItemsSales.

Y hacemos un `php artisan migrate:fresh --seed` y procedemos a hacer la tabla **ItemSales** con `php artisan make:model ItemSales -m` y `php artisan make:seeder ItemSalesSeeder` y editamos la migración y la añadimos al seeder padre:

```
    public function up(): void
    {
        Schema::create('item_sales', function (Blueprint $table) {
            $table->id();
            $table->integer('qty');
            $table->date('date');
            $table->foreignId('items_id')->constrained()->onDelete('cascade');
            $table->foreignId('sales_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }
```

Y modificamos los modelos:

***app/Models/ItemSales.php***:

```
class ItemSales extends Model
class ItemSales extends Model
{
    protected $fillable = [ 'items_id', 'sales_id'];
    use HasFactory;

    public function sales(): HasMany
    {
        return $this->hasMany(Sales::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(Items::class);
    }
}
```

***app/Models/Sales.php***:

```
    public function item_sales(): HasMany
    {
        return $this->hasMany(ItemSales::class);
    }
}
```

***app/Models/Items.php***:
```
    public function item_sale(): HasMany
    {
        return $this->hasMany(ItemSales::class);
    }
}
```

Y para comprobar el funcionamiento hacemos `php artisan make:factory ItemSalesFactory`, lo rellenamos y lo llamamos en el seeder:

```
    public function definition(): array
    {
        return [
            'qty' => $this->faker->randomNumber(1),
            'date' => $this->faker->date(),
            'items_id' => Items::factory(),
            'sales_id' => Sales::factory(),
        ];
    }
}
```

## 3. Descargamos e importamos la plantilla.

Y ejecutamos `php artisan migrate:fresh --seed`. Ahora, vamos a crear una autenticación, hacemos `composer require laravel/ui`, `php artisan ui bootstrap`, `php artisan ui bootstrap --auth` y `npm install && npm run dev` y el **login nos dará un error**.

Ahora, nos vamos a ***https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css*** y descargamos ese archivo, para meterlo en ***public/css/bootstrap.min.css*** y luego nos vamos a ***resources/views/layouts/app.blade.php*** y dejamos el código así, comentando una línea y añadiendo otra:

```
{{-- @vite(['resources/sass/app.scss', 'resources/js/app.js']) --}}
    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
```

Y ahora **nos funciona el login**.

A este punto, usaremos la plantilla de ***adminLte*** para hacer una web parecida a:

![image](https://github.com/user-attachments/assets/e95532a7-1b64-4f10-9f52-133512d794e8)

Y descargamos el .zip de **Moodle** ***(https://drive.google.com/file/d/1fqfkAV_eW3d50Wnjo3j8lXnrTlRdaeZB/view)*** y nos cogemos las carpetas **dist** y **plugins** y las pegamos en ***/public*** y ejecutamos un `php artisan livewire:layout` y eso nos genera un ***resources/views/components/layouts/app.blade.php*** y sustituimo todo el código por el **index.html**. Ahora, nos vamos a la **línea 160** e indicamos el ***slot*** para nuestros componentes:

```
    <section class="content">
      <div class="container-fluid">
            {{ $slot }}
      </div>
    </section>
```

## 4. Empezamos a crear componentes para hacer que la plantilla funcione con nuestros propios datos.

### 4.1. Crear la ruta a categorías y su vista inicial.

Y **creamos un componente de livewire que corresponde al inicio** con `php artisan make:livewire Home/inicio` y ahora, la forma en la que cargamos los componentes en el **$slot** es mediante rutas en ***routes/web.php***:

```
<?php

use App\Livewire\Home\Inicio;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/inicio', Inicio::class)->name('inicio');
```

Y ahora, todo lo que modifiquemos en ***resources/views/livewire/home/inicio.blade.php*** lo podremos ver en `:8000/inicio`, por ejemplo:

```
<div>
    <h1>Inicio</h1>
</div>
```

![image](https://github.com/user-attachments/assets/e9b67c3c-3908-4ddf-8b49-640fedd61c53)

Ahora, organizamos el código para que sea **eficientemente reusable**, para ello, dentro de ***views/components/layouts***, creamos una carpeta denomidada **partials** y pegamos **la carpeta partials de su proyecto** y como hemos codigo el código hecho no tenemos que modificar nada, también nos podemos traer el **app.blade.php** que tiene hecha ella, simplemente importa los componentes que hemos copiado antes, pero nos dará errores, porque estos componentes llaman a ***rutas que no tenemos aún definidas y a su vez, llaman a componentes no creados aún, vamos ahora***.

Empezamos en ***app/Livewire/Home/Inicio.php*** y metemos debajo de `use Livewire/Component` la siguiente línea `use Livewire\Attributes\Title;` y la siguiente `#[Title('Inicio test')]` y cosas como cambiar el título de la pestaña para que lo haga automáticamente se hace en el ***app.blade.php*** y en el **Title** dejamos `<title> {{ $title ?? config('app.name') }} </title>`.

Ahora, creamos **componentes anónimo**, son archivos de blade que se ubican en la carpeta components, vamos a crear uno para los **cards de Bootstrap**, creamos un archivo en ***views/components/card.blade.php*** y metemos el siguiente código:

```
{{-- Inicializamos variables en vacío --}}
@props(['cardTitle' => '',
        'cardTools' => '',
        'cardFooter' => ''])

<div class="card">
    <div class="card-header">
        <h3 class="card-title"> {{ $cardTitle }}</h3>
        <div class="card-tools">
            {{ $cardTools }}
        </div>
    </div>

    <div class="card-body">
        {{ $slot }}
    </div>

    <div class="card-footer">
        <div class="float-right">
            {{ $cardFooter }}
        </div>
    </div>
</div>
```

Ahora, en ***inicio.blade.php*** llamamos a `<x-card></x-card>` y, el código anterior ya está hecho para que el contenido sea variable y se reciba, pero tenemos que terminar de definir la llamada a **x-card**:

```
<div>
    <h1>Inicio</h1>

    <x-card cardTitle="Card Title" cardFooter="Card Footer">
        <x-slot:cardTools>
            <a href="#" class="btn btn-primary">Crear</a>
        </x-slot:cardTools>
        Contenido
    </x-card>
</div>
```

Ahora, creamos una tabla en ***views/components/table.blade.php***:

```
<div class="mb-3 d-flex justify-content-between">
    <div>
        <span> Mostrar</span>
        <select wire:model.live='cant'>
            <option value="5">5</option>
            <option value="10">10</option>
            <option value="15">15</option>
            <option value="20">20</option>
            <option value="100">100</option>
        </select>
        <span>Entradas</span>
    </div>
    <div>
        <input type="text"
               wire:model.live='search'
               class="form-control"
               placeholder="Buscar...">
    </div>
</div>

<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead>
            <tr> {{ $thead }} </tr>
        </thead>

        <tbody>
            {{ $slot }}
        </tbody>
    </table>
</div>
```

Y dejamos el inicio así:

```
<div>
    <h1>Inicio</h1>

    <x-card cardTitle="Card Title" cardFooter="Card Footer">
        <x-slot:cardTools>
            <a href="#" class="btn btn-primary">Crear</a>
        </x-slot:cardTools>
        <x-table>
            <x-slot:thead>
                <th>thead</th>
                <th>thead</th>
            </x-slot>

            <tr>
                <td>...</td>
                <td>...</td>
            </tr>
        </x-table>
    </x-card>
</div>
```

Bien, ahora, si has estado ejecutando el código hasta ahora, como dije antes, te va a dar error, es ahora cuando empezamos con ello, empezamos generando el componente de **CategoryComponent** con `php artisan make:livewire Category/CategoryComponent` y le creamos la ruta `Route::get('/categories', CategoryComponent::class)->name('categories');` y en ***resources/views/livewire/category/category-component.blade.php***, el ***CategoryComponent.php*** debe quedar así:

```
<?php

namespace App\Livewire\Category;

use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Categorías')]

class CategoryComponent extends Component
{
    public function render()
    {
        return view('livewire.category.category-component');
    }
}
```

Ahora, incluiremos **card** y **tabla** en el componente creado, copiando la estructura de ***inicio.blade.php*** y la pegamos en ***category-component.blade.php***. Nuestro documento debe quedar así:

```
<div>
    {{-- llamamos al componente de mi card --}}
    <x-card cardTitle="Listado Categorias" cardFooter="Card Footer">
        <x-slot:cardTools>
            <a href="#" class="btn btn-primary" wire:click='create'>
                Crear categoria</a>
        </x-slot:cardTools>

        <x-table>
            <x-slot:thead>
                <th>ID</th>
                <th>Nombre</th>
                <th width="3%">...</th>
                <th width="3%">...</th>
                <th width="3%">...</th>
            </x-slot>
                <tr>
                    <td>...</td>
                    <td>...</td>
                    <td>
                        <a href="#" title="ver" class="btn btn-success btn-xs">
                            <i class="far fa-eye"></i>
                        </a>
                    </td>

                    <td>
                        <a href="#" title="editar"
                            class="btn btn-primary btn-xs">
                            <i class="far fa-edit"></i>
                        </a>
                    </td>

                    <td>
                        <a href="#"
                            title="eliminar" class="btn btn-danger btn-xs">
                            <i class="far fa-trash-alt"></i>
                        </a>
                    </td>
                </tr>
        </x-table>

    </x-card>
</div>
```

Ahora, si queremos que nos funcione para ver algo visual, debemos ir a ***sidebar.blade.php*** y donde llama a la ruta **productos quitarlo o comentarlo**, igual en ***app.blade.php*** comentamos de momento la línea `{{-- @livewire('messages') --}}` y ya si nos vamos a categorías veremos esto:

![image](https://github.com/user-attachments/assets/33d72094-eef5-4e4c-a2f7-226920f94830)

### 4.2. Creamos una categoría.

Ahora vamos a crear un botón para **Crear una categoría** y para ello debemos crear un modal con **Bootstrap**, eligiendo el *Live Demo*, lo primero es crear un componente, copiando todo el modal menos el botón, lo llamaremos ***modal.blade.php*** junto a *table* y *card*:

```
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                ...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>
```

Ahora, en el botón de **Crear Categoría**, añadimos ***data-toggle*** y ***data-target*** en ***category-component.blade.php***:

```
<x-slot:cardTools>
            <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                Crear categoria</a>
        </x-slot:cardTools>
```

Aunque, para llamar al modal y que funcione debemos bajar al final del archivo, y antes de cerrar con `</div>` añadimos `<x-modal></x-modal>`, ahora nos abre el modal, pero tenemos que definir las variables del componente, para que sea dinámico:

```
@props(['modalTitle' => '',
        'modalId' => '',
        'modalSize' => ''
])

<!-- Modal -->
<div wire:ignore.self
     class="modal fade" id="{{ $modalId }}" tabindex="-1" 
     role="dialog" aria-labelledby="exampleModalLabel" 
     aria-hidden="true"
     data-backdrop="static"
     data-keyboard="false">  

     <div class="modal-dialog {{ $modalSize }}">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ $modalTitle }}</h5>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>            
            </div>
            <div class="modal-body">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
```

Pero para que esto funcione, tenemos que hacer que el botón cuando es clicado mande información al modal en el ***category-component.blade.php*** con `<x-modal modalId="modalCategory" modalTitle="Categorías"></x-modal>` al final del archivo y ahora creamos un formulario para enviar el modal:

```
    <x-modal modalId="modalCategory" modalTitle="Categorías">
        <form>
            <div class="row">
                <div class="col">
                    <input type="text" class="form-control" placeholder="Nombre Categoría:">
                </div>
            </div>
            <hr>
            <button class="btn btn-primary float-right">
                Save changes
            </button>
        </form>
    </x-modal>
```

Pero para que esto funcione, tenemos que cambiar el `data-target="#modalCategory"` en el `<a></a>`. Con esto, en el listado de categorías indicaremos cuantas hay, por lo que creamos una variable que los muestre en el **modelo**:

```
class CategoryComponent extends Component
{
    public $totalRegistros=0;
    
    public function render()
    {
        return view('livewire.category.category-component');
    }

    public function mount(){
        $this->totalRegistros = Categories::count();
    }
}
```

Y tenemos que poner en ***category-component.blade.php*** `<x-card cardTitle="Listado Categorias ( {{ $totalRegistros }})" cardFooter="Card Footer">`. Ahora toca crear el formulario del modal, haciendo los siguientes pasos:

**Creamos el método** en ***CategoryComponent.php***:
```
public function store() {
        dump('Crear Category');
    }
```

**Llamamos al método dentro de nuestro form**:
```
<form wire:submit="store">
```

Y ahora al darle a `Save Changes` nos salta la función **dump()** en pantalla, por lo que ahora, realizamos la vista del formulario y sus validaciones, para esto, modificamos el **modelo**:

```
    public function store() {
        $rules = [
            'name' => 'required | min:5 | max:255 | unique:categories',
        ];
        $messages = [
            'name.required' => 'EL nombre es requerido',
            'name.min' => 'El nombre debe tener mínimo 5 caracteres',
            'name.max' => 'El nombre no debe superar 255 caracteres',
            'name.unique' => 'El nombre de la categoría ya está en uso'
        ];

        $this->validate($rules, $messages);
    }
}
```

Y los mostramos en la vista:

```
    <x-modal modalId="modalCategory"
    modalTitle="Categorías">
        <form wire:submit="submit">
            <div class="form-row">
                <div class="form-group col-12">
                
                    <label for="name">Nombre:</label>
                    <input wire:model='name'
                            id="name"
                            type="text"
                            class="form-control"
                            placeholder="Nombre Categoría:">

                            @error('name')
                                <div class="alert alert-danger w-100 mt-2">
                                    {{ $message }}
                                </div>
                            @enderror   
                </div>
            </div>
            <hr>
            <button class="btn btn-primary float-right">
                        Save changes
            </button>
        </form>
    </x-modal>
```

Y por último, almacenamos el registro en la BBDD, para ello hacemos la lógica necesaria:

```
public function store() {
        $rules = [
            'name' => 'required | min:5 | max:255 | unique:categories',
        ];
        $messages = [
            'name.required' => 'EL nombre es requerido',
            'name.min' => 'El nombre debe tener mínimo 5 caracteres',
            'name.max' => 'El nombre no debe superar 255 caracteres',
            'name.unique' => 'El nombre de la categoría ya está en uso'
        ];

        $this->validate($rules, $messages);

        $category = new Categories();
        $category->name= $this->name;
        $category->save();
    }
```

Y ahora queremos que se cierre el modal al guardar una categoría al meter en el modelo, en la función **store()** un `$this->dispatch('close-modal', 'modalCategory)`. Ahora mostraremos un mensaje que indique que se creó a través de un componente, con `php artisan make:livewire Messages` y le metemos:

```
<div>
    @if ( session()->has('msg') )
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Mensaje!</strong> 
                {{ session('msg') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
</div>
```

Y ahora lo descomentamos en ***app.blade.php***, también, en ***CategoryComponent.php*** añadimos un `$this->dispatch('msg', 'Categoria creada correctamente');` y en el ***app/Livewire/Messages.php*** ponemos:

```
<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;

class Messages extends Component
{
    public function render()
    {
        return view('livewire.messages');
    }

    //Cuando se emita el evento (On) se ejecutará la función
    #[On('msg')]
    public function msgs($msg) {
        session()->flash('msg', $msg);
    }
}
```

Y ahora, hacemos que se actualice el **contador y y que se resetee el menú de texto donde escribimos la categoría**:

```
<?php

namespace App\Livewire\Category;

use App\Models\Categories;
use Livewire\Attributes\Title;
use Livewire\Component;


#[Title('Categorías')]

class CategoryComponent extends Component
{
    public $totalRegistros=0;
    public $name;

    public function render(){
        $this->totalRegistros = Categories::count();

        return view('livewire.category.category-component');
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
}
```

### 4.3. Mostrando las categorías.

Ahora vamos a hacer que se listen las categorías recogiéndolas de la **BBDD**, para ello, nos vamos al **Modelo** de ***CategoryComponent.php*** y en render:

```
public function render(){
        $this->totalRegistros = Categories::count();

        $categories = Categories::all()->reverse();

        return view('livewire.category.category-component',
        [
            'categories' => $categories
        ]);
    }
```

A parte, necesitamos un **for else** en la tabla para mostrarlos:

```
<x-table>
            <x-slot:thead>
                <th>ID</th>
                <th>Nombre</th>
                <th width="3%">...</th>
                <th width="3%">...</th>
                <th width="3%">...</th>
            </x-slot>
            @forelse ($categories as $category)
            <tr>
                <td> {{ $category->id }} </td>
                <td> {{ $category->name }} </td>
                <td>
                    <a href="#"
                       title="ver"
                       class="btn btn-success btn-sm">
                        <i class="far fa-eye"></i>
                    </a>
                </td>

                <td>
                    <a href="#"
                       wire:click='edit( {{ $category->id }})'
                       title="editar"
                       class="btn btn-primary btn-sm">
                        <i class="far fa-edit"></i>
                    </a>
                </td>

                <td>
                       {{-- Emito evento delete==sweetAlert --}}
                    <a wire:click="$dispatch('delete', {id: {{ $category->id }}, 
                                   eventname:'destroyCategory'})"
                       title="eliminar"
                       class="btn btn-danger btn-sm">
                        <i class="far fa-trash-alt"></i>
                    </a>
                </td>
            </tr>
        @empty
            <tr class="text-center"> 
                <td colspan="5">No hay registros que mostrar</td>
            </tr>
        @endforelse
        </x-table>
```

Y con esto, nos lo cargará, pero para que se pagine, en el **Modelo** debemos añadir:

```
class CategoryComponent extends Component
{
    public $totalRegistros=0;
    public $name;
    use WithPagination;


    public function render(){
        $this->totalRegistros = Categories::count();

        $categories = Categories::orderBy('id', 'desc')->paginate(5);

        return view('livewire.category.category-component',
        [
            'categories' => $categories
        ]);
    }
```

Y ahora añadimos los botones de la paginación:

```
<x-slot:cardFooter>
            {{ $categories->links() }}
        </x-slot>
```

Ahora, nuestra paginación estará usando **Tailwind**, pero debemos indicar a **Livewire** que use **Bootstrap**, para ello, ejecutamos `php artisan livewire:publish --config` y veremos el archivo ***livewire.php*** en la **ruta indicada en la terminal**, dentro de este, en `'pagination_theme' => 'bootstrap',` lo debemos dejar así.

Ahora, ubicamos la paginación en el lateral derecho, para ello nos vamos a ***resources/views/components/card.blade.php*** y:

```
<div class="card-footer">
        <div class="float-right">
            {{ $cardFooter }}
        </div>
    </div>
```

Pero, **si hemos copiado el código como yo, esto ya está hecho**. Ahora vamos a crear el buscar, para ello:

***CategoryComponent.php***:

```
class CategoryComponent extends Component
{
    public $totalRegistros=0;
    public $name;
    use WithPagination;
    public $search="";


    public function render(){
        if($this->search != '') {
            $this->resetPage();
        }

        $this->totalRegistros = Categories::count();

        $categories = Categories::where('name','like','%'.$this->search.'%')->orderBy('id', 'desc')->paginate(5);

        return view('livewire.category.category-component',
        [
            'categories' => $categories
        ]);
    }
```

***table.blade.php***:

```
<div>
        <input type="text"
               wire:model.live='search'
               class="form-control"
               placeholder="Buscar...">
    </div>
```

Esto segundo **ya lo teníamos**, y *ya tenemos búsqueda dinámica*. Ahora queremos crear un **botón con el número de entidades a mostrar**, por lo que en el **Modelo**:

```
class CategoryComponent extends Component
{
    public $totalRegistros=0;
    public $name;
    use WithPagination;
    public $search="";
    public $cant=5;


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
```

### 4.4. Editando una categoría.

Y como ya tenemos copiado el código, el botón ya aparece y llama a actualizar **cant**.

Ahora, creamos en el **Modelo** un atributo `public $Id;` en mayúscula obligatoriamente, y creamos la siguiente función edit:

```
    public function edit(Categories $category) {
        $this->Id = $category->id;

        $this->name = $category->name;

        $this->dispatch('open-modal', 'modalCategory');
    }
```

Y con esto, pasamos al **Componente** y añadimos `<form wire:submit={{ $Id == 0 ? "store" : "update($Id)" }}>` y en el **Modelo** creamos el *update*:

```
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
```

Y para que el texto de la categoría modificada no se quede guardado al crear una después, cambiamos esto al principio del **Componente**:

```
<x-slot:cardTools>
            <a  href="#" 
                class="btn btn-primary" 
                wire:click='create'>
                Crear categoria</a>
        </x-slot:cardTools>
```

Pero debemos crear esa función en el **Modelo**:

```
   public function create() {
        $this->Id = 0;

        $this->reset(['name']);

        $this->resetErrorBag();

        $this->dispatch('open-modal', 'modalCategory');
    }
```

Con el `$this->resetErrorBag();` prevenimos de que, si por ejemplo, *le damos a crear una categoría, introducimos algo que no es válido, nos da el mensaje de error, sin ese fragmento de código, si cerrásemos el modal y lo abriésemos, seguiría apareciendo el error, pero al poner eso, desaparece junto al modal*.

### 4.5. Viendo las características de las categorías.

Ahora vamos a crear un componente para *ver los detalles de la categoría al clicar en el ojo*, para ello, `php artisan make:livewire Category/CategoryShow'` y añadimos la siguiente ruta `Route::get('/categories/{category}, CategoryShow::class)->name('categories.show');` y probamos a meter algo en el componente a ver si funciona `<h1>Ver categoría</h1>` y hacemos que el botón llame en el ***category-component.blade.php*** con `<a href="{{ route('categories.show', $category)}}"` y puede que nos de un error en ***resources/views/layouts/partials/content-header.blade.php*** nos de un error, para ello, en el modelo ***CategoryShow.php*** metemos `#[Title('Ver Categoría')]` antes de la *clase*.

Ahora, procedemos a modificar el **Componente** para añadirle información:

```
<x-card cardTitle="Detalles Categoría">
    <x-slot:cardTools>
        <a href="{{ route('categories') }}" class="btn btn-primary">
            <i class="fas fa-arrow-circle-left">
                Regresar a la Categoría
            </i>
        </a>
    </x-slot:cardTools>
</x-card>
```

Y en el **Modelo** añadimos:

```
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
```

En el **componente**:

```
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
```

Y en las **migración de productos cambiamos** `$table->foreignId('categories_id')->constrained('categories')->onDelete('cascade');` porque sino nos da un error.

### 4.6. Eliminando categorías.

Ahora, para que se pueda eliminar una categoría, como *ya tenemos el código copiado*, sólo tenemos que poner el método que borra la categoría en el **Modelo**:

```
#[On('destroyCategory')]
    public function destroy($id){
            /*Si no encuentra la categoría, muestra página NOT FOUND */
            $category = Categories::findOrfail($id);
            
            /* Eliminamos categoria */
            $category->delete();

            /* Emitir evento para ceruse App\Models\Categories;rar modal */
            $this->dispatch('msg', 'Categoría borrada correctamente');
    }
```

### 5. Productos.

Empezamos con un `php artisan make:livewire Product/ProductComponent` y en el **Modelo** añadimos `#[Title('Productos')]`, añadimos la ruta `Route::get('/products', ProductComponent::class)->name('products');` y lo añadimos en el ***sidebar.blade.php***:

```
<li class="nav-item">
                  <a href="{{ route('products') }}" class="nav-link">
                      <i class="nav-icon fas fa-tshirt"></i>
                      <p>
                          Productos
                      </p>
                  </a>
              </li>
```

Y con esto ya accedemos a el, pero necesitamos una *vista* que muestre dichos productos, para ello, creamos el componente con `php artisan make:livewire Product/ProductShow`, añadiendo de nuevo la ruta `Route::get('/products/${product}', ProductShow::class)->name('products.show');` y ahora del proyecto de Carmen ***nos podemos copiar los siguientes archivos: ProductComponent.php, product-component.blade.php, ProductShow.php y product-show.blade.php*** y la carpeta ***resources/views/products*** con su contenido.

Aún así, vamos a tener varios pequeños fallos, ya que el de Carmen se llama *Category* y el nuestro es *Categories*, por lo que debemos irnos al ***Modelo Products.php*** y cambiar `public function categories(): BelongsTo`, luego, en el modelo ***ProductsComponent.php*** y sustituimos todos las llamadas de una por otra, por último, en ***products-component.blade.php*** debemos tener:

```
<a class="badge badge-secondary" href="{{ route('categories.show', $product->categories->id) }}">
                            {{ $product->categories->name }}
                        </a>
```

Y por último, en la migración de producto añadir `$table->bigInteger('bar_code');` y en la factoría `'bar_code' => $this->faker->ean13(),`.

Con esto, tenemos el proyecto terminado hasta donde está el **PDF**.
