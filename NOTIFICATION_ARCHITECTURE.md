# Arsitektur Sistem Notifikasi Pembayaran Tiket

## 📐 System Architecture Diagram

```
┌─────────────────────────────────────────────────────────────────────────┐
│                         EVENT TICKETING PLATFORM                        │
└─────────────────────────────────────────────────────────────────────────┘

                              CHECKOUT FLOW
                                   │
                                   ▼
                    ┌──────────────────────────┐
                    │  CheckoutController      │
                    │  └─ POST /checkout       │
                    └──────────────────────────┘
                                   │
                                   ▼
                    ┌──────────────────────────┐
                    │ TicketOrderService       │
                    │ └─ processOrder()        │
                    └──────────────────────────┘
                                   │
                    ┌──────────────┴──────────────┐
                    │                             │
                    ▼                             ▼
        ┌─────────────────────┐      ┌────────────────────────┐
        │  Create Order       │      │  Create TicketDetails  │
        │  └─ Database        │      │  └─ Database           │
        └─────────────────────┘      └────────────────────────┘
                    │
                    ▼
        ┌─────────────────────────────────────┐
        │   NOTIFICATION TRIGGERED            │
        │   user->notify(new OrderPaid($order))
        └─────────────────────────────────────┘
                    │
        ┌───────────┴───────────┐
        │                       │
        ▼                       ▼
    ┌────────────────┐   ┌──────────────────┐
    │ DATABASE       │   │ MAIL             │
    │ CHANNEL        │   │ CHANNEL          │
    └────────────────┘   └──────────────────┘
        │                       │
        ▼                       ▼
    ┌────────────────┐   ┌──────────────────┐
    │ Notifications  │   │ SMTP Server      │
    │ Table Saved    │   │ (Mail Driver)    │
    └────────────────┘   └──────────────────┘
        │                       │
        ▼                       ▼
    ┌──────────────────────────────────────┐
    │ CUSTOMER RECEIVES NOTIFICATION       │
    │                                      │
    │ 1. Dashboard Section                 │
    │ 2. Bell Icon with Badge              │
    │ 3. Notifications Page                │
    │ 4. Email Inbox                       │
    └──────────────────────────────────────┘
```

## 🔄 Workflow Sequence

```
Customer              Application           Database              Mail Server
    │                     │                      │                    │
    ├─── Beli Tiket ──────►│                      │                    │
    │                      │                      │                    │
    │                      ├─ Create Order ──────►│                    │
    │                      │ & Ticket Details     │                    │
    │                      │                      ├─ Save Order        │
    │                      │                      │                    │
    │                      ├─ Trigger Event ─────┐│                    │
    │                      │ (OrderPaid)          ││                    │
    │                      │                      ├─ Save Notification │
    │                      │                      │                    │
    │                      ├─────────────────────────────Send Email───►│
    │                      │                      │                    │
    │                      │                      │                    ├─ Queue
    │                      │                      │                    │
    │                      │◄─────────────────────────Email Sent──────┤
    │                      │                      │                    │
    │◄─ Redirect to ───────┤                      │                    │
    │  Dashboard           │                      │                    │
    │                      │                      │                    │
    ├─ View Dashboard ────►│                      │                    │
    │                      ├─ Fetch User Data ───►│                    │
    │                      │ & Notifications      │                    │
    │                      │◄─ Return Data ───────┤                    │
    │◄─ Render Dashboard ──┤                      │                    │
    │  with Notifications  │                      │                    │
    │                      │                      │                    │
    ├─ Click Bell Icon ───►│                      │                    │
    │                      ├─ Get Notifications ─►│                    │
    │                      │◄─ Return List ───────┤                    │
    │◄─ View All ──────────┤                      │                    │
    │  Notifications       │                      │                    │
    │                      │                      │                    │
    ├─ Mark as Read ──────►│                      │                    │
    │                      ├─ Update read_at ────►│                    │
    │                      │                      ├─ Update            │
    │◄─ Confirmation ──────┤                      │                    │
    │                      │                      │                    │
    ├─ Delete Notif ──────►│                      │                    │
    │                      ├─ Delete Notif ──────►│                    │
    │                      │                      ├─ Delete            │
    │◄─ Confirmation ──────┤                      │                    │
    │                      │                      │                    │
```

## 📦 Component Structure

```
app/
├── Models/
│   ├── User.php (use Notifiable)
│   ├── Order.php
│   └── TicketDetail.php
│
├── Notifications/
│   └── OrderPaid.php ◄─── Notification Class
│       ├── via() - Define channels (database, mail)
│       ├── toMail() - Email template
│       └── toArray() - Notification data
│
├── Services/
│   └── TicketOrderService.php
│       └── processOrder() - Trigger notification
│
└── Http/
    ├── Controllers/
    │   ├── CheckoutController.php
    │   │   └── store() - Process checkout
    │   ├── Dashboard/
    │   │   └── CustomerDashboardController.php
    │   │       └── index() - Show dashboard with notifications
    │   └── NotificationController.php ◄─── Notification Management
    │       ├── index() - List notifications
    │       ├── markRead() - Mark single as read
    │       ├── markAllRead() - Mark all as read
    │       ├── destroy() - Delete notification
    │       └── deleteAll() - Delete all
    │
resources/
├── views/
│   ├── components/
│   │   └── notifications.blade.php ◄─── Reusable Component
│   │
│   ├── notifications/
│   │   └── index.blade.php ◄─── Full Notifications Page
│   │
│   ├── customer/
│   │   └── dashboard.blade.php
│   │       └── Include notifications component
│   │
│   └── layouts/
│       └── navigation.blade.php
│           └── Bell icon with badge
│
database/
└── migrations/
    └── 2026_05_15_000000_create_notifications_table.php
        └── notifications table schema

routes/
└── web.php
    └── notification routes (auth middleware)
```

