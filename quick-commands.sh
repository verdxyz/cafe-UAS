#!/bin/bash

# Quick Commands Helper untuk Laravel Cafe

show_menu() {
    echo ""
    echo "=========================================="
    echo "🚀 Laravel Cafe - Quick Commands"
    echo "=========================================="
    echo ""
    echo "1.  Start containers"
    echo "2.  Stop containers"
    echo "3.  Restart containers"
    echo "4.  View logs (all)"
    echo "5.  View logs (app only)"
    echo "6.  View logs (nginx only)"
    echo "7.  View logs (mysql only)"
    echo "8.  Container status"
    echo "9.  Shell into app container"
    echo "10. Run migrations"
    echo "11. Clear cache"
    echo "12. Create admin user"
    echo "13. Database backup"
    echo "14. Test API endpoints"
    echo "15. Update & Rebuild"
    echo "0.  Exit"
    echo ""
    echo "=========================================="
}

while true; do
    show_menu
    read -p "Pilih menu (0-15): " choice
    echo ""
    
    case $choice in
        1)
            echo "Starting containers..."
            docker-compose up -d
            ;;
        2)
            echo "Stopping containers..."
            docker-compose down
            ;;
        3)
            echo "Restarting containers..."
            docker-compose restart
            ;;
        4)
            echo "Viewing all logs... (Ctrl+C to exit)"
            docker-compose logs -f
            ;;
        5)
            echo "Viewing app logs... (Ctrl+C to exit)"
            docker-compose logs -f app
            ;;
        6)
            echo "Viewing nginx logs... (Ctrl+C to exit)"
            docker-compose logs -f nginx
            ;;
        7)
            echo "Viewing mysql logs... (Ctrl+C to exit)"
            docker-compose logs -f mysql
            ;;
        8)
            echo "Container status:"
            docker-compose ps
            ;;
        9)
            echo "Opening shell in app container..."
            docker-compose exec app bash
            ;;
        10)
            echo "Running migrations..."
            docker-compose exec app php artisan migrate
            ;;
        11)
            echo "Clearing cache..."
            docker-compose exec app php artisan cache:clear
            docker-compose exec app php artisan config:clear
            docker-compose exec app php artisan route:clear
            echo "✓ Cache cleared"
            ;;
        12)
            echo "Creating admin user..."
            ./create-admin.sh
            ;;
        13)
            BACKUP_FILE="backup_$(date +%Y%m%d_%H%M%S).sql"
            echo "Creating database backup: $BACKUP_FILE"
            docker-compose exec mysql mysqldump -u cafe_user -pcafe_secret cafe_db > $BACKUP_FILE
            echo "✓ Backup created: $BACKUP_FILE"
            ;;
        14)
            echo "Testing API endpoints..."
            echo ""
            echo "1. Health Check:"
            curl -s http://203.175.10.112/api/health | jq . || curl http://203.175.10.112/api/health
            echo ""
            echo ""
            echo "2. Menu List:"
            curl -s http://203.175.10.112/api/menu | jq . || curl http://203.175.10.112/api/menu
            echo ""
            ;;
        15)
            echo "Updating and rebuilding..."
            git pull origin main 2>/dev/null || echo "Skipping git pull..."
            docker-compose up -d --build
            docker-compose exec app php artisan migrate --force
            docker-compose exec app php artisan config:cache
            docker-compose exec app php artisan route:cache
            echo "✓ Update complete"
            ;;
        0)
            echo "Exiting..."
            exit 0
            ;;
        *)
            echo "Invalid option. Please try again."
            ;;
    esac
    
    echo ""
    read -p "Press Enter to continue..."
done
