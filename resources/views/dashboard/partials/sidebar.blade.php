<div id="sidebar" class="fixed inset-y-0 left-0 w-64 bg-gray-800 text-white p-4 transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out z-50">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Admin Navigation</h2>
        <button id="closeSidebar" class="md:hidden p-2 focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>
    <ul>
        <li class="mb-4">
            <a href="{{ route('dashboard.admin') }}" class="block p-2 rounded hover:bg-gray-600 {{ Route::is('dashboard.admin') ? 'bg-gray-600' : '' }}">Dashboard</a>
        </li>
        <li class="mb-4">
            <a href="{{ route('dashboard.lowstock-purchases') }}" class="block p-2 rounded hover:bg-gray-600 {{ Route::is('dashboard.lowstock-purchases') ? 'bg-gray-600' : '' }}">Low Stock & Purchases</a>
        </li>
        <li>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-left p-2 rounded hover:bg-red-600">Logout</button>
            </form>
        </li>
    </ul>
</div>
<!-- Overlay for mobile when sidebar is open -->
<div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 hidden md:hidden z-40"></div>