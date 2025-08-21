# Engineer Test

Application is broken up into an api written in Vanilla PHP and a client written in Vue 3.

Database is provided by a MySQL container, on first run the database will schema will be created using `docker/mysql/init/001_init.sql`.

Client provides simple ways to download a sample CSV as well as a reset function (this will truncate the tables).

Unit tests are executed in a separate container and can be run side by side with application containers.

## How to run

### Prerequisites

- Docker Desktop

### Application

    docker compose up

### Tests

    docker compose -f compose.tests.yml up --abort-on-container-exit 

## Potential Improvements

### Application
- Remove dangerous truncate functions
- Integrate database transactions for imports to guard against partial import
- Improve CSV import service to allow for any order of columns
- Replace init .sql file with a more robust migration system
- Add support for different users by adding data segregation by user
- Add .env file support for dev environments and remove hardcoded dev DB credentials
- Replace dynamic routing with a more robust static route map
- Add extra validation for data inputs
- Add application logging

### Tests

- Expand unit test coverage
- Alter test environment to adequately test application `exit` conditions
- Add integration tests
- Add end-to-end tests for the Vue client

### Containers

- Refactor Dockerfiles to use multi-stage builds for smaller image sizes
- Refactor Dockerfiles to allow for production deployments
- Modify compose configuration for dev environments