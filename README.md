# Symfony API Throttling

## Description
This project demonstrates custom API throttling with two endpoints, supporting different rate-limiting strategies and client configurations.

## Endpoints
- `GET /foo` → Token Bucket
- `GET /bar` → Fixed Window

## Requirements
- PHP 8.1 or higher
- git
- composer

## Installation
1. Clone the repository:
   ```bash
   git clone git@github.com:freelancerwebro/api-throttle-symfony.git

2. Navigate to the project directory:
   ```bash
   cd api-throttle-symfony
   ```
3. Install dependencies:
   ```bash
    composer install
    ```
4. Start the Symfony server:
   ```bash
    symfony server:start
    ```
5. Load test data:
   ```bash
   php bin/console doctrine:fixtures:load
   ```
## Example Requests
Fixed Window - client 1
  ```bash
  curl -X GET /bar \
    --header 'Authorization: Bearer 1'
  ```

Token Bucket - client 2
```bash
  curl -X GET /foo \
    --header 'Authorization: Bearer 2'
  ```
