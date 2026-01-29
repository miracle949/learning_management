/* ==========================
   SUBJECT + MODULE DETECTION
========================== */
const quizParams = new URLSearchParams(window.location.search);
const quizSubject = quizParams.get("subject") || "default";
const quizModule = quizParams.get("module");

/* ==========================
   QUIZZES PER MODULE
========================== */
const quizzes = {
  module1: [
    {
      question: "What does OSI stand for in the OSI Entrepreneurship?",
      options: [
        "Open Systems Interconnection",
        "Operating System Interface",
        "Optical Signal Integration",
        "Online Service Infrastructure"
      ],
      correct: 0
    },
    {
      question: "Which layer is responsible for data Entrepreneurship?",
      options: [
        "Application",
        "Presentation",
        "Session",
        "Transport"
      ],
      correct: 1
    }
  ],

  module2: [
    {
      question: "Which device connects different networks?",
      options: [
        "Hub",
        "Switch",
        "Router",
        "Repeater"
      ],
      correct: 2
    },
    {
      question: "What protocol is used for web browsing?",
      options: [
        "FTP",
        "SMTP",
        "HTTP",
        "SNMP"
      ],
      correct: 2
    }
  ],

  module3: [
    {
      question: "Which topology uses a central device?",
      options: [
        "Ring",
        "Bus",
        "Star",
        "Mesh"
      ],
      correct: 2
    }
  ]
};

/* ==========================
   ACTIVE QUIZ (SAFE)
========================== */
const activeQuiz = quizzes[quizModule] || [];
let quizCurrentQuestion = 0;

/* ==========================
   RESTORE SAVED ANSWERS
========================== */
let quizAnswers = JSON.parse(
  localStorage.getItem(`${quizSubject}_${quizModule}_quizAnswers`)
) || new Array(activeQuiz.length).fill(null);

/* ==========================
   DOM ELEMENTS
========================== */
const questionEl = document.querySelector(".answer-quiz h3");
const optionsEl = document.querySelector(".options");
const progressText = document.querySelector(".quiz-title p");
const progressPercent = document.querySelector(".quiz-title span");
const progressBar = document.querySelector(".quiz-progress");
const answeredText = document.getElementById("answered-count");

/* ==========================
   LOAD QUESTION
========================== */
function loadQuestion(index) {
  const q = activeQuiz[index];
  if (!q) return;

  questionEl.innerText = q.question;

  const percent = Math.round(((index + 1) / activeQuiz.length) * 100);
  progressText.innerText = `Question ${index + 1} of ${activeQuiz.length}`;
  progressPercent.innerText = `${percent}% Complete`;
  progressBar.style.width = percent + "%";

  optionsEl.innerHTML = "";
  q.options.forEach((opt, i) => {
    const checked = quizAnswers[index] === i ? "checked" : "";
    optionsEl.innerHTML += `
      <label class="option">
        <input type="radio" name="quiz" ${checked} onclick="saveAnswer(${i})">
        <span class="radio"></span>
        <span class="text">${opt}</span>
      </label>
    `;
  });

  const prevBtn = document.getElementById("prevBtn");
  prevBtn.classList.toggle("disabled", index === 0);
  prevBtn.style.pointerEvents = index === 0 ? "none" : "auto";

  document.getElementById("nextBtn").querySelector("span").innerText =
    index === activeQuiz.length - 1 ? "Finish Quiz" : "Next Question";
}

/* ==========================
   SAVE ANSWER
========================== */
function saveAnswer(optionIndex) {
  quizAnswers[quizCurrentQuestion] = optionIndex;

  localStorage.setItem(
    `${quizSubject}_${quizModule}_quizAnswers`,
    JSON.stringify(quizAnswers)
  );

  updateAnsweredCount();
}

/* ==========================
   ANSWER COUNT
========================== */
function updateAnsweredCount() {
  const answered = quizAnswers.filter(a => a !== null).length;
  answeredText.innerText = `${answered} / ${activeQuiz.length} answered`;
}

/* ==========================
   NAVIGATION
========================== */
document.getElementById("prevBtn").addEventListener("click", e => {
  e.preventDefault();
  if (quizCurrentQuestion > 0) {
    quizCurrentQuestion--;
    loadQuestion(quizCurrentQuestion);
  }
});

document.getElementById("nextBtn").addEventListener("click", e => {
  e.preventDefault();
  if (quizCurrentQuestion < activeQuiz.length - 1) {
    quizCurrentQuestion++;
    loadQuestion(quizCurrentQuestion);
  } else {
    submitQuiz();
  }
});

/* ==========================
   SUBMIT QUIZ
========================== */
function submitQuiz() {
  let score = 0;
  activeQuiz.forEach((q, i) => {
    if (quizAnswers[i] === q.correct) score++;
  });

  localStorage.setItem(
    `${quizSubject}_${quizModule}_quizScore`,
    score
  );

  alert(`Quiz Finished!\nScore: ${score} / ${activeQuiz.length}`);
}

/* ==========================
   INITIAL LOAD
========================== */
if (activeQuiz.length > 0) {
  loadQuestion(quizCurrentQuestion);
  updateAnsweredCount();
} else {
  questionEl.innerText = "No quiz available for this module.";
}
