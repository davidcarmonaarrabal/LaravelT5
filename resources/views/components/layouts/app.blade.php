<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title> {{ $title ?? config('app.name') }} </title>

    <!-- Estilos: lo llamo desde el componente -->
    @include('components.layouts.partials.styles')
</head>

<body class="hold-transition dark-mode sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
    <div class="wrapper">

        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__wobble" src="dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60"
                width="60">
        </div>

        <!-- Navbar: lo llamamos desde el componente -->
        @include('components.layouts.partials.navbar')
        <!-- /.navbar -->

        <!-- Main Sidebar Container: lo llamamos desde el componente -->
        @include('components.layouts.partials.sidebar')

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header: lo llamo desde el componente -->

            <!-- /.content-header -->
            @include('components.layouts.partials.content-header')

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">

                    @livewire('messages')

                    {{ $slot }}

                    <!-- /.row -->
                </div><!--/. container-fluid -->
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->



        <!-- Main Footer: lo llamo desde el componente -->
        @include('components.layouts.partials.footer')
    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->
    @include('components.layouts.partials.scripts')
    <!-- PLUGINS -->

    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('close-modal', (idModal) => {
                $('#' + idModal).modal('hide');
            })
        });

        // Modal para editar
        document.addEventListener('livewire:init', () => {
            Livewire.on('open-modal', (idModal) => {
                $('#' + idModal).modal('show');
            })
        });

        // Modal para el sweetAlert
        document.addEventListener('livewire:init', () => {
            Livewire.on('delete', (e) => {
                // alert(e.id+'-'+e.eventname)
                Swal.fire({
                    title: "¿Estas seguro?",
                    text: "Esta acción no se puede revertir!!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Sí, eliminar categoría!",
                    cancelButtonText: "Cancelar"
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Emitimos evento para eliminar registro si se acepta
                        Livewire.dispatch(e.eventname, { id: e.id })
                    }
                });

            })
        });
    </script>

</body>

</html>
