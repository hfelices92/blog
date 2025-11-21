<x-layouts.admin>


    @push('css')
        <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    @endpush
    <div class="mb-4 ">

        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{ route('admin.dashboard') }}">Dashboard</flux:breadcrumbs.item>
            <flux:breadcrumbs.item href="{{ route('admin.posts.index') }}">Posts</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>Editar</flux:breadcrumbs.item>
        </flux:breadcrumbs>

    </div>



    <form method="POST" action="{{ route('admin.posts.update', $post) }}">
        @csrf
        @method('PUT')
        <div class="relative mb-2">
            <img id="imgPreview" class="w-10/12 mx-auto aspect-video object-cover object-center"
                src="https://t3.ftcdn.net/jpg/10/22/24/80/360_F_1022248039_7LDxHRi3Mlt9BK3wzLBUGZp9XAO1gt2s.jpg"
                alt="">
            <div class="absolute top-12 right-15">
                <label class="bg-slate-500 text-white px-4 py-2 rounded-sm cursor-pointer">
                    Cambiar Imagen
                    <input onchange="preview_image(event,'#imgPreview')" type="file" name="image" class="hidden"
                        accept="image" />
                </label>
            </div>
        </div>
        <div class="space-y-4 bg-white px-6 rounded-lg shadow-lg py-6">
            <flux:input name="title" label="Título" value="{{ old('title', $post->title) }}" />
            <flux:input id="slug" name="slug" label="Slug" value="{{ old('slug', $post->slug) }}" />
            <flux:select wire:model="category" placeholder="Seleccionar categoría" label="Categoría" name="category_id">

                @foreach ($categories as $category)
                    <flux:select.option :selected="$category->id === old('category_id', $post->category_id)"
                        class="capitalize" value="{{ $category->id }}">{{ $category->name }}
                    </flux:select.option>
                @endforeach
            </flux:select>

            <flux:textarea name="excerpt" label="Exctacto" rows="4">{{ old('excerpt', $post->excerpt) }}
            </flux:textarea>
            <div>
                <p class="font-medium text-sm mb-1">Etiquetas</p>
                <select id="tags" name="tags[]" multiple="multiple" class="w-full">
                    @foreach ($tags as $tag)
                        <option value="{{ $tag->name }}" @selected(in_array($tag->name, old('tags', $post->tags->pluck('name')->toArray())))>
                            {{ $tag->name }}
                        </option>
                    @endforeach


                </select>
            </div>
            <div>
                <p class="font-medium text-sm mb-1">Contenido</p>
                <div id="editor">{!! old('content', $post->content) !!}</div>
                <textarea name="content" class="hidden" id="content">{{ old('content', $post->content) }}</textarea>
            </div>
            <div>
                <p class="text-sm font-semibold">Estado</p>
                <div class="flex gap-3 mt-3">

                    <label class="flex flex-col  items-center">
                        Oculto
                        <input type="radio" name="is_published" value="0" @checked(old('is_published', $post->is_published) == '0') />
                    </label>

                    <label class="flex flex-col items-center">
                        Publicado
                        <input type="radio" name="is_published" value="1" @checked(old('is_published', $post->is_published) == '1') />
                    </label>
                </div>

            </div>

            <div class="flex justify-end">
                <flux:button type="submit" class="cursor-pointer" variant="primary">Guardar</flux:button>
            </div>
        </div>
        </from>

        @push('js')
            {{-- jQuery CDN --}}
            <script src="https://code.jquery.com/jquery-3.7.1.min.js"
                integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
            {{-- Quill CDN --}}
            <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
            {{-- Select2 CDN --}}
            <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
            <script>
                $(document).ready(function() {
                    $('#tags').select2({
                        tags: true,
                        placeholder: "Seleccionar etiquetas",
                        allowClear: true,
                        tokenSeparators: [',']

                    });
                });
            </script>

            <script>
                const quill = new Quill('#editor', {
                    theme: 'snow'
                });

                quill.on('text-change', function() {
                    document.getElementById('content').value = quill.root.innerHTML;
                });
            </script>
        @endpush

</x-layouts.admin>
