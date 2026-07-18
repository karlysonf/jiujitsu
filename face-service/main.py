import io
import json
import logging
from typing import List

import cv2
import numpy as np
from fastapi import FastAPI, File, Form, HTTPException, UploadFile
from insightface.app import FaceAnalysis
from numpy.linalg import norm

# Setup Logging
logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

# Initialize FastAPI App
app = FastAPI(title="Face Recognition API", description="API for detecting and matching faces using InsightFace.")

# Initialize InsightFace Model
logger.info("Loading InsightFace model...")
# Using buffalo_l or buffalo_s. If not downloaded, it will download automatically.
try:
    face_app = FaceAnalysis(name="buffalo_l", providers=['CPUExecutionProvider'])
    face_app.prepare(ctx_id=0, det_size=(640, 640))
    logger.info("Model loaded successfully.")
except Exception as e:
    logger.error(f"Failed to load model: {e}")
    face_app = None

def compute_similarity(embedding1, embedding2):
    """Compute cosine similarity between two embeddings."""
    return np.dot(embedding1, embedding2) / (norm(embedding1) * norm(embedding2))

@app.post("/recognize")
async def recognize(
    group_photo: UploadFile = File(...),
    reference_data: UploadFile = File(...)  # Expecting JSON file with students data
):
    """
    Endpoint to recognize students in a group photo.
    `reference_data` format (as JSON file):
    [
        {"id": 1, "image_base64": "base64_string_here"},
        ...
    ]
    """
    if face_app is None:
        raise HTTPException(status_code=500, detail="Face model not initialized")

    try:
        # Parse group photo
        group_bytes = await group_photo.read()
        nparr = np.frombuffer(group_bytes, np.uint8)
        group_img = cv2.imdecode(nparr, cv2.IMREAD_COLOR)

        if group_img is None:
            raise HTTPException(status_code=400, detail="Invalid group photo format")

        # Detect faces in group photo
        logger.info("Detecting faces in group photo...")
        group_faces = face_app.get(group_img)
        logger.info(f"Found {len(group_faces)} faces in group photo.")

        if not group_faces:
            return {"success": True, "identified_ids": []}

        # Parse reference data
        import base64
        try:
            ref_bytes = await reference_data.read()
            students = json.loads(ref_bytes.decode('utf-8'))
        except (json.JSONDecodeError, UnicodeDecodeError):
            raise HTTPException(status_code=400, detail="Invalid reference_data JSON")

        identified_ids = set()
        threshold = 0.45  # Standard threshold for cosine similarity with InsightFace

        # Detect reference faces
        for student in students:
            student_id = student.get("id")
            b64_data = student.get("image_base64")

            if not student_id or not b64_data:
                continue

            try:
                # Decode base64 reference image
                ref_bytes_img = base64.b64decode(b64_data)
                nparr_ref = np.frombuffer(ref_bytes_img, np.uint8)
                ref_img = cv2.imdecode(nparr_ref, cv2.IMREAD_COLOR)

                if ref_img is None:
                    continue

                # Get embedding for reference photo
                ref_faces = face_app.get(ref_img)
                if not ref_faces:
                    logger.warning(f"No face found in reference photo for student {student_id}")
                    continue
                
                # Assume the largest/most prominent face is the student
                ref_embedding = ref_faces[0].embedding

                # Compare with all faces in the group photo
                for face in group_faces:
                    sim = compute_similarity(ref_embedding, face.embedding)
                    if sim > threshold:
                        identified_ids.add(student_id)
                        break  # Found this student, move to next reference photo

            except Exception as ex:
                logger.error(f"Error processing reference for student {student_id}: {ex}")
                continue

        return {
            "success": True,
            "identified_ids": list(identified_ids)
        }

    except HTTPException:
        raise
    except Exception as e:
        logger.error(f"Error during recognition: {e}")
        raise HTTPException(status_code=500, detail=str(e))
