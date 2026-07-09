<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Little Latte Cafe</title>
    
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
    </style>
</head>
<body class="bg-[#efeee6] text-[#252525] antialiased selection:bg-[#252525] selection:text-[#efeee6] font-light min-h-screen flex flex-col">

    <!-- HEADER MINIMAL -->
    <header class="flex items-center justify-between py-8 px-5 sm:px-8 lg:px-14 w-full max-w-[112rem] mx-auto">
        <a href="/" class="text-xl font-light uppercase tracking-tight hover:opacity-60 transition-opacity">Little Latte Cafe</a>
        <a href="/login" class="font-light uppercase tracking-tight text-sm hover:opacity-60 transition-opacity border-b border-[#252525]/20 pb-1">Sign In</a>
    </header>

    <!-- MAIN CONTENT -->
    <main class="flex-grow flex items-center justify-center px-5 py-12">
        <div class="w-full max-w-md">
            
            <div class="mb-12 text-center">
                <!-- Diamond Motif -->
                <div class="flex justify-center gap-3 mb-8">
                    <div class="size-3 bg-[#252525] rotate-45"></div>
                    <div class="size-3 bg-[#efeee6] border border-[#252525] rotate-45"></div>
                    <div class="size-3 bg-[#252525] rotate-45"></div>
                </div>
                <h1 class="text-4xl lg:text-5xl font-light uppercase tracking-tight leading-none mb-4">Join<br>Us</h1>
                <p class="text-[#252525]/70 text-sm font-light uppercase tracking-tight">Create an account to start ordering.</p>
            </div>

            <!-- ALERT BOX (Hidden by default) -->
            <div id="alertBox" class="hidden border border-[#252525] p-4 mb-8 text-sm font-light uppercase tracking-tight flex items-start gap-3">
                <i data-lucide="alert-circle" class="size-5 shrink-0" stroke-width="1.5"></i>
                <span id="alertMessage"></span>
            </div>

            <form id="registerForm" class="flex flex-col gap-8">
                <!-- Name Input -->
                <div class="flex flex-col gap-2">
                    <label for="name" class="text-xs font-light uppercase tracking-tight text-[#252525]/70">Full Name</label>
                    <input type="text" id="name" name="name" required
                        class="w-full bg-transparent border-b border-[#252525]/30 py-3 text-lg focus:outline-none focus:border-[#252525] transition-colors placeholder:text-[#252525]/20 rounded-none"
                        placeholder="John Doe">
                </div>

                <!-- Email Input -->
                <div class="flex flex-col gap-2">
                    <label for="email" class="text-xs font-light uppercase tracking-tight text-[#252525]/70">Email Address</label>
                    <input type="email" id="email" name="email" required
                        class="w-full bg-transparent border-b border-[#252525]/30 py-3 text-lg focus:outline-none focus:border-[#252525] transition-colors placeholder:text-[#252525]/20 rounded-none"
                        placeholder="hello@example.com">
                </div>

                <!-- Password Input -->
                <div class="flex flex-col gap-2">
                    <label for="password" class="text-xs font-light uppercase tracking-tight text-[#252525]/70">Password (Min. 6 chars)</label>
                    <input type="password" id="password" name="password" required minlength="6"
                        class="w-full bg-transparent border-b border-[#252525]/30 py-3 text-lg focus:outline-none focus:border-[#252525] transition-colors placeholder:text-[#252525]/20 rounded-none"
                        placeholder="••••••••">
                </div>

                <!-- Submit Button -->
                <button type="submit" id="submitBtn" class="mt-4 w-full border border-[#252525] py-4 font-light uppercase tracking-tight hover:bg-[#252525] hover:text-[#efeee6] transition-colors flex justify-center items-center gap-2">
                    <span>Create Account</span>
                    <i data-lucide="arrow-right" class="size-4" stroke-width="1.5"></i>
                </button>
            </form>

        </div>
    </main>

    <script>
        lucide.createIcons();

        document.getElementById('registerForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const alertBox = document.getElementById('alertBox');
            const alertMessage = document.getElementById('alertMessage');
            const submitBtn = document.getElementById('submitBtn');

            // Reset UI
            alertBox.classList.add('hidden');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span>Processing...</span>';

            try {
                const response = await fetch('/api/auth/register', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    // Send role default as 'pengunjung' according to rules
                    body: JSON.stringify({ nama: name, email, password, role: 'pengunjung' })
                });

                const data = await response.json();

                if (response.ok || response.status === 201) {
                    // Registration success, redirect to login
                    window.location.href = '/login?registered=true';
                } else {
                    // Format validation errors
                    if(data.errors) {
                        const firstError = Object.values(data.errors)[0][0];
                        alertMessage.textContent = firstError;
                    } else {
                        alertMessage.textContent = data.message || 'Registration failed.';
                    }
                    alertBox.classList.remove('hidden');
                }
            } catch (error) {
                alertMessage.textContent = 'Network error. Please try again later.';
                alertBox.classList.remove('hidden');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<span>Create Account</span><i data-lucide="arrow-right" class="size-4" stroke-width="1.5"></i>';
                lucide.createIcons();
            }
        });
    </script>
</body>
</html>
