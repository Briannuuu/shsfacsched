import mysql.connector
from mysql.connector import Error
import joblib  # For loading the ML model
from ortools.sat.python import cp_model  # For constraint programming
import pandas as pd

def create_connection():
    """Create a connection to the MySQL database."""
    try:
        connection = mysql.connector.connect(
            host='localhost',
            user='root',
            password='',
            database='shsfacdb'
        )
        if connection.is_connected():
            print("Connected to the Database!")
        return connection
    except Error as e:
        print(f"Error: {e}")
        return None

def validate_room(connection, room_id):
    """Check if the room_id exists in the room table."""
    try:
        cursor = connection.cursor()
        query = "SELECT COUNT(*) FROM room WHERE room_id = %s"
        cursor.execute(query, (room_id,))
        result = cursor.fetchone()
        return result[0] > 0  # Return True if the room exists
    except Error as e:
        print(f"Error validating room: {e}")
        return False
    finally:
        cursor.close()

def map_room_name_to_id(predicted_room):
    """Map the predicted room name to the room_id in the database."""
    room_mapping = {
        "401": 1,  # Example mapping
        "402": 2,
        "403": 3
    }
    return room_mapping.get(predicted_room, None)

def insert_schedule(connection, schedule_row):
    """Insert a schedule row into the database."""
    try:
        cursor = connection.cursor()

        query = """
        INSERT INTO schedules (fac_id, subj_id, room_id, sec_id, day, start_time, end_time)
        VALUES (%s, %s, %s, %s, %s, %s, %s)
        """
        values = (
            schedule_row["fac_id"],
            schedule_row["subj_id"],
            schedule_row["room_id"],
            schedule_row["sec_id"],
            schedule_row["day"],
            schedule_row["start_time"],
            schedule_row["end_time"]
        )
        cursor.execute(query, values)
        connection.commit()
        print("Schedule inserted successfully!")
    except Error as e:
        print(f"Error inserting schedule: {e}")
    finally:
        cursor.close()

def load_pipeline(pipeline_path):
    """Load the saved preprocessing pipeline."""
    return joblib.load(pipeline_path)

def predict_room(pipeline, input_features):
    """Predict the room assignment using the ML pipeline."""
    try:
        df = pd.DataFrame([input_features])
        prediction = pipeline.predict(df)
        return prediction[0]
    except Exception as e:
        print(f"Error during prediction: {e}")
        return None

def optimize_schedule():
    """Optimize schedule timing using constraint programming."""
    model = cp_model.CpModel()

    # Define variables
    start_time = model.NewIntVar(6, 18, 'start_time')  # Start time in hours (e.g., 6 AM to 6 PM)
    duration = model.NewIntVar(1, 2, 'duration')  # Duration in hours (e.g., 1 or 2 hours)
    end_time = model.NewIntVar(7, 19, 'end_time')  # End time must be after start time

    # Constraints
    model.Add(end_time == start_time + duration)

    # Solve the model
    solver = cp_model.CpSolver()
    status = solver.Solve(model)

    if status == cp_model.OPTIMAL:
        return {
            "start_time": f"{solver.Value(start_time)}:00:00",
            "end_time": f"{solver.Value(end_time)}:00:00"
        }
    else:
        print("No feasible schedule found.")
        return None

def main():
    # Load the pipeline
    pipeline = load_pipeline("model/preprocessing_pipeline.pkl")

    # Example input data for room prediction
    input_features = {
        "subject": "TLE",
        "section": "Argon",
        "day": "Monday",
        "start_time": 6.0,
        "end_time": 6.666667
    }

    # Predict the room
    predicted_room = predict_room(pipeline, input_features)
    if predicted_room:
        print(f"Predicted Room: {predicted_room}")

        # Map predicted room to room_id
        connection = create_connection()
        if connection:
            room_id = map_room_name_to_id(predicted_room)
            if room_id and validate_room(connection, room_id):
                print(f"Validated Room ID: {room_id}")

                # Optimize the schedule
                optimized_schedule = optimize_schedule()
                if optimized_schedule:
                    print("Optimized Schedule:", optimized_schedule)

                    # Insert the schedule into the database
                    schedule_row = {
                        "fac_id": 1,  # Example faculty ID
                        "subj_id": 1,  # Example subject ID
                        "room_id": room_id,  # Validated room ID
                        "sec_id": 1,  # Example section ID
                        "day": input_features["day"],
                        "start_time": optimized_schedule["start_time"],
                        "end_time": optimized_schedule["end_time"]
                    }
                    insert_schedule(connection, schedule_row)
                else:
                    print("Failed to optimize schedule.")
            else:
                print("Invalid room or room not found in the database.")
            connection.close()
        else:
            print("Failed to connect to the database.")
    else:
        print("Room prediction failed.")

if __name__ == "__main__":
    main()