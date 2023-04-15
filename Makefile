export UID=1000
export GID=1000

up:
	docker-compose up
down:
	docker-compose down
php:
	docker exec -it -u 1000 app-php bash