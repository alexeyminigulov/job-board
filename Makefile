docker-clear: sudo rm -rf var/cache/dev

docker-up: docker-compose up --build -d

permission: sudo chmod -R 777 var/cache/dev