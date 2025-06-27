from flask import Flask, request, jsonify
from resume_analyzer import extract_resume_data
from job_recommender import recommend_jobs
from pdf_generator import generate_result_pdf
import os

app = Flask(__name__)

@app.route('/analyze', methods=['POST'])
def analyze_resume():
    uploaded_file = request.files['resume']
    user_id = request.form.get('user_id')

    save_path = f'../uploads/resumes/user_{user_id}/'
    os.makedirs(save_path, exist_ok=True)

    resume_path = os.path.join(save_path, uploaded_file.filename)
    uploaded_file.save(resume_path)

    resume_data = extract_resume_data(resume_path)
    recommendations = recommend_jobs(resume_data)

    absolute_pdf_path = os.path.join(save_path, 'ai_report.pdf')
    generate_result_pdf(resume_data, recommendations, absolute_pdf_path)

    relative_web_path = f'/uploads/resumes/user_{user_id}/ai_report.pdf'

    return jsonify({
        "resume_data": resume_data,
        "recommendations": recommendations,
        "pdf_path": relative_web_path
    })

if __name__ == '__main__':
    app.run(debug=True)
