from azure_client import client, DEPLOYMENT
import json, re

SYSTEM_PROMPT = """
You are an expert career mentor and job-market analyst.

Given a user's skills, respond ONLY with a JSON object containing:

1. "recommended_jobs": array of job-role strings (4-5 items, bullet-style).
2. "skill_gaps":   for each job, list the 2-4 most important missing skills. 
   Example: { "Python Developer": ["Django", "REST"], ... }
3. "weekly_plan": ordered list of ≤8 strings (Week 1 … Week 8) describing what to learn or build each week.
4. "career_advice": 3-4 sentence paragraph.
5. "course_links": 8-10 objects with include which are free and which are paid {title,url,type}.
6. "job_scope": map job → market-demand score 1-10 (higher = hotter role).
7.  "average_salaries": average salary of that role score in LPA (higher = hotter role).
Return pure JSON—no markdown, no extra text.
"""

def recommend_jobs(resume_data):
    skills = ", ".join(resume_data.get("skills", [])) or "None"

    try:
        resp = client.chat.completions.create(
            model=DEPLOYMENT,
            messages=[
                {"role": "system", "content": SYSTEM_PROMPT},
                {"role": "user", "content": f"My skills: {skills}"}
            ],
            temperature=0.7,
            max_tokens=900
        )
        raw = resp.choices[0].message.content
        json_str = re.search(r"\{.*\}", raw, re.S).group()
        return json.loads(json_str)

    except Exception as e:
        print("Azure GPT error:", e)
        # minimal fallback
        return {
            "recommended_jobs": [],
            "skill_gaps":      {},
            "weekly_plan":     [],
            "career_advice":   "Azure AI unavailable—try later.",
            "course_links":    [],
            "job_scope":       {},
            "average_salaries": {}
        }
