#!/bin/bash

# Upload Fixed Files to Server

echo "================================================"
echo "📤 Uploading Fixed Files to Server"
echo "================================================"
echo ""

SERVER="root@203.175.10.112"
REMOTE_PATH="/opt/.izzudin/cafe-UAS"

echo "Uploading DatabaseSeeder..."
scp database/seeders/DatabaseSeeder.php $SERVER:$REMOTE_PATH/database/seeders/

echo "Uploading Factory files..."
scp database/factories/UserFactory.php $SERVER:$REMOTE_PATH/database/factories/
scp database/factories/MenuFactory.php $SERVER:$REMOTE_PATH/database/factories/
scp database/factories/OrderFactory.php $SERVER:$REMOTE_PATH/database/factories/
scp database/factories/ReservationFactory.php $SERVER:$REMOTE_PATH/database/factories/
scp database/factories/ReviewFactory.php $SERVER:$REMOTE_PATH/database/factories/

echo ""
echo "================================================"
echo "✅ Upload Complete!"
echo "================================================"
echo ""
echo "Now run on server:"
echo "  ssh $SERVER"
echo "  cd $REMOTE_PATH"
echo "  docker-compose exec app php artisan migrate:fresh --seed --force"
echo ""
