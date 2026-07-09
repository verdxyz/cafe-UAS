# 🎨 Visual Deployment Guide

## 📊 Arsitektur Sistem

```
┌─────────────────────────────────────────────────────────────────┐
│                    Internet / Client                            │
│                  (Browser, Postman, Mobile App)                 │
└────────────────────────┬────────────────────────────────────────┘
                         │
                         │ HTTP Request
                         │ http://203.175.10.112
                         │
                         ▼
┌─────────────────────────────────────────────────────────────────┐
│                        VPS Server                                │
│                    IP: 203.175.10.112                           │
│                                                                  │
│  ┌────────────────────────────────────────────────────────────┐ │
│  │              Docker Container: cafe-nginx                   │ │
│  │                 (Nginx Web Server)                          │ │
│  │                     Port: 80                                │ │
│  │                                                             │ │
│  │  • Menerima HTTP requests                                  │ │
│  │  • Serve static files (CSS, JS, images)                    │ │
│  │  • Forward PHP requests ke PHP-FPM                         │ │
│  │  • Load balancing & caching                                │ │
│  └────────────────┬───────────────────────────────────────────┘ │
│                   │                                              │
│                   │ Forward PHP requests                         │
│                   │ FastCGI Protocol                             │
│                   ▼                                              │
│  ┌────────────────────────────────────────────────────────────┐ │
│  │              Docker Container: cafe-app                     │ │
│  │              (PHP 8.2 FPM + Laravel)                        │ │
│  │                    Port: 9000                               │ │
│  │                                                             │ │
│  │  • Menjalankan aplikasi Laravel                            │ │
│  │  • Handle business logic                                   │ │
│  │  • Process requests & responses                            │ │
│  │  • JWT authentication                                      │ │
│  │  • API endpoints                                           │ │
│  └────────────────┬───────────────────────────────────────────┘ │
│                   │                                              │
│                   │ Database queries                             │
│                   │ MySQL Protocol                               │
│                   ▼                                              │
│  ┌────────────────────────────────────────────────────────────┐ │
│  │              Docker Container: cafe-mysql                   │ │
│  │                (MySQL 8.0 Database)                         │ │
│  │                    Port: 3306                               │ │
│  │                                                             │ │
│  │  • Menyimpan data aplikasi                                 │ │
│  │  • Users, Menu, Orders, Reservations, Reviews              │ │
│  │  • Persistent storage via volume                           │ │
│  └────────────────────────────────────────────────────────────┘ │
│                                                                  │
│  ┌────────────────────────────────────────────────────────────┐ │
│  │                Docker Network: cafe-network                 │ │
│  │      (Menghubungkan semua containers secara internal)      │ │
│  └────────────────────────────────────────────────────────────┘ │
│                                                                  │
│  ┌────────────────────────────────────────────────────────────┐ │
│  │                Docker Volume: cafe_mysql_data               │ │
│  │          (Persistent storage untuk MySQL data)             │ │
│  └────────────────────────────────────────────────────────────┘ │
└─────────────────────────────────────────────────────────────────┘
```

---

## 🔄 Request Flow

```
1. CLIENT REQUEST
   │
   │  curl http://203.175.10.112/api/menu
   │
   ▼

2. NGINX CONTAINER (Port 80)
   │
   ├─► Static files? → Serve directly (CSS, JS, images)
   │
   ├─► PHP files? → Forward to PHP-FPM ↓
   │
   ▼

3. LARAVEL APP CONTAINER (Port 9000)
   │
   ├─► Route: /api/menu
   │   └─► Controller: MenuController@index
   │       │
   │       ├─► Check authentication (JWT)
   │       │
   │       ├─► Query database ↓
   │       │
   │       ▼
   
4. MYSQL CONTAINER (Port 3306)
   │
   ├─► Execute: SELECT * FROM menus
   │
   └─► Return data ↑
   
5. LARAVEL APP
   │
   ├─► Format response (MenuResource)
   │
   └─► Return JSON ↑
   
6. NGINX
   │
   └─► Send to client ↑
   
7. CLIENT RECEIVES
   {
     "success": true,
     "data": [...]
   }
```

---

## 📁 Project Structure di Server

