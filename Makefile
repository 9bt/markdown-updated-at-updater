# Makefile

BASE_TARGET_DIR :=
CONDITION :=

deps:
	composer install

run:
	php main.php $(BASE_TARGET_DIR) "$(CONDITION)"
