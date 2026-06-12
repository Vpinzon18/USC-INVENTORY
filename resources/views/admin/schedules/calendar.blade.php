<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Cronograma Visual Power BI') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-4 bg-blue-50 border-l-4 border-blue-400 p-4 rounded-md flex justify-between items-center shadow-sm">
                <div class="text-sm text-blue-700">
                    <span class="font-bold">¿El reporte no carga?</span> 
                    Por restricciones del navegador, primero debes validar tu sesión de Office 365.
                </div>
                <a href="https://app.powerbi.com/reportEmbed?reportId=d2a6c881-052d-4b28-8500-61b4116b46c6&autoAuth=true&ctid=47e962fd-bc3b-4eed-b1b8-d70eee9acd65" 
                   target="_blank"
                   class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm transition-colors shadow">
                    Validar Sesión
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; max-width: 100%;">
                        <iframe 
                            style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: 0;"
                            title="usc" 
                            src="https://app.powerbi.com/reportEmbed?reportId=d2a6c881-052d-4b28-8500-61b4116b46c6&autoAuth=true&ctid=47e962fd-bc3b-4eed-b1b8-d70eee9acd65" 
                            frameborder="0" 
                            allowFullScreen="true"
                            sandbox="allow-scripts allow-popups allow-popups-to-escape-sandbox allow-same-origin allow-forms">
                        </iframe>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</x-app-layout>