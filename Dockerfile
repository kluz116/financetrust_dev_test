FROM node:alpine
COPY . /app
COPY appData . /app/
COPY package*.json . /app/
WORKDIR /app
RUN npm install
ENV PORT=3044
EXPOSE 3044
CMD node index.js