## 🗄️ Database Schema

```
notifications table:
┌────────────────────────────────────────────────┐
│ id (UUID PRIMARY KEY)                          │
├────────────────────────────────────────────────┤
│ notifiable_id (FK → users.id)                  │
│ notifiable_type (VARCHAR 255)                  │
│ type (VARCHAR 255)                             │
│ data (LONGTEXT - JSON serialized)              │
│ read_at (TIMESTAMP NULL)                       │
│ created_at (TIMESTAMP)                         │
│ updated_at (TIMESTAMP)                         │
└────────────────────────────────────────────────┘

Example data row:
┌────────────────────────────────────────────────────────────────┐
│ id: 550e8400-e29b-41d4-a716-446655440000                       │
│ notifiable_id: 1                                               │
│ notifiable_type: App\Models\User                               │
│ type: App\Notifications\OrderPaid                              │
│ data: {                                                        │
│   "order_id": 1,                                               │
│   "invoice_number": "INV-20260515-ABCD",                       │
│   "ticket_count": 3,                                           │
│   "total_amount": "150000.00",                                 │
│   "event_title": "Concert Event 2026",                         │
│   "message": "Pembayaran tiket berhasil! 3 tiket untuk...",   │
│   "type": "order_paid"                                         │
│ }                                                              │
│ read_at: NULL (atau timestamp jika sudah dibaca)              │
│ created_at: 2026-05-15 10:30:45                               │
│ updated_at: 2026-05-15 10:30:45                               │
└────────────────────────────────────────────────────────────────┘
```

## 🔐 Security Flow

```
┌──────────────────────────────────────────────┐
│ Request comes in                             │
└──────────────────────────────────────────────┘
              │
              ▼
┌──────────────────────────────────────────────┐
│ Middleware: auth                             │
│ (Verify user is logged in)                   │
└──────────────────────────────────────────────┘
              │
        ┌─────┴─────┐
        │           │
    ✓ Valid    ✗ Invalid
        │           │
        ▼           ▼
    Proceed    Redirect to Login
        │
        ▼
┌──────────────────────────────────────────────┐
│ Controller checks: Auth::user()->id           │
│ Only fetch notifications for current user    │
└──────────────────────────────────────────────┘
              │
        ┌─────┴─────┐
        │           │
    ✓ Owner    ✗ Not Owner
        │           │
        ▼           ▼
    Return    Return 403
    Data      Forbidden
```

## 📊 Data Flow for Notification Storage

```
TicketOrderService::processOrder()
    │
    ├─ Create Order
    │   └─ Order::create([...])
    │
    ├─ Create Ticket Details
    │   └─ Loop & create ticket details
    │
    ├─ Update Quota
    │   └─ Decrement available quota
    │
    ▼
Notification::notify(OrderPaid::class)
    │
    ├─ Channel: Database
    │   │
    │   ├─ OrderPaid::toArray()
    │   │   └─ Return array with order data
    │   │
    │   └─ Store in notifications table
    │       ├─ notifiable_id = user_id
    │       ├─ type = 'App\Notifications\OrderPaid'
    │       ├─ data = JSON serialized array
    │       └─ read_at = NULL
    │
    └─ Channel: Mail
        │
        ├─ OrderPaid::toMail()
        │   └─ Build email message
        │
        └─ Queue for SMTP delivery
            └─ Mail driver sends email
```

## 🎨 UI/UX Flow

```
┌─────────────────────────────────┐
│ Navbar with Bell Icon           │
│ 🔔 (Red badge: 3)               │
└─────────────────────────────────┘
              │
        ┌─────┴──────┐
        │            │
    Click    Auto Check
    Bell    (on page load)
        │            │
        ▼            ▼
┌──────────────────────────────────┐
│ Notifications Page               │
│                                  │
│ ┌────────────────────────────┐   │
│ │ Unread Notification        │   │
│ │ [Blue dot indicator]       │   │
│ │ ✓ Pembayaran Berhasil      │   │
│ │ [✓ Checkmark] [🗑️ Delete] │   │
│ └────────────────────────────┘   │
│                                  │
│ ┌────────────────────────────┐   │
│ │ Read Notification          │   │
│ │ ✓ Pembayaran Berhasil      │   │
│ │ [🗑️ Delete]                │   │
│ └────────────────────────────┘   │
└──────────────────────────────────┘
              │
        ┌─────┴──────┐
        │            │
   Mark Read     Delete
        │            │
        ▼            ▼
   Update        Delete
   read_at    from database
```

## 🚀 Performance Considerations

```
Query Optimization:
├─ Indexed columns:
│  ├─ notifiable_id
│  ├─ notifiable_type
│  └─ created_at
│
├─ Pagination:
│  └─ 15 notifications per page
│
└─ Eager Loading:
   └─ Load related data when needed

Caching Strategy:
├─ Cache unread count
├─ Invalidate on mark-read action
└─ Clear on delete action

Queue Configuration:
├─ Use database queue for emails
├─ Prevents blocking response
└─ Configurable retry policy
```

---

**Last Updated**: May 15, 2026
**Version**: 1.0.0
