<x-layouts.admin>
    <div class="mb-4 flex justify-between items-center">

        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{ route('admin.dashboard') }}">Dashboard</flux:breadcrumbs.item>
            <flux:breadcrumbs.item href="#">Posts</flux:breadcrumbs.item>
        </flux:breadcrumbs>
        <a href="{{ route('admin.posts.create') }}" class="btn btn-blue">Crear Nuevo Post</a>
    </div>


    <div class="relative overflow-x-auto bg-neutral-primary-soft shadow-xs rounded-base border border-default">
        <table class="w-full text-sm text-left rtl:text-right text-body">
            <thead class="text-sm text-body bg-neutral-secondary-soft border-b rounded-base border-default">
                <tr class="bg-slate-800 text-white">
                    <th scope="col" class="px-6 py-3 font-medium">
                        ID
                    </th>
                    <th scope="col" class="px-6 py-3 font-medium">
                        Título
                    </th>
                    <th scope="col" class="px-6 py-3 font-medium text-center" width="150">
                        Opciones
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($posts as $post)
                    <tr class="border-b hover:bg-neutral-primary-soft/50">

                        <td class="px-6 py-4">
                            {{ $post->id }}
                        </td>
                        <td class="px-6 py-4 font-medium text-body capitalize">
                            {{ $post->title }}
                        </td>
                        <td class="flex gap-2 items-center px-6 py-4">
                            <a href="{{ route('admin.posts.edit', $post) }}"
                                class="btn btn-green hover:underline">Editar</a>

                            <form action="{{ route('admin.posts.destroy', $post) }}" method="POST"
                                class="delete-form">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-red">
                                    Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach

            </tbody>
        </table>
    </div>

    <div class="mt-4  w-6/12" >
        {{ $posts->links() }}
    </div>
    @push('js')
        <script>
            document.querySelectorAll('.delete-form').forEach(form => {
                form.addEventListener('submit', function(event) {
                    event.preventDefault();
                    Swal.fire({
                        title: "¿Estás seguro?",
                        text: "No podrás revertir esto.",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Sí, eliminar!",
                        cancelButtonText: "Cancelar"
                    }).then((result) => {
                        if (result.isConfirmed) {

                            form.submit();
                        }
                    });
                });
            });
        </script>
    @endpush
</x-layouts.admin>
