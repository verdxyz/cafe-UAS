<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Little Latte Cafe</title>
    
    <!-- Tailwind CSS (CDN) -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body {
            background-color: #efeee6;
            color: #252525;
        }
        ::selection {
            background-color: #252525;
            color: #efeee6;
        }
        
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
        }
    </style>
</head>
<body class="bg-[#efeee6] text-[#252525] antialiased selection:bg-[#252525] selection:text-[#efeee6] font-light min-h-screen hidden" id="appBody">

    <div class="mx-auto w-full max-w-[112rem] px-5 sm:px-8 lg:px-14 pb-20">
        
        <!-- HEADER / NAVIGATION -->
        <header class="flex items-center justify-between py-8 border-b border-[#252525]/20 mb-12">
            <div class="flex items-center gap-4">
                <!-- Motif Diamond -->
                <div class="size-4 bg-[#252525] rotate-45"></div>
                <div class="text-xl font-light uppercase tracking-tight">System Control</div>
            </div>
            
            <nav class="hidden md:flex items-center gap-9 font-light uppercase tracking-tight text-sm">
                <a href="#overview" onclick="switchTab('overview', this)" class="tab-link border-b border-[#252525]/50 pb-1">Overview</a>
                <a href="#menu" onclick="switchTab('menu', this)" class="tab-link opacity-60 hover:opacity-100 transition-opacity pb-1">Menu</a>
                <a href="#orders" onclick="switchTab('orders', this)" class="tab-link opacity-60 hover:opacity-100 transition-opacity pb-1">Orders</a>
                <a href="#reservations" onclick="switchTab('reservations', this)" class="tab-link opacity-60 hover:opacity-100 transition-opacity pb-1">Reservations</a>
            </nav>
            
            <div class="flex items-center gap-6">
                <span class="hidden md:block font-light uppercase tracking-tight text-xs text-[#252525]/60" id="adminName">Admin</span>
                <button onclick="logout()" class="font-light uppercase tracking-tight text-sm hover:opacity-60 transition-opacity flex items-center gap-2">
                    Log Out <i data-lucide="log-out" class="size-4" stroke-width="1.5"></i>
                </button>
            </div>
        </header>

        <!-- MAIN DASHBOARD -->
        <main>
            <div class="flex justify-between items-end mb-8">
                <h1 class="text-4xl lg:text-6xl font-light uppercase tracking-tight leading-none" id="pageTitle">Overview</h1>
                <div class="font-light uppercase tracking-tight text-sm text-[#252525]/60" id="currentDate"></div>
            </div>

            <!-- TAB: OVERVIEW -->
            <div id="tab-overview" class="tab-content active">
                <!-- STATS GRID -->
                <section class="grid grid-cols-2 lg:grid-cols-4 gap-8 mb-20">
                    <div class="border border-[#252525]/20 p-8 flex flex-col justify-between h-48 hover:bg-[#252525]/5 transition-colors cursor-default">
                        <div class="flex justify-between items-start">
                            <span class="font-light uppercase tracking-tight text-xs text-[#252525]/70">Total Revenue</span>
                            <i data-lucide="activity" class="size-5 text-[#252525]/50" stroke-width="1.5"></i>
                        </div>
                        <div class="text-5xl lg:text-6xl font-light uppercase tracking-tight" id="statRevenue">--</div>
                    </div>

                    <div class="border border-[#252525]/20 p-8 flex flex-col justify-between h-48 hover:bg-[#252525]/5 transition-colors cursor-default">
                        <div class="flex justify-between items-start">
                            <span class="font-light uppercase tracking-tight text-xs text-[#252525]/70">Orders Today</span>
                            <i data-lucide="coffee" class="size-5 text-[#252525]/50" stroke-width="1.5"></i>
                        </div>
                        <div class="text-5xl lg:text-6xl font-light uppercase tracking-tight" id="statOrders">--</div>
                    </div>

                    <div class="border border-[#252525]/20 p-8 flex flex-col justify-between h-48 hover:bg-[#252525]/5 transition-colors cursor-default">
                        <div class="flex justify-between items-start">
                            <span class="font-light uppercase tracking-tight text-xs text-[#252525]/70">Pending RSVPs</span>
                            <i data-lucide="calendar" class="size-5 text-[#252525]/50" stroke-width="1.5"></i>
                        </div>
                        <div class="text-5xl lg:text-6xl font-light uppercase tracking-tight" id="statRsvp">--</div>
                    </div>

                    <div class="border border-[#252525]/20 p-8 flex flex-col justify-between h-48 hover:bg-[#252525]/5 transition-colors cursor-default">
                        <div class="flex justify-between items-start">
                            <span class="font-light uppercase tracking-tight text-xs text-[#252525]/70">Active Menus</span>
                            <i data-lucide="grid-3x3" class="size-5 text-[#252525]/50" stroke-width="1.5"></i>
                        </div>
                        <div class="text-5xl lg:text-6xl font-light uppercase tracking-tight" id="statMenu">--</div>
                    </div>
                </section>

                <section class="mb-24">
                    <div class="flex justify-between items-center mb-8">
                        <h2 class="text-2xl lg:text-3xl font-light uppercase tracking-tight">Recent Dispatches</h2>
                        <button onclick="switchTab('orders', document.querySelectorAll('.tab-link')[2])" class="border border-[#252525] px-4 py-2 font-light uppercase tracking-tight text-xs hover:bg-[#252525] hover:text-[#efeee6] transition-colors">
                            View All
                        </button>
                    </div>

                    <div class="w-full overflow-x-auto">
                        <table class="w-full text-left font-light uppercase tracking-tight text-sm whitespace-nowrap">
                            <thead>
                                <tr class="border-b border-[#252525] text-[#252525]/60">
                                    <th class="py-4 font-light">Order ID</th>
                                    <th class="py-4 font-light">Customer</th>
                                    <th class="py-4 font-light">Item</th>
                                    <th class="py-4 font-light">Qty</th>
                                    <th class="py-4 font-light">Date</th>
                                    <th class="py-4 font-light text-right">Status</th>
                                </tr>
                            </thead>
                            <tbody id="overviewTableBody">
                                <tr><td colspan="6" class="py-8 text-center text-[#252525]/40">Loading data...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>

            <!-- TAB: MENU -->
            <div id="tab-menu" class="tab-content">
                <section class="mb-24">
                    <div class="flex justify-between items-center mb-8 border-b border-[#252525]/20 pb-4">
                        <h2 class="text-xl font-light uppercase tracking-tight text-[#252525]/60">Manage your cafe's offerings</h2>
                        <button onclick="openMenuModal()" class="border border-[#252525] px-4 py-2 font-light uppercase tracking-tight text-xs hover:bg-[#252525] hover:text-[#efeee6] transition-colors flex items-center gap-2">
                            <i data-lucide="plus" class="size-4" stroke-width="1.5"></i> Add Menu
                        </button>
                    </div>

                    <div class="w-full overflow-x-auto">
                        <table class="w-full text-left font-light uppercase tracking-tight text-sm whitespace-nowrap">
                            <thead>
                                <tr class="border-b border-[#252525] text-[#252525]/60">
                                    <th class="py-4 font-light">ID</th>
                                    <th class="py-4 font-light">Name</th>
                                    <th class="py-4 font-light">Category</th>
                                    <th class="py-4 font-light">Price</th>
                                    <th class="py-4 font-light">Stock</th>
                                    <th class="py-4 font-light text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="menuTableBody">
                                <tr><td colspan="6" class="py-8 text-center text-[#252525]/40">Loading data...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>

            <!-- TAB: ORDERS -->
            <div id="tab-orders" class="tab-content">
                <section class="mb-24">
                    <div class="flex justify-between items-center mb-8 border-b border-[#252525]/20 pb-4">
                        <h2 class="text-xl font-light uppercase tracking-tight text-[#252525]/60">Manage customer orders</h2>
                    </div>

                    <div class="w-full overflow-x-auto">
                        <table class="w-full text-left font-light uppercase tracking-tight text-sm whitespace-nowrap">
                            <thead>
                                <tr class="border-b border-[#252525] text-[#252525]/60">
                                    <th class="py-4 font-light">ID</th>
                                    <th class="py-4 font-light">Customer</th>
                                    <th class="py-4 font-light">Menu Item</th>
                                    <th class="py-4 font-light">Qty</th>
                                    <th class="py-4 font-light">Total Price</th>
                                    <th class="py-4 font-light">Status</th>
                                    <th class="py-4 font-light text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="ordersFullTableBody">
                                <tr><td colspan="7" class="py-8 text-center text-[#252525]/40">Loading data...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>

            <!-- TAB: RESERVATIONS -->
            <div id="tab-reservations" class="tab-content">
                <section class="mb-24">
                    <div class="flex justify-between items-center mb-8 border-b border-[#252525]/20 pb-4">
                        <h2 class="text-xl font-light uppercase tracking-tight text-[#252525]/60">Manage table bookings</h2>
                    </div>

                    <div class="w-full overflow-x-auto">
                        <table class="w-full text-left font-light uppercase tracking-tight text-sm whitespace-nowrap">
                            <thead>
                                <tr class="border-b border-[#252525] text-[#252525]/60">
                                    <th class="py-4 font-light">ID</th>
                                    <th class="py-4 font-light">Customer</th>
                                    <th class="py-4 font-light">Date & Time</th>
                                    <th class="py-4 font-light">Guests</th>
                                    <th class="py-4 font-light">Status</th>
                                    <th class="py-4 font-light text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="reservationsTableBody">
                                <tr><td colspan="6" class="py-8 text-center text-[#252525]/40">Loading data...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        </main>
    </div>

    <!-- MODAL BACKDROP -->
    <div id="modalBackdrop" class="fixed inset-0 bg-[#efeee6]/95 backdrop-blur-sm z-50 flex items-center justify-center hidden">
        <div class="bg-[#efeee6] border border-[#252525] w-full max-w-lg p-8 relative">
            <button onclick="closeModal()" class="absolute top-6 right-6 hover:opacity-60 transition-opacity">
                <i data-lucide="x" class="size-6" stroke-width="1.5"></i>
            </button>
            
            <h2 id="modalTitle" class="text-3xl font-light uppercase tracking-tight mb-8 leading-none">Form</h2>
            
            <!-- ALERT BOX IN MODAL -->
            <div id="modalAlert" class="hidden border border-[#252525] p-3 mb-6 text-sm font-light uppercase tracking-tight"></div>

            <form id="modalForm" class="flex flex-col gap-6">
                <div id="modalFields" class="flex flex-col gap-6"></div>
                <button type="submit" id="modalSubmit" class="mt-4 w-full border border-[#252525] py-4 font-light uppercase tracking-tight hover:bg-[#252525] hover:text-[#efeee6] transition-colors flex justify-center items-center gap-2">
                    <span>Save</span>
                </button>
            </form>
        </div>
    </div>

    <script>
        lucide.createIcons();

        const token = localStorage.getItem('jwt_token');
        const userStr = localStorage.getItem('user');

        if (!token || !userStr) {
            window.location.href = '/login';
        } else {
            const user = JSON.parse(userStr);
            if (user.role !== 'admin') {
                alert('Access Denied. Admins only.');
                window.location.href = '/';
            } else {
                document.getElementById('appBody').classList.remove('hidden');
                document.getElementById('adminName').textContent = user.nama;
                fetchOverviewData(); // Initial load
            }
        }

        const dateOptions = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        document.getElementById('currentDate').textContent = new Date().toLocaleDateString('en-US', dateOptions);

        function logout() {
            localStorage.removeItem('jwt_token');
            localStorage.removeItem('user');
            window.location.href = '/';
        }

        /* TABS */
        function switchTab(tabId, el = null) {
            document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
            document.getElementById('tab-' + tabId).classList.add('active');
            
            if(el) {
                document.querySelectorAll('.tab-link').forEach(link => {
                    link.classList.remove('border-b', 'border-[#252525]/50');
                    link.classList.add('opacity-60', 'hover:opacity-100');
                });
                el.classList.add('border-b', 'border-[#252525]/50');
                el.classList.remove('opacity-60', 'hover:opacity-100');
            }

            document.getElementById('pageTitle').textContent = tabId;

            if(tabId === 'overview') fetchOverviewData();
            if(tabId === 'menu') fetchMenuData();
            if(tabId === 'orders') fetchOrdersData();
            if(tabId === 'reservations') fetchReservationsData();
        }

        /* API UTILS */
        async function api(url, method = 'GET', body = null) {
            const headers = {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            };
            const res = await fetch('/api' + url, {
                method,
                headers,
                body: body ? JSON.stringify(body) : null
            });
            if(res.status === 204) return null;
            const data = await res.json();
            if (!res.ok) throw new Error(data.message || 'Request failed');
            return data;
        }

        function renderRowEmpty(colSpan, msg) {
            return `<tr><td colspan="${colSpan}" class="py-8 text-center text-[#252525]/40">${msg}</td></tr>`;
        }

        /* OVERVIEW DATA */
        async function fetchOverviewData() {
            try {
                const reportData = await api('/orders/report?period=monthly');
                const ordersData = await api('/orders?limit=5');
                const menuData = await api('/menu');
                const resvData = await api('/reservations');
                
                const formattedRevenue = ((reportData.total_income || 0) / 1000).toLocaleString('en-US', { minimumFractionDigits: 0, maximumFractionDigits: 2 });
                document.getElementById('statRevenue').textContent = formattedRevenue + 'K';
                document.getElementById('statOrders').textContent = reportData.total_orders || 0;
                document.getElementById('statMenu').textContent = menuData.data ? menuData.data.length : 0;
                document.getElementById('statRsvp').textContent = resvData.data ? resvData.data.filter(r => r.status==='pending').length : 0;

                const tbody = document.getElementById('overviewTableBody');
                tbody.innerHTML = '';
                if (ordersData.data && ordersData.data.length > 0) {
                    ordersData.data.forEach(order => {
                        let statusColor = 'text-[#252525]';
                        if(order.status === 'pending') statusColor = 'text-[#252525]/50';
                        if(order.status === 'selesai') statusColor = 'text-[#252525] font-medium';
                        tbody.innerHTML += `
                            <tr class="border-b border-[#252525]/10 hover:bg-[#252525]/5 transition-colors">
                                <td class="py-4">#${String(order.id).padStart(4, '0')}</td>
                                <td class="py-4">${order.user ? order.user.nama : '-'}</td>
                                <td class="py-4">${order.menu ? order.menu.nama : '-'}</td>
                                <td class="py-4">${order.jumlah}</td>
                                <td class="py-4">${new Date(order.tanggal).toLocaleDateString('en-US')}</td>
                                <td class="py-4 text-right ${statusColor}">${order.status}</td>
                            </tr>
                        `;
                    });
                } else tbody.innerHTML = renderRowEmpty(6, 'No orders found.');
            } catch(e) { console.error(e); }
        }

        /* MENU CRUD */
        let currentEditingId = null;
        let currentModalType = ''; // menu, order, reservation

        async function fetchMenuData() {
            try {
                const menuData = await api('/menu');
                const tbody = document.getElementById('menuTableBody');
                tbody.innerHTML = '';
                if (menuData.data && menuData.data.length > 0) {
                    menuData.data.forEach(m => {
                        tbody.innerHTML += `
                            <tr class="border-b border-[#252525]/10 hover:bg-[#252525]/5 transition-colors">
                                <td class="py-4">#${m.id}</td>
                                <td class="py-4">${m.nama}</td>
                                <td class="py-4">${m.kategori}</td>
                                <td class="py-4">$${m.harga}</td>
                                <td class="py-4">${m.stok}</td>
                                <td class="py-4 text-right">
                                    <button onclick='openMenuModal(${JSON.stringify(m)})' class="mr-3 opacity-60 hover:opacity-100"><i data-lucide="edit-2" class="size-4" stroke-width="1.5"></i></button>
                                    <button onclick="deleteEntity('menu', ${m.id})" class="opacity-60 hover:opacity-100 text-red-800"><i data-lucide="trash-2" class="size-4" stroke-width="1.5"></i></button>
                                </td>
                            </tr>
                        `;
                    });
                    lucide.createIcons();
                } else tbody.innerHTML = renderRowEmpty(6, 'No menu items.');
            } catch(e) { console.error(e); }
        }

        function openMenuModal(item = null) {
            currentModalType = 'menu';
            currentEditingId = item ? item.id : null;
            document.getElementById('modalTitle').textContent = item ? 'Edit Menu' : 'New Menu';
            
            document.getElementById('modalFields').innerHTML = `
                <div class="flex flex-col gap-2">
                    <label class="text-xs font-light uppercase tracking-tight text-[#252525]/70">Name</label>
                    <input type="text" id="m_nama" required value="${item ? item.nama : ''}" class="w-full bg-transparent border-b border-[#252525]/30 py-2 focus:outline-none focus:border-[#252525]">
                </div>
                <div class="flex flex-col gap-2">
                    <label class="text-xs font-light uppercase tracking-tight text-[#252525]/70">Category</label>
                    <input type="text" id="m_kategori" required value="${item ? item.kategori : ''}" class="w-full bg-transparent border-b border-[#252525]/30 py-2 focus:outline-none focus:border-[#252525]">
                </div>
                <div class="flex flex-col gap-2">
                    <label class="text-xs font-light uppercase tracking-tight text-[#252525]/70">Description</label>
                    <textarea id="m_deskripsi" class="w-full bg-transparent border-b border-[#252525]/30 py-2 focus:outline-none focus:border-[#252525]">${item ? item.deskripsi : ''}</textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="flex flex-col gap-2">
                        <label class="text-xs font-light uppercase tracking-tight text-[#252525]/70">Price</label>
                        <input type="number" id="m_harga" step="0.01" required value="${item ? item.harga : ''}" class="w-full bg-transparent border-b border-[#252525]/30 py-2 focus:outline-none focus:border-[#252525]">
                    </div>
                    <div class="flex flex-col gap-2">
                        <label class="text-xs font-light uppercase tracking-tight text-[#252525]/70">Stock</label>
                        <input type="number" id="m_stok" required value="${item ? item.stok : ''}" class="w-full bg-transparent border-b border-[#252525]/30 py-2 focus:outline-none focus:border-[#252525]">
                    </div>
                </div>
            `;
            openModal();
        }

        /* ORDERS CRUD */
        async function fetchOrdersData() {
            try {
                const ordersData = await api('/orders');
                const tbody = document.getElementById('ordersFullTableBody');
                tbody.innerHTML = '';
                if (ordersData.data && ordersData.data.length > 0) {
                    ordersData.data.forEach(o => {
                        let statusColor = 'text-[#252525]';
                        if(o.status === 'pending') statusColor = 'text-[#252525]/50';
                        if(o.status === 'selesai') statusColor = 'text-[#252525] font-medium';
                        tbody.innerHTML += `
                            <tr class="border-b border-[#252525]/10 hover:bg-[#252525]/5 transition-colors">
                                <td class="py-4">#${o.id}</td>
                                <td class="py-4">${o.user ? o.user.nama : '-'}</td>
                                <td class="py-4">${o.menu ? o.menu.nama : '-'}</td>
                                <td class="py-4">${o.jumlah}</td>
                                <td class="py-4">$${o.total_harga}</td>
                                <td class="py-4 ${statusColor}">${o.status}</td>
                                <td class="py-4 text-right">
                                    <button onclick='openOrderModal(${JSON.stringify(o)})' class="mr-3 opacity-60 hover:opacity-100"><i data-lucide="edit-2" class="size-4" stroke-width="1.5"></i></button>
                                    <button onclick="deleteEntity('orders', ${o.id})" class="opacity-60 hover:opacity-100 text-red-800"><i data-lucide="trash-2" class="size-4" stroke-width="1.5"></i></button>
                                </td>
                            </tr>
                        `;
                    });
                    lucide.createIcons();
                } else tbody.innerHTML = renderRowEmpty(7, 'No orders found.');
            } catch(e) { console.error(e); }
        }

        function openOrderModal(item) {
            currentModalType = 'orders';
            currentEditingId = item.id;
            document.getElementById('modalTitle').textContent = 'Edit Order Status';
            
            document.getElementById('modalFields').innerHTML = `
                <div class="flex flex-col gap-2">
                    <label class="text-xs font-light uppercase tracking-tight text-[#252525]/70">Status</label>
                    <select id="o_status" class="w-full bg-transparent border-b border-[#252525]/30 py-3 uppercase tracking-tight text-sm focus:outline-none focus:border-[#252525]">
                        <option value="pending" ${item.status==='pending'?'selected':''}>Pending</option>
                        <option value="proses" ${item.status==='proses'?'selected':''}>Proses</option>
                        <option value="selesai" ${item.status==='selesai'?'selected':''}>Selesai</option>
                        <option value="dibatalkan" ${item.status==='dibatalkan'?'selected':''}>Dibatalkan</option>
                    </select>
                </div>
            `;
            openModal();
        }

        /* RESERVATIONS CRUD */
        async function fetchReservationsData() {
            try {
                const resvData = await api('/reservations');
                const tbody = document.getElementById('reservationsTableBody');
                tbody.innerHTML = '';
                if (resvData.data && resvData.data.length > 0) {
                    resvData.data.forEach(r => {
                        let statusColor = 'text-[#252525]';
                        if(r.status === 'pending') statusColor = 'text-[#252525]/50';
                        if(r.status === 'confirmed') statusColor = 'text-[#252525] font-medium';
                        if(r.status === 'cancelled') statusColor = 'text-red-800';
                        tbody.innerHTML += `
                            <tr class="border-b border-[#252525]/10 hover:bg-[#252525]/5 transition-colors">
                                <td class="py-4">#${r.id}</td>
                                <td class="py-4">${r.user ? r.user.nama : '-'}</td>
                                <td class="py-4">${new Date(r.tanggal_reservasi).toLocaleString('en-US')}</td>
                                <td class="py-4">${r.jumlah_orang}</td>
                                <td class="py-4 ${statusColor}">${r.status}</td>
                                <td class="py-4 text-right">
                                    <button onclick='openReservationModal(${JSON.stringify(r)})' class="mr-3 opacity-60 hover:opacity-100"><i data-lucide="edit-2" class="size-4" stroke-width="1.5"></i></button>
                                    <button onclick="deleteEntity('reservations', ${r.id})" class="opacity-60 hover:opacity-100 text-red-800"><i data-lucide="trash-2" class="size-4" stroke-width="1.5"></i></button>
                                </td>
                            </tr>
                        `;
                    });
                    lucide.createIcons();
                } else tbody.innerHTML = renderRowEmpty(6, 'No reservations found.');
            } catch(e) { console.error(e); }
        }

        function openReservationModal(item) {
            currentModalType = 'reservations';
            currentEditingId = item.id;
            document.getElementById('modalTitle').textContent = 'Edit Reservation Status';
            
            document.getElementById('modalFields').innerHTML = `
                <div class="flex flex-col gap-2">
                    <label class="text-xs font-light uppercase tracking-tight text-[#252525]/70">Status</label>
                    <select id="r_status" class="w-full bg-transparent border-b border-[#252525]/30 py-3 uppercase tracking-tight text-sm focus:outline-none focus:border-[#252525]">
                        <option value="pending" ${item.status==='pending'?'selected':''}>Pending</option>
                        <option value="confirmed" ${item.status==='confirmed'?'selected':''}>Confirmed</option>
                        <option value="cancelled" ${item.status==='cancelled'?'selected':''}>Cancelled</option>
                    </select>
                </div>
            `;
            openModal();
        }

        /* MODAL UTILS */
        function openModal() {
            document.getElementById('modalBackdrop').classList.remove('hidden');
            document.getElementById('modalAlert').classList.add('hidden');
        }
        function closeModal() {
            document.getElementById('modalBackdrop').classList.add('hidden');
        }

        document.getElementById('modalForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const alertBox = document.getElementById('modalAlert');
            const submitBtn = document.getElementById('modalSubmit');
            
            let payload = {};
            if(currentModalType === 'menu') {
                payload = {
                    nama: document.getElementById('m_nama').value,
                    kategori: document.getElementById('m_kategori').value,
                    deskripsi: document.getElementById('m_deskripsi').value,
                    harga: document.getElementById('m_harga').value,
                    stok: document.getElementById('m_stok').value
                };
            } else if (currentModalType === 'orders') {
                payload = { status: document.getElementById('o_status').value };
            } else if (currentModalType === 'reservations') {
                payload = { status: document.getElementById('r_status').value };
            }

            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span>Saving...</span>';

            try {
                if(currentEditingId) {
                    await api(`/${currentModalType}/${currentEditingId}`, 'PUT', payload);
                } else {
                    await api(`/${currentModalType}`, 'POST', payload);
                }
                closeModal();
                if(currentModalType === 'menu') fetchMenuData();
                if(currentModalType === 'orders') fetchOrdersData();
                if(currentModalType === 'reservations') fetchReservationsData();
            } catch (err) {
                alertBox.textContent = err.message;
                alertBox.classList.remove('hidden');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<span>Save</span>';
            }
        });

        async function deleteEntity(type, id) {
            if(!confirm('Are you sure you want to delete this?')) return;
            try {
                await api(`/${type}/${id}`, 'DELETE');
                if(type === 'menu') fetchMenuData();
                if(type === 'orders') fetchOrdersData();
                if(type === 'reservations') fetchReservationsData();
            } catch (err) {
                alert(err.message);
            }
        }
    </script>
</body>
</html>
