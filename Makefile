nginx-reload:
	docker compose exec nginx sh -c " \
		./docker-entrypoint.d/20-envsubst-on-templates.sh && \
		nginx -s reload"

up:
	docker compose up -d

down:
	docker compose down

restart:
	docker compose restart

backend:
	docker compose exec backend bash

readability:
	docker compose exec readability bash

socket.io:
	docker compose exec socket.io bash

queue:
	docker compose exec backend bash -c "./artisan queue:work"

queued:
	docker compose exec backend bash -c "XDEBUG_SESSION_START=cli ./artisan queue:work"

tinker:
	docker compose exec backend bash -c "./artisan tinker"

tinkerd:
	docker compose exec backend bash -c "XDEBUG_SESSION_START=tinker ./artisan tinker"

cache-clear:
	docker compose exec backend bash -c "./artisan cache:clear"

migrate:
	docker compose exec backend bash -c "./artisan migrate"

nginx-reload-config:
	docker compose exec nginx nginx -s reload

dep: #backend-dependency:
	docker compose exec backend bash -c "vendor/bin/deptrac"

composerdu:
	docker compose exec backend bash -c "composer du"

front-build:
	docker compose exec backend bash -c "cd /var/www/nova-components/ReleaseTool&&npm run dev"

front-watch:
	docker compose exec backend bash -c "cd /var/www/nova-components/ReleaseTool&&npm run watch"