```
/opt/.izzudin/cafe-UAS/
│
├── 📂 app/                          # Laravel application code
│   ├── Http/
│   │   ├── Controllers/             # API controllers
│   │   ├── Middleware/              # JWT, Role checks
│   │   ├── Requests/                # Form validation
│   │   └── Resources/               # API resources
│   └── Models/                      # Eloquent models
│
├── 📂 bootstrap/                    # Laravel bootstrap
│   └── cache/                       # Bootstrap cache
│
├── 📂 config/                       # Configuration files
│
├── 📂 database/                     # Database related
│   ├── migrations/                  # Database schema
│   └── seeders/                     # Sample data
│
├── 📂 docker/                       # 🐳 Docker configs
│   └── nginx/
│       ├── nginx.conf               # Nginx main config
│       └── conf.d/
│           └── default.conf         # Virtual host config
│
├── 📂 public/                       # Public web root
│   └── index.php                    # Laravel entry point
│
├── 📂 resources/                    # Views, assets
│
├── 📂 routes/                       # Route definitions
│   ├── api.php                      # API routes
│   └── web.php                      # Web routes
│
├── 📂 storage/                      # Storage & logs
│   ├── app/
│   ├── framework/
│   └── logs/                        # Laravel logs
│
├── 📂 vendor/                       # Composer dependencies
│
├── 📄 .env                          # Environment config
├── 📄 .env.production               # Production template
│
├── 🐳 Dockerfile                    # App container definition
├── 🐳 docker-compose.yml            # Multi-container orchestration
│
├── 🚀 deploy.sh                     # Deployment script
├── 🔐 create-admin.sh               # Admin user creation
├── ⚡ quick-commands.sh             # Management menu
│
└── 📚 Documentation/
    ├── DEPLOY_README.md             # Quick start
    ├── DEPLOY_STEPS.md              # Step by step
    ├── DEPLOYMENT.md                # Full guide
    ├── DEPLOYMENT_SUMMARY.md        # Summary
    └── COPY_TO_SERVER.txt           # Upload guide
```

---

## ⚙️ Container Configuration

### Container 1: cafe-nginx
```yaml
Image: nginx:alpine
Port: 80 → 80 (Public access)
Volume: 
  - Project files (read-only)
  - Nginx configs
Network: cafe-network
Purpose: Web server & reverse proxy
```

### Container 2: cafe-app
```yaml
Image: Built from Dockerfile (PHP 8.2 FPM)
Port: 9000 (Internal only)
Volume: 
  - Project files (read-write)
  - storage/ (persistent)
  - bootstrap/cache/ (persistent)
Environment:
  - DB_HOST=mysql
  - DB_DATABASE=cafe_db
  - DB_USERNAME=cafe_user
  - DB_PASSWORD=cafe_secret
Network: cafe-network
Purpose: Run Laravel application
```

### Container 3: cafe-mysql
```yaml
Image: mysql:8.0
Port: 3306 (Internal + External)
Volume: cafe_mysql_data (persistent)
Environment:
  - MYSQL_ROOT_PASSWORD=root
  - MYSQL_DATABASE=cafe_db
  - MYSQL_USER=cafe_user
  - MYSQL_PASSWORD=cafe_secret
Network: cafe-network
Purpose: Database storage
```

---

## 🔐 Authentication Flow

```
1. LOGIN REQUEST
   POST /api/auth/login
   {
     "email": "admin@cafe.com",
     "password": "admin123"
   }
   │
   ▼

2. LARAVEL AUTHENTICATION
   │
   ├─► Validate credentials
   │   └─► Check email & password in users table
   │
   ├─► Generate JWT tokens
   │   ├─► Access Token (expires in 1 hour)
   │   └─► Refresh Token (expires in 7 days)
   │
   └─► Return tokens
       {
         "success": true,
         "data": {
           "user": {...},
           "access_token": "eyJ0eXAi...",
           "refresh_token": "def502...",
           "expires_in": 3600
         }
       }

3. AUTHENTICATED REQUEST
   GET /api/orders
   Headers: {
     "Authorization": "Bearer eyJ0eXAi..."
   }
   │
   ▼

4. JWT MIDDLEWARE
   │
   ├─► Verify token signature
   ├─► Check expiration
   ├─► Extract user ID
   │
   └─► Allow access or reject (401)
```

---

## 📊 Database Schema

```
┌─────────────────┐         ┌─────────────────┐
│     users       │         │      menus      │
├─────────────────┤         ├─────────────────┤
│ id              │         │ id              │
│ nama            │         │ nama_menu       │
│ email           │    ┌────│ kategori        │
│ password        │    │    │ harga           │
│ role            │    │    │ deskripsi       │
│ created_at      │    │    │ gambar_url      │
│ updated_at      │    │    │ ketersediaan    │
└────────┬────────┘    │    │ created_at      │
         │             │    │ updated_at      │
         │             │    └─────────────────┘
         │             │
         │             │    ┌─────────────────┐
         │             └────│     orders      │
         │                  ├─────────────────┤
         │                  │ id              │
         ├──────────────────│ user_id         │
         │                  │ menu_id         │
         │                  │ jumlah          │
         │                  │ total_harga     │
         │                  │ status          │
         │                  │ catatan         │
         │                  │ created_at      │
         │                  │ updated_at      │
         │                  └─────────────────┘
         │
         │                  ┌─────────────────┐
         └──────────────────│  reservations   │
                            ├─────────────────┤
                            │ id              │
                            │ user_id         │
                            │ tanggal         │
                            │ waktu           │
                            │ jumlah_orang    │
                            │ status          │
                            │ catatan         │
                            │ created_at      │
                            │ updated_at      │
                            └─────────────────┘
```

