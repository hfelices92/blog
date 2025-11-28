<x-layouts.admin>
    <div class="mb-4 flex justify-between items-center">

        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{ route('admin.dashboard') }}">Dashboard</flux:breadcrumbs.item>
            <flux:breadcrumbs.item href="{{ route('admin.permissions.index') }}">{{__('Permissions')}}</flux:breadcrumbs.item>
            <flux:breadcrumbs.item href="#">{{__('Edit Permission')}}</flux:breadcrumbs.item>
        </flux:breadcrumbs>
    </div>


   
    <form class="space-y-4 bg-white px-6 rounded-lg shadow-lg py-6" method="POST"
        action="{{ route('admin.permissions.update', $permission) }}">
        @csrf
        @method('PUT')
        <flux:input name="name" label="Nombre" value="{{ old('name', $permission->name) }}" />
        <div class="flex justify-end">
            <flux:button type="submit" class="cursor-pointer" variant="primary">Guardar</flux:button>
        </div>
        </from>
   
</x-layouts.admin>
