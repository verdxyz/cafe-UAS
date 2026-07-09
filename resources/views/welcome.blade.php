<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Little Latte Cafe</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <!-- GA4 -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-2M6V79H761"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'G-2M6V79H761');
    </script>
    <style>
        /* Custom styles to match fidelity */
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
        
        <!-- SECTION 1 - HEADER -->
        <header class="flex justify-between py-8 items-center">
            <div class="md:hidden font-light uppercase tracking-tight text-xl">Little Latte Cafe</div>
            
            <nav class="hidden md:flex flex-grow justify-between items-center text-sm uppercase tracking-wider" id="mainNav">
                <div class="flex gap-8 items-center">
                    <a href="#home" class="hover:opacity-60 transition-opacity">Home</a>
                    <a href="#about" class="hover:opacity-60 transition-opacity">About me</a>
                    <a href="#best" class="hover:opacity-60 transition-opacity">Best seller</a>
                    <a href="#menu" class="hover:opacity-60 transition-opacity">Menu</a>
                    <a href="/reservations" class="hover:opacity-60 transition-opacity">Reservations</a>
                    <a href="#contact" class="hover:opacity-60 transition-opacity">Contact us</a>
                </div>
                
                <div class="flex items-center gap-8">
                    <!-- Guest Only -->
                    <a href="/login" class="guest-only hover:opacity-60 transition-opacity border-l border-[#252525] pl-8">Login</a>
                    <a href="/register" class="guest-only hover:opacity-60 transition-opacity">Register</a>
                    
                    <!-- Auth Only -->
                    <div class="auth-only hidden flex items-center gap-8 border-l border-[#252525] pl-8">
                        <a href="/dashboard" class="hover:opacity-60 transition-opacity">Dashboard</a>
                        <span id="navUserName" class="opacity-60"></span>
                        <button onclick="handleLogout()" class="hover:opacity-60 transition-opacity flex items-center gap-1"><i data-lucide="log-out" class="size-4" stroke-width="1.5"></i> Logout</button>
                    </div>
                </div>
            </nav>

            <button class="grid size-11 place-items-center rounded-full border border-[#252525] md:hidden hover:bg-[#252525] hover:text-[#efeee6] transition-colors">
                <i data-lucide="menu" stroke-width="1.5"></i>
            </button>
        </header>

        <!-- SECTION 2 - HERO (ID: home) -->
        <section id="home" class="relative mt-4">
            <div class="grid lg:grid-cols-[1fr_1.15fr_12rem] gap-8 items-end mb-16 relative z-10">
                <!-- Left: 7-diamond decorative grid -->
                <div>
                    <div class="grid grid-cols-4 gap-[2px] w-24 mb-6">
                        <div class="aspect-square bg-[#252525] rotate-45"></div>
                        <div class="aspect-square bg-[#252525] rotate-45"></div>
                        <div class="aspect-square bg-[#252525] rotate-45"></div>
                        <div class="aspect-square bg-[#252525] rotate-45"></div>
                        <!-- row 2 with col-start-2 -->
                        <div class="aspect-square bg-[#252525] rotate-45 col-start-2"></div>
                        <div class="aspect-square bg-[#252525] rotate-45"></div>
                        <div class="aspect-square bg-[#252525] rotate-45"></div>
                    </div>
                    <p class="text-sm max-w-xs opacity-70 leading-relaxed text-[#252525]/70">
                        Experience the finest blends curated for coffee lovers. Every cup tells a story of passion and craft.
                    </p>
                </div>

                <!-- Center: Headings -->
                <div class="font-light uppercase tracking-tight text-4xl sm:text-5xl lg:text-6xl leading-none">
                    <div>Warm cups.</div>
                    <div class="ml-12 md:ml-24 mt-2">Open hearts.</div>
                </div>

                <!-- Right: Circular "Order Now" button -->
                <div class="hidden lg:grid size-32 border border-[#252525] rounded-full place-items-center group cursor-pointer hover:bg-[#252525] hover:text-[#efeee6] transition-colors ml-auto relative">
                    <span class="text-sm uppercase tracking-wider absolute top-8">Order</span>
                    <span class="text-sm uppercase tracking-wider absolute bottom-8">Now</span>
                    <i data-lucide="arrow-up-right" stroke-width="1.5" class="absolute right-4 transition-transform group-hover:translate-x-1 group-hover:-translate-y-1"></i>
                </div>
            </div>

            <!-- Visual: Full-width hero image -->
            <div class="relative w-full aspect-[4/3] md:aspect-[21/9] overflow-hidden rounded-sm">
                <!-- Overlapping text -->
                <h1 class="absolute -top-4 left-0 w-full text-center text-[15vw] leading-none font-light uppercase tracking-tight text-stroke-transparent pointer-events-none z-20 whitespace-nowrap">
                    Little Latte
                </h1>
                
                <img src="https://hoirqrkdgbmvpwutwuwj.supabase.co/storage/v1/object/public/assets/assets/084e9852-b8c5-4f7a-801e-1758ba192969_3840w.png" 
                     alt="Cafe Hero" 
                     class="w-full h-full object-cover"
                     style="filter: brightness(0.62) contrast(1.08) saturate(0.92);">
                
                <!-- 5 squares at bottom right -->
                <div class="absolute -bottom-4 right-6 grid grid-cols-5 gap-1 z-20">
                    <div class="size-8 bg-[#efeee6]"></div>
                    <div class="size-8 bg-[#efeee6]"></div>
                    <div class="size-8 bg-[#efeee6]"></div>
                    <div class="size-8 bg-[#efeee6]"></div>
                    <div class="size-8 bg-[#efeee6]"></div>
                </div>
            </div>
        </section>

        <!-- SECTION 3 - ABOUT (ID: about) -->
        <section id="about" class="mt-32">
            <div class="grid lg:grid-cols-[1.2fr_1fr] gap-12 mb-16 items-start">
                <h2 class="text-3xl md:text-5xl font-light uppercase tracking-tight leading-tight">
                    Where every pour is a work of art and every sip a moment of joy.
                </h2>
                <div class="flex items-center gap-4 lg:justify-end">
                    <div class="flex gap-1">
                        <i data-lucide="star" class="fill-[#252525] size-5" stroke-width="1.5"></i>
                        <i data-lucide="star" class="fill-[#252525] size-5" stroke-width="1.5"></i>
                        <i data-lucide="star" class="fill-[#252525] size-5" stroke-width="1.5"></i>
                        <i data-lucide="star" class="fill-[#252525] size-5" stroke-width="1.5"></i>
                        <i data-lucide="star" class="fill-[#252525] size-5" stroke-width="1.5"></i>
                    </div>
                    <span class="text-sm uppercase tracking-wider font-medium">5.0 Stars</span>
                </div>
            </div>

            <!-- Mid part: 4-column feature row -->
            <div class="grid lg:grid-cols-[10rem_18rem_1fr_12rem] gap-8 items-end border-t border-[#252525]/20 pt-12 relative">
                <div class="text-xl">01 / 09</div>
                <div class="aspect-[3/4] w-full overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1501339847302-ac426a4a7cbb?auto=format&fit=crop&w=900&q=85" alt="Cafe Exterior" class="w-full h-full object-cover grayscale opacity-90">
                </div>
                <div class="pb-8">
                    <h3 class="text-2xl md:text-4xl font-light uppercase tracking-tight max-w-md">Small-batch espresso, served with soul</h3>
                </div>
                <!-- Accent: Two black squares -->
                <div class="hidden lg:flex gap-4 justify-end absolute right-0 bottom-0">
                    <div class="size-16 bg-[#252525]"></div>
                    <div class="size-16 bg-[#252525] translate-y-16"></div>
                </div>
            </div>
        </section>

        <!-- SECTION 4 - HISTORY -->
        <section class="mt-48">
            <div class="grid lg:grid-cols-[24rem_1fr_11rem] gap-8 items-center">
                <div class="aspect-square w-full overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1521017432531-fbd92d768814?auto=format&fit=crop&w=900&q=85" alt="Cafe Interior" class="w-full h-full object-cover grayscale">
                </div>
                <h2 class="text-5xl md:text-7xl font-light uppercase tracking-tight leading-none text-center lg:text-left">
                    Brewing<br>stories<br>since 2024
                </h2>
                <div class="grid size-32 md:size-44 border border-[#252525] rounded-full place-items-center group cursor-pointer hover:bg-[#252525] hover:text-[#efeee6] transition-colors mx-auto lg:mx-0">
                    <span class="text-sm uppercase tracking-wider text-center">Read<br>More</span>
                </div>
            </div>
        </section>

        <!-- SECTION 5 - MENU QUICK-LIST -->
        <section class="mt-32">
            <div class="grid lg:grid-cols-[1fr_1.25fr_0.85fr] gap-12 lg:gap-8 items-center">
                <!-- Left: List -->
                <div class="flex flex-col">
                    <div class="flex justify-between py-6 border-b border-[#252525]/50 group cursor-pointer">
                        <span class="text-sm opacity-50 group-hover:opacity-100 transition-opacity">01</span>
                        <span class="uppercase tracking-wider text-lg group-hover:pl-4 transition-all">Signature Roasts</span>
                        <i data-lucide="arrow-right" stroke-width="1.5" class="opacity-0 -translate-x-4 group-hover:opacity-100 group-hover:translate-x-0 transition-all"></i>
                    </div>
                    <div class="flex justify-between py-6 border-b border-[#252525]/50 group cursor-pointer">
                        <span class="text-sm opacity-50 group-hover:opacity-100 transition-opacity">02</span>
                        <span class="uppercase tracking-wider text-lg group-hover:pl-4 transition-all">Artisan Pastries</span>
                        <i data-lucide="arrow-right" stroke-width="1.5" class="opacity-0 -translate-x-4 group-hover:opacity-100 group-hover:translate-x-0 transition-all"></i>
                    </div>
                    <div class="flex justify-between py-6 border-b border-[#252525]/50 group cursor-pointer">
                        <span class="text-sm opacity-50 group-hover:opacity-100 transition-opacity">03</span>
                        <span class="uppercase tracking-wider text-lg group-hover:pl-4 transition-all">Seasonal Drinks</span>
                        <i data-lucide="arrow-right" stroke-width="1.5" class="opacity-0 -translate-x-4 group-hover:opacity-100 group-hover:translate-x-0 transition-all"></i>
                    </div>
                </div>

                <!-- Center: Cafe window -->
                <div class="aspect-[4/3] w-full overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1442512595331-e89e73853f31?auto=format&fit=crop&w=1200&q=85" alt="Cafe Window" class="w-full h-full object-cover">
                </div>

                <!-- Right: Heading -->
                <h2 class="text-3xl md:text-4xl font-light uppercase tracking-tight text-right lg:text-left">
                    Rooted in flavor, poured with precision.
                </h2>
            </div>
        </section>

        <!-- SECTION 6 - BEST SELLERS (ID: best) -->
        <section id="best" class="mt-32">
            <div class="flex justify-between items-end border-b border-[#252525] pb-4 mb-12">
                <h2 class="text-4xl font-light uppercase tracking-tight">Best Sellers</h2>
                <a href="#menu" class="text-sm uppercase tracking-wider underline underline-offset-4 hover:opacity-60 transition-opacity">View full menu</a>
            </div>

            <div class="grid lg:grid-cols-[0.75fr_1.2fr_0.75fr_10rem] gap-8">
                <!-- Item 1 -->
                <div class="group cursor-pointer">
                    <div class="aspect-[3/4] w-full overflow-hidden mb-4">
                        <img src="https://images.unsplash.com/photo-1461023058943-07fcbe16d735?auto=format&fit=crop&w=700&q=85" alt="Iced Latte" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                    </div>
                    <div class="flex justify-between items-start">
                        <h3 class="font-light uppercase tracking-tight text-xl max-w-[8rem]">Cocoa Hazelnut Latte</h3>
                        <span class="text-2xl font-light">$5</span>
                    </div>
                </div>
                
                <!-- Item 2 (Larger) -->
                <div class="group cursor-pointer lg:-mt-12">
                    <div class="aspect-[4/5] w-full overflow-hidden mb-6">
                        <img src="https://images.unsplash.com/photo-1517701604599-bb29b565090c?auto=format&fit=crop&w=1000&q=85" alt="Creamy Latte" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                    </div>
                    <div class="flex justify-between items-start px-4">
                        <h3 class="font-light uppercase tracking-tight text-3xl max-w-[12rem]">Strawberry Dirty Soda</h3>
                        <span class="text-6xl font-light">$7</span>
                    </div>
                </div>

                <!-- Item 3 -->
                <div class="group cursor-pointer lg:mt-12">
                    <div class="aspect-square w-full overflow-hidden mb-4">
                        <img src="https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?auto=format&fit=crop&w=700&q=85" alt="Hot Chocolate" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                    </div>
                    <div class="flex justify-between items-start">
                        <h3 class="font-light uppercase tracking-tight text-xl max-w-[8rem]">Classic Hot Chocolate</h3>
                        <span class="text-4xl font-light">$4</span>
                    </div>
                </div>

                <!-- Pagination indicator -->
                <div class="hidden lg:flex flex-col gap-2 items-end justify-end pb-8">
                    <div class="h-[2px] bg-[#252525] w-8"></div>
                    <div class="h-[2px] bg-[#252525]/50 w-6"></div>
                    <div class="h-[2px] bg-[#252525]/30 w-4"></div>
                </div>
            </div>
        </section>

        <!-- SECTION 7 - FULL MENU (ID: menu) -->
        <section id="menu" class="mt-32 border-t border-[#252525] pt-16">
            <div class="grid lg:grid-cols-[24rem_1fr] gap-16">
                <!-- Left -->
                <div>
                    <h2 class="text-5xl font-light uppercase tracking-tight leading-none mb-12">
                        Fresh made,<br>every day
                    </h2>
                    <div class="aspect-[4/3] w-full overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1498804103079-a6351b050096?auto=format&fit=crop&w=900&q=85" alt="Cafe Customers" class="w-full h-full object-cover grayscale">
                    </div>
                </div>

                <!-- Right: Detailed list -->
                <div class="flex flex-col w-full">

                    <!-- Throttle Error Banner -->
                    <div id="throttleError" class="hidden mb-4 px-4 py-3 bg-red-900/90 text-[#efeee6] text-sm uppercase tracking-wider text-center transition-all">
                        ⚠ Too many requests — please wait a moment before trying again.
                    </div>

                    <!-- Search Bar -->
                    <div class="flex gap-3 mb-4">
                        <div class="flex-1 relative">
                            <input type="text" id="menuSearch" placeholder="Search menu by name..." class="w-full bg-transparent border border-[#252525]/40 px-4 py-2.5 text-sm tracking-wider placeholder:text-[#252525]/40 focus:outline-none focus:border-[#252525] transition-colors">
                            <i data-lucide="search" class="absolute right-3 top-1/2 -translate-y-1/2 size-4 opacity-40" stroke-width="1.5"></i>
                        </div>
                    </div>

                    <!-- Price Filter + Sort Row -->
                    <div class="flex flex-wrap gap-3 mb-4">
                        <input type="number" id="menuMinPrice" placeholder="Min price" min="0" step="0.01" class="w-28 bg-transparent border border-[#252525]/40 px-3 py-2 text-sm tracking-wider placeholder:text-[#252525]/40 focus:outline-none focus:border-[#252525] transition-colors">
                        <input type="number" id="menuMaxPrice" placeholder="Max price" min="0" step="0.01" class="w-28 bg-transparent border border-[#252525]/40 px-3 py-2 text-sm tracking-wider placeholder:text-[#252525]/40 focus:outline-none focus:border-[#252525] transition-colors">
                        <select id="menuSortBy" class="bg-transparent border border-[#252525]/40 px-3 py-2 text-sm uppercase tracking-wider focus:outline-none focus:border-[#252525] transition-colors cursor-pointer">
                            <option value="">Sort by</option>
                            <option value="nama">Name</option>
                            <option value="harga">Price</option>
                            <option value="kategori">Category</option>
                        </select>
                        <select id="menuSortOrder" class="bg-transparent border border-[#252525]/40 px-3 py-2 text-sm uppercase tracking-wider focus:outline-none focus:border-[#252525] transition-colors cursor-pointer">
                            <option value="asc">A → Z / Low → High</option>
                            <option value="desc">Z → A / High → Low</option>
                        </select>
                        <button onclick="applyMenuFilters()" class="border border-[#252525] px-5 py-2 text-sm uppercase tracking-wider hover:bg-[#252525] hover:text-[#efeee6] transition-colors">Apply</button>
                        <button onclick="resetMenuFilters()" class="border border-[#252525]/40 px-5 py-2 text-sm uppercase tracking-wider hover:border-[#252525] transition-colors">Reset</button>
                    </div>

                    <!-- Category Filters -->
                    <div class="flex gap-3 mb-6 text-sm uppercase tracking-wider overflow-x-auto pb-2" id="menuFilters">
                        <button onclick="setCategory('')" class="border border-[#252525] px-4 py-2 bg-[#252525] text-[#efeee6] transition-colors filter-btn whitespace-nowrap" data-cat="">All</button>
                        <button onclick="setCategory('makanan')" class="border border-[#252525] px-4 py-2 hover:bg-[#252525] hover:text-[#efeee6] transition-colors filter-btn whitespace-nowrap" data-cat="makanan">Makanan</button>
                        <button onclick="setCategory('minuman')" class="border border-[#252525] px-4 py-2 hover:bg-[#252525] hover:text-[#efeee6] transition-colors filter-btn whitespace-nowrap" data-cat="minuman">Minuman</button>
                        <button onclick="setCategory('snack')" class="border border-[#252525] px-4 py-2 hover:bg-[#252525] hover:text-[#efeee6] transition-colors filter-btn whitespace-nowrap" data-cat="snack">Snack</button>
                        <button onclick="setCategory('coffee')" class="border border-[#252525] px-4 py-2 hover:bg-[#252525] hover:text-[#efeee6] transition-colors filter-btn whitespace-nowrap" data-cat="coffee">Coffee</button>
                    </div>

                    <!-- Menu Items -->
                    <div class="flex flex-col" id="landingMenuContainer">
                        <div class="py-12 text-center text-[#252525]/60 uppercase tracking-wider animate-pulse">
                            Loading menu...
                        </div>
                    </div>

                    <!-- Pagination -->
                    <div id="menuPagination" class="flex items-center justify-between mt-8 pt-6 border-t border-[#252525]/30">
                        <span id="paginationInfo" class="text-sm opacity-60 tracking-wider"></span>
                        <div class="flex gap-2" id="paginationButtons"></div>
                    </div>

                    <!-- Bottom row -->
                    <div class="grid grid-cols-2 gap-8 mt-12 pt-8 border-t border-[#252525]">
                        <p class="text-sm opacity-70 max-w-xs leading-relaxed text-[#252525]/70">
                            We source our beans globally and roast locally. Alternative milks available upon request (+0.50).
                        </p>
                        <div class="flex justify-end">
                            <div class="w-32 aspect-[3/4] overflow-hidden">
                                <img src="https://images.unsplash.com/photo-1513267048331-5611cad62e41?auto=format&fit=crop&w=700&q=85" alt="Iced Drink" class="w-full h-full object-cover">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- SECTION 8 - INFO GRID -->
        <section class="mt-32 grid sm:grid-cols-2 lg:grid-cols-4 border-y border-[#252525] divide-y sm:divide-y-0 sm:divide-x divide-[#252525]">
            <div class="p-8 md:p-12 flex flex-col items-center text-center group cursor-pointer hover:bg-[#252525] hover:text-[#efeee6] transition-colors">
                <i data-lucide="clock" stroke-width="1.5" class="size-8 mb-6 opacity-80 group-hover:opacity-100"></i>
                <h3 class="text-sm uppercase tracking-wider mb-2 font-medium">Hours</h3>
                <p class="text-sm opacity-70 font-light">Mon-Fri: 7am - 6pm<br>Sat-Sun: 8am - 5pm</p>
            </div>
            <div class="p-8 md:p-12 flex flex-col items-center text-center group cursor-pointer hover:bg-[#252525] hover:text-[#efeee6] transition-colors">
                <i data-lucide="map-pin" stroke-width="1.5" class="size-8 mb-6 opacity-80 group-hover:opacity-100"></i>
                <h3 class="text-sm uppercase tracking-wider mb-2 font-medium">Location</h3>
                <p class="text-sm opacity-70 font-light">123 Brew Lane<br>Portland, OR 97204</p>
            </div>
            <div class="p-8 md:p-12 flex flex-col items-center text-center group cursor-pointer hover:bg-[#252525] hover:text-[#efeee6] transition-colors">
                <i data-lucide="credit-card" stroke-width="1.5" class="size-8 mb-6 opacity-80 group-hover:opacity-100"></i>
                <h3 class="text-sm uppercase tracking-wider mb-2 font-medium">Payment</h3>
                <p class="text-sm opacity-70 font-light">Card Only<br>Apple Pay Accepted</p>
            </div>
            <div class="p-8 md:p-12 flex flex-col items-center text-center group cursor-pointer hover:bg-[#252525] hover:text-[#efeee6] transition-colors">
                <i data-lucide="accessibility" stroke-width="1.5" class="size-8 mb-6 opacity-80 group-hover:opacity-100"></i>
                <h3 class="text-sm uppercase tracking-wider mb-2 font-medium">Access</h3>
                <p class="text-sm opacity-70 font-light">Wheelchair Accessible<br>Free Wi-Fi</p>
            </div>
        </section>

        <!-- SECTION 9 - FOOTER (ID: contact) -->
        <footer id="contact" class="mt-32 pb-12">
            <!-- Top -->
            <div class="grid lg:grid-cols-[20rem_1fr] gap-12 mb-24 items-center">
                <a href="#" class="grid size-32 border border-[#252525] rounded-full place-items-center group cursor-pointer hover:bg-[#252525] hover:text-[#efeee6] transition-colors">
                    <span class="text-sm uppercase tracking-wider">Instagram</span>
                </a>
                <h2 class="text-5xl md:text-[8vw] font-light uppercase tracking-tight leading-none text-center lg:text-left">
                    Little Latte Cafe
                </h2>
            </div>
            
            <!-- Bottom -->
            <div class="grid lg:grid-cols-[1fr_12rem_12rem_20rem] gap-12 items-end">
                <div class="text-sm opacity-70 font-light text-[#252525]/70">
                    &copy; 2024 Little Latte Cafe.<br>All rights reserved.
                </div>
                
                <div class="flex flex-col gap-4 text-sm uppercase tracking-wider">
                    <span class="opacity-50 mb-2">Menu</span>
                    <a href="#best" class="hover:opacity-60 transition-opacity">Best Sellers</a>
                    <a href="#menu" class="hover:opacity-60 transition-opacity">Full Menu</a>
                    <a href="#" class="hover:opacity-60 transition-opacity">Seasonal</a>
                </div>
                
                <div class="flex flex-col gap-4 text-sm uppercase tracking-wider">
                    <span class="opacity-50 mb-2">Visit</span>
                    <a href="#about" class="hover:opacity-60 transition-opacity">Our Story</a>
                    <a href="#" class="hover:opacity-60 transition-opacity">Careers</a>
                    <a href="#" class="hover:opacity-60 transition-opacity">Press</a>
                </div>

                <div class="text-xl font-light uppercase tracking-tight lg:text-right">
                    123 Brew Lane<br>Portland, OR 97204
                </div>
            </div>
        </footer>

    </div>

    <script>
        lucide.createIcons();

        const token = localStorage.getItem('jwt_token');
        const userStr = localStorage.getItem('user');

        function updateNav() {
            const guestEls = document.querySelectorAll('.guest-only');
            const authEls = document.querySelectorAll('.auth-only');
            const nameEl = document.getElementById('navUserName');
            if (token && userStr) {
                const user = JSON.parse(userStr);
                guestEls.forEach(el => el.classList.add('hidden'));
                authEls.forEach(el => el.classList.remove('hidden'));
                if(nameEl) nameEl.textContent = user.nama || 'User';
            } else {
                guestEls.forEach(el => el.classList.remove('hidden'));
                authEls.forEach(el => el.classList.add('hidden'));
            }
        }

        function handleLogout() {
            localStorage.removeItem('jwt_token');
            localStorage.removeItem('user');
            updateNav();
            window.location.href = '/';
        }

        updateNav();

        // ─── Menu State ─────────────────────────────────────────────────
        let menuState = { category: '', search: '', minPrice: '', maxPrice: '', sortBy: '', sortOrder: 'asc', page: 1 };

        function setCategory(cat) {
            menuState.category = cat;
            menuState.page = 1;
            document.querySelectorAll('.filter-btn').forEach(btn => {
                if (btn.dataset.cat.toLowerCase() === cat.toLowerCase()) {
                    btn.classList.add('bg-[#252525]', 'text-[#efeee6]');
                } else {
                    btn.classList.remove('bg-[#252525]', 'text-[#efeee6]');
                }
            });
            loadLandingMenu();
        }

        function applyMenuFilters() {
            menuState.search = document.getElementById('menuSearch').value.trim();
            menuState.minPrice = document.getElementById('menuMinPrice').value;
            menuState.maxPrice = document.getElementById('menuMaxPrice').value;
            menuState.sortBy = document.getElementById('menuSortBy').value;
            menuState.sortOrder = document.getElementById('menuSortOrder').value;
            menuState.page = 1;
            loadLandingMenu();
        }

        function resetMenuFilters() {
            menuState = { category: '', search: '', minPrice: '', maxPrice: '', sortBy: '', sortOrder: 'asc', page: 1 };
            document.getElementById('menuSearch').value = '';
            document.getElementById('menuMinPrice').value = '';
            document.getElementById('menuMaxPrice').value = '';
            document.getElementById('menuSortBy').value = '';
            document.getElementById('menuSortOrder').value = 'asc';
            setCategory('');
        }

        function goToPage(p) {
            menuState.page = p;
            loadLandingMenu();
        }

        // Enter key on search
        document.getElementById('menuSearch')?.addEventListener('keydown', e => {
            if (e.key === 'Enter') applyMenuFilters();
        });

        async function loadLandingMenu() {
            const s = menuState;
            const params = new URLSearchParams();
            params.set('limit', '5');
            params.set('page', s.page);
            if (s.category) params.set('category', s.category);
            if (s.search) params.set('search', s.search);
            if (s.minPrice) params.set('min_price', s.minPrice);
            if (s.maxPrice) params.set('max_price', s.maxPrice);
            if (s.sortBy) { params.set('sort_by', s.sortBy); params.set('sort_order', s.sortOrder); }

            const container = document.getElementById('landingMenuContainer');
            const throttleBanner = document.getElementById('throttleError');

            try {
                const res = await fetch(`/api/menu?${params}`, { headers: { 'Accept': 'application/json' } });

                // Handle throttle (429)
                if (res.status === 429) {
                    const err = await res.json();
                    throttleBanner.textContent = '⚠ ' + (err.message || 'Too many requests');
                    throttleBanner.classList.remove('hidden');
                    return;
                }
                throttleBanner.classList.add('hidden');

                const data = await res.json();

                if (data.data && data.data.length > 0) {
                    container.innerHTML = '';
                    data.data.forEach(item => {
                        const stockInfo = item.stok > 0
                            ? `<button onclick="placeOrder(${item.id})" class="text-sm uppercase tracking-wider border border-[#252525] px-4 py-1 hover:bg-[#252525] hover:text-[#efeee6] transition-colors mt-2">Order</button>`
                            : `<span class="text-sm uppercase tracking-wider text-red-800 mt-2 block">Sold Out</span>`;
                        container.innerHTML += `
                            <div class="flex justify-between items-end border-b border-[#252525]/30 py-6">
                                <div>
                                    <h3 class="text-2xl font-light uppercase tracking-tight">${item.nama}</h3>
                                    <span class="text-sm opacity-70 block mt-1">${item.kategori}</span>
                                </div>
                                <div class="text-right flex flex-col items-end">
                                    <div class="text-xl">$${parseFloat(item.harga).toLocaleString('en-US')}</div>
                                    ${stockInfo}
                                </div>
                            </div>`;
                    });
                } else {
                    container.innerHTML = `<div class="py-12 text-center text-[#252525]/60 uppercase tracking-wider">No menu items found.</div>`;
                }

                // Render pagination
                renderPagination(data.pagination);
            } catch (err) {
                console.error("Failed to fetch menu:", err);
            }
        }

        function renderPagination(pg) {
            if (!pg) return;
            const info = document.getElementById('paginationInfo');
            const btns = document.getElementById('paginationButtons');
            const start = (pg.page - 1) * pg.limit + 1;
            const end = Math.min(pg.page * pg.limit, pg.total);
            info.textContent = pg.total > 0 ? `Showing ${start}–${end} of ${pg.total}` : 'No results';

            let html = '';
            // Prev
            if (pg.page > 1) {
                html += `<button onclick="goToPage(${pg.page - 1})" class="border border-[#252525] px-3 py-1.5 text-sm uppercase tracking-wider hover:bg-[#252525] hover:text-[#efeee6] transition-colors">‹ Prev</button>`;
            }
            // Page numbers
            for (let i = 1; i <= pg.totalPages; i++) {
                const active = i === pg.page ? 'bg-[#252525] text-[#efeee6]' : 'hover:bg-[#252525] hover:text-[#efeee6]';
                html += `<button onclick="goToPage(${i})" class="border border-[#252525] px-3 py-1.5 text-sm tracking-wider transition-colors ${active}">${i}</button>`;
            }
            // Next
            if (pg.page < pg.totalPages) {
                html += `<button onclick="goToPage(${pg.page + 1})" class="border border-[#252525] px-3 py-1.5 text-sm uppercase tracking-wider hover:bg-[#252525] hover:text-[#efeee6] transition-colors">Next ›</button>`;
            }
            btns.innerHTML = html;
        }

        async function placeOrder(menuId) {
            if (!token) { alert('Please login first to place an order.'); window.location.href = '/login'; return; }
            try {
                const res = await fetch('/api/orders', {
                    method: 'POST',
                    headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json', 'Content-Type': 'application/json' },
                    body: JSON.stringify({ menu_id: menuId, jumlah: 1 })
                });
                const data = await res.json();
                if (res.ok || res.status === 201) { alert('Order placed successfully! (Status: Pending)'); loadLandingMenu(); }
                else { alert('Failed to place order: ' + (data.message || 'Unknown error')); }
            } catch (err) { alert('Network error. Please try again.'); }
        }

        // Init
        loadLandingMenu();
    </script>
</body>
</html>
