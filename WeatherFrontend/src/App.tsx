import { useState, useEffect } from "react";
import axios from "axios";
import type { DayForecast } from "./types";
import ForecastTable from "./components/ForecastTable";
import "./App.scss";

const CITIES = ["Brisbane", "Gold Coast", "Sunshine Coast"];

function App() {
  const [selectedCity, setSelectedCity] = useState<string>("");
  const [forecast, setForecast] = useState<DayForecast[] | null>(null);
  const [loading, setLoading] = useState<boolean>(false);
  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    if (!selectedCity) {
      setForecast(null);
      return;
    }

    const fetchForecast = async () => {
      setLoading(true);
      setError(null);
      try {
        const response = await axios.get<DayForecast[]>(
          `api/weather/${selectedCity}`
        );
        setForecast(response.data);
        setError(null);
      } catch (err) {
        const errorMessage =
          axios.isAxiosError(err) && err.response?.data?.error
            ? err.response.data.error
            : "An unexpected error occurred.";

        setError(errorMessage);
        setForecast(null);
      } finally {
        setLoading(false);
      }
    };

    fetchForecast();
  }, [selectedCity]);

  return (
    <div className="app-container">
      <div className="content-wrapper">
        <h1>5-Day Weather Forecast</h1>

        <div className="city-selector">
          <label htmlFor="city-select">Select a City:</label>
          <select
            id="city-select"
            value={selectedCity}
            onChange={(e) => setSelectedCity(e.target.value)}
          >
            <option value="">Please choose a city</option>
            {CITIES.map((city) => (
              <option key={city} value={city}>
                {city}
              </option>
            ))}
          </select>
        </div>

        <div className="forecast-display">
          {loading && <p>Loading forecast...</p>}

          {error && (
            <div className="error-message">
              <p>{error}</p>
            </div>
          )}

          {forecast && !loading && <ForecastTable data={forecast} />}
        </div>
      </div>
    </div>
  );
}

export default App;
