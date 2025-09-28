<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Property Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium mb-4">{{ __('Manage Your Properties') }}</h3>
                    
                    <div class="mt-4">
                        <!-- Properties list table will go here -->
                        <p>Property management interface will be implemented here.</p>
                        
                        <div class="mt-4">
                            <a href="#" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                                Add New Property
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
