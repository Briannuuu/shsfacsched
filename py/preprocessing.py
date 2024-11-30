import pandas as pd
from sklearn.compose import ColumnTransformer
from sklearn.pipeline import Pipeline
from sklearn.preprocessing import OneHotEncoder, StandardScaler
from sklearn.ensemble import RandomForestClassifier
import joblib

# Load the CSV file
data = pd.read_csv("csv/data.csv")

# Print original column names for debugging
print("Original Columns in the CSV:", data.columns.tolist())

# Clean and standardize column names
data.columns = data.columns.str.strip().str.lower().str.replace(" ", "_")
print("Standardized Columns in the CSV:", data.columns.tolist())

# Replace semicolon with colon in time columns
data["start_time"] = data["start_time"].astype(str).str.replace(";", ":", regex=False)
data["end_time"] = data["end_time"].astype(str).str.replace(";", ":", regex=False)

# Replace NaN values in time columns with a default value (e.g., "0:00")
data["start_time"] = data["start_time"].fillna("0:00")
data["end_time"] = data["end_time"].fillna("0:00")

# Print rows with NaN or invalid times for debugging
print("Rows with potential issues in time columns:")
print(data[data["start_time"].str.contains("nan|None", na=True, case=False, regex=True)])

# Define a function to clean and validate time values
def clean_time_format(time_str):
    try:
        # Skip invalid rows explicitly
        if pd.isna(time_str) or not isinstance(time_str, str) or time_str.strip().lower() in ["nan", "none", ""]:
            return None
        # Handle ranges like "00 - 6"
        if "-" in time_str:
            time_str = time_str.split("-")[1].strip()
        hours, minutes = map(int, time_str.split(":"))
        return hours + minutes / 60.0
    except Exception as e:
        print(f"Invalid time format: {time_str}. Error: {e}")
        return None  # Return None for invalid formats

# Apply time cleaning and validation
data["start_time"] = data["start_time"].apply(clean_time_format)
data["end_time"] = data["end_time"].apply(clean_time_format)

# Drop rows with invalid or missing time values
data = data.dropna(subset=["start_time", "end_time"])

# Print cleaned data for verification
print("Cleaned Data:")
print(data.head())

# Define Features and Target
target_column = "room"
X = data.drop(target_column, axis=1)  # Drop the target variable
y = data[target_column]  # Target variable

# Preprocessing Steps
preprocessor = ColumnTransformer(
    transformers=[
        ("num", StandardScaler(), ["start_time", "end_time"]),  # Match standardized names
        ("cat", OneHotEncoder(handle_unknown='ignore'), ["section", "day"])  # Updated encoder
    ]
)

# Full Pipeline (Preprocessing + Model)
pipeline = Pipeline(steps=[
    ("preprocessor", preprocessor),
    ("model", RandomForestClassifier(n_estimators=100, random_state=42))
])

# Train the Pipeline
pipeline.fit(X, y)

# Save the Pipeline
joblib.dump(pipeline, "model/preprocessing_pipeline.pkl")
print("Preprocessing pipeline saved!")