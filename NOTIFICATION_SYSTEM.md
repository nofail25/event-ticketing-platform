# Sistem Notifikasi Pembayaran Tiket

## 📋 Ringkasan Implementasi

Sistem notifikasi telah berhasil diintegrasikan ke dalam platform event ticketing untuk memberitahu pengguna saat tiket mereka berhasil dibayar.

## 🎯 Fitur yang Diimplementasikan

### 1. **Notification Class** 
**File:** `app/Notifications/OrderPaid.php`
- Mengirim notifikasi ketika order (tiket) berhasil dibayar
- Mendukung 2 channel pengiriman:
  - **Database**: Menyimpan notifikasi di database untuk ditampilkan di dashboard
  - **Mail**: Mengirim email kepada customer
- Notifikasi mencakup informasi:
  - Nomor Invoice
  - Jumlah tiket yang dibeli
  - Total pembayaran
  - Nama acara/event

### 2. **Database Migration**
**File:** `database/migrations/2026_05_15_000000_create_notifications_table.php`
- Membuat tabel `notifications` untuk menyimpan semua notifikasi
- Mendukung fitur read/unread notifications
- Indexed untuk performa query yang optimal

### 3. **Service Layer Update**
**File:** `app/Services/TicketOrderService.php`
- Update method `processOrder()` untuk mengirim notifikasi otomatis
- Notifikasi dikirim setelah order berhasil dibuat
- Menggunakan queue untuk pengiriman email yang non-blocking

### 4. **Controller**
**File:** `app/Http/Controllers/NotificationController.php`

**Actions yang tersedia:**
- `index()` - Menampilkan semua notifikasi user
- `markRead($id)` - Menandai notifikasi spesifik sebagai sudah dibaca
- `markAllRead()` - Menandai semua notifikasi sebagai dibaca
- `destroy($id)` - Menghapus notifikasi spesifik
- `deleteAll()` - Menghapus semua notifikasi

### 5. **Views/UI Components**

#### a. **Notification Component**
**File:** `resources/views/components/notifications.blade.php`
- Component reusable untuk menampilkan daftar notifikasi
- Menampilkan notifikasi terbaru dengan styling yang menarik
- Support untuk notifikasi yang belum dibaca dan sudah dibaca
- Tombol untuk menandai sebagai dibaca dan menghapus

#### b. **Notifications Index Page**
**File:** `resources/views/notifications/index.blade.php`
- Halaman lengkap untuk mengelola semua notifikasi
- Pagination untuk notifikasi yang banyak
- Fitur bulk action (tandai semua dibaca, hapus semua)
- Status indicator untuk notifikasi yang belum dibaca

#### c. **Dashboard Integration**
**File:** `resources/views/customer/dashboard.blade.php`
- Menampilkan section "Notifikasi Terbaru" dengan max 5 notifikasi
- Link "Lihat semua notifikasi" untuk akses halaman lengkap
- Update controller untuk passing data notifikasi ke view

### 6. **Navigation Bar Update**
**File:** `resources/views/layouts/navigation.blade.php`
- Menambahkan bell icon untuk notifikasi di navbar
- Menampilkan badge counter untuk jumlah notifikasi belum dibaca
- Link langsung ke halaman notifikasi

### 7. **Routes**
**File:** `routes/web.php`

Routes yang ditambahkan:
```php
Route::middleware('auth')->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::put('/notifications/{id}/mark-read', [NotificationController::class, 'markRead'])->name('notifications.mark-read');
    Route::put('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.mark-all-read');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::delete('/notifications', [NotificationController::class, 'deleteAll'])->name('notifications.delete-all');
});
```

## 🔄 Alur Kerja (Workflow)

1. **Customer membeli tiket** → Checkout Controller
2. **Process Order** → TicketOrderService::processOrder()
3. **Order berhasil dibuat** → Notifikasi dikirim otomatis
4. **User menerima notifikasi di 2 tempat:**
   - Email (jika mail driver dikonfigurasi)
   - Database (tampil di dashboard)
5. **User dapat mengelola notifikasi:**
   - Lihat di dashboard atau halaman notifikasi
   - Tandai sebagai dibaca
   - Hapus notifikasi

## 📧 Email Notification Template

Email yang dikirim berisi:
- Greeting personalized dengan nama customer
- Nomor Invoice
- Jumlah tiket
- Total pembayaran
- Nama acara
- CTA button "Lihat E-Tiket Anda"
- Instruksi untuk melihat tiket dan barcode

## 🎨 Design & Styling

### Notifikasi yang Belum Dibaca
- Background: Light blue (bg-blue-50)
- Border: Blue (border-blue-200)
- Indikator: Blue dot di top-right

### Notifikasi Sudah Dibaca
- Background: White
- Border: Light gray
- Tanpa blue indicator

### Status Colors
- **Pembayaran Berhasil**: Emerald/Green
- **Default Notification**: Blue/Indigo
- **Unread Indicator**: Red badge dengan counter

## 🔒 Security & Best Practices

1. **Authentication Required**: Semua routes memerlukan middleware `auth`
2. **Authorization**: User hanya bisa melihat notifikasi mereka sendiri
3. **Queue Support**: Email dikirim via queue (non-blocking)
4. **Database Transactions**: Order creation dan notification dalam satu transaction
5. **Timestamp**: Notifikasi ditrack dengan `created_at` dan `read_at`

## 📊 Database Schema

```sql
CREATE TABLE notifications (
    id UUID PRIMARY KEY,
    notifiable_id BIGINT UNSIGNED,
    notifiable_type VARCHAR(255) DEFAULT 'App\Models\User',
    type VARCHAR(255),
    data LONGTEXT,
    read_at TIMESTAMP NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (notifiable_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX (notifiable_id, notifiable_type, created_at)
);
```

## 🚀 Testing Notification

Untuk test notifikasi:

1. **Order tiket** melalui checkout
2. **Cek Dashboard** → Bagian "Notifikasi Terbaru" akan menampilkan notifikasi pembayaran
3. **Klik bell icon** di navbar untuk melihat semua notifikasi
4. **Tandai sebagai dibaca** menggunakan tombol checkmark
5. **Hapus notifikasi** menggunakan tombol trash

## 📝 Kustomisasi Lanjutan

### Menambah Channel Pengiriman Lain

Edit `app/Notifications/OrderPaid.php`:
```php
public function via(object $notifiable): array
{
    return ['database', 'mail', 'sms']; // Tambahkan channel lain
}
```

### Mengubah Email Template

Edit `app/Notifications/OrderPaid.php` method `toMail()` untuk customize subject, greeting, dan content email.

### Menambah Tipe Notifikasi

1. Buat notification class baru: `app/Notifications/YourNotification.php`
2. Update service untuk mengirim notifikasi: `$user->notify(new YourNotification())`
3. Component otomatis akan menampilkan notifikasi berdasarkan data array

## ✅ Checklist Implementasi

- ✅ Notification class dibuat
- ✅ Migration database dibuat dan dijalankan
- ✅ Service layer diupdate
- ✅ Controller dibuat
- ✅ Views/Components dibuat
- ✅ Routes ditambahkan
- ✅ Navigation updated
- ✅ Dashboard integration selesai

## 🎓 Kesimpulan

Sistem notifikasi telah fully integrated dan siap digunakan. Customer akan otomatis menerima notifikasi pembayaran baik melalui email maupun dashboard, dengan interface yang user-friendly untuk mengelola notifikasi mereka.
