from ortools.sat.python import cp_model
import sys
import json

def main():
    # Load the predicted schedule from PHP or ML script
    try:
        predicted_schedule = json.loads(sys.argv[1])
    except (json.JSONDecodeError, IndexError):
        print(json.dumps({"error": "Invalid JSON input"}))
        return

    # Validate input data
    if not isinstance(predicted_schedule, list) or not predicted_schedule:
        print(json.dumps({"error": "Predicted schedule must be a non-empty list"}))
        return

    required_keys = {"subject", "room", "section", "day", "start_time", "end_time"}
    for entry in predicted_schedule:
        if not isinstance(entry, dict) or not required_keys.issubset(entry.keys()):
            print(json.dumps({"error": "Each entry must be a dictionary with required keys"}))
            return

    # Define the CP model
    model_cp = cp_model.CpModel()

    # Define rooms, times, sections, and days based on the dataset
    rooms = list(set(entry['room'] for entry in predicted_schedule))
    timeslots = [(entry['start_time'], entry['end_time']) for entry in predicted_schedule]
    sections = [entry['section'] for entry in predicted_schedule]
    days = list(set(entry['day'] for entry in predicted_schedule))

    # Create assignments dictionary
    assignments = {}
    for room in rooms:
        for day in days:
            for start_time, end_time in timeslots:
                for section in sections:
                    key = (room, day, start_time, section)
                    assignments[key] = model_cp.NewBoolVar(f'{room}_{day}_{start_time}_{section}')

    # Add constraints to avoid overlapping rooms and times on the same day
    for room in rooms:
        for day in days:
            for start_time, end_time in timeslots:
                model_cp.AddAtMostOne(assignments[(room, day, start_time, section)] for section in sections)

    # Solve the model
    solver = cp_model.CpSolver()
    status = solver.Solve(model_cp)

    # Prepare output if an optimal solution is found
    optimized_schedule = []
    if status == cp_model.OPTIMAL:
        for entry in predicted_schedule:
            for room in rooms:
                for day in days:
                    for start_time, end_time in timeslots:
                        key = (room, day, start_time, entry['section'])
                        if solver.Value(assignments[key]) == 1:
                            entry['room'] = room
                            entry['day'] = day
                            entry['start_time'] = start_time
                            entry['end_time'] = end_time
                            optimized_schedule.append(entry)

    # Output the optimized schedule with consistent JSON structure
    print(json.dumps(optimized_schedule))

if __name__ == "__main__":
    main()