FROM openjdk:17

WORKDIR /app

COPY build/libs/test-0.0.1-SNAPSHOT.jar app.jar

EXPOSE 8083

CMD ["java", "-jar", "/app/app.jar"]
