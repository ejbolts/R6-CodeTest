import React from "react";
import type { DayForecast } from "../types";

interface ForecastTableProps {
  data: DayForecast[];
}

const ForecastTable: React.FC<ForecastTableProps> = ({ data }) => {
  if (!Array.isArray(data) || data.length === 0) {
    return (
      <div className="forecast-table-container">
        <p>No forecast data available</p>
      </div>
    );
  }

  return (
    <div className="forecast-table-container">
      <table>
        <thead>
          <tr>
            <th>Day</th>
            <th>Temperature</th>
          </tr>
        </thead>
        <tbody>
          {data.map((day, index) => (
            <tr key={day.date}>
              <td>Day {index + 1}</td>
              <td>
                Avg: {day.avg_temp}°C, Max: {day.max_temp}°C, Low:{" "}
                {day.min_temp}
                °C
              </td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
};

export default ForecastTable;
