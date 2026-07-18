import requests
import json
import base64
import os

url = "http://127.0.0.1:8002/recognize"

reference_data = [{"id": 1, "image_base64": "dummy"}]

files = {
    'group_photo': ('test.txt', b"This is not an image", 'text/plain')
}

data = {
    'reference_data': json.dumps(reference_data)
}

response = requests.post(url, files=files, data=data)
print("Status Code:", response.status_code)
print("Response:", response.text)
