# Recipe Statistics API Project

This project provides a set of API endpoints to process data and automatically generate a JSON file with  data and various calculated statistics as in the requireements.

## Features
- Aggregate and generate comprehensive recipe statistics data into a JSON file
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
### Aggregated Data Endpoint (Generate Result Json File)

#### Summary

The `aggregated-data` endpoint aggregates various recipe-related statistics into a single response. This endpoint generates a JSON file that includes unique recipe counts, recipe occurrences, busiest postcode, and matched recipe names as required.

#### Description

This endpoint provides a detailed aggregation of recipe data and stores the result in a JSON file. It includes:

- **Unique Recipe Count**: Calculates the total number of unique recipes in the dataset.
- **Count Per Recipe**: Counts the occurrences of each unique recipe, sorted alphabetically by recipe name.
- **Busiest Postcode**: identifies the postcde with the highest number of recipe deliveries and the count of deliveries to that postcode.
- **Matched Recipe Names**: Provides a list of recipe names that match any of the provided keywords, sorted alphabetically.

Optionally, you can provide:
- **fixtures_file**: A custom fixtures file to override the default dataset.
- **keywords**: Keywords for matching recipe names to filter specific recipes.

The aggregated data is stored in a JSON file located in the `public/results` directory. The response includes the path to this file for easy access in browesr and downloading it.

#### Endpoint
GET /api/aggregated-data

- **Parameters**:
    - `fixtures_file` (optional): Path to custom .json fixtures file.
    - `keywords` (optional): List of keywords to match in recipe names.


#### Example Request
```http
GET /api/aggregated-data?keywords[]=Potato&keywords[]=Veggie
```
#### Example Response
```json
{
  "json_result_file_path": "http://localhost:8080/results/aggregated_data.json",
  "data": {
    "unique_recipe_count": 15,
    "count_per_recipe": [
      {
        "recipe": "Mediterranean Baked Veggies",
        "count": 1
      },
      {
        "recipe": "Speedy Steak Fajitas",
        "count": 1
      },
      {
        "recipe": "Tex-Mex Tilapia",
        "count": 3
      }
    ],
    "busiest_postcode": {
      "postcode": "10120",
      "delivery_count": 1000
    },
    "match_by_name": [
      "Mediterranean Baked Veggies",
      "Speedy Steak Fajitas",
      "Tex-Mex Tilapia"
    ]
  }
  
}
```
### Additional API Endpoints

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
