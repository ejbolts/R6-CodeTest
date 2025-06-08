# 5-Day Weather Forecast Application



## Prerequisites
-   PHP >= 8.2
-   Composer
-   Node.js & npm
-   A local development environment like Laragon, Valet, or Herd. 
---


## Design Decisions & Assumptions

1. **Decoupled (Headless) Architecture vs. Inertia.js**:
    - **Decision**: I chose to build the Laravel backend as a pure, stateless API and the frontend as a completely separate React SPA. This is in contrast to using a tool like Inertia.js, which creates a more tightly-coupled "modern monolith".
    - **Reasoning**: While Inertia is a powerful tool, a decoupled architecture was chosen for its clear separation of concerns. This approach allows the frontend and backend to be developed, tested, and deployed independently, which is a common and 
  robust pattern for modern web applications. It provides maximum flexibility and avoids complex build-tool integrations between the Laravel backend and the Vite-powered frontend.

2.  **State Management in React**:
    -   **Decision**: All application state (selected city, forecast data, loading/error states) is managed locally within the main `App.tsx` component using React's built-in `useState` and `useEffect` hooks.
    -   **Reasoning**: For an application of this scale, local state is simpler, easier to reason about, and sufficient for the project's requirements.

3.  **Error Handling Strategy**:
    -   **Decision**: The Laravel API catches specific exceptions (e.g., from the WeatherBit API) and returns structured JSON error responses with appropriate HTTP status codes (e.g., 404, 500). The React frontend then parses these JSON responses to display user-friendly error messages.
    -   **Reasoning**: This provides a consistent and informative error-handling experience. The user is never shown a generic browser error or a broken page; they receive clear feedback about what went wrong.

4.  **Console Command Implementation**:
    -   **Decision**: The `forecast` command logic was defined directly in `routes/console.php` using a closure.
    -   **Reasoning**: For a command with straightforward logic, this approach is faster and requires less boilerplate than creating a dedicated command class file, keeping the implementation simple and self-contained.
  
5. Automated Reporting via Task Scheduler:
   - **Decision**: The requirement for a daily automated report was implemented using Laravel's built-in Task Scheduler to run the forecast Artisan command.
    - **Reasoning**: This is the idiomatic and most robust way to handle recurring background tasks in Laravel. It would leverages the server's Cron daemon for reliability and provides a fluent, expressive API within the `console.php` file for defining the schedule.

6.  **Assumptions**:
    -   It is assumed that the reviewer has a local PHP development environment (like Laragon, Herd, or Valet) capable of serving the Laravel application.
    -   The application does not require a database, as all data is fetched from a third-party API and is not persisted.
    -   Authentication is not required for the API endpoints as per the project scope.

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
    APP_URL=http://weatherBackend.test

    # IMPORTANT: Add your WeatherBit API credentials
    WEATHERBIT_API_KEY=YOUR_API_KEY_HERE
    WEATHERBIT_API_URL=https://api.weatherbit.io/v2.0/
    ```

5.  **Generate an application key:**
    ```bash
    php artisan key:generate
    ```

6.  **Configure your local web server** (e.g. in Laragon) to ensure the document root for `weatherbackend.test` points to the `/public` directory of the project.

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
    Open your web browser and navigate to the URL provided by the Vite server.

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


**Side note** i plan on containerising the Laravel backend and run on my current ec2 with subdomain url that i would add to readme soon, so that is why there is a docker file.
