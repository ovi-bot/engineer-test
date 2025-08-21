FROM node:20-alpine AS builder
WORKDIR /client

COPY client/package*.json ./
RUN --mount=type=cache,target=/root/.npm \
    npm install

COPY client/ .
RUN npm run build

FROM nginx:1.27-alpine

COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf
COPY --from=builder /client/dist /usr/share/nginx/html

RUN chmod -R 755 /usr/share/nginx/html

EXPOSE 80
CMD ["nginx", "-g", "daemon off;"]
