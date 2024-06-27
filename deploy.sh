#!/bin/bash
./gradlew build
docker build -t fintrust:latest .
docker run -d --restart unless-stopped -p 8080:8080 --name fintrust_container fintrust:latest
