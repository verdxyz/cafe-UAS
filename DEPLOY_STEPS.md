# 🚀 Langkah-langkah Deploy ke VPS (203.175.10.112)

## Persiapan di Local Machine

### 1. Upload Project ke Server
```bash
# Dari local machine, upload project ke server
scp -r /home/lbi/Documents/joki/cafe-UAS root@203.175.10.112:/opt/.izzudin/

# Atau jika sudah di server, git clone
# cd /opt/.izzudin
# git clone <repository-url> cafe-UAS
```

---

## Eksekusi di Server VPS

### 2. Login ke VPS
```bash
ssh root@203.175.10.112
cd /opt/.izzudin/cafe-UAS
```

### 3. Install Docker & Docker Compose (Jika belum)
```bash
# Install Docker
curl -fsSL https://get.docker.com | sh

# Install Docker Compose
apt install docker-compose -y

# Verifikasi
docker --version
docker-compose --version
```

### 4. Jalankan Deploy Script
```bash
# Beri permission execute
chmod +x deploy.sh

# Jalankan deployment
./deploy.sh
```

Script akan otomatis:
- Setup .env file
- Build Docker images
- Start semua containers (nginx, app, mysql)
- Generate APP_KEY
- Run migrations
- Cache configuration

### 5. Buat Admin User
```bash
docker-compose exec app php artisan tinker --execute "
\$user = App\Models\User::create([
    'nama' => 'Admin Cafe',
    'email' => 'admin@cafe.com',
    'password' => bcrypt('admin123'),
    'role' => 'admin'
]);
echo 'Admin created: ' . \$user->email;
"
```

**Kredensial Admin:**
- Email: `admin@cafe.com`
- Password: `admin123`

---

## 6. Test Akses

### Test Homepage
```bash
curl http://203.175.10.112
```

### Test API
```bash
# Test endpoint menu
curl http://203.175.10.112/api/menu

# Test login
curl -X POST http://203.175.10.112/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "admin@cafe.com",
    "password": "admin123"
  }'
```

### Akses dari Browser
- Homepage: `http://203.175.10.112`
- API: `http://203.175.10.112/api`
- Health Check: `http://203.175.10.112/api/health`

---

## Commands Penting

### Monitoring
```bash
# Lihat status containers
docker-compose ps

# Lihat logs real-time
docker-compose logs -f

# Lihat logs specific service
docker-compose logs -f app
docker-compose logs -f nginx
docker-compose logs -f mysql
```

### Management
```bash
# Restart containers
docker-compose restart

# Stop containers
docker-compose down

# Rebuild setelah update code
docker-compose up -d --build

# Masuk ke container
docker-compose exec app bash
```

### Laravel Commands
```bash
# Clear cache
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan route:clear

# Run migration
docker-compose exec app php artisan migrate

# Tinker (PHP REPL)
docker-compose exec app php artisan tinker
```

---

## Troubleshooting

### Port 80 sudah digunakan
```bash
# Cek service yang menggunakan port 80
netstat -tulpn | grep :80

# Stop Apache jika ada
systemctl stop apache2
systemctl disable apache2

# Atau ubah port di docker-compose.yml menjadi 81
# ports:
#   - "81:80"
```

### Permission Error
```bash
docker-compose exec app chmod -R 775 /var/www/storage /var/www/bootstrap/cache
docker-compose exec app chown -R www-data:www-data /var/www
```

### Database Connection Error
```bash
# Restart MySQL container
docker-compose restart mysql

# Check MySQL logs
docker-compose logs mysql

# Test connection
docker-compose exec mysql mysql -u cafe_user -pcafe_secret cafe_db -e "SHOW TABLES;"
```

### 500 Internal Server Error
```bash
# Check logs
docker-compose logs app

# Clear cache
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear

# Check .env
docker-compose exec app cat .env | grep DB_
```

---

## Security Tips

### 1. Ganti Password Admin
```bash
docker-compose exec app php artisan tinker --execute "
\$admin = App\Models\User::where('email', 'admin@cafe.com')->first();
\$admin->password = bcrypt('PasswordBaruYangKuat123!');
\$admin->save();
echo 'Password updated!';
"
```

### 2. Ubah JWT Secret
```bash
# Generate random JWT secret
php -r "echo bin2hex(random_bytes(32));"

# Edit .env dan update JWT_SECRET
nano .env

# Restart containers
docker-compose restart
```

### 3. Setup Firewall
```bash
# Allow port 80
ufw allow 80/tcp

# Enable firewall
ufw enable
```

### 4. Backup Database
```bash
# Backup
docker-compose exec mysql mysqldump -u cafe_user -pcafe_secret cafe_db > backup_$(date +%Y%m%d).sql

# Restore
docker-compose exec -T mysql mysql -u cafe_user -pcafe_secret cafe_db < backup_20250101.sql
```

---

## Update Aplikasi

```bash
# Pull latest code
git pull origin main

# Rebuild containers
docker-compose up -d --build

# Run migrations
docker-compose exec app php artisan migrate --force

# Clear cache
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan route:cache
```

---

## Checklist Deployment

- [ ] Docker & Docker Compose terinstall
- [ ] Project sudah di /opt/.izzudin/cafe-UAS
- [ ] File .env sudah dikonfigurasi
- [ ] Containers berjalan (docker-compose ps)
- [ ] Database migration sukses
- [ ] Admin user dibuat
- [ ] Test akses via curl berhasil
- [ ] Test akses via browser berhasil
- [ ] Password admin sudah diganti
- [ ] JWT secret sudah di-generate

---

## URLs Aplikasi

| Endpoint | URL |
|----------|-----|
| Homepage | http://203.175.10.112 |
| API Base | http://203.175.10.112/api |
| Health | http://203.175.10.112/api/health |
| Menu | http://203.175.10.112/api/menu |
| Auth | http://203.175.10.112/api/auth |

---

## Kontak & Support

Jika ada masalah saat deployment, cek:
1. Docker logs: `docker-compose logs -f`
2. Laravel logs: `docker-compose exec app tail -f storage/logs/laravel.log`
3. Nginx logs: `docker-compose logs nginx`

**Selamat! Aplikasi sudah berhasil di-deploy! 🎉**
