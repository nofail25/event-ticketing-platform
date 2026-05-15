# Quick Start Guide - Sistem Notifikasi Pembayaran Tiket

## 🚀 Cara Menggunakan Sistem Notifikasi

### 1. **Testing Notifikasi Pembayaran**

#### Step 1: Login sebagai Customer
- Buka aplikasi dan login dengan akun customer

#### Step 2: Beli Tiket
- Pilih event dari halaman utama
- Klik "Checkout" untuk membeli tiket
- Isi form checkout dengan data yang diperlukan
- Klik "Proses Pembayaran"

#### Step 3: Lihat Notifikasi di Dashboard
- Setelah checkout berhasil, kembali ke dashboard customer
- Cari section "Notifikasi Terbaru"
- Notifikasi pembayaran akan tampil dengan informasi:
  - ✓ Badge "Pembayaran Berhasil"
  - Invoice number
  - Jumlah tiket
  - Total pembayaran
  - Nama event

#### Step 4: Lihat Semua Notifikasi
- Klik bell icon 🔔 di navbar atas
- Atau klik "Lihat semua notifikasi" di dashboard
- Halaman notifikasi akan menampilkan:
  - List lengkap semua notifikasi
  - Badge counter untuk notifikasi belum dibaca
  - Opsi untuk menandai sebagai dibaca
  - Opsi untuk menghapus notifikasi

### 2. **Fitur-Fitur Notifikasi**

#### a. **Notifikasi yang Belum Dibaca**
- Background biru muda (bg-blue-50)
- Border biru (border-blue-200)
- Blue dot indicator di corner
- Tombol checkmark untuk menandai sebagai dibaca

#### b. **Menandai Notifikasi Sebagai Dibaca**
- Klik tombol checkmark (✓) pada notifikasi
- Notifikasi akan berubah ke status "dibaca"
- Warna berubah menjadi normal (putih)
- Blue dot indicator hilang

#### c. **Menghapus Notifikasi**
- Klik tombol trash (🗑️) pada notifikasi
- Notifikasi akan dihapus dari database
- Confirmation dialog akan ditampilkan

#### d. **Bulk Actions di Halaman Notifikasi**
- "Tandai Semua Dibaca" - Menandai semua notifikasi sebagai dibaca
- "Hapus Semua" - Menghapus semua notifikasi (dengan confirmation)

### 3. **Email Notification**

Jika mail driver sudah dikonfigurasi, customer akan menerima email dengan:

**Email Content:**
```
Halo [Customer Name]!

Pembayaran tiket Anda telah berhasil diproses.

Nomor Invoice: INV-20260515-XXXX
Jumlah Tiket: 3 tiket
Total Pembayaran: Rp 150.000
Acara: Concert Event 2026

Status Pembayaran: Lunas

E-tiket Anda sudah siap untuk digunakan. Silakan kunjungi dashboard 
untuk melihat detail tiket dan barcode.

[Lihat E-Tiket Anda] ← Click button

Terimakasih telah membeli tiket bersama kami!

Salam hangat,
[Platform Name]
```

### 4. **Notification Badge di Navbar**

- Bell icon 🔔 ditampilkan di navbar atas
- Badge merah dengan counter menunjukkan notifikasi belum dibaca
- Badge hanya tampil jika ada notifikasi belum dibaca
- Klik bell icon untuk langsung ke halaman notifikasi

## 📱 Mobile Responsive

Semua fitur notifikasi fully responsive:
- Desktop: Tampil dengan layout grid
- Mobile: Tampil dengan layout stacked
- Touch-friendly buttons dengan ukuran cukup besar

## 🔧 Customization

### Mengubah Isi Email Notifikasi

Edit file: `app/Notifications/OrderPaid.php`

```php
public function toMail(object $notifiable): MailMessage
{
    // Edit di sini untuk customize subject dan content
    return (new MailMessage)
        ->subject('Custom Subject')
        ->greeting('Custom Greeting')
        ->line('Custom message');
}
```

### Menambah Channel Pengiriman (SMS, Push, dll)

Edit method `via()` di `OrderPaid.php`:
```php
public function via(object $notifiable): array
{
    return ['database', 'mail', 'sms']; // Tambah channel di sini
}
```

### Mengubah Styling Notifikasi

Edit component: `resources/views/components/notifications.blade.php`

Ubah color scheme:
- `bg-blue-50` → ganti dengan warna lain
- `border-blue-200` → ganti dengan warna border
- `from-emerald-500` → ganti dengan gradient color

## 📊 Monitoring Notifikasi

### Cek dari Database

```bash
# SSH ke server dan buka MySQL
mysql> SELECT * FROM notifications WHERE user_id = 1;

# Lihat notifikasi belum dibaca
mysql> SELECT * FROM notifications WHERE read_at IS NULL;

# Lihat notifikasi tertentu
mysql> SELECT * FROM notifications WHERE type = 'App\\Notifications\\OrderPaid';
```

### Debugging via Laravel Tinker

```bash
php artisan tinker

# Lihat notifikasi user tertentu
$user = App\Models\User::find(1);
$user->notifications()->get();

# Count unread notifications
$user->unreadNotifications()->count();

# Lihat data notifikasi
$notification = $user->notifications()->first();
$notification->data;
```

## 🐛 Troubleshooting

### Notifikasi Tidak Muncul di Dashboard
1. **Cek database migration**: `php artisan migrate:status`
2. **Cek notification record**: Lihat di `notifications` table
3. **Cek controller** mengirim data ke view

### Email Tidak Terkirim
1. **Cek .env**: `MAIL_DRIVER`, `MAIL_FROM_ADDRESS` sudah benar?
2. **Cek queue**: Jika menggunakan queue, pastikan queue worker running
3. **Test email**: `php artisan tinker` → `Mail::send(...)`

### Notifikasi Belum Dibaca Tidak Muncul
1. **Cek `read_at` timestamp**: Harus NULL untuk notifikasi belum dibaca
2. **Refresh page**: Browser cache mungkin belum update
3. **Check order**: Pastikan order berhasil dibuat dengan `payment_status = 'paid'`

## ✨ Best Practices

1. **Don't forget to run migrations**: `php artisan migrate`
2. **Queue mail sending**: Gunakan queue untuk email agar tidak blocking
3. **Provide feedback**: Toast/Alert untuk confirm action (delete/mark as read)
4. **Clean old notifications**: Buat command untuk delete notifikasi lama
5. **Monitor email delivery**: Setup webhook untuk track email status

## 📚 Related Files

- Model: `app/Models/Order.php` (sudah punya Notifiable trait di User)
- Service: `app/Services/TicketOrderService.php`
- Controller: `app/Http/Controllers/NotificationController.php`
- Notification: `app/Notifications/OrderPaid.php`
- Views: `resources/views/notifications/`
- Components: `resources/views/components/notifications.blade.php`
- Migration: `database/migrations/2026_05_15_000000_create_notifications_table.php`
- Routes: `routes/web.php` (notification routes)

---

**Happy Testing! 🎉**
