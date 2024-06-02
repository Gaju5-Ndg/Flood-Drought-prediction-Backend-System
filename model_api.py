from flask import Flask, request, jsonify
import joblib

app = Flask(__name__)

# Load the linear regression model
model = joblib.load('public/linear_regression_model.pkl')

@app.route('/predict', methods=['POST'])
def predict():
    data = request.json  # Assuming you're sending JSON data
    prediction = model.predict(data)
    return jsonify({'prediction': prediction.tolist()})

if __name__ == '__main__':
    app.run(debug=True)
