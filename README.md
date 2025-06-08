# 5-Day Weather Forecast Application



## Prerequisites
-   PHP >= 8.2
-   Composer
-   Node.js & npm
-   Docker
-   A local development environment like Laragon, Valet, or Herd. (These instructions assume a setup similar to Laragon where `http://weather-app.test` points to the Laravel project).
---

## Installation & Setup

### 1. Backend (Laravel API)

1.  **Clone the repository:**
    ```bash
    git clone https://github.com/ejbolts/R6-CodeTest.git
    cd weatherbackend
    ```

2.  **Install PHP dependencies:**
    ```bash
    composer install
    ```

3.  **Create your environment file:**
    Copy the example environment file.
    ```bash
    cp .env.example .env
    ```

4.  **Configure your `.env` file:**
    Open the `.env` file and set the following variables.
    ```dotenv
    # Set your local development URL
    APP_URL=http://weather-app.test

    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_USERNAME=root
    DB_PASSWORD=

    # IMPORTANT: Add your WeatherBit API credentials
    WEATHERBIT_API_KEY=YOUR_API_KEY_HERE
    WEATHERBIT_API_URL=https://api.weatherbit.io/v2.0/
    ```

5.  **Generate an application key:**
    ```bash
    php artisan key:generate
    ```

6.  **Configure your local web server** (e.g., in Laragon) to ensure the document root for `weatherbackend.test` points to the `/public` directory of the project.

### 2. Frontend (React)

1.  **Navigate to the frontend directory:**
    ```bash
    cd ../weatherfrontend
    ```

2.  **Install Node.js dependencies:**
    ```bash
    npm install
    ```

---

## Running the Application (Local Development)

1.  **Start the Backend Server:**
    Your local development environment (Laragon in my case) will automatically serve the Laravel application at the URL you configured (e.g., `http://weatherbackend.test`).

2.  **Start the Frontend Server:**
    In a new terminal, navigate to the frontend directory and run the Vite development server.
    ```bash
    cd weatherfrontend
    npm run dev
    ```

3.  **View the Application:**
    Open your web browser and navigate to the URL provided by the Vite server (usually `http://localhost:5173`).

---

## Console Command

The application includes a console command to fetch and display the 5-day forecast in a table.

1.  Navigate to the Laravel project directory.
2.  Run the command with or without a city name:
    ```bash
    php artisan forecast "Brisbane"
    ```

### Automated Reporting

A scheduled task is defined in `app/Console/console.php` to run the `forecast` command daily at 7:00 AM and append the output to `storage/logs/daily-forecast.log`.
On a production server, this would be enabled by adding the following single Cron entry:

```cron
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```
