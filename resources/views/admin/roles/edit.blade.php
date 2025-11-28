<x-layouts.admin>
    <div class="mb-4 flex justify-between items-center">

        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{ route('admin.dashboard') }}">Dashboard</flux:breadcrumbs.item>
            <flux:breadcrumbs.item href="{{ route('admin.roles.index') }}">{{__('Roles')}}</flux:breadcrumbs.item>
            <flux:breadcrumbs.item href="#">{{__('Edit Roles')}}</flux:breadcrumbs.item>
        </flux:breadcrumbs>
    </div>


   
    <form class="space-y-4  px-6 rounded-lg shadow-lg py-6" method="POST"
        action="{{ route('admin.roles.update', $role) }}">
        @csrf
        @method('PUT')
        <flux:input name="name" label="Nombre" value="{{ old('name', $role->name) }}" />
            <div>
            <p class="text-sm font-medium mb-4">Permisos</p>

            <ul>
                @foreach ($permissions as $permission)
                    <li class="mb-2">
                        <label class="inline-flex items-center">
                            <input @checked(in_array($permission->id, old('permissions', $role->permissions->pluck('id')->toArray()))) type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                                class="form-checkbox h-5 w-5 text-blue-600">
                            <span class="ml-2 text-body capitalize">{{ $permission->name }}</span>
                        </label>
                    </li>
                    
                @endforeach
            </ul>
        </div>
        <div class="flex justify-end">
            <flux:button type="submit" class="cursor-pointer" variant="primary">Guardar</flux:button>
        </div>
        </from>
   
</x-layouts.admin>
