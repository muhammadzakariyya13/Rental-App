<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Browse Properties') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium mb-4">{{ __('Available Properties') }}</h3>
                    
                    <div class="mt-4">
                        <!-- Search form -->
                        <form class="mb-6">
                            <div class="flex gap-4 flex-wrap">
                                <input type="text" placeholder="Search by location or property name" 
                                    class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 block mt-1 w-full dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                                    Search
                                </button>
                            </div>
                        </form>
                        
                        <!-- Properties grid will go here -->
                        <p>Property browsing interface will be implemented here.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
