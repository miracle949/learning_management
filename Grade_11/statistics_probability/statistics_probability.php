<style>
    .container-fluid .rightbar .module-title {
        width: 100%;
        height: 270px;
        background-color: #5D7C81;
        padding: 3rem;
        color: white;
        border-radius: 10px;
        box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 4px;
        border: 1px solid rgba(0, 0, 0, 0.1);
    }

    .container-fluid .rightbar .module-parent-progress .module-progress .progress-bar .progress {
        position: absolute;
        padding: 0.7rem;
        left: 0;
        width: 60%;
        background-color: #5D7C81;
        border-radius: 28px;
        box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 4px;
        border: 1px solid rgba(0, 0, 0, 0.1);
    }
</style>

<div class="module-title">
    <h1>Statistics and Probability</h1>
    <p><?= $_SESSION['section'] ?></p>
</div>

<div class="your-module">
    <h2>Your Modules</h2>
</div>

<div class="module-parent-progress">

    <div class="module-progress" data-module-id="module1">
        <h3>Statistics and Probability</h3>

        <p>Learn the fundamentals of philosophy, including major branches, key thinkers, and basic
            philosophical concepts.</p>

        <div class="title-progress-bar">
            <span class="lessonText">0 of 10 lessons</span>
            <span class="lessonPercent">0%</span>
        </div>

        <div class="parent-progress-bar">
            <div class="progress-bar">
                <div class="progress"></div>
            </div>
        </div>

        <div class="footer-bar">
            <a href="/learning_management/public/?url=subject_lessons&subject=statistics_probability&module=module1">Continue learning <i
                    class="fa fa-arrow-right"></i></a>
        </div>
    </div>

    <div class="module-progress" data-module-id="module2">
        <h3>Statistics and Probability</h3>

        <p>Learn the fundamentals of philosophy, including major branches, key thinkers, and basic
            philosophical concepts.</p>

        <div class="title-progress-bar">
            <span class="lessonText">0 of 10 lessons</span>
            <span class="lessonPercent">0%</span>
        </div>

        <div class="parent-progress-bar">
            <div class="progress-bar">
                <div class="progress"></div>
            </div>
        </div>

        <div class="footer-bar">
            <a href="/learning_management/public/?url=subject_lessons&subject=statistics_probability&module=module2">Continue learning <i
                    class="fa fa-arrow-right"></i></a>
        </div>
    </div>

    <div class="module-progress" data-module-id="module3">
        <h3>Statistics and Probability</h3>

        <p>Learn the fundamentals of philosophy, including major branches, key thinkers, and basic
            philosophical concepts.</p>

        <div class="title-progress-bar">
            <span class="lessonText">0 of 10 lessons</span>
            <span class="lessonPercent">0%</span>
        </div>

        <div class="parent-progress-bar">
            <div class="progress-bar">
                <div class="progress"></div>
            </div>
        </div>

        <div class="footer-bar">
            <a href="/learning_management/public/?url=subject_lessons&subject=statistics_probability&module=module3">Continue learning <i
                    class="fa fa-arrow-right"></i></a>
        </div>
    </div>

</div>