<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Batara Sura Mulia</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-[#F8FAFC] text-slate-900 overflow-x-hidden">

    <nav class="fixed w-full z-50 glass-nav border-b border-slate-200/60">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 bg-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-200">
                        <i class="fas fa-layer-group text-white text-xl"></i>
                    </div>
                    <a href="" class="text-xl font-extrabold tracking-tight hover:text-blue-900">Batara Sura Mulia</a>
                </div>
                
                <div class="hidden md:flex items-center gap-10 font-semibold text-sm text-slate-600">

                    <a href="login/login.php" class="bg-slate-900 text-white px-7 py-2.5 rounded-full hover:bg-blue-600 transition-all active:scale-95 shadow-md">
                        Login
                    </a>
                </div>

                <div class="md:hidden flex items-center">
                    <button id="menu-toggle" class="w-10 h-10 flex items-center justify-center bg-slate-100 rounded-lg text-slate-600 focus:outline-none">
                        <i class="fas fa-bars text-xl" id="menu-icon"></i>
                    </button>
                </div>
            </div>
        </div>

        <div id="mobile-menu" class="md:hidden bg-white border-t border-slate-100">
            <div class="grid grid-cols-3 gap-3">
                <a href="#fitur" class="flex flex-col items-center p-4 bg-slate-50 rounded-2xl border border-slate-100 active:bg-blue-50 transition">
                    <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mb-2">
                        <i class="fas fa-rocket text-sm"></i>
                    </div>
                    <span class="text-[10px] font-bold text-slate-700 uppercase">Fitur</span>
                </a>
                
                <a href="#tentang" class="flex flex-col items-center p-4 bg-slate-50 rounded-2xl border border-slate-100 active:bg-blue-50 transition">
                    <div class="w-10 h-10 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mb-2">
                        <i class="fas fa-info-circle text-sm"></i>
                    </div>
                    <span class="text-[10px] font-bold text-slate-700 uppercase">Tentang</span>
                </a>

               <a href="login/login.php" class="flex flex-col items-center p-4 bg-slate-50 rounded-2xl border border-slate-100 active:bg-blue-50 transition">
                   <div class="w-10 h-10 bg-ivory-100 text-ivory-600 rounded-full flex items-center justify-center mb-2">
                        <i class="fas fa-user-lock text-sm"></i>
                    </div>
                    <span class="text-[10px] font-bold text-slate-600 uppercase">Login</span>
                </a>
            </div>
        </div>
    </nav>

    <section class="relative pt-40 pb-20 px-6 hero-gradient">
        <div class="max-w-7xl mx-auto grid lg:grid-cols-2 gap-16 items-center">
            <div class="z-10 text-center lg:text-left">
               
                <h1 class="text-4xl md:text-5xl lg:text-7xl font-extrabold text-slate-900 leading-[1.1] mb-8">
                    Kelola Material <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-500">Lebih Cerdas.</span>
                </h1>
                <p class="text-lg text-slate-500 mb-10 max-w-xl mx-auto lg:mx-0 leading-relaxed">
                    Sistem informasi untuk pemantauan material produksi radiator PT BataraSura Mulia secara real-time.
                </p>
                <div class="flex flex-col sm:flex-row justify-center lg:justify-start gap-4">
                    <a href="#fitur" class="bg-blue-600 text-white px-10 py-4 rounded-2xl font-bold hover:bg-blue-700 transition-all shadow-xl shadow-blue-200 flex items-center justify-center gap-2">
                        Eksplorasi Fitur <i class="fas fa-chevron-down text-sm"></i>
                    </a>
                </div>
            </div>
            <div class="relative flex justify-center lg:justify-end">
                <div class="absolute w-[300px] h-[300px] bg-blue-200/30 rounded-full blur-[80px] -z-10"></div>
                <img src="https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?auto=format&fit=crop&q=80&w=1000" 
                     class="w-full max-w-lg rounded-[2.5rem] shadow-2xl animate-float border-[8px] md:border-[12px] border-white" alt="System Preview">
            </div>
        </div>
    </section>

    <section id="fitur" class="py-24 bg-white relative">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-16 gap-6">
                <div class="max-w-2xl">
                    <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900 mb-4">Kendali Total di Ujung Jari</h2>
                    <p class="text-slate-500">Sederhanakan alur kerja industri dengan modul fungsional kami.</p>
                </div>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
    <?php
    $features = [
        ['icon' => 'fa-server', 'title' => 'Daftar Material', 'desc'  => 'Pusat data terintegrasi untuk katalogisasi bahan baku.'],
        ['icon' => 'fa-box-open', 'title' => 'Stock Material', 'desc' => 'Pantau ketersediaan stok live dengan alert dini.'],
        ['icon' => 'fa-vial', 'title' => 'Proses Produksi', 'desc' => 'Estimasi kebutuhan bahan secara presisi.'],
        ['icon' => 'fa-chart-pie', 'title' => 'Laporan Stok', 'desc' => 'Analisis performa inventaris melalui laporan berkala.']
    ];

    foreach($features as $f):
    ?>
    <a href="login/login.php" class="block group p-10 bg-slate-50 rounded-[2rem] hover:bg-white hover:shadow-xl transition-all border border-transparent hover:border-slate-100 no-underline text-inherit">
        
        <div class="w-14 h-14 rounded-2xl bg-white shadow-sm flex items-center justify-center text-blue-600 text-2xl mb-8 group-hover:bg-blue-600 group-hover:text-white transition-all duration-300">
            <i class="fas <?= $f['icon']; ?>"></i>
        </div>

        <h3 class="text-xl font-bold mb-4 group-hover:text-blue-600 transition-colors italic-none">
            <?= $f['title']; ?>
        </h3>

        <p class="text-slate-500 text-sm leading-relaxed">
            <?= $f['desc']; ?>
        </p>

        
    </a>
    <?php endforeach; ?>
</div>
        </div>
    </section>

    <footer class="bg-slate-900 text-slate-500 py-12 px-6 text-center border-t border-slate-800">
        <p class="text-sm font-medium">&copy; <?php echo date('Y'); ?> PT BataraSura Mulia. All rights reserved.</p>
    </footer>

    <script>
        const menuToggle = document.getElementById('menu-toggle');
        const mobileMenu = document.getElementById('mobile-menu');
        const menuIcon = document.getElementById('menu-icon');

        menuToggle.addEventListener('click', () => {
            const isOpen = mobileMenu.classList.toggle('open');
            
            // Ganti ikon bars ke times (X)
            if (isOpen) {
                menuIcon.classList.replace('fa-bars', 'fa-times');
            } else {
                menuIcon.classList.replace('fa-times', 'fa-bars');
            }
        });

        // Menutup menu otomatis jika layar di-resize ke ukuran desktop
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 768) {
                mobileMenu.classList.remove('open');
                menuIcon.classList.replace('fa-times', 'fa-bars');
            }
        });

        // Smooth scroll close menu
        document.querySelectorAll('#mobile-menu a').forEach(link => {
            link.addEventListener('click', () => {
                mobileMenu.classList.remove('open');
                menuIcon.classList.replace('fa-times', 'fa-bars');
            });
        });
    </script>
</body>
</html>