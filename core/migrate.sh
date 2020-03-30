#!/usr/bin/env bash
yes | ./yii migrate/up
yes | ./yii migrate-queue
yes | ./yii migrate-user
yes | ./yii migrate/up --migrationPath=@yii/rbac/migrations
yes | ./yii rbac/init