---

## 🔄 Deployment Process Flow

```
┌─────────────────────────────────────────────────────────┐
│                    LOCAL MACHINE                        │
├─────────────────────────────────────────────────────────┤
│                                                         │
│  1. Develop code                                       │
│  2. Test locally                                       │
│  3. Push to Git (optional)                            │
│  4. Upload to server via SCP/Git                      │
│                                                         │
└────────────────────┬────────────────────────────────────┘
                     │
                     │ scp -r cafe-UAS/ root@203.175.10.112:/opt/.izzudin/
                     │
                     ▼
┌─────────────────────────────────────────────────────────┐
│              VPS SERVER (203.175.10.112)               │
├─────────────────────────────────────────────────────────┤
│                                                         │
│  5. Login: ssh root@203.175.10.112                    │
│                                                         │
│  6. Install Docker & Docker Compose                   │
│     curl -fsSL https://get.docker.com | sh            │
│     apt install docker-compose -y                     │
│                                                         │
│  7. Navigate to project                               │
│     cd /opt/.izzudin/cafe-UAS                        │
│                                                         │
│  8. Run deployment script                             │
│     ./deploy.sh                                       │
│                                                         │
│     ┌───────────────────────────────────────┐         │
│     │  deploy.sh Process:                   │         │
│     │                                        │         │
│     │  ✓ Check Docker installation          │         │
│     │  ✓ Setup .env file                    │         │
│     │  ✓ Set permissions                    │         │
│     │  ✓ Stop old containers                │         │
│     │  ✓ Build Docker images                │         │
│     │  ✓ Start containers                   │         │
│     │  ✓ Generate APP_KEY                   │         │
│     │  ✓ Run migrations                     │         │
│     │  ✓ Seed database (optional)           │         │
│     │  ✓ Cache configuration                │         │
│     │                                        │         │
│     └───────────────────────────────────────┘         │
│                                                         │
│  9. Create admin user                                 │
│     ./create-admin.sh                                 │
│                                                         │
│  10. Test deployment                                  │
│      curl http://203.175.10.112                      │
│      curl http://203.175.10.112/api/menu             │
│                                                         │
└────────────────────┬────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────┐
│                  APPLICATION RUNNING                    │
├─────────────────────────────────────────────────────────┤
│                                                         │
│  ✅ Nginx: Running on port 80                         │
│  ✅ Laravel: Running on port 9000 (internal)          │
│  ✅ MySQL: Running on port 3306                       │
│                                                         │
│  🌐 Access: http://203.175.10.112                     │
│  🔗 API: http://203.175.10.112/api                    │
│                                                         │
└─────────────────────────────────────────────────────────┘
```

---

## 🛠️ Management Commands Visual

```
./quick-commands.sh
│
├─► 1. Start containers          → docker-compose up -d
├─► 2. Stop containers           → docker-compose down
├─► 3. Restart containers        → docker-compose restart
│
├─► 4. View logs (all)           → docker-compose logs -f
├─► 5. View logs (app)           → docker-compose logs -f app
├─► 6. View logs (nginx)         → docker-compose logs -f nginx
├─► 7. View logs (mysql)         → docker-compose logs -f mysql
│
├─► 8. Container status          → docker-compose ps
├─► 9. Shell into app            → docker-compose exec app bash
│
├─► 10. Run migrations           → php artisan migrate
├─► 11. Clear cache              → php artisan cache:clear
│
├─► 12. Create admin user        → ./create-admin.sh
├─► 13. Database backup          → mysqldump > backup.sql
│
├─► 14. Test API endpoints       → curl http://203.175.10.112/api/*
└─► 15. Update & Rebuild         → git pull && docker-compose up -d --build
```

---

## 📈 Monitoring Dashboard

