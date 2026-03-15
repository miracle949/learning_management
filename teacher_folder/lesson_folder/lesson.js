document.addEventListener("DOMContentLoaded", function () {

    const contentContainer = document.getElementById("contentContainer");
    const addModuleBtn = document.getElementById("addModuleBtn");

    // ── helpers ───────────────────────────────────────────────────────────────

    function createRemoveButton() {
        return `<button type="button" class="remove-item btn btn-sm">
                    <i class="fa fa-times"></i>
                </button>`;
    }

    function reNumberModules() {
        document.querySelectorAll(".module-item").forEach((mod, i) => {
            mod.querySelector(".module-label").textContent = "Module " + (i + 1);
        });
    }

    function reNumberInModule(moduleEl, typeClass, labelClass, labelText) {
        moduleEl.querySelectorAll("." + typeClass).forEach((item, i) => {
            item.querySelector("." + labelClass).textContent = labelText + " " + (i + 1);
        });
    }

    function checkEmpty() {
        const noModules = document.querySelectorAll(".module-item").length === 0;
        document.querySelector(".text-content").style.display = noModules ? "flex" : "none";
    }

    // ── LESSON builder ────────────────────────────────────────────────────────

    function buildLesson() {
        const el = document.createElement("div");
        el.className = "card-body lesson-item";
        el.innerHTML = `
            <div class="card-body-header d-flex justify-content-between align-items-center">
                <div class="card-nav">
                    <div class="card-icon"><i class="fa fa-file"></i></div>
                    <p class="lesson-label">Lesson 1</p>
                </div>
                ${createRemoveButton()}
            </div>
            <hr>
            <div class="row">
                <div class="col-lg-12 mt-4">
                    <label>Title *</label>
                    <input type="text" name="lesson_title[]" class="form-control mt-2" placeholder="Enter lesson title">
                </div>
                <div class="col-lg-12 mt-4">
                    <label>Content *</label>
                    <textarea name="lesson_content[]" class="form-control mt-2" rows="7" placeholder="Enter lesson content"></textarea>
                </div>
            </div>`;
        return el;
    }

    // ── VIDEO builder ─────────────────────────────────────────────────────────

    function buildVideo() {
        const el = document.createElement("div");
        el.className = "card-body video-item";
        el.innerHTML = `
            <div class="card-body-header d-flex justify-content-between align-items-center">
                <div class="card-nav">
                    <div class="card-icon"><i class="fa fa-video"></i></div>
                    <p class="video-label">Video 1</p>
                </div>
                ${createRemoveButton()}
            </div>
            <hr>
            <div class="row">
                <div class="col-lg-12 mt-4">
                    <label>Title *</label>
                    <input type="text" name="video_title[]" class="form-control mt-2" placeholder="Enter video title">
                </div>
                <div class="col-lg-12 mt-4">
                    <label>Video URL *</label>
                    <input type="url" name="video_url[]" class="form-control mt-2" placeholder="https://youtube.com/...">
                </div>
                <div class="col-lg-12 mt-4">
                    <label>Description</label>
                    <textarea name="video_description[]" class="form-control mt-2" rows="7" placeholder="Video description"></textarea>
                </div>
            </div>`;
        return el;
    }

    // ── IMAGE builder ─────────────────────────────────────────────────────────

    function buildImage() {
        const el = document.createElement("div");
        el.className = "card-body image-item";
        el.innerHTML = `
            <div class="card-body-header d-flex justify-content-between align-items-center">
                <div class="card-nav">
                    <div class="card-icon"><i class="fa fa-image"></i></div>
                    <p class="image-label">Image 1</p>
                </div>
                ${createRemoveButton()}
            </div>
            <hr>
            <div class="row">
                <div class="col-lg-12 mt-4">
                    <label>Title *</label>
                    <input type="text" name="image_title[]" class="form-control mt-2" placeholder="Enter image title">
                </div>
                <div class="col-lg-12 mt-4">
                    <label>Image URL *</label>
                    <input type="url" name="image_url[]" class="form-control mt-2" placeholder="https://example.com/image.jpg">
                </div>
                <div class="col-lg-12 mt-4">
                    <label>Description</label>
                    <textarea name="image_description[]" class="form-control mt-2" rows="7" placeholder="Image description"></textarea>
                </div>
            </div>`;
        return el;
    }

    // ── ACTIVITY builder ──────────────────────────────────────────────────────
    // Activity has: Title, Instructions, Points, and multiple questions
    // Each question can be: Essay (open-ended) or Multiple Choice

    function buildActivity() {
        const el = document.createElement("div");
        el.className = "card-body activity-item";
        el.innerHTML = `
            <div class="card-body-header d-flex justify-content-between align-items-center">
                <div class="card-nav">
                    <div class="card-icon"><i class="fa fa-circle-question"></i></div>
                    <p class="activity-label">Activity 1</p>
                </div>
                ${createRemoveButton()}
            </div>
            <hr>

            <!-- Activity details -->
            <div class="row">
                <div class="col-lg-12 mt-4">
                    <label>Activity Title *</label>
                    <input type="text" name="activity_title[]" class="form-control mt-2" placeholder="Enter activity title">
                </div>
                <div class="col-lg-12 mt-4">
                    <label>Instructions</label>
                    <textarea name="activity_instructions[]" class="form-control mt-2" rows="3" placeholder="Enter instructions for students (e.g. Answer the following questions)"></textarea>
                </div>
                <div class="col-lg-6 mt-4">
                    <label>Total Points</label>
                    <input type="number" name="activity_points[]" class="form-control mt-2" placeholder="e.g. 10" min="1">
                </div>
                <div class="col-lg-6 mt-4">
                    <label>Time Limit (minutes)</label>
                    <input type="number" name="activity_time[]" class="form-control mt-2" placeholder="e.g. 20" min="1">
                </div>
            </div>

            <!-- Activity questions -->
            <div class="activity-questions-container mt-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <label class="mb-0" style="font-weight:600;">Questions</label>
                    <button type="button" class="btn-add-activity-question">
                        <i class="fa fa-plus"></i> Add Question
                    </button>
                </div>
                <div class="activity-questions-list d-flex flex-column gap-3">
                    <div class="activity-questions-empty text-content" style="margin-top:0;">
                        <i class="fa fa-circle-question"></i>
                        <p>No questions yet — click "Add Question" to start.</p>
                    </div>
                </div>
            </div>`;

        const questionsList = el.querySelector(".activity-questions-list");
        const questionsEmpty = el.querySelector(".activity-questions-empty");
        const addQBtn = el.querySelector(".btn-add-activity-question");

        function hideQEmpty() { questionsEmpty.style.display = "none"; }
        function maybeShowQEmpty() {
            if (questionsList.querySelectorAll(".activity-question-item").length === 0)
                questionsEmpty.style.display = "flex";
        }
        function reNumberActivityQuestions() {
            questionsList.querySelectorAll(".activity-question-item").forEach((q, i) => {
                q.querySelector(".activity-question-num").textContent = "Question " + (i + 1);
            });
        }

        addQBtn.addEventListener("click", () => {
            hideQEmpty();
            const q = buildActivityQuestion();
            questionsList.appendChild(q);
            reNumberActivityQuestions();

            q.querySelector(".remove-activity-question").addEventListener("click", () => {
                q.remove();
                reNumberActivityQuestions();
                maybeShowQEmpty();
            });

            // wire up the question type toggle
            wireActivityQuestionType(q);
        });

        return el;
    }

    // ── single Activity Question ──────────────────────────────────────────────

    function buildActivityQuestion() {
        const q = document.createElement("div");
        q.className = "activity-question-item card-body";
        q.innerHTML = `
            <div class="card-body-header d-flex justify-content-between align-items-center">
                <div class="card-nav">
                    <div class="card-icon">
                        <i class="fa fa-pencil"></i>
                    </div>
                    <p class="activity-question-num mb-0" style="font-size:15px;font-weight:600;">Question 1</p>
                </div>
                <button type="button" class="remove-activity-question btn btn-sm">
                    <i class="fa fa-times"></i>
                </button>
            </div>
            <hr>
            <div class="row">

                <!-- Question type selector -->
                <div class="col-lg-12 mt-3">
                    <label>Question Type *</label>
                    <select name="activity_question_type[]" class="form-control mt-2 activity-question-type-select">
                        <option value="essay">Essay / Open-ended</option>
                        <option value="multiple_choice">Multiple Choice</option>
                    </select>
                </div>

                <!-- Question text -->
                <div class="col-lg-12 mt-3">
                    <label>Question *</label>
                    <input type="text" name="activity_question_text[]" class="form-control mt-2" placeholder="Enter your question">
                </div>

                <!-- Essay answer box (shown for Essay type) -->
                <div class="activity-essay-fields col-lg-12 mt-3">
                    <label>Sample / Model Answer <span style="color:#aaa;font-size:12px;">(optional — for teacher reference)</span></label>
                    <textarea name="activity_essay_answer[]" class="form-control mt-2" rows="4" placeholder="Enter a sample answer for reference..."></textarea>
                </div>

                <!-- Multiple choice fields (hidden by default) -->
                <div class="activity-mc-fields" style="display:none; width:100%;">
                    <div class="col-lg-12 mt-3">
                        <label>Choice A *</label>
                        <input type="text" name="activity_choice_a[]" class="form-control mt-2" placeholder="Choice A">
                    </div>
                    <div class="col-lg-12 mt-3">
                        <label>Choice B *</label>
                        <input type="text" name="activity_choice_b[]" class="form-control mt-2" placeholder="Choice B">
                    </div>
                    <div class="col-lg-12 mt-3">
                        <label>Choice C</label>
                        <input type="text" name="activity_choice_c[]" class="form-control mt-2" placeholder="Choice C (optional)">
                    </div>
                    <div class="col-lg-12 mt-3">
                        <label>Choice D</label>
                        <input type="text" name="activity_choice_d[]" class="form-control mt-2" placeholder="Choice D (optional)">
                    </div>
                    <div class="col-lg-12 mt-3">
                        <label>Correct Answer *</label>
                        <select name="activity_correct_answer[]" class="form-control mt-2">
                            <option value="">-- Select correct answer --</option>
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="C">C</option>
                            <option value="D">D</option>
                        </select>
                    </div>
                </div>

            </div>`;
        return q;
    }

    // toggle essay / multiple choice fields when type changes
    function wireActivityQuestionType(q) {
        const select = q.querySelector(".activity-question-type-select");
        const essayFields = q.querySelector(".activity-essay-fields");
        const mcFields = q.querySelector(".activity-mc-fields");

        select.addEventListener("change", () => {
            if (select.value === "multiple_choice") {
                essayFields.style.display = "none";
                mcFields.style.display = "block";
            } else {
                essayFields.style.display = "block";
                mcFields.style.display = "none";
            }
        });
    }

    // ── QUIZ builder ──────────────────────────────────────────────────────────

    function buildQuiz() {
        const el = document.createElement("div");
        el.className = "card-body quiz-item";
        el.innerHTML = `
            <div class="card-body-header d-flex justify-content-between align-items-center">
                <div class="card-nav">
                    <div class="card-icon"><i class="fa fa-pen-to-square"></i></div>
                    <p class="quiz-label">Quiz 1</p>
                </div>
                ${createRemoveButton()}
            </div>
            <hr>
            <div class="row">
                <div class="col-lg-12 mt-4">
                    <label>Quiz Title *</label>
                    <input type="text" name="quiz_title[]" class="form-control mt-2" placeholder="Enter quiz title">
                </div>
                <div class="col-lg-12 mt-4">
                    <label>Instructions</label>
                    <textarea name="quiz_instructions[]" class="form-control mt-2" rows="4" placeholder="Enter quiz instructions (e.g. Answer all questions carefully)"></textarea>
                </div>
                <div class="col-lg-6 mt-4">
                    <label>Time Limit (minutes)</label>
                    <input type="number" name="quiz_time_limit[]" class="form-control mt-2" placeholder="e.g. 30" min="1">
                </div>
                <div class="col-lg-6 mt-4">
                    <label>Passing Score (%)</label>
                    <input type="number" name="quiz_passing_score[]" class="form-control mt-2" placeholder="e.g. 75" min="1" max="100">
                </div>
            </div>

            <div class="quiz-questions-container mt-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <label class="mb-0" style="font-weight:600;">Questions</label>
                    <button type="button" class="btn-add-question">
                        <i class="fa fa-plus"></i> Add Question
                    </button>
                </div>
                <div class="questions-list d-flex flex-column gap-3">
                    <div class="quiz-questions-empty text-content" style="margin-top:0;">
                        <i class="fa fa-circle-question"></i>
                        <p>No questions yet — click "Add Question" to start.</p>
                    </div>
                </div>
            </div>`;

        const questionsList = el.querySelector(".questions-list");
        const questionsEmpty = el.querySelector(".quiz-questions-empty");
        const addQBtn = el.querySelector(".btn-add-question");

        function hideQEmpty() { questionsEmpty.style.display = "none"; }
        function maybeShowQEmpty() {
            if (questionsList.querySelectorAll(".question-item").length === 0)
                questionsEmpty.style.display = "flex";
        }
        function reNumberQuestions() {
            questionsList.querySelectorAll(".question-item").forEach((q, i) => {
                q.querySelector(".question-num").textContent = "Question " + (i + 1);
            });
        }

        addQBtn.addEventListener("click", () => {
            hideQEmpty();
            const q = buildQuizQuestion();
            questionsList.appendChild(q);
            reNumberQuestions();

            q.querySelector(".remove-question").addEventListener("click", () => {
                q.remove();
                reNumberQuestions();
                maybeShowQEmpty();
            });
        });

        return el;
    }

    // ── single Quiz Question ──────────────────────────────────────────────────

    function buildQuizQuestion() {
        const q = document.createElement("div");
        q.className = "question-item card-body";
        q.innerHTML = `
            <div class="card-body-header d-flex justify-content-between align-items-center">
                <div class="card-nav">
                    <div class="card-icon"><i class="fa fa-list-check"></i></div>
                    <p class="question-num mb-0" style="font-size:15px;font-weight:600;">Question 1</p>
                </div>
                <button type="button" class="remove-question btn btn-sm">
                    <i class="fa fa-times"></i>
                </button>
            </div>
            <hr>
            <div class="row">
                <div class="col-lg-12 mt-3">
                    <label>Question *</label>
                    <input type="text" name="question_text[]" class="form-control mt-2" placeholder="Enter your question">
                </div>
                <div class="col-lg-6 mt-3">
                    <label>Choice A *</label>
                    <input type="text" name="choice_a[]" class="form-control mt-2" placeholder="Choice A">
                </div>
                <div class="col-lg-6 mt-3">
                    <label>Choice B *</label>
                    <input type="text" name="choice_b[]" class="form-control mt-2" placeholder="Choice B">
                </div>
                <div class="col-lg-6 mt-3">
                    <label>Choice C</label>
                    <input type="text" name="choice_c[]" class="form-control mt-2" placeholder="Choice C (optional)">
                </div>
                <div class="col-lg-6 mt-3">
                    <label>Choice D</label>
                    <input type="text" name="choice_d[]" class="form-control mt-2" placeholder="Choice D (optional)">
                </div>
                <div class="col-lg-12 mt-3">
                    <label>Correct Answer *</label>
                    <select name="correct_answer[]" class="form-control mt-2">
                        <option value="">-- Select correct answer --</option>
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="C">C</option>
                        <option value="D">D</option>
                    </select>
                </div>
            </div>`;
        return q;
    }

    // ── MODULE builder ────────────────────────────────────────────────────────

    function buildModule() {
        const mod = document.createElement("div");
        mod.className = "card-body module-item";

        mod.innerHTML = `
            <div class="card-body-header d-flex justify-content-between align-items-center">
                <div class="card-nav">
                    <div class="card-icon"><i class="fa fa-file"></i></div>
                    <p class="module-label">Module</p>
                </div>
                ${createRemoveButton()}
            </div>
            <hr>
            <div class="row">
                <div class="col-lg-12 mt-4">
                    <label>Module Title *</label>
                    <input type="text" name="module_title[]" class="form-control mt-2" placeholder="Enter module title">
                </div>
                <div class="col-lg-12 mt-4">
                    <label>Module Description</label>
                    <textarea name="module_content[]" class="form-control mt-2" rows="4" placeholder="Brief description of this module"></textarea>
                </div>
            </div>

            <div class="buttons d-flex flex-wrap mt-4">
                <button type="button" class="btn-add-lesson">
                    <i class="fa fa-file"></i> Add Lesson
                </button>
                <button type="button" class="btn-add-video">
                    <i class="fa fa-video"></i> Add Video
                </button>
                <button type="button" class="btn-add-image">
                    <i class="fa fa-image"></i> Add Image
                </button>
                <button type="button" class="btn-add-activity">
                    <i class="fa fa-circle-question"></i> Add Activity
                </button>
                <button type="button" class="btn-add-quiz">
                    <i class="fa fa-pen-to-square"></i> Add Quiz
                </button>
            </div>

            <div class="nested-content-container d-flex flex-column gap-3 mt-3">
                <div class="nested-empty text-content" style="margin-top: 1rem;">
                    <i class="fa fa-circle-question"></i>
                    <p>No content yet — add a Lesson, Video, Image, Activity, or Quiz above.</p>
                </div>
            </div>`;

        const nestedContainer = mod.querySelector(".nested-content-container");
        const emptyState = mod.querySelector(".nested-empty");

        function hideEmpty() { emptyState.style.display = "none"; }
        function maybeShowEmpty() {
            if (nestedContainer.querySelectorAll(".card-body").length === 0)
                emptyState.style.display = "flex";
        }

        mod.querySelector(".btn-add-lesson").addEventListener("click", () => {
            hideEmpty();
            nestedContainer.appendChild(buildLesson());
            reNumberInModule(mod, "lesson-item", "lesson-label", "Lesson");
        });

        mod.querySelector(".btn-add-video").addEventListener("click", () => {
            hideEmpty();
            nestedContainer.appendChild(buildVideo());
            reNumberInModule(mod, "video-item", "video-label", "Video");
        });

        mod.querySelector(".btn-add-image").addEventListener("click", () => {
            hideEmpty();
            nestedContainer.appendChild(buildImage());
            reNumberInModule(mod, "image-item", "image-label", "Image");
        });

        mod.querySelector(".btn-add-activity").addEventListener("click", () => {
            hideEmpty();
            nestedContainer.appendChild(buildActivity());
            reNumberInModule(mod, "activity-item", "activity-label", "Activity");
        });

        mod.querySelector(".btn-add-quiz").addEventListener("click", () => {
            hideEmpty();
            nestedContainer.appendChild(buildQuiz());
            reNumberInModule(mod, "quiz-item", "quiz-label", "Quiz");
        });

        // remove nested items
        nestedContainer.addEventListener("click", (e) => {
            const removeBtn = e.target.closest(".remove-item");
            if (!removeBtn) return;
            const item = removeBtn.closest(".card-body");
            if (!item || item.classList.contains("module-item")) return;
            item.remove();
            reNumberInModule(mod, "lesson-item", "lesson-label", "Lesson");
            reNumberInModule(mod, "video-item", "video-label", "Video");
            reNumberInModule(mod, "image-item", "image-label", "Image");
            reNumberInModule(mod, "activity-item", "activity-label", "Activity");
            reNumberInModule(mod, "quiz-item", "quiz-label", "Quiz");
            maybeShowEmpty();
        });

        // remove the whole module
        mod.querySelector(".card-body-header .remove-item").addEventListener("click", () => {
            mod.remove();
            reNumberModules();
            checkEmpty();
        });

        return mod;
    }

    // ── top-level Add Module ──────────────────────────────────────────────────

    addModuleBtn.addEventListener("click", () => {
        document.querySelector(".text-content").style.display = "none";
        const mod = buildModule();
        contentContainer.appendChild(mod);
        reNumberModules();
    });

    // ── initial empty state ───────────────────────────────────────────────────
    checkEmpty();
});