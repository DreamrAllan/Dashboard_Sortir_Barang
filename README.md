# 📦 Sortir Barang - Dashboard Inventaris

Aplikasi manajemen inventaris barang berbasis web dengan Laravel. Dilengkapi dengan dashboard modern, sistem kategori, manajemen barang, dan pencatatan transaksi masuk/keluar.

## ✨ Fitur

- 🔐 **Autentikasi** - Login & Register dengan session management
- 📊 **Dashboard** - Statistik ringkasan inventaris
- 🏷️ **Kategori** - Kelola kategori barang
- 📦 **Barang** - CRUD barang dengan kode otomatis
- 🔄 **Transaksi** - Catat barang masuk & keluar dengan update stok otomatis
- 🎨 **UI Modern** - Desain minimalis dengan gradient biru, sidebar collapsible

## 🛠️ Tech Stack

- **Backend**: Laravel 11
- **Database**: MySQL
- **Frontend**: Blade + Tailwind CSS
- **Container**: Docker

## 🚀 Instalasi

### Dengan Docker

```bash
# Clone repository
git clone https://github.com/DreamrAllan/Dashboard_Sortir_Barang.git
cd Dashboard_Sortir_Barang

# Jalankan Docker
docker-compose up -d

# Install dependencies
docker exec sortir-barang-app composer install

# Setup environment
cp .env.example .env
docker exec sortir-barang-app php artisan key:generate

# Migrasi database
docker exec sortir-barang-app php artisan migrate

# Akses di browser
http://localhost:8000
```

### Tanpa Docker

```bash
# Clone repository
git clone https://github.com/DreamrAllan/Dashboard_Sortir_Barang.git
cd Dashboard_Sortir_Barang

# Install dependencies
composer install

# Setup environment
cp .env.example .env
php artisan key:generate

# Konfigurasi database di .env
# DB_DATABASE=sortir_barang
# DB_USERNAME=root
# DB_PASSWORD=

# Migrasi database
php artisan migrate

# Jalankan server
php artisan serve
```

## 📸 Screenshot

### Dashboard
- Statistik total barang, stok, kategori
- Peringatan stok menipis
- Transaksi terbaru

### Sidebar
- Collapsible dengan animasi smooth
- State tersimpan di localStorage

## 📁 Struktur

```
├── app/Http/Controllers/Web/   # Controllers
├── resources/views/            # Blade templates
│   ├── layouts/app.blade.php   # Layout utama
│   ├── dashboard.blade.php     # Dashboard
│   ├── auth/                   # Login & Register
│   ├── categories/             # CRUD Kategori
│   ├── items/                  # CRUD Barang
│   └── transactions/           # CRUD Transaksi
├── routes/web.php              # Web routes
└── docker-compose.yml          # Docker config
```

## 👤 Author

**Allan Raditya Hutomo**

## 📄 License

MIT License
