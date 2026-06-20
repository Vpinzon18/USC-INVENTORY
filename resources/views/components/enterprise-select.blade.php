@props(['name', 'placeholder', 'endpoint', 'initialValue' => '', 'initialText' => ''])

<div x-data="enterpriseSelect({
        endpoint: '{{ $endpoint }}',
        value: '{{ $initialValue }}',
        text: '{{ $initialText }}'
     })"
     class="relative flex flex-col gap-1 w-full"
     @click.away="closeMenu()">

    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide">{{ $placeholder }}</label>

    <button type="button" @click="toggleMenu()" 
            class="flex items-center justify-between w-full px-3 py-2.5 text-sm text-left bg-white border rounded-lg shadow-sm focus:outline-none transition-colors" 
            :class="open ? 'border-blue-500 ring-2 ring-blue-100' : 'border-slate-300 hover:border-blue-400'">
        
        <span x-text="text || 'Seleccionar...'" :class="text ? 'text-slate-800 font-medium truncate' : 'text-slate-400'"></span>
        
        <div class="flex items-center gap-1 shrink-0">
            <template x-if="value">
                <div @click.stop="clearSelection()" class="p-0.5 rounded-md hover:bg-slate-100 text-slate-400 hover:text-rose-500 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </div>
            </template>
            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
        </div>
    </button>

    <input type="hidden" name="{{ $name }}" :value="value">

    <div x-show="open" 
         x-transition.opacity.duration.200ms
         style="display: none;"
         class="absolute z-50 w-full md:w-[350px] top-full mt-2 bg-white border border-slate-200 rounded-xl shadow-2xl overflow-hidden flex flex-col">
        
        <div class="p-2 border-b border-slate-100 bg-slate-50 relative">
            <svg class="absolute left-4 top-4 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" x-model="search" x-ref="searchInput" @input.debounce.300ms="fetchData()" placeholder="Escribe para buscar..."
                   class="w-full pl-8 pr-3 py-1.5 text-sm border border-slate-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-inner bg-white">
        </div>

        <ul class="max-h-60 overflow-y-auto p-1 scroll-smooth">
            <li x-show="loading" class="p-4 text-center">
                <svg class="animate-spin h-5 w-5 text-blue-500 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
            </li>

            <li x-show="!loading && options.length === 0" class="p-4 text-center text-sm text-slate-500">
                No se encontraron resultados
            </li>

            <template x-for="option in options" :key="option.id">
                <li @click="selectOption(option)" 
                    class="px-3 py-2 rounded-lg cursor-pointer hover:bg-blue-50 border border-transparent hover:border-blue-100 transition-colors flex flex-col gap-0.5 group/item">
                    <span class="font-bold text-slate-700 text-sm group-hover/item:text-blue-700" x-text="option.primary"></span>
                    <span class="text-[10px] text-slate-500 uppercase font-medium tracking-tight" x-text="option.secondary"></span>
                </li>
            </template>
        </ul>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('enterpriseSelect', (config) => ({
        open: false,
        search: '',
        options: [],
        loading: false,
        endpoint: config.endpoint,
        value: config.value,
        text: config.text,

        toggleMenu() {
            this.open = !this.open;
            if (this.open) {
                // Focus automático en el buscador y carga inicial si está vacío
                setTimeout(() => this.$refs.searchInput.focus(), 50);
                if (this.options.length === 0) this.fetchData();
            }
        },
        closeMenu() {
            this.open = false;
        },
        clearSelection() {
            this.value = '';
            this.text = '';
            this.search = '';
        },
        selectOption(option) {
            this.value = option.id;
            this.text = option.primary;
            this.closeMenu();
        },
        async fetchData() {
            this.loading = true;
            try {
                // Petición AJAX con la búsqueda
                let response = await fetch(`${this.endpoint}?q=${encodeURIComponent(this.search)}`);
                this.options = await response.json();
            } catch (e) {
                console.error("Error cargando filtros:", e);
                this.options = [];
            }
            this.loading = false;
        }
    }))
})
</script>