<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Make a Reservation - Little Latte Cafe</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        .text-stroke-transparent {
            -webkit-text-stroke: 0.08rem #efeee6;
            color: transparent;
        }
        body {
            background-color: #efeee6;
            color: #252525;
        }
        ::selection {
            background-color: #252525;
            color: #efeee6;
        }
    </style>
</head>
<body class="bg-[#efeee6] text-[#252525] antialiased selection:bg-[#252525] selection:text-[#efeee6] font-light">
    <div class="mx-auto min-h-screen w-full max-w-[112rem] overflow-hidden px-5 sm:px-8 lg:px-14">
        
        <!-- HEADER -->
        <header class="flex justify-between py-8 items-center border-b border-[#252525]/20 mb-12">
            <a href="/" class="font-light uppercase tracking-tight text-xl hover:opacity-60 transition-opacity">Little Latte Cafe</a>
            
            <nav class="hidden md:flex items-center gap-8 text-sm uppercase tracking-wider">
                <a href="/" class="hover:opacity-60 transition-opacity">Home</a>
                <a href="/#menu" class="hover:opacity-60 transition-opacity">Menu</a>
                <a href="/reservations" class="border-b border-[#252525]/50 pb-1">Reservations</a>
                
                <div class="auth-only hidden flex items-center gap-8 border-l border-[#252525] pl-8">
                    <a href="/dashboard" class="hover:opacity-60 transition-opacity">Dashboard</a>
                    <span id="navUserName" class="opacity-60"></span>
                    <button onclick="handleLogout()" class="hover:opacity-60 transition-opacity flex items-center gap-1">
                        <i data-lucide="log-out" class="size-4" stroke-width="1.5"></i> Logout
                    </button>
                </div>
                
                <div class="guest-only flex items-center gap-8 border-l border-[#252525] pl-8">
                    <a href="/login" class="hover:opacity-60 transition-opacity">Login</a>
                </div>
            </nav>

            <button class="grid size-11 place-items-center rounded-full border border-[#252525] md:hidden hover:bg-[#252525] hover:text-[#efeee6] transition-colors">
                <i data-lucide="menu" stroke-width="1.5"></i>
            </button>
        </header>

        <!-- MAIN CONTENT -->
        <main class="py-12">
            <div class="grid lg:grid-cols-[1.2fr_1fr] gap-16 items-start">
                
                <!-- LEFT: Form Section -->
                <div>
                    <div class="mb-12">
                        <h1 class="text-5xl md:text-6xl font-light uppercase tracking-tight leading-none mb-4">Book your table</h1>
                        <p class="text-[#252525]/70 leading-relaxed max-w-xl">
                            Reserve your spot at Little Latte Cafe. Enter your details below, and we'll confirm your booking shortly.
                        </p>
                    </div>

                    <!-- Alert Box -->
                    <div id="alertBox" class="hidden border border-[#252525] p-4 mb-8 text-sm font-light uppercase tracking-tight"></div>

                    <!-- Reservation Form -->
                    <form id="reservationForm" class="flex flex-col gap-8">
                        <div class="grid md:grid-cols-2 gap-8">
                            <div class="flex flex-col gap-2">
                                <label class="text-xs font-light uppercase tracking-tight text-[#252525]/70">Your Name *</label>
                                <input type="text" id="customerName" readonly class="w-full bg-transparent border-b border-[#252525]/30 py-3 focus:outline-none focus:border-[#252525] uppercase tracking-tight text-sm">
                            </div>
                            
                            <div class="flex flex-col gap-2">
                                <label class="text-xs font-light uppercase tracking-tight text-[#252525]/70">Email *</label>
                                <input type="email" id="customerEmail" readonly class="w-full bg-transparent border-b border-[#252525]/30 py-3 focus:outline-none focus:border-[#252525] text-sm">
                            </div>
                        </div>

                        <div class="grid md:grid-cols-2 gap-8">
                            <div class="flex flex-col gap-2">
                                <label class="text-xs font-light uppercase tracking-tight text-[#252525]/70">Date *</label>
                                <input type="date" id="reservationDate" required class="w-full bg-transparent border-b border-[#252525]/30 py-3 focus:outline-none focus:border-[#252525] uppercase tracking-tight text-sm">
                            </div>
                            
                            <div class="flex flex-col gap-2">
                                <label class="text-xs font-light uppercase tracking-tight text-[#252525]/70">Time *</label>
                                <input type="time" id="reservationTime" required class="w-full bg-transparent border-b border-[#252525]/30 py-3 focus:outline-none focus:border-[#252525] uppercase tracking-tight text-sm">
                            </div>
                        </div>

                        <div class="flex flex-col gap-2">
                            <label class="text-xs font-light uppercase tracking-tight text-[#252525]/70">Number of Guests * (1-50)</label>
                            <input type="number" id="guestCount" min="1" max="50" required class="w-full bg-transparent border-b border-[#252525]/30 py-3 focus:outline-none focus:border-[#252525] uppercase tracking-tight text-sm">
                        </div>

                        <div class="flex flex-col gap-2">
                            <label class="text-xs font-light uppercase tracking-tight text-[#252525]/70">Special Requests (Optional)</label>
                            <textarea id="specialRequests" rows="4" placeholder="Any dietary requirements or special occasions..." class="w-full bg-transparent border border-[#252525]/30 p-3 focus:outline-none focus:border-[#252525] text-sm resize-none"></textarea>
                        </div>

                        <button type="submit" id="submitBtn" class="w-full border border-[#252525] py-5 font-light uppercase tracking-tight hover:bg-[#252525] hover:text-[#efeee6] transition-colors flex justify-center items-center gap-2 text-lg">
                            <span>Reserve Table</span>
                            <i data-lucide="calendar-check" class="size-5" stroke-width="1.5"></i>
                        </button>

                        <p class="text-xs text-[#252525]/60 leading-relaxed">
                            * Required fields. By submitting this form, you agree to our reservation policies. Cancellations must be made at least 2 hours in advance.
                        </p>
                    </form>
                </div>

                <!-- RIGHT: Info Section -->
                <div class="lg:sticky lg:top-8">
                    <!-- Hero Image -->
                    <div class="aspect-[4/5] w-full overflow-hidden mb-8">
                        <img src="https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=1000&q=85" 
                             alt="Cafe Interior" 
                             class="w-full h-full object-cover grayscale opacity-90">
                    </div>

                    <!-- Info Cards -->
                    <div class="space-y-6">
                        <div class="border border-[#252525]/20 p-6">
                            <div class="flex items-start gap-4">
                                <i data-lucide="clock" class="size-6 text-[#252525] opacity-70 flex-shrink-0 mt-1" stroke-width="1.5"></i>
                                <div>
                                    <h3 class="text-sm uppercase tracking-wider mb-2 font-medium">Operating Hours</h3>
                                    <p class="text-sm opacity-70 font-light leading-relaxed">
                                        Monday - Friday: 7:00 AM - 6:00 PM<br>
                                        Saturday - Sunday: 8:00 AM - 5:00 PM
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="border border-[#252525]/20 p-6">
                            <div class="flex items-start gap-4">
                                <i data-lucide="map-pin" class="size-6 text-[#252525] opacity-70 flex-shrink-0 mt-1" stroke-width="1.5"></i>
                                <div>
                                    <h3 class="text-sm uppercase tracking-wider mb-2 font-medium">Location</h3>
                                    <p class="text-sm opacity-70 font-light leading-relaxed">
                                        123 Brew Lane<br>
                                        Portland, OR 97204<br>
                                        United States
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="border border-[#252525]/20 p-6">
                            <div class="flex items-start gap-4">
                                <i data-lucide="info" class="size-6 text-[#252525] opacity-70 flex-shrink-0 mt-1" stroke-width="1.5"></i>
                                <div>
                                    <h3 class="text-sm uppercase tracking-wider mb-2 font-medium">Reservation Policy</h3>
                                    <p class="text-sm opacity-70 font-light leading-relaxed">
                                        • Reservations held for 15 minutes<br>
                                        • Parties of 10+ require advance notice<br>
                                        • Please call for same-day bookings
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Decorative Elements -->
                    <div class="mt-12 flex gap-2">
                        <div class="size-8 bg-[#252525] rotate-45"></div>
                        <div class="size-8 bg-[#252525] rotate-45"></div>
                    </div>
                </div>
            </div>

            <!-- My Reservations Section -->
            <section id="myReservationsSection" class="mt-32 pt-16 border-t border-[#252525]">
                <div class="flex justify-between items-end border-b border-[#252525] pb-4 mb-12">
                    <h2 class="text-4xl font-light uppercase tracking-tight">My Reservations</h2>
                    <button onclick="loadMyReservations()" class="text-sm uppercase tracking-wider underline underline-offset-4 hover:opacity-60 transition-opacity flex items-center gap-2">
                        <i data-lucide="refresh-cw" class="size-4" stroke-width="1.5"></i> Refresh
                    </button>
                </div>

                <div class="w-full overflow-x-auto">
                    <table class="w-full text-left font-light uppercase tracking-tight text-sm">
                        <thead>
                            <tr class="border-b border-[#252525] text-[#252525]/60">
                                <th class="py-4 font-light">ID</th>
                                <th class="py-4 font-light">Date & Time</th>
                                <th class="py-4 font-light">Guests</th>
                                <th class="py-4 font-light">Status</th>
                                <th class="py-4 font-light text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="myReservationsTable">
                            <tr><td colspan="5" class="py-8 text-center text-[#252525]/60">Loading your reservations...</td></tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>

        <!-- FOOTER -->
        <footer class="mt-32 pb-12 border-t border-[#252525]/20 pt-12">
            <div class="flex justify-between items-center text-sm opacity-70">
                <div>&copy; 2024 Little Latte Cafe. All rights reserved.</div>
                <div class="flex gap-8">
                    <a href="/#about" class="hover:opacity-60 transition-opacity">About</a>
                    <a href="/#contact" class="hover:opacity-60 transition-opacity">Contact</a>
                </div>
            </div>
        </footer>
    </div>

    <script>
        lucide.createIcons();

        const token = localStorage.getItem('jwt_token');
        const userStr = localStorage.getItem('user');

        // Check if user is logged in
        if (!token || !userStr) {
            alert('Please login first to make a reservation.');
            window.location.href = '/login';
        } else {
            const user = JSON.parse(userStr);
            document.getElementById('customerName').value = user.nama || '';
            document.getElementById('customerEmail').value = user.email || '';
            document.getElementById('navUserName').textContent = user.nama || 'User';
            
            // Show/hide nav elements
            document.querySelectorAll('.guest-only').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('.auth-only').forEach(el => el.classList.remove('hidden'));
            
            // Set minimum date to today
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('reservationDate').setAttribute('min', today);
            
            // Load user's reservations
            loadMyReservations();
        }

        function handleLogout() {
            localStorage.removeItem('jwt_token');
            localStorage.removeItem('user');
            window.location.href = '/';
        }

        // Submit Reservation
        document.getElementById('reservationForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const alertBox = document.getElementById('alertBox');
            const submitBtn = document.getElementById('submitBtn');
            
            const date = document.getElementById('reservationDate').value;
            const time = document.getElementById('reservationTime').value;
            const guestCount = parseInt(document.getElementById('guestCount').value);
            
            // Send as separate fields (tanggal and jam)
            const payload = {
                tanggal: date,
                jam: time,
                jumlah_orang: guestCount
            };
            
            // Disable button
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span>Processing...</span>';
            
            try {
                const res = await fetch('/api/reservations', {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(payload)
                });
                
                const data = await res.json();
                
                if (res.ok || res.status === 201) {
                    alertBox.textContent = '✓ Reservation submitted successfully! Status: Pending confirmation.';
                    alertBox.classList.remove('hidden', 'border-red-800', 'text-red-800');
                    alertBox.classList.add('border-[#252525]', 'text-[#252525]');
                    
                    // Reset form
                    document.getElementById('reservationDate').value = '';
                    document.getElementById('reservationTime').value = '';
                    document.getElementById('guestCount').value = '';
                    document.getElementById('specialRequests').value = '';
                    
                    // Reload reservations list
                    loadMyReservations();
                    
                    // Scroll to reservations section
                    setTimeout(() => {
                        document.getElementById('myReservationsSection').scrollIntoView({ behavior: 'smooth' });
                    }, 500);
                } else {
                    // Show validation errors
                    let errorMsg = '✗ Failed to submit reservation:\n';
                    if (data.errors) {
                        Object.keys(data.errors).forEach(key => {
                            errorMsg += `\n• ${data.errors[key].join(', ')}`;
                        });
                    } else {
                        errorMsg += '\n• ' + (data.message || 'Unknown error');
                    }
                    alertBox.textContent = errorMsg;
                    alertBox.style.whiteSpace = 'pre-line';
                    alertBox.classList.remove('hidden', 'border-[#252525]');
                    alertBox.classList.add('border-red-800', 'text-red-800');
                }
            } catch (err) {
                alertBox.textContent = '✗ Network error. Please check your connection and try again.';
                alertBox.classList.remove('hidden', 'border-[#252525]');
                alertBox.classList.add('border-red-800', 'text-red-800');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<span>Reserve Table</span><i data-lucide="calendar-check" class="size-5" stroke-width="1.5"></i>';
                lucide.createIcons();
            }
        });

        // Load My Reservations
        async function loadMyReservations() {
            try {
                const res = await fetch('/api/reservations', {
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    }
                });
                const data = await res.json();
                const tbody = document.getElementById('myReservationsTable');
                
                if (data.data && data.data.length > 0) {
                    tbody.innerHTML = '';
                    data.data.forEach(r => {
                        let statusColor = 'text-[#252525]';
                        let statusBg = 'bg-[#252525]/10';
                        if (r.status === 'pending') {
                            statusColor = 'text-[#252525]/50';
                            statusBg = 'bg-[#252525]/5';
                        } else if (r.status === 'confirmed') {
                            statusColor = 'text-[#252525] font-medium';
                            statusBg = 'bg-[#252525]/20';
                        } else if (r.status === 'cancelled') {
                            statusColor = 'text-red-800';
                            statusBg = 'bg-red-800/10';
                        }
                        
                        const canCancel = r.status === 'pending';
                        
                        tbody.innerHTML += `
                            <tr class="border-b border-[#252525]/10 hover:bg-[#252525]/5 transition-colors">
                                <td class="py-4">#${String(r.id).padStart(4, '0')}</td>
                                <td class="py-4">${new Date(r.tanggal_reservasi).toLocaleString('en-US', { 
                                    year: 'numeric', month: 'short', day: 'numeric', 
                                    hour: '2-digit', minute: '2-digit' 
                                })}</td>
                                <td class="py-4">${r.jumlah_orang} ${r.jumlah_orang > 1 ? 'guests' : 'guest'}</td>
                                <td class="py-4">
                                    <span class="${statusBg} ${statusColor} px-3 py-1 text-xs">${r.status}</span>
                                </td>
                                <td class="py-4 text-right">
                                    ${canCancel ? `
                                        <button onclick="cancelReservation(${r.id})" class="opacity-60 hover:opacity-100 text-red-800 uppercase text-xs tracking-wider">
                                            Cancel
                                        </button>
                                    ` : `<span class="text-xs opacity-40">—</span>`}
                                </td>
                            </tr>
                        `;
                    });
                } else {
                    tbody.innerHTML = `<tr><td colspan="5" class="py-8 text-center text-[#252525]/60">You don't have any reservations yet.</td></tr>`;
                }
            } catch (err) {
                console.error('Failed to load reservations:', err);
            }
        }

        // Cancel Reservation
        async function cancelReservation(id) {
            if (!confirm('Are you sure you want to cancel this reservation?')) return;
            
            try {
                const res = await fetch(`/api/reservations/${id}`, {
                    method: 'PUT',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ status: 'cancelled' })
                });
                
                if (res.ok) {
                    alert('Reservation cancelled successfully.');
                    loadMyReservations();
                } else {
                    const data = await res.json();
                    alert('Failed to cancel: ' + (data.message || 'Unknown error'));
                }
            } catch (err) {
                alert('Network error. Please try again.');
            }
        }
    </script>
</body>
</html>
