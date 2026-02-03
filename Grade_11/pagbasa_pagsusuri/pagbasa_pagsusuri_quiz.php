<div class="navbar-quiz">
    <div class="navbar-title">
        <a href="/learning_management/public/?url=subject_lessons&subject=pagbasa_pagsusuri&module=module1">
            <i class="fa fa-arrow-left"></i>
            <span>Back to Module</span>
        </a>
    </div>

    <div class="navbar-complete-time">
        <!-- <div class="time">
                        <i class="fa fa-clock"></i>
                        <p>0:00</p>
                    </div> -->
        <div class="answered">
            <p id="answered-count">0 / 10 answered</p>
        </div>
    </div>
</div>

<div class="body-quiz">
    <div class="quiz-section">

        <div class="quiz-progress-bar">
            <div class="quiz-title">
                <p id="question-count"></p>
                <span id="question-percent"></span>
            </div>

            <div class="quiz-parent-progress">
                <div class="quiz-progress" id="quizProgress"></div>
            </div>
        </div>

        <div class="answer-quiz">
            <div class="multiple">
                <span>Multiple Choice</span>
            </div>

            <h3 id="quiz-question"></h3>

            <div class="choose-answer mt-4">
                <div class="options" id="quiz-options"></div>
            </div>
        </div>

        <div class="quiz-footer">
            <nav aria-label="Page navigation">
                <ul class="pagination">
                    <li class="page-item">
                        <a class="page-link" href="#" id="prevBtn">
                            <i class="fa fa-chevron-left"></i>
                            <span>Previous</span>
                        </a>
                    </li>

                    <li class="page-item">
                        <a class="page-link" href="#" id="nextBtn">
                            <span>Next Question</span>
                            <i class="fa fa-chevron-right"></i>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>

    </div>
</div>