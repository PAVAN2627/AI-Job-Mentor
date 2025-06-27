from fpdf import FPDF

def generate_result_pdf(resume_data, recommendations, output_path):
    pdf = FPDF()
    pdf.add_page()
    pdf.set_font("Arial", size=12)

    def clean(text):
        return str(text).replace("—", "-").encode("latin-1", "ignore").decode("latin-1")

    pdf.multi_cell(0, 10, clean("Career Advice:"))
    pdf.multi_cell(0, 8, clean(recommendations.get("career_advice", "")))
    pdf.ln(5)

    pdf.multi_cell(0, 10, clean("Recommended Jobs:"))
    for job in recommendations.get("recommended_jobs", []):
        pdf.multi_cell(0, 8, clean(f"• {job}"))
    pdf.ln(5)

    pdf.multi_cell(0, 10, clean("Courses:"))
    for course in recommendations.get("course_links", []):
        pdf.multi_cell(0, 8, clean(f"• {course['title']} ({course['type']}) - {course['url']}"))
    pdf.ln(5)

    # ✅ FIXED: use recommendations here instead of recs
    pdf.multi_cell(0, 10, clean("Skill-Gaps by Job:"))
    for job, gaps in recommendations.get("skill_gaps", {}).items():
        pdf.multi_cell(0, 8, clean(f"• {job}: {', '.join(gaps)}"))
    pdf.ln(5)

    pdf.multi_cell(0, 10, clean("8-Week Learning Plan:"))
    for wk in recommendations.get("weekly_plan", []):
        pdf.multi_cell(0, 8, clean(f"• {wk}"))
    pdf.ln(5)

    pdf.multi_cell(0, 10, clean("Job-Market Demand:"))
    for job, score in recommendations.get("job_scope", {}).items():
        pdf.multi_cell(0, 8, clean(f"• {job}: {score}/10"))
    pdf.ln(5)
    pdf.multi_cell(0, 10, clean("Average Salaries (INR/month):"))
    for job, salary in recommendations.get("average_salaries", {}).items():
        pdf.multi_cell(0, 8, clean(f"• {job}: ₹{salary:,}"))
    pdf.output(output_path)
