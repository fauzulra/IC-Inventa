<aside class="flex flex-col h-full bg-white overflow-hidden border-r border-gray-200">

    <nav class="flex-1 min-h-0 space-y-1 overflow-y-auto pb-4 custom-scrollbar">

        <a href="{{ route('dashboard') }}"
            class="group flex items-center text-sm font-medium text-gray-800 transition-colors duration-200 {{ request()->routeIs('dashboard') ? 'bg-[#D9D9D9]' : 'hover:bg-gray-100' }}">
            <div class="flex items-center text-gray-600">
                <div class="px-6 py-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                    </svg>
                </div>
                <span class="font-medium">Dashboard</span>
            </div>
        </a>

        <a href="{{ route('project.index') }}"
            class="group flex items-center text-sm font-medium text-gray-800 transition-colors duration-200 {{ request()->routeIs('project.*') ? 'bg-[#D9D9D9]' : 'hover:bg-gray-100' }}">
            <div class="flex items-center text-gray-600">
                <div class="px-6 py-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 0 0 .75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.111 48.111 0 0 0-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0 1 12 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 0 1-.673-.38m0 0A2.18 2.18 0 0 1 3 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 0 1 3.413-.387m7.5 0V5.25A2.25 2.25 0 0 0 13.5 3h-3a2.25 2.25 0 0 0-2.25 2.25v.894m7.5 0a48.667 48.667 0 0 0-7.5 0M12 12.75h.008v.008H12v-.008Z" />
                    </svg>
                </div>
                <span class="font-medium">Proyek</span>
            </div>
        </a>

        <a href="{{ route('supplier.index') }}"
            class="group flex items-center text-sm font-medium text-gray-800 transition-colors duration-200 {{ request()->routeIs('supplier.*') ? 'bg-[#D9D9D9]' : 'hover:bg-gray-100' }}">
            <div class="flex items-center text-gray-600">
                <div class="px-6 py-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                    </svg>
                </div>
                <span class="font-medium">Pemasok</span>
            </div>
        </a>

        <div class="group/material relative">
            <button
                class="w-full flex items-center justify-between text-sm font-medium text-gray-800 transition-colors duration-200 {{ request()->routeIs('material.*') ? 'bg-[#D9D9D9]' : 'hover:bg-gray-200 group-hover/material:bg-gray-200' }}">
                <div class="flex items-center text-gray-600">
                    <div class="px-6 py-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
                        </svg>
                    </div>
                    <span class="font-medium">Material Item</span>
                </div>
                <div class="px-3 text-gray-500">
                    <svg id="chevron-material" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                        fill="currentColor"
                        class="w-5 h-5 transition-transform duration-300 group-hover/material:rotate-180 {{ request()->routeIs('material.*') ? 'rotate-180' : '' }}">
                        <path fill-rule="evenodd"
                            d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
            </button>

            <div id="submenu-material"
                class="flex-col bg-gray-50/50 overflow-hidden transition-all duration-300 ease-in-out {{ request()->routeIs('material.*') ? 'flex' : 'hidden group-hover/material:flex' }}">

                {{-- PERBAIKAN: Tambahkan 'material.project.show' --}}
                <a href="{{ route('material.index') }}"
                    class="pl-4 text-sm font-medium transition-colors duration-200 {{ request()->routeIs('material.index', 'material.project.show') ? 'bg-[#D9D9D9]' : 'hover:bg-gray-200' }}">
                    <div class="flex items-center text-gray-600">
                        <div class="px-6 py-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
                            </svg>
                        </div>
                        <span class="font-medium">Data Master</span>
                    </div>
                </a>

                {{-- PERBAIKAN: Tambahkan 'material.order.show' --}}
                <a href="{{ route('material.order') }}"
                    class="pl-4 text-sm font-medium text-gray-800 transition-colors duration-200 {{ request()->routeIs('material.order', 'material.order.show') ? 'bg-[#D9D9D9]' : 'hover:bg-gray-200' }}">
                    <div class="flex items-center text-gray-600">
                        <div class="px-6 py-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                            </svg>
                        </div>
                        <span class="font-medium">Pemesanan</span>
                    </div>
                </a>

                {{-- PERBAIKAN: Tambahkan 'material.confirmation.show' --}}
                <a href="{{ route('material.confirmation') }}"
                    class="pl-4 text-sm font-medium text-gray-800 transition-colors duration-200 {{ request()->routeIs('material.confirmation', 'material.confirmation.show') ? 'bg-[#D9D9D9]' : 'hover:bg-gray-200' }}">
                    <div class="flex items-center text-gray-600">
                        <div class="px-6 py-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                        </div>
                        <span class="font-medium">Konfirmasi</span>
                    </div>
                </a>
            </div>
        </div>

        <div class="group/transfer relative">
            <button
                class="w-full flex items-center justify-between text-sm font-medium text-gray-800 transition-colors duration-200 {{ request()->routeIs('itemtransfer.*') ? 'bg-[#D9D9D9]' : 'hover:bg-gray-200 group-hover/transfer:bg-gray-200' }}">
                <div class="flex items-center text-gray-600">
                    <div class="px-6 py-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19.5 12c0-1.232-.046-2.453-.138-3.662a4.006 4.006 0 0 0-3.7-3.7 48.678 48.678 0 0 0-7.324 0 4.006 4.006 0 0 0-3.7 3.7c-.017.22-.032.441-.046.662M19.5 12l3-3m-3 3-3-3m-12 3c0 1.232.046 2.453.138 3.662a4.006 4.006 0 0 0 3.7 3.7 48.656 48.656 0 0 0 7.324 0 4.006 4.006 0 0 0 3.7-3.7c.017-.22.032-.441.046-.662M4.5 12l3 3m-3-3-3 3" />
                        </svg>
                    </div>
                    <span class="font-medium">Transfer Barang</span>
                </div>
                <div class="px-3 text-gray-500">
                    <svg id="chevron-transfer" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                        fill="currentColor"
                        class="w-5 h-5 transition-transform duration-300 group-hover/transfer:rotate-180 {{ request()->routeIs('itemtransfer.*') ? 'rotate-180' : '' }}">
                        <path fill-rule="evenodd"
                            d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
            </button>

            <div id="submenu-transfer"
                class="flex-col bg-gray-50/50 overflow-hidden transition-all duration-300 ease-in-out {{ request()->routeIs('itemtransfer.*') ? 'flex' : 'hidden group-hover/transfer:flex' }}">

                @if (auth()->user()->hasRole('admin'))
                    <a href="{{ route('itemtransfer.index') }}"
                        class="pl-4 text-sm font-medium transition-colors duration-200 {{ request()->routeIs('itemtransfer.index') ? 'bg-[#D9D9D9]' : 'hover:bg-gray-200' }}">
                        <div class="flex items-center text-gray-600">
                            <div class="px-6 py-2">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
                                </svg>
                            </div>
                            <span class="font-medium">Data Master</span>
                        </div>
                    </a>
                @endif

                {{-- PERBAIKAN: Tambahkan 'itemtransfer.order.show' --}}
                <a href="{{ route('itemtransfer.order') }}"
                    class="pl-4 text-sm font-medium text-gray-800 transition-colors duration-200 {{ request()->routeIs('itemtransfer.order', 'itemtransfer.order.show') ? 'bg-[#D9D9D9]' : 'hover:bg-gray-200' }}">
                    <div class="flex items-center text-gray-600">
                        <div class="px-6 py-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                            </svg>
                        </div>
                        <span class="font-medium">Pemesanan</span>
                    </div>
                </a>

                {{-- PERBAIKAN: Tambahkan 'itemtransfer.confirmation.show' --}}
                <a href="{{ route('itemtransfer.confirmation') }}"
                    class="pl-4 text-sm font-medium text-gray-800 transition-colors duration-200 {{ request()->routeIs('itemtransfer.confirmation', 'itemtransfer.confirmation.show') ? 'bg-[#D9D9D9]' : 'hover:bg-gray-200' }}">
                    <div class="flex items-center text-gray-600">
                        <div class="px-6 py-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                        </div>
                        <span class="font-medium">Konfirmasi</span>
                    </div>
                </a>
            </div>
        </div>
        <a href="{{ route('incominggood.index') }}"
            class="group flex items-center text-sm font-medium text-gray-800 transition-colors duration-200 {{ request()->routeIs('incominggood.*') ? 'bg-[#D9D9D9]' : 'hover:bg-gray-100' }}">
            <div class="flex items-center text-gray-600">
                <div class="px-6 py-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M12 3v13.5M8.25 12.75 12 16.5l3.75-3.75" />
                    </svg>
                </div>
                <span class="font-medium">Barang Masuk</span>
            </div>
        </a>

        <a href="{{ route('outgoinggood.index') }}"
            class="group flex items-center text-sm font-medium text-gray-800 transition-colors duration-200 {{ request()->routeIs('outgoinggood.*') ? 'bg-[#D9D9D9]' : 'hover:bg-gray-100' }}">
            <div class="flex items-center text-gray-600">
                <div class="px-6 py-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" />
                    </svg>
                </div>
                <span class="font-medium">Barang Keluar</span>
            </div>
        </a>

        @if (auth()->user()->hasRole('admin'))
            <a href="{{ route('users.index') }}"
                class="group flex items-center text-sm font-medium text-gray-800 transition-colors duration-200 {{ request()->routeIs('users.*') ? 'bg-[#D9D9D9]' : 'hover:bg-gray-100' }}">
                <div class="flex items-center text-gray-600">
                    <div class="px-6 py-2">
                        <!-- Ikon User Edit / Pengguna -->
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                        </svg>
                    </div>
                    <span class="font-medium">Kelola Akun</span>
                </div>
            </a>
        @endif

    </nav>

    <div class="shrink-0 border-t border-gray-200 p-4 bg-white">
        <a href="#" class="flex items-center mb-4">
            <div
                class="shrink-0 flex items-center justify-center h-10 w-10 rounded-full bg-gray-200 text-gray-600 font-bold uppercase">
                {{ substr(auth()->user()->name, 0, 2) }}
            </div>
            <div class="ml-4">
                <p class="text-sm font-bold text-gray-900">{{ ucwords(auth()->user()->name) }}</p>
                <p class="text-xs font-medium text-gray-500 capitalize">
                    {{ str_replace('_', ' ', auth()->user()->roles->first()->name ?? 'User') }}
                </p>
            </div>
        </a>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit"
                class="group flex items-center w-full text-sm font-bold text-gray-700 hover:text-red-600 transition-colors duration-200">
                <i class="fas fa-sign-out-alt text-xl px-1 ml-2 mr-5 text-gray-500 group-hover:text-red-600"></i>
                Keluar
            </button>
        </form>
    </div>
</aside>

<script>
    // Script ini opsional jika Anda ingin kembalikan fungsi klik manual di masa depan
    function toggleDropdown(menuId, iconId) {
        const menu = document.getElementById(menuId);
        const icon = document.getElementById(iconId);

        if (menu && icon) {
            menu.classList.toggle('max-h-0');
            menu.classList.toggle('opacity-0');
            menu.classList.toggle('max-h-96');
            menu.classList.toggle('opacity-100');

            icon.classList.toggle('rotate-180');
        }
    }
</script>
