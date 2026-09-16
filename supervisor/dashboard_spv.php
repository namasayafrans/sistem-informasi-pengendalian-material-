<?php
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
    <title>Supervisor Dashboard - BataraSura</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8FAFC; }
        .glass-sidebar { background: #FFFFFF; border-right: 1px solid #E2E8F0; }
        .nav-pill { transition: all 0.3s ease; color: #64748B; }

        /* State Active: Professional Indigo/Violet untuk Supervisor */
        .nav-active {
            background: linear-gradient(135deg, #4F46E5 0%, #6366F1 100%);
            color: white !important;
            box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.3);
        }

        .nav-pill:hover:not(.nav-active) {
            background: #EEF2FF;
            color: #4F46E5;
        }

        .custom-scroll::-webkit-scrollbar { width: 6px; }
        .custom-scroll::-webkit-scrollbar-track { background: transparent; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #E2E8F0; border-radius: 10px; }
    </style>
</head>
<body class="flex h-screen p-6 gap-6">

    <!-- Sidebar Khusus Supervisor -->
    <aside class="w-24 lg:w-72 glass-sidebar rounded-[3rem] shadow-xl flex flex-col p-6 items-center lg:items-stretch">
        <div class="mb-12 mt-4 text-center">
            <div class="w-14 h-14 bg-gradient-to-br from-[#4F46E5] to-[#6366F1] rounded-2xl mx-auto flex items-center justify-center text-white shadow-lg mb-4">
                <i class="fas fa-user-shield text-2xl"></i>
            </div>
            <h1 class="hidden lg:block font-black text-xl tracking-tighter text-slate-800 uppercase italic">Batara<span class="text-indigo-600">Sura</span></h1>
        </div>

        <nav class="flex-1 space-y-4">
            <!-- Menu Overview -->
            <a href="?page=overview" class="nav-pill flex items-center gap-4 px-6 py-4 rounded-[1.5rem] font-bold <?= ($page == 'overview') ? 'nav-active' : '' ?>">
                <div class="w-8 flex justify-center">
                    <i class="fas fa-chart-line text-lg"></i>
                </div>
                <span class="hidden lg:block text-sm tracking-tight">Overview</span>
            </a>
            
            <!-- Menu Monitoring Stok (Hanya View/Monitoring) -->
            <a href="?page=monitoring-stok" class="nav-pill flex items-center gap-4 px-6 py-4 rounded-[1.5rem] font-bold <?= ($page == 'monitoring-stok') ? 'nav-active' : '' ?>">
                <div class="w-8 flex justify-center">
                    <i class="fas fa-eye text-lg"></i>
                </div>
                <span class="hidden lg:block text-sm tracking-tight">Monitoring Stok</span>
            </a>
        </nav>

        <!-- Logout -->
        <a href="../login/logout.php" class="mt-auto flex items-center justify-center lg:justify-start gap-4 px-6 py-4 text-slate-400 hover:text-red-500 transition-all font-black">
            <i class="fas fa-sign-out-alt"></i> 
            <span class="hidden lg:block text-sm">Logout</span>
        </a>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col overflow-hidden">
        <header class="flex justify-between items-center mb-8 px-4">
            <div>
                <h2 class="text-3xl font-black text-slate-800 tracking-tight capitalize"><?= str_replace('-', ' ', $page) ?></h2>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.3em]">Supervisor Control Panel</p>
            </div>
            
            <div class="flex items-center gap-4 bg-white p-2 pr-6 rounded-2xl shadow-sm border border-slate-100">
                <img src="https://ui-avatars.com/api/?name=Supervisor&background=4F46E5&color=fff" class="w-10 h-10 rounded-xl shadow-md shadow-indigo-100">
                <div class="hidden sm:block">
                    <p class="text-xs font-black text-slate-800 leading-none">Sandra SPV</p>
                    <p class="text-[10px] text-indigo-500 font-bold uppercase tracking-widest">Supervisor</p>
                </div>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto pr-2 custom-scroll">
            <?php 
                // Pastikan file route-supervisor.php ada di folder pages
                if(file_exists('pages/route.php')){
                    include 'pages/route.php'; 
                } else {
                    echo "<div class='p-10 bg-white rounded-3xl border border-dashed border-slate-300 text-center text-slate-400 font-bold'>Halaman belum tersedia di folder pages.</div>";
                }
            ?>
        </div>
    </main>
</body>
</html>