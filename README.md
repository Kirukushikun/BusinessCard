# BFC Digital VCard System
> Laravel 12 + Filament v4 — Digital Business Card Management

**Live URL:** https://vcard.bfcgroup.ph  
**Admin Panel:** https://vcard.bfcgroup.ph/admin  
**Public Card:** https://vcard.bfcgroup.ph/card/{slug}  
**Server:** 10.10.0.107:8003 → Cloudflare Tunnel → vcard.bfcgroup.ph

---

## Tech Stack

- **Framework:** Laravel 12 + PHP 8.3
- **Admin Panel:** Filament v4 (NOT v3 — installed via `composer require filament/filament` without version pin)
- **Database:** MySQL 8.0 (Docker)
- **Cache/Queue:** Redis (Docker)
- **Web Server:** Apache 2.4 (Docker)
- **Proxy:** Cloudflare Tunnel
- **QR Code:** simplesoftwareio/simple-qrcode

---

## Project Structure

```
/var/www/business-card/
├── docker-compose.yml
├── Dockerfile
└── BusinessCard/          ← Laravel project root
    ├── app/
    │   ├── Filament/Resources/
    │   │   ├── BusinessCardResource.php
    │   │   └── BusinessCardResource/
    │   │       ├── Pages/
    │   │       │   ├── ListBusinessCards.php
    │   │       │   ├── CreateBusinessCard.php
    │   │       │   └── EditBusinessCard.php
    │   │       ├── Schemas/
    │   │       │   └── BusinessCardForm.php
    │   │       └── Tables/
    │   │           └── BusinessCardsTable.php
    │   ├── Models/
    │   │   ├── BusinessCard.php
    │   │   └── User.php           ← Must implement FilamentUser!
    │   └── Providers/
    │       └── AppServiceProvider.php
    ├── resources/views/card/
    │   └── show.blade.php
    ├── public/img/
    │   ├── BFC.png                ← Company logo (header)
    │   ├── BFC-White.png          ← White logo (card dark panel)
    │   └── BFC.ico                ← Favicon
    ├── bootstrap/app.php          ← trustProxies config
    ├── config/database.php        ← SSL fix for MySQL
    └── routes/web.php
```

---

## Fresh Deployment Steps

### 1. Clone and navigate
```bash
cd /var/www/business-card
git clone <repo-url> BusinessCard
```

### 2. Build and start containers
```bash
sudo docker compose up -d
```

### 3. Enter the container
```bash
sudo docker compose exec app bash
```

### 4. Install dependencies
```bash
composer install --optimize-autoloader --no-dev
```

### 5. Setup environment
```bash
cp .env.example .env
nano .env
```

### 6. Run setup commands
```bash
php artisan key:generate
php artisan migrate --force
php artisan storage:link
php artisan filament:assets
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 7. Create admin user
```bash
php artisan make:filament-user
```

### 8. Restart cloudflared (outside container)
```bash
exit
sudo systemctl restart cloudflared
```

---

## Critical .env Values

```env
APP_NAME="BFC Digital VCard"
APP_ENV=production
APP_URL=https://vcard.bfcgroup.ph

DB_CONNECTION=mysql
DB_HOST=db                    # ← MUST be 'db' not '127.0.0.1'
DB_PORT=3306
DB_DATABASE=vcard_database
DB_USERNAME=vcard_user
DB_PASSWORD=vcard_password

SESSION_DRIVER=database
SESSION_DOMAIN=vcard.bfcgroup.ph
SESSION_SECURE_COOKIE=true

FILESYSTEM_DISK=local
```

---

## Critical Code Fixes (DO NOT REMOVE)

### 1. AppServiceProvider.php — Force HTTPS + Root URL
```php
public function boot(): void
{
    if (app()->environment('production')) {
        \URL::forceRootUrl(config('app.url'));
        \URL::forceScheme('https');
    }
}
```
**Why:** Laravel is behind Cloudflare tunnel. Without this, redirects go to `https://localhost:8003` instead of `https://vcard.bfcgroup.ph`.

### 2. bootstrap/app.php — Trust Cloudflare Proxy
```php
->withMiddleware(function (Middleware $middleware): void {
    $middleware->trustProxies(at: '*');
})
```
**Why:** Laravel doesn't trust Cloudflare headers by default, causing mixed content and wrong URL generation.

