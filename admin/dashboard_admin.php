
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


session_start();
require '../config/config.php';

if (!isset($_SESSION['id'])) {
    header("Location: ../login/login.php");
    exit;
}

$page = isset($_GET['page']) ? $_GET['page'] : 'overview';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin </title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8FAFC; }
        .glass-sidebar { background: #FFFFFF; border-right: 1px solid #E2E8F0; }
        .nav-pill { transition: all 0.3s ease; color: #64748B; }

        /* State Active: Professional Blue Glow */
        .nav-active {
            background: linear-gradient(135deg, #2563EB 0%, #3B82F6 100%);
            color: white !important;
            box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.3);
        }

        .nav-pill:hover:not(.nav-active) {
            background: #EFF6FF;
            color: #2563EB;
        }

        /* Custom Scrollbar untuk area konten */
        .custom-scroll::-webkit-scrollbar { width: 6px; }
        .custom-scroll::-webkit-scrollbar-track { background: transparent; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #E2E8F0; border-radius: 10px; }
    </style>
</head>
<body class="flex h-screen p-6 gap-6">

    <aside class="w-24 lg:w-72 glass-sidebar rounded-[3rem] shadow-xl flex flex-col p-6 items-center lg:items-stretch">
        <div class="mb-12 mt-4 text-center">
            <div class="w-14 h-14 bg-gradient-to-br from-[#2563EB] to-[#3B82F6] rounded-2xl mx-auto flex items-center justify-center text-white shadow-lg mb-4">
                <i class="fas fa-warehouse text-2xl"></i>
            </div>
            <h1 class="hidden lg:block font-black text-xl tracking-tighter text-slate-800 uppercase italic">Batara<span class="text-blue-600">Sura</span></h1>
        </div>

       <nav class="flex-1 space-y-3 relative">
    <a href="?page=overview" class="nav-pill flex items-center gap-4 px-6 py-4 rounded-[1.5rem] font-bold <?= ($page == 'overview' || $page == '') ? 'nav-active' : '' ?>">
        <div class="w-8 flex justify-center">
            <i class="fas fa-chart-pie text-lg"></i>
        </div>
        <span class="hidden lg:block text-sm tracking-tight">Overview</span>
    </a>
    
    <a href="?page=daftar-material" class="nav-pill flex items-center gap-4 px-6 py-4 rounded-[1.5rem] font-bold <?= in_array($page, ['daftar-material', 'tambah-data', 'ubah-data']) ? 'nav-active' : '' ?>">
        <div class="w-8 flex justify-center">
            <i class="fas fa-box text-lg"></i>
        </div>
        <span class="hidden lg:block text-sm tracking-tight">Daftar Material</span>
    </a>
    
    <a href="?page=stok-material" class="nav-pill flex items-center gap-4 px-6 py-4 rounded-[1.5rem] font-bold <?= in_array($page, ['stok-material', 'tambah-stok', 'upgrade-stok']) ? 'nav-active' : '' ?>">
        <div class="w-8 flex justify-center">
            <i class="fas fa-layer-group text-lg"></i>
        </div>
        <span class="hidden lg:block text-sm tracking-tight">Stok Material</span>
    </a>

    <a href="?page=proses-produksi" class="nav-pill flex items-center gap-4 px-6 py-4 rounded-[1.5rem] font-bold <?= ($page == 'proses-produksi') ? 'nav-active' : '' ?>">
        <div class="w-8 flex justify-center">
            <i class="fas fa-vial text-lg"></i>
        </div>
        <span class="hidden lg:block text-sm tracking-tight">Proses Produksi</span>
    </a>
    
    <div class="group relative">
        <div class="nav-pill flex items-center justify-between gap-4 px-6 py-4 rounded-[1.5rem] font-bold cursor-pointer transition-none <?= (strpos($page, 'laporan') !== false) ? 'nav-active' : '' ?>">
            <div class="flex items-center gap-4">
                <div class="w-8 flex justify-center">
                    <i class="fas fa-file-contract text-lg"></i>
                </div>
                <span class="hidden lg:block text-sm tracking-tight text-inherit">Laporan</span>
            </div>
            <i class="fas fa-chevron-right text-[10px] opacity-100 group-hover:translate-x-1 transition-transform hidden lg:block"></i>
        </div>

        <div class="absolute left-full top-0 ml-2 w-64 opacity-0 invisible group-hover:opacity-100 group-hover:visible z-[999]">
            <div class="bg-white p-3 rounded-[2.5rem] shadow-2xl border border-slate-100 flex flex-col gap-2">
                
                <a href="?page=laporan-stok" 
                   class="flex items-center gap-4 px-5 py-5 rounded-3xl transition-all
                   <?= ($page == 'laporan-stok') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-100' : 'bg-emerald-500 text-white hover:bg-emerald-600 shadow-md' ?>">
                    <div class="w-6 flex justify-center">
                        <i class="fas fa-layer-group text-sm"></i>
                    </div>
                    <span class="text-[11px] font-black uppercase tracking-widest">Stok Material</span>
                </a>

                <a href="?page=laporan-produksi" 
                   class="flex items-center gap-4 px-5 py-5 rounded-3xl transition-all
                   <?= ($page == 'laporan-produksi') ? 'bg-amber-500 text-white shadow-lg shadow-amber-100' : 'bg-amber-400 text-amber-950 hover:bg-amber-500 shadow-md' ?>">
                    <div class="w-6 flex justify-center">
                        <i class="fas fa-industry text-sm"></i>
                    </div>
                    <span class="text-[11px] font-black uppercase tracking-widest text-inherit">Laporan Produksi</span>
                </a>

            </div>
        </div>
    </div>
</nav>
        <a href="../login/logout.php" class="mt-auto flex items-center justify-center lg:justify-start gap-4 px-6 py-4 text-slate-400 hover:text-red-500 transition-all font-black">
            <i class="fas fa-sign-out-alt"></i> <span class="hidden lg:block text-sm">Logout</span>
        </a>
    </aside>

    <main class="flex-1 flex flex-col overflow-hidden">
        <header class="flex justify-between items-center mb-8 px-4">
            <div>
                <h2 class="text-3xl font-black text-slate-800 tracking-tight capitalize"><?= str_replace('-', ' ', $page) ?></h2>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.3em]">BataraSura Internal System</p>
            </div>
            
            <div class="flex items-center gap-4 bg-white p-2 pr-6 rounded-2xl shadow-sm border border-slate-100">
                <img src="https://ui-avatars.com/api/?name=Admin&background=2563EB&color=fff" class="w-10 h-10 rounded-xl shadow-md shadow-blue-100">
                <div class="hidden sm:block">
                    <p class="text-xs font-black text-slate-800 leading-none">Indra Admin</p>
                    <p class="text-[10px] text-blue-500 font-bold uppercase tracking-widest">Administrator</p>
                </div>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto pr-2 custom-scroll">
            <?php 
                // Memanggil file routing terpisah
                include 'pages/route.php'; 
            ?>
        </div>
    </main>
</body>
</html>