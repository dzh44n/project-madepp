
<?php
require 'function.php';
require 'cek.php';

// Menghitung statistik dashboard
$qJenis = mysqli_query($conn, "SELECT COUNT(*) AS total FROM stock");
$totalJenis = mysqli_fetch_assoc($qJenis)['total'];

$qStok = mysqli_query($conn, "SELECT COALESCE(SUM(stock), 0) AS total FROM stock");
$totalStok = mysqli_fetch_assoc($qStok)['total'];

$qMasuk = mysqli_query($conn, "SELECT COALESCE(SUM(qty), 0) AS total FROM masuk");
$totalMasuk = mysqli_fetch_assoc($qMasuk)['total'];

$qKeluar = mysqli_query($conn, "SELECT COALESCE(SUM(qty), 0) AS total FROM keluar");
$totalKeluar = mysqli_fetch_assoc($qKeluar)['total'];

// Data barang
$ambilsemuadatastock = mysqli_query($conn, "SELECT * FROM stock ORDER BY idbarang DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Dashboard | StockBarang</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
          rel="stylesheet">

    <style>
        :root {
            color-scheme: light;
            --bg: #f4f7fb;
            --surface: #ffffff;
            --text: #172b4d;
            --muted: #7b879b;
            --border: #e7edf5;
            --sidebar: #14294d;
            --primary: #3478f6;
            --input: #ffffff;
        }

        body.dark-mode {
            color-scheme: dark;
            --bg: #101827;
            --surface: #1b2638;
            --text: #e8eef8;
            --muted: #a1aec2;
            --border: #303d51;
            --sidebar: #111c2e;
            --primary: #6095ff;
            --input: #202d42;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            background: var(--bg);
            color: var(--text);
            font-family: "Segoe UI", Arial, sans-serif;
            transition: background .25s, color .25s;
        }

        a { text-decoration: none; }

        .sidebar {
            width: 250px;
            position: fixed;
            inset: 0 auto 0 0;
            background: var(--sidebar);
            color: white;
            padding: 25px 16px;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            transition: transform .25s;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 21px;
            font-weight: 700;
            padding: 5px 12px 35px;
            color: white;
        }

        .brand-icon {
            width: 42px;
            height: 42px;
            display: grid;
            place-items: center;
            border-radius: 13px;
            background: #3478f6;
        }

        .menu-label {
            color: #8fa5c7;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1.5px;
            padding: 0 13px;
            margin-bottom: 12px;
        }

        .menu-link {
            display: flex;
            align-items: center;
            gap: 13px;
            padding: 13px 14px;
            margin-bottom: 7px;
            border-radius: 10px;
            color: #c5d2e7;
            font-size: 14px;
            transition: .2s;
        }

        .menu-link i {
            width: 20px;
            text-align: center;
        }

        .menu-link:hover,
        .menu-link.active {
            background: #2e64ba;
            color: white;
        }

        .sidebar-bottom {
            margin-top: auto;
        }

        .main {
            margin-left: 250px;
            min-height: 100vh;
        }

        .topbar {
            min-height: 76px;
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            padding: 15px 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
        }

        .topbar-title {
            font-weight: 700;
            font-size: 18px;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .icon-button {
            width: 42px;
            height: 42px;
            border: 1px solid var(--border);
            background: var(--surface);
            color: var(--text);
            border-radius: 12px;
            cursor: pointer;
        }

        .icon-button:hover { color: var(--primary); }

        .content {
            padding: 30px;
            max-width: 1600px;
            margin: auto;
        }

        .welcome {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            margin-bottom: 28px;
        }

        .welcome h1 {
            font-size: 27px;
            font-weight: 750;
            margin-bottom: 8px;
        }

        .welcome p {
            color: var(--muted);
            margin: 0;
            font-size: 14px;
        }

        .primary-button {
            border: 0;
            background: var(--primary);
            color: white;
            border-radius: 10px;
            padding: 12px 17px;
            font-size: 14px;
            font-weight: 600;
        }

        .primary-button:hover {
            color: white;
            filter: brightness(1.08);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 18px;
            margin-bottom: 26px;
        }

        .stat-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 22px;
            min-width: 0;
            box-shadow: 0 3px 12px rgba(20, 40, 70, .025);
        }

        .stat-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            margin-bottom: 20px;
        }

        .stat-icon {
            width: 46px;
            height: 46px;
            display: grid;
            place-items: center;
            border-radius: 13px;
            font-size: 19px;
        }

        .blue { background: #e8f0ff; color: #3478f6; }
        .green { background: #e3f8ed; color: #1b9a60; }
        .orange { background: #fff0df; color: #e99127; }
        .purple { background: #f0e8ff; color: #8654dc; }

        .stat-label {
            color: var(--muted);
            font-size: 13px;
            margin-bottom: 8px;
        }

        .stat-number {
            color: var(--text);
            font-size: 30px;
            font-weight: 750;
            overflow-wrap: anywhere;
        }

        .stat-note {
            color: var(--muted);
            font-size: 12px;
            margin-top: 7px;
        }

        .panel {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            overflow: hidden;
        }

        .panel-header {
            padding: 22px 24px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 15px;
        }

        .panel-header h2 {
            font-size: 17px;
            font-weight: 700;
            margin: 0 0 5px;
        }

        .panel-header p {
            color: var(--muted);
            font-size: 13px;
            margin: 0;
        }

        .search-box {
            background: var(--input);
            border: 1px solid var(--border);
            border-radius: 10px;
            display: flex;
            align-items: center;
            padding: 0 12px;
            gap: 9px;
            color: var(--muted);
        }

        .search-box input {
            width: 190px;
            max-width: 100%;
            padding: 11px 0;
            border: none;
            outline: none;
            background: transparent;
            color: var(--text);
            font-size: 13px;
        }

        .table-responsive { padding: 0 20px 15px; }

        table {
            width: 100%;
            border-collapse: collapse;
            color: var(--text) !important;
        }

        thead th {
            background: var(--surface) !important;
            color: var(--muted) !important;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .5px;
            padding: 18px 12px !important;
            border-bottom: 1px solid var(--border) !important;
            white-space: nowrap;
        }

        tbody td {
            background: var(--surface) !important;
            color: var(--text) !important;
            padding: 17px 12px !important;
            font-size: 13px;
            border-bottom: 1px solid var(--border) !important;
            vertical-align: middle;
        }

        tbody tr:last-child td { border-bottom: none !important; }

        .item-name {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 650;
        }

        .item-icon {
            width: 38px;
            height: 38px;
            flex-shrink: 0;
            display: grid;
            place-items: center;
            background: var(--bg);
            color: var(--primary);
            border-radius: 10px;
        }

        .stock-badge {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 7px;
            font-size: 12px;
            font-weight: 700;
            background: #e3f8ed;
            color: #16834e;
        }

        .stock-low {
            background: #fff0df;
            color: #b86a0c;
        }

        .empty-state {
            text-align: center;
            color: var(--muted) !important;
            padding: 35px !important;
        }

        .modal-content {
            background: var(--surface);
            color: var(--text);
            border: 1px solid var(--border);
            border-radius: 16px;
        }

        .modal-header, .modal-footer {
            border-color: var(--border);
        }

        .form-control {
            background: var(--input);
            color: var(--text);
            border-color: var(--border);
        }

        .form-control:focus {
            background: var(--input);
            color: var(--text);
        }

        .btn-close { filter: var(--close-filter, none); }

        body.dark-mode .blue,
        body.dark-mode .green,
        body.dark-mode .orange,
        body.dark-mode .purple {
            background: #29384d;
            color: #b9d2ff;
        }

        body.dark-mode .stock-badge {
            background: #1f4939;
            color: #8ee0b4;
        }

        body.dark-mode .stock-low {
            background: #503b27;
            color: #ffd09a;
        }

        body.dark-mode .btn-close {
            filter: invert(1);
        }

        .mobile-menu { display: none; }

        @media (max-width: 1100px) {
            .stats-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .content { padding: 22px; }
        }

        @media (max-width: 700px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .main { margin-left: 0; }
            .mobile-menu { display: inline-grid; place-items: center; }
            .topbar { padding: 13px 16px; }
            .content { padding: 20px 14px; }
            .welcome { align-items: flex-start; flex-direction: column; }
            .welcome h1 { font-size: 23px; }
            .stats-grid { gap: 12px; }
            .stat-card { padding: 15px; }
            .stat-number { font-size: 25px; }
            .stat-icon { width: 38px; height: 38px; }
            .panel-header { padding: 18px; }
            .search-box { width: 100%; }
            .search-box input { width: 100%; }
        }
    </style>
</head>

<body>

<!-- SIDEBAR -->
<aside class="sidebar" id="sidebar">

    <a href="index.php" class="brand">
        <span class="brand-icon">
            <i class="fa-solid fa-boxes-stacked"></i>
        </span>
        StockBarang
    </a>

    <div class="menu-label">MENU UTAMA</div>

    <a href="index.php" class="menu-link active">
        <i class="fa-solid fa-house"></i>
        Dashboard
    </a>

    <a href="index.php" class="menu-link">
        <i class="fa-solid fa-boxes-stacked"></i>
        Stok Barang
    </a>

    <a href="masuk.php" class="menu-link">
        <i class="fa-solid fa-arrow-right-to-bracket"></i>
        Barang Masuk
    </a>

    <a href="keluar.php" class="menu-link">
        <i class="fa-solid fa-arrow-right-from-bracket"></i>
        Barang Keluar
    </a>

    <div class="sidebar-bottom">
        <div class="menu-label">AKUN</div>

        <a href="logout.php" class="menu-link"
           onclick="return confirm('Apakah kamu yakin ingin logout?')">
            <i class="fa-solid fa-right-from-bracket"></i>
            Logout
        </a>
    </div>

</aside>

<!-- KONTEN UTAMA -->
<div class="main">

    <header class="topbar">
        <div class="topbar-right">
            <button class="icon-button mobile-menu"
                    id="mobileMenu" aria-label="Buka menu">
                <i class="fa-solid fa-bars"></i>
            </button>

            <span class="topbar-title">Dashboard</span>
        </div>

        <div class="topbar-right">
            <button class="icon-button" id="themeToggle"
                    aria-label="Ganti mode warna"
                    title="Ganti mode gelap/terang">
                <i class="fa-solid fa-moon" id="themeIcon"></i>
            </button>

            <div class="text-end">
                <div style="font-size:13px;font-weight:700;">
                    Administrator
                </div>
                <div style="font-size:11px;color:var(--muted);">
                    Pengelola Gudang
                </div>
            </div>

            <div class="brand-icon"
                 style="width:40px;height:40px;border-radius:50%;color:white;">
                <i class="fa-solid fa-user"></i>
            </div>
        </div>
    </header>

    <main class="content">

        <section class="welcome">
            <div>
                <h1>Selamat Datang 👋</h1>
                <p>Berikut ringkasan persediaan barang di gudang kamu.</p>
            </div>

            <button class="primary-button"
                    data-bs-toggle="modal"
                    data-bs-target="#myModal">
                <i class="fa-solid fa-plus"></i>
                Tambah Barang
            </button>
        </section>

        <!-- KARTU STATISTIK -->
        <section class="stats-grid">

            <div class="stat-card">
                <div class="stat-top">
                    <span class="stat-label">Total Jenis Barang</span>
                    <span class="stat-icon blue">
                        <i class="fa-solid fa-boxes-stacked"></i>
                    </span>
                </div>
                <div class="stat-number">
                    <?= number_format((int)$totalJenis, 0, ',', '.') ?>
                </div>
                <div class="stat-note">Jenis barang terdaftar</div>
            </div>

            <div class="stat-card">
                <div class="stat-top">
                    <span class="stat-label">Total Stok</span>
                    <span class="stat-icon green">
                        <i class="fa-solid fa-cubes"></i>
                    </span>
                </div>
                <div class="stat-number">
                    <?= number_format((int)$totalStok, 0, ',', '.') ?>
                </div>
                <div class="stat-note">Jumlah unit dalam stok saat ini</div>
            </div>

            <div class="stat-card">
                <div class="stat-top">
                    <span class="stat-label">Total Barang Masuk</span>
                    <span class="stat-icon orange">
                        <i class="fa-solid fa-arrow-down"></i>
                    </span>
                </div>
                <div class="stat-number">
                    <?= number_format((int)$totalMasuk, 0, ',', '.') ?>
                </div>
                <div class="stat-note">Akumulasi transaksi barang masuk</div>
            </div>

            <div class="stat-card">
                <div class="stat-top">
                    <span class="stat-label">Total Barang Keluar</span>
                    <span class="stat-icon purple">
                        <i class="fa-solid fa-arrow-up"></i>
                    </span>
                </div>
                <div class="stat-number">
                    <?= number_format((int)$totalKeluar, 0, ',', '.') ?>
                </div>
                <div class="stat-note">Akumulasi transaksi barang keluar</div>
            </div>

        </section>

        <!-- TABEL STOK -->
        <section class="panel">

            <div class="panel-header">
                <div>
                    <h2>Daftar Stok Barang</h2>
                    <p>Pantau persediaan barang yang tersimpan di gudang.</p>
                </div>

                <label class="search-box">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="search" id="searchBarang"
                           placeholder="Cari nama barang...">
                </label>
            </div>

            <div class="table-responsive">
                <table id="tabelBarang">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama Barang</th>
                            <th>Deskripsi</th>
                            <th>Stok Tersedia</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>
                    <?php
                    $i = 1;

                    if ($ambilsemuadatastock &&
                        mysqli_num_rows($ambilsemuadatastock) > 0) {

                        while ($data = mysqli_fetch_assoc($ambilsemuadatastock)) {
                            $namabarang = $data['namabarang'];
                            $deskripsi = $data['deskripsi'];
                            $stock = (int)$data['stock'];

                            $status = $stock <= 3 ? 'Stok Rendah' : 'Tersedia';
                            $badgeClass = $stock <= 3 ? 'stock-badge stock-low' : 'stock-badge';
                    ?>
                        <tr>
                            <td><?= $i++ ?></td>

                            <td>
                                <div class="item-name">
                                    <span class="item-icon">
                                        <i class="fa-solid fa-box"></i>
                                    </span>
                                    <span>
                                        <?= htmlspecialchars($namabarang, ENT_QUOTES, 'UTF-8') ?>
                                    </span>
                                </div>
                            </td>

                            <td>
                                <?= htmlspecialchars($deskripsi, ENT_QUOTES, 'UTF-8') ?>
                            </td>

                            <td>
                                <strong>
                                    <?= number_format($stock, 0, ',', '.') ?>
                                </strong>
                                unit
                            </td>

                            <td>
                                <span class="<?= $badgeClass ?>">
                                    <?= $status ?>
                                </span>
                            </td>
                        </tr>
                    <?php
                        }
                    } else {
                    ?>
                        <tr>
                            <td colspan="5" class="empty-state">
                                <i class="fa-solid fa-box-open"></i>
                                <br><br>
                                Belum ada data barang.
                                Klik Tambah Barang untuk memulai.
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>

                <div id="hasilPencarian"
                     style="display:none;padding:20px;color:var(--muted);">
                    Barang tidak ditemukan.
                </div>
            </div>

        </section>

        <div style="text-align:center;color:var(--muted);font-size:12px;margin-top:25px;">
            &copy; <?= date('Y') ?> StockBarang. Sistem Informasi Persediaan Barang.
        </div>

    </main>
</div>

<!-- MODAL TAMBAH BARANG -->
<div class="modal fade" id="myModal" tabindex="-1"
     aria-labelledby="modalTitle" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <form method="post">

                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">
                        <i class="fa-solid fa-plus-circle"></i>
                        Tambah Barang Baru
                    </h5>

                    <button type="button" class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Tutup"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label">Nama Barang</label>
                        <input type="text" name="namabarang"
                               class="form-control"
                               placeholder="Contoh: Nabati"
                               maxlength="25" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Deskripsi Barang</label>
                        <input type="text" name="deskripsi"
                               class="form-control"
                               placeholder="Contoh: Snack"
                               maxlength="100" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jumlah Stok</label>
                        <input type="number" name="stock"
                               class="form-control"
                               placeholder="Masukkan jumlah stok"
                               min="0" required>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">
                        Batal
                    </button>

                    <button type="submit" name="addnewbarang"
                            class="btn btn-primary">
                        <i class="fa-solid fa-floppy-disk"></i>
                        Simpan Barang
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // MODE GELAP DAN TERANG
    const themeToggle = document.getElementById('themeToggle');
    const themeIcon = document.getElementById('themeIcon');

    function applyTheme(theme) {
        document.body.classList.toggle('dark-mode', theme === 'dark');

        themeIcon.className = theme === 'dark'
            ? 'fa-solid fa-sun'
            : 'fa-solid fa-moon';

        themeToggle.title = theme === 'dark'
            ? 'Aktifkan mode terang'
            : 'Aktifkan mode gelap';
    }

    // Memuat tema yang sebelumnya dipilih
    let savedTheme = 'light';

    try {
        savedTheme = localStorage.getItem('stockbarang-theme') || 'light';
    } catch (e) {}

    applyTheme(savedTheme);

    themeToggle.addEventListener('click', function () {
        const newTheme = document.body.classList.contains('dark-mode')
            ? 'light'
            : 'dark';

        applyTheme(newTheme);

        try {
            localStorage.setItem('stockbarang-theme', newTheme);
        } catch (e) {}
    });

    // MENU SIDEBAR DI HP
    document.getElementById('mobileMenu').addEventListener('click', function () {
        document.getElementById('sidebar').classList.toggle('show');
    });

    // PENCARIAN BARANG
    const searchInput = document.getElementById('searchBarang');
    const tableRows = document.querySelectorAll('#tabelBarang tbody tr');
    const hasilPencarian = document.getElementById('hasilPencarian');

    searchInput.addEventListener('input', function () {
        const keyword = this.value.toLowerCase().trim();
        let visibleRows = 0;

        tableRows.forEach(function (row) {
            const cocok = row.innerText.toLowerCase().includes(keyword);

            row.style.display = cocok ? '' : 'none';

            if (cocok) visibleRows++;
        });

        hasilPencarian.style.display =
            visibleRows === 0 ? 'block' : 'none';
    });
</script>

</body>
</html>