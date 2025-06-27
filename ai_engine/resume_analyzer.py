import re
import fitz  # PyMuPDF

KNOWN_SKILLS = [
    # Programming Languages
    "Python", "Java", "C", "C++", "C#", "JavaScript", "TypeScript", "Go", "Rust", "Ruby", "PHP", "Swift", "Kotlin",
    
    # Web Development
    "HTML", "CSS", "Bootstrap", "Tailwind", "React", "Vue", "Angular", "Next.js", "Node.js", "Express.js", "jQuery",
    
    # Mobile Development
    "Android", "Android Studio", "Kotlin", "Swift", "iOS", "React Native", "Flutter", "Dart", "Firebase", "REST APIs", "UI/UX Design",
    
    # Backend & APIs
    "Django", "Flask", "Spring", "Laravel", "FastAPI", "GraphQL", "MVC", "ASP.NET",
    
    # Databases
    "MySQL", "PostgreSQL", "SQLite", "MongoDB", "Oracle", "Redis", "Firebase Realtime DB", "Elasticsearch",

    # DevOps & Cloud
    "Docker", "Kubernetes", "AWS", "Azure", "Google Cloud", "CI/CD", "GitHub Actions", "Jenkins", "Terraform", "Ansible",

    # Data Science / Machine Learning
    "NumPy", "Pandas", "Matplotlib", "Seaborn", "Scikit-learn", "TensorFlow", "PyTorch", "OpenCV", "NLTK", "Spacy",
    "Jupyter", "Keras", "XGBoost", "Hugging Face", "Power BI", "Tableau",

    # Version Control / Tools
    "Git", "GitHub", "GitLab", "Bitbucket", "VS Code", "IntelliJ", "Eclipse", "Postman", "Notion", "Slack",

    # Software Engineering Practices
    "Agile", "Scrum", "TDD", "OOP", "Design Patterns", "UML", "Unit Testing", "System Design",

    # UI/UX & Design
    "Figma", "Adobe XD", "Sketch", "Canva", "UI Design", "UX Design", "Wireframing", "Prototyping",

    # Misc
    "Communication", "Teamwork", "Problem Solving", "Leadership", "Project Management"
]


def extract_resume_data(filepath):
    doc = fitz.open(filepath)
    text = ''
    for page in doc:
        text += page.get_text()

    text_lower = text.lower()
    found_skills = []

    for skill in KNOWN_SKILLS:
        # Match complete words (avoid partial match e.g., "Java" in "JavaScript")
        pattern = r'\b' + re.escape(skill.lower()) + r'\b'
        if re.search(pattern, text_lower):
            found_skills.append(skill)

    return {
        "skills": sorted(list(set(found_skills)))  # remove duplicates, sort for neatness
    }
