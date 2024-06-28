# Recipe Statistics API Project

This project provides a set of API endpoints to process an automatically generated JSON file with some data and calculate various statistics.

## Features

- Get recipes matching specific keywords
- Count unique recipes
- Retrieve recipes statistics
- Use custom fixture files for testing
## Technology Stack

- Laravel Framework 10.48.14
- PHP 8.1

## Prerequisites

- Docker
- Docker Compose

## Setup Instructions / Installation

1. **Clone the repository**:
    ```sh
    git clone https://github.com/kidepo/financetrust_dev_test.git
    cd financetrust_dev_test/api-assignment
    git checkout kiyingi_denis_ftb_dev
    ```

2. **Run the setup script**:
    ```sh
    ./setup.sh
    ```
    - If you hit any snag during app setup, can manually check out the detailed setup instructions in the setup script in `financetrust_dev_test/api-assignment/setup.sh`
## Run Instructions

1. **Ensure Docker containers are running**:
    ```sh
    docker-compose up -d
    ```

2. **Access the application**:

    - **API Documentation**: [http://localhost:8080/api/documentation](http://localhost:8080/api/documentation)
    - **API Endpoints**: Can directly test using Swagger web views or use Postman or any API client. 
    - **HOME**: [http://localhost:8080/](http://localhost:8080/). 

## Running Unit Tests

1. **Run the test suite**:
    ```sh
    docker-compose exec app ./vendor/bin/phpunit --testdox
    ```
    - or simply run in application root directory

    ```sh
    docker-compose run --rm test
    ```
## Note on Using Custom Files

- If you plan to use a custom fixtures file, ensure the project is running locally. This setup does not include the capability to reference local file paths from within Docker containers. The custom file should be accessible to the local filesystem where the application is running.


## API Endpoints

- **Get recipes matching specific keywords**:
    ```http
    GET /api/match-by-name?keywords[]=Potato&keywords[]=Veggie&keywords[]=Mushroom
    ```

    - **Parameters**:
        - `keywords` (array): List of keywords to match
        - `fixtures_file` (string, optional): Path to custom `.json` fixtures file

## Example Usage in Postman

1. **Match Recipes by Name**:
    - **Method**: GET
    - **URL**: `http://localhost:8080/api/match-by-name`
    - **Query Parameters**:
        - `keywords` (e.g., `Potato`, `Veggie`, `Mushroom`)
        - `fixtures_file` (e.g., `path/to/your/fixtures.json`)


## Example Usage in Swagger

1. **Access Swagger UI**:
    - **URL**: [http://localhost:8080/api/documentation](http://localhost:8080/api/documentation)

2. **Test Endpoints**:
    - Use the Swagger UI to interact with the API endpoints directly. Fill in the parameters and execute requests to see the responses in real-time!!

## Troubleshooting

- If you encounter any issues with Docker, ensure that Docker and Docker Compose are properly installed and running.
- Ensure that the `.env` file is correctly configured.
- Check Docker container logs for any error messages:
    ```sh
    docker-compose logs
    ```

## License

#FTB ASSIGNMENT.

---

Thank you for reviewing this Recipe Statistics API!
