import requests
import json
import base64
import os

url = "http://127.0.0.1:8002/recognize"

# Create a dummy image
img_data = b"R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==" # 1x1 transparent gif
dummy_base64 = base64.b64encode(img_data).decode('utf-8')

reference_data = [
    {
        "id": 1,
        "image_base64": dummy_base64
    }
]

files = {
    'group_photo': ('test.gif', base64.b64decode(img_data), 'image/gif')
}

data = {
    'reference_data': json.dumps(reference_data)
}

response = requests.post(url, files=files, data=data)
print("Status Code:", response.status_code)
print("Response:", response.text)
