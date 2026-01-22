const quiz = [
  {
    question: "What does OSI stand for in the OSI Model?",
    options: [
      "Open Systems Interconnection",
      "Operating System Interface",
      "Optical Signal Integration",
      "Online Service Infrastructure"
    ],
    correct: 0
  },
  {
    question: "Which layer is responsible for data encryption?",
    options: [
      "Application",
      "Presentation",
      "Session",
      "Transport"
    ],
    correct: 1
  },
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
  },
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
];

let currentQuestion = 0;
let answers = new Array(quiz.length).fill(null);

// DOM
const questionEl = document.querySelector(".answer-quiz h3");
const optionsEl = document.querySelector(".options");
const progressText = document.querySelector(".quiz-title p");
const progressPercent = document.querySelector(".quiz-title span");
const progressBar = document.querySelector(".quiz-progress");
const answeredText = document.getElementById("answered-count");

function loadQuestion(index) {
  const q = quiz[index];

  questionEl.innerText = q.question;

  const percent = Math.round(((index + 1) / quiz.length) * 100);
  progressText.innerText = `Question ${index + 1} of ${quiz.length}`;
  progressPercent.innerText = `${percent}% Complete`;
  progressBar.style.width = percent + "%";

  optionsEl.innerHTML = "";
  q.options.forEach((opt, i) => {
    const checked = answers[index] === i ? "checked" : "";
    optionsEl.innerHTML += `
      <label class="option">
        <input type="radio" name="quiz" ${checked} onclick="saveAnswer(${i})">
        <span class="radio"></span>
        <span class="text">${opt}</span>
      </label>
    `;
  });

  document.getElementById("prevBtn").classList.toggle("disabled", index === 0);
  document.getElementById("nextBtn").querySelector("span").innerText =
    index === quiz.length - 1 ? "Finish Quiz" : "Next Question";
}

function updateAnsweredCount() {
  const answered = answers.filter(a => a !== null).length;
  answeredText.innerText = `${answered} / ${quiz.length} answered`;
}

function saveAnswer(optionIndex) {
  answers[currentQuestion] = optionIndex;
  updateAnsweredCount();
}

document.getElementById("prevBtn").addEventListener("click", e => {
  e.preventDefault();
  if (currentQuestion > 0) {
    currentQuestion--;
    loadQuestion(currentQuestion);
  }
});

document.getElementById("nextBtn").addEventListener("click", e => {
  e.preventDefault();
  if (currentQuestion < quiz.length - 1) {
    currentQuestion++;
    loadQuestion(currentQuestion);
  } else {
    submitQuiz();
  }
});

function submitQuiz() {
  let score = 0;
  quiz.forEach((q, i) => {
    if (answers[i] === q.correct) score++;
  });

  alert(`Quiz Finished!\nScore: ${score} / ${quiz.length}`);
}

// Initial load
loadQuestion(currentQuestion);
updateAnsweredCount();