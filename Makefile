.PHONY: all start

all: start

start:
	./docker/start-project.sh

stop:
	docker-compose down --remove-orphan
