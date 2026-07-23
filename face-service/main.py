import io
import json
import logging
from typing import List

import cv2
import numpy as np
from fastapi import FastAPI, File, Form, HTTPException, UploadFile, Request
from fastapi.exceptions import RequestValidationError
from fastapi.responses import JSONResponse
from insightface.app import FaceAnalysis
from numpy.linalg import norm

# Setup Logging
logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

# Initialize FastAPI App
app = FastAPI(title="Face Recognition API", description="API for detecting and matching faces using InsightFace.")

@app.exception_handler(RequestValidationError)
async def validation_exception_handler(request: Request, exc: RequestValidationError):
    logger.error(f"Validation error: {exc.errors()}")
    logger.error(f"Body: {exc.body}")
    return JSONResponse(
        status_code=422,
        content={"detail": exc.errors(), "body": str(exc.body)},
    )

# Initialize InsightFace Model
logger.info("Loading InsightFace model...")
# Using buffalo_l or buffalo_s. If not downloaded, it will download automatically.
try:
    face_app = FaceAnalysis(name="buffalo_s", providers=['CPUExecutionProvider'])
    face_app.prepare(ctx_id=0, det_size=(640, 640))
    logger.info("Model buffalo_s loaded successfully.")
except Exception as e:
    logger.warning(f"Failed to load buffalo_s, trying buffalo_l: {e}")
    try:
        face_app = FaceAnalysis(name="buffalo_l", providers=['CPUExecutionProvider'])
        face_app.prepare(ctx_id=0, det_size=(640, 640))
        logger.info("Model buffalo_l loaded successfully.")
    except Exception as ex:
        logger.error(f"Failed to load face model: {ex}")
        face_app = None

def compute_similarity(embedding1, embedding2):
    """Compute cosine similarity between two embeddings."""
    return np.dot(embedding1, embedding2) / (norm(embedding1) * norm(embedding2))

@app.post("/extract-embedding")
async def extract_embedding(photo: UploadFile = File(...)):
    """
    Endpoint to extract face embedding from a single student reference photo.
    """
    if face_app is None:
        raise HTTPException(status_code=500, detail="Face model not initialized")

    try:
        photo_bytes = await photo.read()
        nparr = np.frombuffer(photo_bytes, np.uint8)
        img = cv2.imdecode(nparr, cv2.IMREAD_COLOR)

        if img is None:
            raise HTTPException(status_code=400, detail="Invalid photo format")

        faces = face_app.get(img)
        if not faces:
            return {"success": False, "message": "No face found in photo"}

        # Sort faces by area (width * height of bbox) descending to get largest face
        faces = sorted(faces, key=lambda f: (f.bbox[2] - f.bbox[0]) * (f.bbox[3] - f.bbox[1]), reverse=True)
        embedding = faces[0].embedding.tolist()

        return {
            "success": True,
            "embedding": embedding
        }
    except Exception as e:
        logger.error(f"Error extracting embedding: {e}")
        raise HTTPException(status_code=500, detail=str(e))

@app.post("/recognize")
async def recognize(
    group_photo: UploadFile = File(...),
    reference_data: UploadFile = File(...)  # Expecting JSON file with students data
):
    """
    Endpoint to recognize students in a group photo.
    `reference_data` format (as JSON file):
    [
        {"id": 1, "embedding": [0.01, -0.05, ...]},
        {"id": 2, "image_base64": "base64_string_here"} // Fallback
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
        threshold = 0.27  # Relaxed threshold to identify more students in crowded/lower resolution photos

        # Process reference students
        for student in students:
            student_id = student.get("id")
            if not student_id:
                continue

            ref_embedding = None

            # 1. Check if pre-computed embedding is provided
            embedding_list = student.get("embedding")
            if embedding_list and isinstance(embedding_list, list):
                ref_embedding = np.array(embedding_list, dtype=np.float32)

            # 2. Fallback to image_base64 if no embedding provided
            if ref_embedding is None:
                b64_data = student.get("image_base64")
                if b64_data:
                    try:
                        ref_bytes_img = base64.b64decode(b64_data)
                        nparr_ref = np.frombuffer(ref_bytes_img, np.uint8)
                        ref_img = cv2.imdecode(nparr_ref, cv2.IMREAD_COLOR)

                        if ref_img is not None:
                            ref_faces = face_app.get(ref_img)
                            if ref_faces:
                                ref_faces = sorted(ref_faces, key=lambda f: (f.bbox[2] - f.bbox[0]) * (f.bbox[3] - f.bbox[1]), reverse=True)
                                ref_embedding = ref_faces[0].embedding
                    except Exception as ex:
                        logger.error(f"Error processing reference image for student {student_id}: {ex}")

            if ref_embedding is None:
                continue

            # Compare with all faces in the group photo
            for face in group_faces:
                sim = compute_similarity(ref_embedding, face.embedding)
                if sim > threshold:
                    identified_ids.add(student_id)
                    break  # Found this student, move to next reference photo

        return {
            "success": True,
            "identified_ids": list(identified_ids)
        }

    except HTTPException:
        raise
    except Exception as e:
        logger.error(f"Error during recognition: {e}")
        raise HTTPException(status_code=500, detail=str(e))

