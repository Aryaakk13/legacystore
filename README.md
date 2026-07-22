# 🎮 LegacySMP Store

A modern Minecraft server store built with Laravel. Sell ranks, items, crates, and keys to your players with a beautiful, responsive interface.

## 🚀 Features

- **🛒 Full Shopping Cart** - Add items, update quantities, and checkout seamlessly
- **👑 Rank Management** - Sell VIP, VIP+, MVP and custom ranks with automatic command execution
- **🎁 Item & Crate System** - Sell items, spawners, mystery boxes, and keys
- **💳 Multiple Payment Gateways** - Midtrans & Cashfree integration (coming soon)
- **🎮 Minecraft Account Linking** - Link multiple MC accounts to your store account
- **👤 Player Dashboard** - View purchase history, linked accounts, and more
- **🛡️ Admin Panel** - Full admin dashboard with statistics, user management, and order management
- **🛡️ Cloudflare Protection** - Built-in Cloudflare Turnstile CAPTCHA support
- **📊 Analytics** - Track sales, revenue, and popular products

## 📋 Requirements

- PHP 8.0+
- Composer
- MySQL 5.7+ or MariaDB 10.3+
- Node.js & NPM (for frontend assets)
- Minecraft Server (for command delivery)

## ⚙️ Installation

1. **Clone the repository**
```bash
git clone https://github.com/yourusername/legacysmp-store.git
cd legacysmp-store
```

2. **Install PHP dependencies**
```bash
composer install
```

3. **Configure environment**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Configure database**
Edit `.env` file and set your database credentials:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=legacysmp_store
DB_USERNAME=root
DB_PASSWORD=
```

5. **Run migrations and seeders**
```bash
php artisan migrate --seed
```

6. **Install frontend dependencies**
```bash
npm install && npm run dev
```

7. **Start the development server**
```bash
php artisan serve
```

## 🔧 Configuration

### Minecraft Server Connection
Add your server details to `.env`:
```
MINECRAFT_SERVER_IP=play.legacysmp.com
MINECRAFT_RCON_PORT=25575
MINECRAFT_RCON_PASSWORD=your_rcon_password
```

### Payment Gateways
```env
# Midtrans
MIDTRANS_SERVER_KEY=your_server_key
MIDTRANS_CLIENT_KEY=your_client_key

# Cashfree
CASHFREE_APP_ID=your_app_id
CASHFREE_SECRET_KEY=your_secret_key
```

### Cloudflare Turnstile
```env
CLOUDFLARE_TURNSTILE_SITE_KEY=your_site_key
CLOUDFLARE_TURNSTILE_SECRET_KEY=your_secret_key
```

## 📁 Project Structure

```
legacysmp-store/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php
│   │   │   ├── ShopController.php
│   │   │   └── AdminController.php
│   │   └── Middleware/
│   │       ├── AdminMiddleware.php
│   │       └── CloudflareMiddleware.php
│   └── Models/
│       ├── User.php
│       ├── Product.php
│       ├── Purchase.php
│       └── McAccount.php
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/views/
│   ├── layouts/
│   ├── auth/
│   ├── shop/
│   ├── player/
│   └── admin/
└── routes/
    └── web.php
```

## 🛣️ Routes

### Public Routes
| Route | Description |
|-------|-------------|
| `/` | Home/Landing page |
| `/shop` | Shop listing |
| `/shop/product/{id}` | Product details |
| `/login` | Login page |
| `/register` | Registration page |

### Protected Routes (Player)
| Route | Description |
|-------|-------------|
| `/player/dashboard` | Player dashboard |
| `/player/profile` | Player profile |
| `/checkout` | Checkout page |

### Admin Routes
| Route | Description |
|-------|-------------|
| `/admin/dashboard` | Admin dashboard |
| `/admin/statistics` | Sales analytics |
| `/admin/users` | User management |
| `/admin/users/{id}` | User details |
| `/admin/orders` | Order management |

## 🛡️ Security

- All passwords are hashed using Bcrypt
- CSRF protection enabled
- Cloudflare Turnstile CAPTCHA integration
- Admin routes protected by middleware
- SQL injection prevention via Eloquent ORM
- XSS protection via Blade templating

## 🤝 Contributing

Pull requests are welcome. For major changes, please open an issue first.

## 📄 License

This project is for LegacySMP server use. Not affiliated with Mojang AB.

## 💬 Support

Join our [Discord](https://discord.gg/legacysmp) for support and updates.
