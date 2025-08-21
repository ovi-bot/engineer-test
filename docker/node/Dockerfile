FROM node:20-alpine

WORKDIR /app
ENV HOST=0.0.0.0

COPY client/package*.json ./
RUN --mount=type=cache,target=/root/.npm \
    npm install

EXPOSE 5173
CMD ["npm", "run", "dev", "--", "--host", "0.0.0.0", "--port", "5173"]
