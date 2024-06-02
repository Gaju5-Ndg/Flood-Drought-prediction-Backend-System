from fastapi import FastAPI
from pydantic import BaseModel
import pickle

# Load your trained model
with open("best_model.pkl", "rb") as f:
    model = pickle.load(f)

app = FastAPI()

class SensorData(BaseModel):
    waterlevel: float
    humidity: float
    soilmoisture: float
    temperature: float
    

@app.post("/predict/")
async def predict(data: SensorData):
    input_data = [[data.waterlevel, data.humidity, data.soilmoisture, data.temperature]]
    prediction = model.predict(input_data)
    return {"prediction": prediction[0]}
