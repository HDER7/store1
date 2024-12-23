<div x-data="{
    mobileFiltersOpen: false,
    priceRange: @entangle('priceRange'),
    updatePrice(min, max) {
        this.priceRange = [parseInt(min), parseInt(max)];
        $wire.set('priceRange', this.priceRange);
    }
}" class="bg-white">
    <!-- Mobile filter dialog -->
    <div x-show="mobileFiltersOpen"
         class="fixed inset-0 flex z-40 lg:hidden"
         x-cloak>
        <div x-show="mobileFiltersOpen"
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-black bg-opacity-25"
             @click="mobileFiltersOpen = false"></div>

        <div x-show="mobileFiltersOpen"
             x-transition:enter="transition ease-in-out duration-300 transform"
             x-transition:enter-start="translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in-out duration-300 transform"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="translate-x-full"
             class="ml-auto relative max-w-xs w-full h-full bg-white shadow-xl py-4 pb-6 flex flex-col overflow-y-auto">
            <!-- Filtros móviles aquí -->
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-baseline justify-between pt-6 pb-6 border-b border-gray-200">
            <h1 class="text-2xl font-bold tracking-tight text-gray-900">Productos</h1>

            <div class="flex items-center">
                <div class="relative inline-block text-left">
                    <select wire:model="sortBy"
                            class="border-gray-300 rounded-md text-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="newest">Más recientes</option>
                        <option value="price_asc">Precio: Menor a Mayor</option>
                        <option value="price_desc">Precio: Mayor a Menor</option>
                        <option value="oldest">Más antiguos</option>
                    </select>
                </div>

                <button type="button"
                        class="p-2 -m-2 ml-4 sm:ml-6 text-gray-400 hover:text-gray-500 lg:hidden"
                        @click="mobileFiltersOpen = true">
                    <span class="sr-only">Filtros</span>
                    <svg class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-x-8 gap-y-10 pt-6">
            <!-- Filtros Desktop -->
            <div class="hidden lg:block">
                <div class="space-y-6">
                    <!-- Búsqueda -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Buscar</label>
                        <div class="mt-1">
                            <input type="text"
                                   wire:model.debounce.300ms="search"
                                   class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>

                    <!-- Categorías -->
                    <div>
                        <h3 class="text-sm font-medium text-gray-700">Categorías</h3>
                        <div class="mt-2 space-y-2">
                            @foreach($categories as $category)
                                <label class="inline-flex items-center">
                                    <input type="checkbox"
                                           wire:model="selectedCategories"
                                           value="{{ $category->id }}"
                                           class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                    <span class="ml-2 text-sm text-gray-600">{{ $category->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Colores -->
                    <div>
                        <h3 class="text-sm font-medium text-gray-700">Colores</h3>
                        <div class="mt-2 space-y-2">
                            @foreach($this->availableColors as $color)
                                <label class="inline-flex items-center">
                                    <input type="checkbox"
                                           wire:model="selectedColors"
                                           value="{{ $color }}"
                                           class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                    <span class="ml-2 text-sm text-gray-600">{{ $color }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Tallas -->
                    <div>
                        <h3 class="text-sm font-medium text-gray-700">Tallas</h3>
                        <div class="mt-2 space-y-2">
                            @foreach($this->availableSizes as $size)
                                <label class="inline-flex items-center">
                                    <input type="checkbox"
                                           wire:model="selectedSizes"
                                           value="{{ $size }}"
                                           class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                    <span class="ml-2 text-sm text-gray-600">{{ $size }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Rango de Precio -->
                    <div x-data="{ min: @entangle('priceRange.0'), max: @entangle('priceRange.1') }">
                        <h3 class="text-sm font-medium text-gray-700">Precio</h3>
                        <div class="mt-2 space-y-4">
                            <div class="flex items-center space-x-2">
                                <input type="number"
                                       x-model="min"
                                       @change="updatePrice(min, max)"
                                       class="block w-24 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <span>-</span>
                                <input type="number"
                                       x-model="max"
                                       @change="updatePrice(min, max)"
                                       class="block w-24 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                        </div>
                    </div>

                    <!-- Botón Reset -->
                    <button wire:click="resetFilters"
                            class="w-full bg-gray-200 text-gray-800 py-2 px-4 rounded-md hover:bg-gray-300">
                        Limpiar Filtros
                    </button>
                </div>
            </div>

            <!-- Grid de Productos -->
            <div class="lg:col-span-3">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($products as $product)
                        <div class="group relative">
                            <div class="aspect-square w-full overflow-hidden rounded-md bg-gray-200 group-hover:opacity-75">
                                <img src="{{ $product->image }}"
                                     alt="{{ $product->name }}"
                                     class="h-full w-full object-cover object-center">
                            </div>
                            <div class="mt-4 flex justify-between">
                                <div>
                                    <h3 class="text-sm text-gray-700">
                                        <a href="{{ route('products.show', $product) }}">
                                            {{ $product->name }}
                                        </a>
                                    </h3>
                                </div>
                                <p class="text-sm font-medium text-gray-900">${{ number_format($product->price, 2) }}</p>
                            </div>
                            <button wire:click="addToCart({{ $product->id }})"
                                    class="mt-2 w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700">
                                Agregar al carrito
                            </button>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-12">
                            <p class="text-gray-500">No se encontraron productos con los filtros seleccionados.</p>
                        </div>
                    @endforelse
                </div>

                <div class="mt-8">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
