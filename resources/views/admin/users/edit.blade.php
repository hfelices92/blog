<x-layouts.admin>
    <div class="mb-4 flex justify-between items-center">

        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{ route('admin.dashboard') }}">Dashboard</flux:breadcrumbs.item>
            <flux:breadcrumbs.item href="{{ route('admin.users.index') }}">{{ __('Users') }}</flux:breadcrumbs.item>
            <flux:breadcrumbs.item href="#">{{ __('Edit User') }}</flux:breadcrumbs.item>
        </flux:breadcrumbs>
    </div>



    <form class="space-y-4  px-6 rounded-lg shadow-lg py-6" method="POST"
        action="{{ route('admin.users.update', $user) }}">
        @csrf
        @method('PUT')
        <flux:input name="name" label="Nombre" value="{{ old('name', $user->name) }}" />
        <flux:input name="email" label="Correo Electrónico" type="email" value="{{ old('email', $user->email) }}" />
        <flux:input name="password" label="Contraseña" type="password" value="" />
        <flux:input name="password_confirmation" label="Confirmar Contraseña" type="password" value="" />



        <ul>
            @foreach ($roles as $role)
                <li class="mb-2">
                    <label class="inline-flex items-center">
                        <input @checked(in_array($role->id, old('roles', $user->roles->pluck('id')->toArray()))) type="checkbox" name="roles[]" value="{{ $role->id }}"
                            class="form-checkbox h-5 w-5 text-blue-600">
                        <span class="ml-2 text-body capitalize">{{ $role->name }}</span>
                    </label>
                </li>
            @endforeach
        </ul>




        <div class="flex justify-end">
            <flux:button type="submit" class="cursor-pointer" variant="primary">Actualizar</flux:button>
        </div>
        </from>

</x-layouts.admin>