```
┌──────────────────────────────────────────────────────┐
│           SYSTEM MONITORING COMMANDS                 │
├──────────────────────────────────────────────────────┤
│                                                      │
│  Container Status:                                  │
│  $ docker-compose ps                                │
│                                                      │
│  NAME           STATUS       PORTS                  │
│  cafe-nginx     Up           0.0.0.0:80->80        │
│  cafe-app       Up           9000/tcp              │
│  cafe-mysql     Up           0.0.0.0:3306->3306    │
│                                                      │
├──────────────────────────────────────────────────────┤
│  Resource Usage:                                    │
│  $ docker stats                                     │
│                                                      │
│  CONTAINER      CPU %    MEM USAGE      NET I/O    │
│  cafe-nginx     0.5%     10MB / 2GB    1.2MB / 800KB │
│  cafe-app       2.1%     85MB / 2GB    500KB / 1MB   │
│  cafe-mysql     1.3%     150MB / 2GB   200KB / 500KB │
│                                                      │
├──────────────────────────────────────────────────────┤
│  Recent Logs:                                       │
│  $ docker-compose logs --tail 10 app                │
│                                                      │
│  [2026-07-08 10:15:23] GET /api/menu 200           │
│  [2026-07-08 10:15:25] POST /api/auth/login 200    │
│  [2026-07-08 10:15:30] GET /api/orders 200         │
│                                                      │
├──────────────────────────────────────────────────────┤
│  Disk Usage:                                        │
│  $ docker system df                                 │
│                                                      │
│  TYPE        TOTAL    ACTIVE   SIZE                │
│  Images      3        3        450MB               │
│  Containers  3        3        50MB                │
│  Volumes     1        1        200MB               │
│                                                      │
└──────────────────────────────────────────────────────┘
```

---

## 🎯 API Endpoints Map

```
http://203.175.10.112/api/
│
├─► /auth
│   ├─► POST /login          (Login)
│   ├─► POST /register       (Register)
│   ├─► POST /logout         (Logout)
│   └─► POST /refresh        (Refresh token)
│
├─► /menu
│   ├─► GET    /             (List all menu)
│   ├─► POST   /             (Create menu) [Admin]
│   ├─► GET    /{id}         (Show menu)
│   ├─► PUT    /{id}         (Update menu) [Admin]
│   └─► DELETE /{id}         (Delete menu) [Admin]
│
├─► /orders
│   ├─► GET    /             (List orders) [Auth]
│   ├─► POST   /             (Create order) [Auth]
│   ├─► GET    /{id}         (Show order) [Auth]
│   ├─► PUT    /{id}         (Update order) [Auth]
│   └─► DELETE /{id}         (Cancel order) [Auth]
│
├─► /reservations
│   ├─► GET    /             (List reservations) [Auth]
│   ├─► POST   /             (Create reservation) [Auth]
│   ├─► GET    /{id}         (Show reservation) [Auth]
│   ├─► PUT    /{id}         (Update reservation) [Auth]
│   └─► DELETE /{id}         (Cancel reservation) [Auth]
│
└─► /reviews
    ├─► GET    /             (List reviews)
    ├─► POST   /             (Create review) [Auth]
    ├─► GET    /{id}         (Show review)
    ├─► PUT    /{id}         (Update review) [Auth]
    └─► DELETE /{id}         (Delete review) [Auth/Admin]

[Auth] = Requires authentication (Bearer token)
[Admin] = Requires admin role
```

---

## 🔒 Security Layers

```
┌─────────────────────────────────────────────────┐
│               Internet Traffic                  │
└────────────────┬────────────────────────────────┘
                 │
                 ▼
         ┌───────────────┐
         │   FIREWALL    │  Layer 1: Network Security
         │   (UFW)       │  • Allow port 80, 443
         └───────┬───────┘  • Block other ports
                 │
                 ▼
         ┌───────────────┐
         │     NGINX     │  Layer 2: Web Server Security
         │               │  • Rate limiting
         └───────┬───────┘  • Block .env access
                 │          • Hide .git directory
                 ▼
         ┌───────────────┐
         │ JWT MIDDLEWARE│  Layer 3: Authentication
         │               │  • Verify token
         └───────┬───────┘  • Check expiration
                 │
                 ▼
         ┌───────────────┐
         │ ROLE MIDDLEWARE│ Layer 4: Authorization
         │               │  • Check user role
         └───────┬───────┘  • Admin vs User
                 │
                 ▼
         ┌───────────────┐
         │  VALIDATION   │  Layer 5: Input Validation
         │  (Requests)   │  • Sanitize input
         └───────┬───────┘  • Validate data types
                 │
                 ▼
         ┌───────────────┐
         │   ELOQUENT    │  Layer 6: SQL Protection
         │     ORM       │  • Prevent SQL injection
         └───────┬───────┘  • Parameterized queries
                 │
                 ▼
         ┌───────────────┐
         │   DATABASE    │  Layer 7: Data Storage
         │   (MySQL)     │  • Encrypted passwords
         └───────────────┘  • User credentials
```

---

**Panduan visual ini membantu Anda memahami:**
- Arsitektur sistem secara keseluruhan
- Alur request dari client ke database
- Struktur project dan file-file penting
- Konfigurasi containers
- Proses deployment step-by-step
- Command management
- Monitoring dan troubleshooting
- API endpoints mapping
- Security layers

**Happy Deploying! 🚀**
