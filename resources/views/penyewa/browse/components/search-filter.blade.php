<!-- Search & Filter Section -->
<div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-8">
    <form class="space-y-4" method="GET">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Cari Properti</label>
                <input type="text" name="search" placeholder="Nama atau lokasi properti..." 
                    value="{{ request('search') }}"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tipe Properti</label>
                <select name="tipe" onchange="this.form.submit()" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent cursor-pointer">
                    <option value="">Semua Tipe</option>
                    <option value="rumah" {{ request('tipe') === 'rumah' ? 'selected' : '' }}>Rumah</option>
                    <option value="apartemen" {{ request('tipe') === 'apartemen' ? 'selected' : '' }}>Apartemen</option>
                    <option value="kontrakan" {{ request('tipe') === 'kontrakan' ? 'selected' : '' }}>Kontrakan</option>
                    <option value="vila" {{ request('tipe') === 'vila' ? 'selected' : '' }}>Vila</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition">
                    🔍 Cari
                </button>
            </div>
        </div>
    </form>
</div>
