import os
import pandas as pd
from sklearn.ensemble import RandomForestClassifier
from sklearn.model_selection import train_test_split
from sklearn.metrics import accuracy_score
import joblib

# Load the dataset
data = pd.read_csv("csv/data.csv")

# Handle missing values by filling NaN values
data = data.fillna({
    'Subject': '',          # Fill missing subjects with empty string
    'Room': '',             # Fill missing rooms with empty string
    'Section': '',          # Fill missing sections with empty string
    'Day': '',              # Fill missing days with empty string
    'Start Time': '00:00',  # Fill missing start times with a default time
    'End Time': '00:00'     # Fill missing end times with a default time
})

# Preprocess the data: Encode categorical features if necessary
X = pd.get_dummies(data[['Subject', 'Section', 'Day', 'Start Time', 'End Time']])
y = data['Room']  # Target variable is Room

# Split the dataset into training and testing sets
X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42)

# Train the Random Forest model
model = RandomForestClassifier(n_estimators=100, random_state=42)
model.fit(X_train, y_train)

# Evaluate the model's accuracy
y_pred = model.predict(X_test)
accuracy = accuracy_score(y_test, y_pred)
print(f"Model Accuracy: {accuracy * 100:.2f}%")

# Ensure the model directory exists
model_dir = 'model'
os.makedirs(model_dir, exist_ok=True)

# Save the model to the model folder
model_path = os.path.join(model_dir, 'random_forest_model.pkl')
joblib.dump(model, model_path)
print(f"Model saved as '{model_path}'")