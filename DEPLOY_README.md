# 🚀 Quick Deploy Guide - Laravel Cafe

Deploy aplikasi Laravel Cafe ke VPS menggunakan Docker Compose.

**Server IP:** `203.175.10.112`

---

## 🎯 Quick Start (3 Langkah)

### 1️⃣ Upload Project ke Server
```bash
# Dari local
scp -r /path/to/cafe-UAS root@203.175.10.112:/opt/.izzudin/

# Atau di server
cd /opt/.izzudin && git clone <repo-url> cafe-UAS
```

### 2️⃣ Install Docker (Jika belum ada)
```bash
ssh root@203.175.10.112

# Install Docker & Docker Compose
curl -fsSL https://get.docker.com | sh
apt install docker-compose -y
```

### 3️⃣ Deploy!
```bash
cd /opt/.izzudin/cafe-UAS

# Jalankan deployment script
chmod +x deploy.sh
./deploy.sh

# Buat admin user
chmod +x create-admin.sh
./create-admin.sh
```

**SELESAI!** 🎉

Akses: `http://203.175.10.112`

---

## 📱 Test Deployment

```bash
# Test homepage
curl http://203.175.10.112

# Test API
curl http://203.175.10.112/api/menu

# Test login
curl -X POST http://203.175.10.112/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@cafe.com","password":"admin123"}'
```

---

## 🛠️ Management Commands

### Quick Commands Menu
```bash
# Interactive menu untuk management
chmod +x quick-commands.sh
./quick-commands.sh
```

### Manual Commands
```bash
# Status
docker-compose ps

# Logs
docker-compose logs -f app

# Restart
docker-compose restart

# Stop
docker-compose down

# Update
git pull && docker-compose up -d --build
```

---

## 📋 Default Credentials

**Admin:**
- Email: `admin@cafe.com`
- Password: `admin123`

⚠️ **Ganti password setelah login pertama!**

---

## 🔗 URLs

| Service | URL |
|---------|-----|
| Homepage | http://203.175.10.112 |
| API | http://203.175.10.112/api |
| Health Check | http://203.175.10.112/api/health |
| Menu | http://203.175.10.112/api/menu |
| Orders | http://203.175.10.112/api/orders |
| Reservations | http://203.175.10.112/api/reservations |

---

## 🐛 Troubleshooting

### Port 80 sudah digunakan?
```bash
# Stop Apache
systemctl stop apache2

# Atau gunakan port lain (edit docker-compose.yml)
# ports: - "81:80"
```

### Permission error?
```bash
docker-compose exec app chmod -R 775 /var/www/storage /var/www/bootstrap/cache
docker-compose exec app chown -R www-data:www-data /var/www
```

### Database error?
```bash
# Restart MySQL
docker-compose restart mysql

# Check connection
docker-compose exec mysql mysql -u cafe_user -pcafe_secret -e "SHOW DATABASES;"
```

---

## 📚 Documentation

- **Full Guide:** `DEPLOY_STEPS.md`
- **Detailed:** `DEPLOYMENT.md`
- **API Docs:** `postman.md`
- **Project:** `DOKUMENTASI.md`

---

## ✅ Checklist

- [ ] Docker installed
- [ ] Project uploaded
- [ ] `./deploy.sh` executed
- [ ] Admin user created
- [ ] Can access http://203.175.10.112
- [ ] API responds correctly
- [ ] Password changed

---

**Need Help?** Check logs: `docker-compose logs -f`
