FROM openjdk:17

WORKDIR /app

COPY build/libs/test-0.0.1-SNAPSHOT.jar app.jar

# Copy the data.json file into the container
COPY /src/main/resources/data.json /app/resources/data.json

RUN chmod 644 /app/resources/data.json


EXPOSE 8083

CMD ["java", "-jar", "/app/app.jar"]
