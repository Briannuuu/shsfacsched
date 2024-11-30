from sklearn.compose import ColumnTransformer
from sklearn.pipeline import Pipeline
from sklearn.preprocessing import OneHotEncoder, StandardScaler
from sklearn.ensemble import RandomForestClassifier
from sklearn.model_selection import train_test_split
import pandas as pd
import joblib

# Load dataset
data = pd.read_csv("csv/dataset-final.csv")  # Replace with your dataset file

# Ensure consistent feature names
data.columns = data.columns.str.strip().str.lower().str.replace(" ", "_")

# Define features and target
X = data.drop(columns=["room"])  # Replace 'room' with your target column
y = data["room"]

# Split dataset into training and testing sets
X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42)

# Define preprocessing for numerical and categorical features
preprocessor = ColumnTransformer(
    transformers=[
        ("day_encoder", OneHotEncoder(), ["day"]),  # One-hot encode 'day'
        ("section_encoder", OneHotEncoder(), ["section"]),  # One-hot encode 'section'
        ("subject_encoder", OneHotEncoder(), ["subject"]),  # One-hot encode 'subject'
        ("scaler", StandardScaler(), ["start_time", "end_time"])  # Scale numerical features
    ]
)

# Create pipeline with preprocessing and RandomForestClassifier
pipeline = Pipeline(steps=[
    ("preprocessor", preprocessor),
    ("classifier", RandomForestClassifier(n_estimators=100, random_state=42))
])

# Train the pipeline
pipeline.fit(X_train, y_train)

# Save the pipeline for future use
joblib.dump(pipeline, "full_pipeline.pkl")
print("Pipeline saved as 'full_pipeline.pkl'")
