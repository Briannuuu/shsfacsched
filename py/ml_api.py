import sys
import json
import joblib
import pandas as pd

def main():
    try:
        # Load the trained model
        model = joblib.load("model/random_forest_model.pkl")
    except Exception as e:
        print(json.dumps({"error": "Failed to load model", "message": str(e)}))
        return

    # Get input data from the command line (sent by PHP)
    try:
        input_data = json.loads(sys.argv[1])
    except json.JSONDecodeError:
        print(json.dumps({"error": "Invalid JSON input"}))
        return

    # Validate input data
    required_keys = ["subject", "section", "day", "start_time", "end_time"]
    if not all(key in input_data for key in required_keys):
        print(json.dumps({"error": "Missing required input keys"}))
        return

    # Create DataFrame from input data
    input_df = pd.DataFrame([{
        "subject": input_data["subject"],
        "section": input_data["section"],
        "day": input_data["day"],
        "start_time": input_data["start_time"],
        "end_time": input_data["end_time"]
    }])

    # Preprocess the input data to match the model's format
    input_df_encoded = pd.get_dummies(input_df)
    input_df_encoded = input_df_encoded.reindex(columns=model.feature_names_in_, fill_value=0)

    # Make a prediction
    try:
        predicted_room = model.predict(input_df_encoded)[0]
    except Exception as e:
        print(json.dumps({"error": "Prediction failed", "message": str(e)}))
        return

    # Add the predicted room to the input data
    input_data['room'] = predicted_room

    # Output the prediction as JSON
    output_data = {
        "subject": input_data["subject"],
        "room": input_data["room"],
        "section": input_data["section"],
        "day": input_data["day"],
        "start_time": input_data["start_time"],
        "end_time": input_data["end_time"]
    }
    print(json.dumps(output_data))

if __name__ == "__main__":
    main()