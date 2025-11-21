<x-layouts.admin>
    <div class="mb-4 ">

        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{ route('admin.dashboard') }}">Dashboard</flux:breadcrumbs.item>
            <flux:breadcrumbs.item href="{{ route('admin.posts.index') }}">Posts</flux:breadcrumbs.item>

            <flux:breadcrumbs.item>Crear</flux:breadcrumbs.item>

        </flux:breadcrumbs>

    </div>



    <form class="space-y-4  px-6 rounded-lg shadow-lg py-6" method="POST" action="{{ route('admin.posts.store') }}">
        @csrf
        <flux:input name="title" label="Título" oninput="string_to_slug(this.value, '#slug')" value="{{ old('title') }}"  />
        <flux:input id="slug" name="slug" label="Slug" />
        <flux:select wire:model="category" placeholder="Seleccionar categoría" label="Categoría" name="category_id">

            @foreach ($categories as $category)
                <flux:select.option :selected="$category->id === old('category_id')" class="capitalize" value="{{ $category->id }}">{{ $category->name }}
                </flux:select.option>
            @endforeach
        </flux:select>
        <div class="flex justify-end">
            <flux:button type="submit" class="cursor-pointer" variant="primary">Guardar</flux:button>
        </div>
        </from>

       

</x-layouts.admin>