### 3. User.php — FilamentUser Interface (CRITICAL)
```php
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

class User extends Authenticatable implements FilamentUser
{
    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }
}
```
**Why:** Filament v4 requires this interface. Without it, login succeeds but immediately returns 403.

### 4. config/database.php — Disable MySQL SSL Verification
```php
'options' => extension_loaded('pdo_mysql') ? array_filter([
    PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
]) : [],
```
**Why:** MySQL 8.0 Docker image uses self-signed SSL certificate which Laravel rejects by default.

---

## Features

- Business card CRUD via Filament v4 admin panel
- Auto slug generation from employee name
- Public card page at `/card/{slug}`
- QR code generation per card (SVG format)
- QR code lightbox — click to expand with dark overlay
- Copy link with comic speech bubble "Copied! 🎉" effect
- Web Share API for sharing to Viber, WhatsApp, Messenger
- Save to Contacts — downloads `.vcf` vCard file
- Employee photo upload with storage
- Doodle background (chicken, chick, egg, pig icons)
- Mouse proximity hover effect on doodle icons
- Rate limiting on public routes (30 requests/minute)
- Active/Inactive toggle per card (inactive returns 404)
- Responsive mobile layout

---

## Card Design

- **Font:** Poppins (Google Fonts CDN)
- **Colors:** Orange `#ec891b`, Maroon `#ab0b37`
- **Layout:** Landscape 1.75:1 ratio (like physical business card)
- **Max width:** 900px
- **Left panel:** Dark gradient with company logo and tagline
- **Right panel:** Name, position, contacts, QR code, save button

---

## Routes

| Method | URL | Description |
|--------|-----|-------------|
| GET | `/` | Redirects to `/admin` |
| GET | `/admin` | Filament admin panel |
| GET | `/card/{slug}` | Public business card view |
| GET | `/card/{slug}/vcard` | Download .vcf contact file |

---

## Filament v4 Gotchas

> These caused major issues during development. Keep in mind for future reference.

- `Form` class moved to `Filament\Schemas\Schema`
- `Section` moved to `Filament\Schemas\Components\Section`
- `NavigationIcon` type is `string|\BackedEnum|null` not `?string`
- All table actions are in `Filament\Actions\*` not `Filament\Tables\Actions\*`
- Pages must use `namespace App\Filament\Resources\BusinessCardResource\Pages`
- **User model MUST implement `FilamentUser` interface or login returns 403**

---

## Deployment Checklist

- [ ] `DB_HOST=db` in `.env` (not 127.0.0.1)
- [ ] `APP_URL=https://vcard.bfcgroup.ph` in `.env`
- [ ] `APP_ENV=production` in `.env`
- [ ] `SESSION_DOMAIN=vcard.bfcgroup.ph` in `.env`
- [ ] `SESSION_SECURE_COOKIE=true` in `.env`
- [ ] `forceRootUrl` + `forceScheme` in `AppServiceProvider.php`
- [ ] `trustProxies(at: '*')` in `bootstrap/app.php`
- [ ] `FilamentUser` interface on `User` model
- [ ] MySQL SSL disabled in `config/database.php`
- [ ] `php artisan storage:link` ran
- [ ] `php artisan filament:assets` ran
- [ ] `php artisan migrate --force` ran
- [ ] Admin user created via `php artisan make:filament-user`
- [ ] Logo files in `public/img/` (BFC.png, BFC-White.png, BFC.ico)
- [ ] Cloudflared restarted after deployment

---

## Cloudflare Tunnel Config

```yaml
tunnel: 8dc5f5e7-77c7-43af-b5df-bb62556f5574
credentials-file: /home/iverson/.cloudflared/8dc5f5e7-77c7-43af-b5df-bb62556f5574.json
ingress:
  - hostname: vcard.bfcgroup.ph
    service: http://localhost:8003
  - service: http_status:404
```

---

## Docker Compose Ports

| Port | Project |
|------|---------|
| 8001 | pansystem |
| 8002 | crispportal |
| 8003 | **vcard (this project)** |
| 8005 | intellihatch |

---

*Built with 🐔🐷🥚🐣 by Iverson*
