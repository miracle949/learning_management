(function () {

    var urlParams = new URLSearchParams(window.location.search);
    var moduleId = urlParams.get('id') || '';
    var lessonParam = urlParams.get('lesson') || '';
    var subject = urlParams.get('subject') || '';

    if (!moduleId.startsWith('static_')) return;

    var subjectData = (typeof STATIC_LESSONS !== 'undefined' && STATIC_LESSONS[subject]) ? STATIC_LESSONS[subject] : null;
    if (!subjectData || !subjectData[moduleId]) return;

    var moduleData = subjectData[moduleId];
    var lessons = moduleData.lessons || [];
    if (lessons.length === 0) return;

    var currentLesson = lessons.find(function (l) { return l.id === lessonParam; }) || lessons[0];
    var currentIndex = lessons.indexOf(currentLesson);
    var prevLesson = lessons[currentIndex - 1] || null;
    var nextLesson = lessons[currentIndex + 1] || null;

    var letters = ['A', 'B', 'C', 'D'];
    var quizAnswers = {};
    var activityAnswers = {};

    // ── localStorage keys ─────────────────────────────────────────────────────
    function actKey(lessonId) { return 'static_activity_' + moduleId + '_' + lessonId; }
    function qzKey(lessonId) { return 'static_quiz_' + moduleId + '_' + lessonId; }
    function doneKey(lessonId) { return 'static_done_' + moduleId + '_' + lessonId; }

    // ── Completion logic ──────────────────────────────────────────────────────
    // A lesson is "done" when:
    //   • it was explicitly marked done (no activity/quiz), OR
    //   • activity submitted (if any) AND quiz submitted (if any)
    function isLessonComplete(lesson) {
        if (localStorage.getItem(doneKey(lesson.id))) return true;
        var actDone = !lesson.activity || !!localStorage.getItem(actKey(lesson.id));
        var qzDone = !lesson.quiz || !!localStorage.getItem(qzKey(lesson.id));
        return actDone && qzDone;
    }

    // Mark lesson done and persist
    function markLessonDone(lesson) {
        localStorage.setItem(doneKey(lesson.id), '1');
    }

    function calcProgress() {
        if (lessons.length === 0) return 0;
        var done = lessons.filter(isLessonComplete).length;
        return Math.round((done / lessons.length) * 100);
    }

    function updateProgressBar() {
        var pct = calcProgress();
        var bar = document.getElementById('progressBar');
        var pctEl = document.getElementById('progressPercent');
        if (bar) bar.style.width = pct + '%';
        if (pctEl) pctEl.textContent = pct + '%';
    }

    // ── Helpers ───────────────────────────────────────────────────────────────
    function youtubeEmbed(url) {
        var m = url.match(/[?&]v=([a-zA-Z0-9_-]{11})/) || url.match(/youtu\.be\/([a-zA-Z0-9_-]{11})/);
        return m ? 'https://www.youtube.com/embed/' + m[1] : url;
    }

    function lessonUrl(lId) {
        return '/learning_management/public/?url=subject_lessons&subject='
            + encodeURIComponent(subject) + '&id=' + moduleId + '&lesson=' + lId;
    }

    // ── Sidebar ───────────────────────────────────────────────────────────────
    function buildSidebar() {
        var ul = document.querySelector('.sidebar-lessons .sidebar-menu ul');
        if (!ul) return;
        ul.innerHTML = '';

        lessons.forEach(function (l, i) {
            var isActive = (l.id === currentLesson.id);
            var isDone = isLessonComplete(l);
            var li = document.createElement('li');
            li.className = (isActive ? 'active-lesson ' : '') + (isDone ? 'done-lesson' : '');

            // Icon: green check-circle if done, plain circle otherwise
            var iconClass = isDone ? 'fa fa-check-circle' : 'fa fa-circle';

            li.innerHTML =
                '<a href="' + lessonUrl(l.id) + '">'
                + '<i class="' + iconClass + ' lesson-icon-status"></i>'
                + '<span>Lesson ' + (i + 1) + ': '
                + l.title.replace(/^Lesson\s*\d+\s*:\s*/i, '')
                + '</span></a>';

            ul.appendChild(li);
        });
    }

    // ── Module header ─────────────────────────────────────────────────────────
    function buildModuleHeader() {
        var titleEl = document.querySelector('.module-header-title');
        var tagEl = document.querySelector('.module-header-tag');
        var descEl = document.querySelector('.module-header-desc');
        var totalEl = document.getElementById('static-total-lessons');

        if (titleEl) titleEl.textContent = moduleData.title;
        if (tagEl) tagEl.textContent = '📚 ' + subject.toUpperCase();
        if (descEl) descEl.textContent = moduleData.description || '';
        if (totalEl) totalEl.textContent = lessons.length;
    }

    // ── Footer nav builder ────────────────────────────────────────────────────
    function buildFooter() {
        var isDone = isLessonComplete(currentLesson);

        var html = '<div class="view-lessons-footer" style="padding:0 30.4px;">'
            + '<nav aria-label="Lesson navigation"><ul class="pagination m-0">'
            + '<li class="page-item">';

        // Prev
        if (prevLesson) {
            html += '<a class="page-link" href="' + lessonUrl(prevLesson.id) + '">'
                + '<i class="fa fa-chevron-left"></i><span>Previous Lesson</span></a>';
        } else {
            html += '<a class="page-link" style="visibility:hidden;pointer-events:none;">'
                + '<i class="fa fa-chevron-left"></i><span>Previous Lesson</span></a>';
        }

        html += '</li>'
            + '<li class="page-item disabled"><span id="page-indicator">'
            + (currentIndex + 1) + ' / ' + lessons.length
            + '</span></li>'
            + '<li class="page-item">';

        // Next / Finish / Completed
        if (nextLesson) {
            html += '<a class="page-link" id="staticNextBtn" href="' + lessonUrl(nextLesson.id) + '"'
                + ' onclick="handleStaticNext(event)">'
                + '<span>Next Lesson</span><i class="fa fa-chevron-right"></i></a>';
        } else {
            // Last lesson
            if (isDone) {
                html += '<div class="page-link"'
                    + ' style="background:var(--green-dark)!important;color:#fff!important;'
                    + 'border-color:var(--green)!important;font-weight:600;cursor:default;'
                    + 'display:flex;align-items:center;gap:6px;padding:10px 30px;border-radius:10px;">'
                    + '<span>Completed</span><i class="fa fa-check-double"></i></div>';
            } else {
                html += '<a class="page-link" id="staticFinishBtn" href="#"'
                    + ' style="background:var(--green)!important;color:#fff!important;'
                    + 'border-color:var(--green)!important;font-weight:600;"'
                    + ' onclick="handleStaticFinish(event)">'
                    + '<span>Finish</span><i class="fa fa-check"></i></a>';
            }
        }

        html += '</li></ul></nav></div>';
        return html;
    }

    // ── Main content builder ──────────────────────────────────────────────────
    function buildContent() {
        var viewLessons = document.querySelector('.view-lessons');
        if (!viewLessons) return;

        var html = '';

        // Title
        var cleanTitle = currentLesson.title.replace(/^Lesson\s*\d+\s*:\s*/i, '');
        html += '<h3 id="lesson-title">Lesson ' + (currentIndex + 1) + ': ' + cleanTitle + '</h3>';
        html += '<div class="view-lessons-body" style="padding:10px 1.9rem;">';

        // 1. Text
        if (currentLesson.content) {
            html += '<div class="lesson-section">'
                + currentLesson.content.replace(/\n/g, '<br>') + '</div>';
        }

        // 2. Videos
        if (currentLesson.videos && currentLesson.videos.length > 0) {
            html += '<div class="lesson-section">'
                + '<p class="lesson-section-title"><i class="fa fa-video"></i> Videos</p>';
            currentLesson.videos.forEach(function (v) {
                html += '<div class="video-card">'
                    + '<iframe src="' + youtubeEmbed(v.url) + '" allowfullscreen loading="lazy"'
                    + ' allow="accelerometer;autoplay;clipboard-write;encrypted-media;gyroscope;picture-in-picture">'
                    + '</iframe>';
                if (v.title) html += '<div class="video-info"><p class="video-title">' + v.title + '</p></div>';
                html += '</div>';
            });
            html += '</div>';
        }

        // 3. Images
        if (currentLesson.images && currentLesson.images.length > 0) {
            html += '<div class="lesson-section">'
                + '<p class="lesson-section-title"><i class="fa fa-image"></i> Images</p>'
                + '<div class="db-images-grid">';
            currentLesson.images.forEach(function (img) {
                html += '<div class="db-image-card" onclick="dbLightbox(\'' + img.path + '\')">'
                    + '<img src="' + img.path + '" alt="' + (img.title || '') + '" loading="lazy">';
                if (img.title) html += '<div class="db-image-caption">' + img.title + '</div>';
                html += '</div>';
            });
            html += '</div></div>';
        }

        // 4. Flashcards
        if (currentLesson.flashcards && currentLesson.flashcards.length > 0) {
            html += '<div class="lesson-section">'
                + '<p class="lesson-section-title"><i class="fa fa-clone"></i> Flashcards</p>'
                + '<div class="fc-grid">';
            currentLesson.flashcards.forEach(function (fc) {
                html += '<div class="fc-item" onclick="this.classList.toggle(\'flipped\')">'
                    + '<div class="fc-inner">'
                    + '<div class="fc-front"><span class="fc-label">Question</span>'
                    + '<span class="fc-text">' + fc.front + '</span>'
                    + '<span class="fc-hint">Tap to reveal</span></div>'
                    + '<div class="fc-back"><span class="fc-label">Answer</span>'
                    + '<span class="fc-text">' + fc.back + '</span>'
                    + '<span class="fc-hint">Tap to go back</span></div>'
                    + '</div></div>';
            });
            html += '</div></div>';
        }

        // 5. Activity
        if (currentLesson.activity) {
            var act = currentLesson.activity;
            var actSubmitted = !!localStorage.getItem(actKey(currentLesson.id));
            var savedActAnswers = actSubmitted
                ? (JSON.parse(localStorage.getItem(actKey(currentLesson.id))) || {})
                : {};

            html += '<div class="lesson-section">'
                + '<p class="lesson-section-title"><i class="fa fa-pencil-alt"></i> Activities</p>'
                + '<div class="activity-block">'
                + '<div class="activity-intro">'
                + '<div class="activity-intro-icon">✏️</div><div>'
                + '<p class="activity-intro-title">' + act.title + '</p>'
                + (act.instructions ? '<p class="activity-intro-desc">' + act.instructions + '</p>' : '')
                + '<div class="activity-meta-pills">'
                + '<span class="meta-pill pill-purple">' + act.questions.length + ' Questions</span>'
                + '<span class="meta-pill pill-green">⭐ ' + act.total_points + ' pts</span>'
                + '</div></div></div>';

            if (actSubmitted) {
                // Read-only review
                html += '<div class="submitted-notice" style="margin-bottom:16px;">'
                    + '<i class="fa fa-check-circle"></i> Activity submitted! Your answers have been recorded.'
                    + '</div>';

                act.questions.forEach(function (q, qi) {
                    html += '<div class="activity-question">'
                        + '<p class="aq-num">Question ' + (qi + 1) + '</p>'
                        + '<p class="aq-text">' + q.question + '</p>';

                    if (q.type === 'multiple_choice') {
                        var li = 0;
                        var picked = (savedActAnswers[q.id] || '').toLowerCase();
                        Object.keys(q.choices).forEach(function (key) {
                            var isCorrect = (key === q.correct);
                            var isPicked = (key === picked);
                            var isWrong = isPicked && !isCorrect;
                            var bStyle, lStyle, icon;
                            if (isCorrect) {
                                bStyle = 'border-color:#22c55e;background:#f0fdf4;';
                                lStyle = 'background:#22c55e;color:#fff;';
                                icon = '<i class="fa fa-check" style="margin-left:auto;color:#22c55e;"></i>';
                            } else if (isWrong) {
                                bStyle = 'border-color:#ef4444;background:#fef2f2;';
                                lStyle = 'background:#ef4444;color:#fff;';
                                icon = '<i class="fa fa-times" style="margin-left:auto;color:#ef4444;"></i>';
                            } else {
                                bStyle = 'border-color:#e5e7eb;'; lStyle = 'background:#f1f5f9;color:#555;'; icon = '';
                            }
                            html += '<div class="q-choice" style="pointer-events:none;margin-bottom:8px;' + bStyle + '">'
                                + '<span class="choice-letter" style="' + lStyle + '">' + letters[li++] + '</span>'
                                + q.choices[key] + icon + '</div>';
                        });
                    } else {
                        var essay = savedActAnswers[q.id] || '';
                        html += '<div style="background:#f9fafb;border:1.5px solid #e5e7eb;border-radius:8px;'
                            + 'padding:12px 14px;font-size:14px;color:#374151;line-height:1.6;">'
                            + (essay ? essay.replace(/\n/g, '<br>')
                                : '<span style="color:#aaa;font-style:italic;">No answer provided.</span>')
                            + '</div>';
                    }
                    html += '</div>';
                });

            } else {
                // Editable — NO submit button, auto-saved on Next/Finish
                act.questions.forEach(function (q, qi) {
                    html += '<div class="activity-question">'
                        + '<p class="aq-num">Question ' + (qi + 1) + '</p>'
                        + '<p class="aq-text">' + q.question + '</p>';

                    if (q.type === 'multiple_choice') {
                        html += '<div class="mc-choices">';
                        var li = 0;
                        Object.keys(q.choices).forEach(function (key) {
                            html += '<label class="mc-label"'
                                + ' onclick="staticPickMC(this,\'' + q.id + '\',\'' + key + '\')">'
                                + '<input type="radio" name="act_' + q.id + '">'
                                + '<span class="mc-letter">' + letters[li++] + '</span>'
                                + q.choices[key] + '</label>';
                        });
                        html += '</div>';
                    } else {
                        html += '<textarea class="activity-answer" placeholder="Write your answer here..." rows="3"'
                            + ' oninput="staticTextAnswer(\'' + q.id + '\', this.value)"></textarea>';
                    }
                    html += '</div>';
                });
                // NO submit button here — saved automatically on navigation
            }

            html += '</div></div>'; // /activity-block /lesson-section
        }

        // 6. Quiz
        if (currentLesson.quiz) {
            var qz = currentLesson.quiz;
            var qzSubmitted = !!localStorage.getItem(qzKey(currentLesson.id));
            var savedQzResult = qzSubmitted
                ? (JSON.parse(localStorage.getItem(qzKey(currentLesson.id))) || {})
                : null;

            html += '<div class="lesson-section">'
                + '<p class="lesson-section-title"><i class="fa fa-clipboard-list"></i> Quiz</p>';

            if (qzSubmitted && savedQzResult) {
                // Result + read-only review
                var savedQzAnswers = savedQzResult.answers || {};

                html += '<div class="quiz-intro"><div class="quiz-intro-icon">📋</div>'
                    + '<div style="width:100%;">'
                    + '<p class="quiz-intro-title">' + qz.title + '</p>'
                    + (qz.instructions ? '<p class="quiz-intro-desc">' + qz.instructions + '</p>' : '')
                    + '<div class="activity-meta-pills" style="display:flex;justify-content:space-between;align-items:center;">'
                    + '<div>'
                    + '<span class="meta-pill pill-purple">' + qz.questions.length + ' Questions</span>'
                    + '<span class="meta-pill pill-green">Passing: ' + qz.passing_score + '%</span>'
                    + '</div>'
                    + '<div><p class="result-label" style="margin:0;font-size:11px;">'
                    + 'You scored ' + savedQzResult.score + ' out of ' + savedQzResult.total
                    + ' — <strong>' + (savedQzResult.passed ? '✅ Passed' : '❌ Failed') + '</strong>'
                    + '</p></div>'
                    + '</div></div></div>';

                qz.questions.forEach(function (q, qi) {
                    var picked = (savedQzAnswers[q.id] || '').toLowerCase();
                    html += '<div class="q-card">'
                        + '<p class="q-number">Question ' + (qi + 1) + '</p>'
                        + '<p class="q-text">' + q.question + '</p>'
                        + '<div class="q-choices">';
                    var li = 0;
                    Object.keys(q.choices).forEach(function (key) {
                        var isCorrect = (key === q.correct);
                        var isPicked = (key === picked);
                        var isWrong = isPicked && !isCorrect;
                        var bStyle, lStyle, icon;
                        if (isCorrect) {
                            bStyle = 'border-color:#22c55e;background:#f0fdf4;';
                            lStyle = 'background:#22c55e;color:#fff;';
                            icon = '<i class="fa fa-check" style="margin-left:auto;color:#22c55e;"></i>';
                        } else if (isWrong) {
                            bStyle = 'border-color:#ef4444;background:#fef2f2;';
                            lStyle = 'background:#ef4444;color:#fff;';
                            icon = '<i class="fa fa-times" style="margin-left:auto;color:#ef4444;"></i>';
                        } else {
                            bStyle = 'border-color:#e5e7eb;'; lStyle = 'background:#f1f5f9;color:#555;'; icon = '';
                        }
                        html += '<div class="q-choice" style="pointer-events:none;' + bStyle + '">'
                            + '<span class="choice-letter" style="' + lStyle + '">' + letters[li++] + '</span>'
                            + q.choices[key] + icon + '</div>';
                    });
                    html += '</div></div>';
                });

            } else {
                // Active quiz — NO submit button, auto-scored on Next/Finish
                html += '<div class="quiz-intro"><div class="quiz-intro-icon">📋</div><div>'
                    + '<p class="quiz-intro-title">' + qz.title + '</p>'
                    + (qz.instructions ? '<p class="quiz-intro-desc">' + qz.instructions + '</p>' : '')
                    + '<div class="activity-meta-pills">'
                    + '<span class="meta-pill pill-purple">' + qz.questions.length + ' Questions</span>'
                    + '<span class="meta-pill pill-green">Passing: ' + qz.passing_score + '%</span>'
                    + '</div></div></div>';

                qz.questions.forEach(function (q, qi) {
                    html += '<div class="q-card">'
                        + '<p class="q-number">Question ' + (qi + 1) + ' of ' + qz.questions.length + '</p>'
                        + '<p class="q-text">' + q.question + '</p>'
                        + '<div class="q-choices">';
                    var li = 0;
                    Object.keys(q.choices).forEach(function (key) {
                        html += '<div class="q-choice"'
                            + ' data-qid="' + q.id + '" data-key="' + key + '"'
                            + ' onclick="staticPickQuiz(this)">'
                            + '<span class="choice-letter">' + letters[li++] + '</span>'
                            + q.choices[key] + '</div>';
                    });
                    html += '</div></div>';
                });

                // Status counter only — no submit button
                html += '<div class="quiz-nav">'
                    + '<span class="quiz-status" id="static_quiz_status">0 / '
                    + qz.questions.length + ' answered</span>'
                    + '</div>';
            }

            html += '</div>'; // /lesson-section
        }

        html += '</div>'; // /view-lessons-body

        // Footer nav
        html += buildFooter();

        viewLessons.innerHTML = html;
    }

    // ── Auto-save current lesson before navigating ────────────────────────────
    function saveCurrentLesson() {
        // Save activity answers if activity exists and not yet submitted
        if (currentLesson.activity && !localStorage.getItem(actKey(currentLesson.id))) {
            if (Object.keys(activityAnswers).length > 0) {
                localStorage.setItem(actKey(currentLesson.id), JSON.stringify(activityAnswers));
            }
        }

        // Auto-score quiz if quiz exists and not yet submitted
        if (currentLesson.quiz && !localStorage.getItem(qzKey(currentLesson.id))) {
            var questions = currentLesson.quiz.questions;
            var correct = 0;
            var answers = {};
            questions.forEach(function (q) {
                answers[q.id] = quizAnswers[q.id] || '';
                if (quizAnswers[q.id] === q.correct) correct++;
            });
            var pct = Math.round((correct / questions.length) * 100);
            var passed = pct >= currentLesson.quiz.passing_score;
            localStorage.setItem(qzKey(currentLesson.id), JSON.stringify({
                score: correct, total: questions.length, pct: pct, passed: passed, answers: answers
            }));
        }

        // Mark lesson done if it has no activity and no quiz
        if (!currentLesson.activity && !currentLesson.quiz) {
            markLessonDone(currentLesson);
        }

        // Mark done if both are now submitted
        if (isLessonComplete(currentLesson)) {
            markLessonDone(currentLesson);
        }
    }

    // ── Navigation handlers ───────────────────────────────────────────────────
    window.handleStaticNext = function (e) {
        e.preventDefault();
        saveCurrentLesson();
        window.location.href = lessonUrl(nextLesson.id);
    };

    window.handleStaticFinish = function (e) {
        e.preventDefault();
        saveCurrentLesson();
        // Rebuild to show "Completed" button and update progress
        buildContent();
        buildSidebar();
        updateProgressBar();
    };

    // ── Quiz interaction ──────────────────────────────────────────────────────
    window.staticPickQuiz = function (el) {
        var qid = el.dataset.qid;
        document.querySelectorAll('.q-choice[data-qid="' + qid + '"]').forEach(function (c) {
            c.classList.remove('selected');
            var letter = c.querySelector('.choice-letter');
            if (letter) { letter.style.background = ''; letter.style.color = ''; }
        });
        el.classList.add('selected');
        var letter = el.querySelector('.choice-letter');
        if (letter) { letter.style.background = 'var(--green)'; letter.style.color = '#fff'; }
        quizAnswers[qid] = el.dataset.key;

        var total = currentLesson.quiz ? currentLesson.quiz.questions.length : 0;
        var answered = Object.keys(quizAnswers).length;
        var status = document.getElementById('static_quiz_status');
        if (status) status.textContent = answered + ' / ' + total + ' answered';
    };

    // ── Activity interaction ──────────────────────────────────────────────────
    window.staticPickMC = function (label, qid, key) {
        activityAnswers[qid] = key;
        var parent = label.closest('.mc-choices');
        if (parent) parent.querySelectorAll('.mc-label').forEach(function (l) {
            l.style.borderColor = ''; l.style.background = '';
        });
        label.style.borderColor = 'var(--green)';
        label.style.background = '#f0fdf4';
    };

    window.staticTextAnswer = function (qid, val) {
        activityAnswers[qid] = val;
    };

    // ── Boot ──────────────────────────────────────────────────────────────────
    document.addEventListener('DOMContentLoaded', function () {
        buildSidebar();
        buildContent();
        buildModuleHeader();
        updateProgressBar();
    });

})();