<style>
    .container-fluid .rightbar .module-title {
        width: 100%;
        /* height: 270px; */
        /* background-color: #00C950; */
        border: 1px solid rgba(0, 0, 0, 0.2);
        /* padding: 3rem; */
        color: white;
        box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 4px;
        border-radius: 10px;
    }

    .container-fluid .rightbar .module-title .module-body .module-picture {
        position: absolute;
        inset: 0;
        background-image: url('../images/work_picture.jpg');
        background-size: cover;
        background-position: center 30%;
        opacity: 0.55;
        height: 100%;
    }

    .container-fluid .rightbar .module-title .module-body {
        /* background-image: url('../images/philosophy_picture.jpg');
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover; */
        overflow: hidden;
        position: relative;
        padding: 1.8rem 1.9rem 1.8rem 1.9rem;
        display: flex;
        justify-content: space-between;
        gap: 2rem;
        align-items: center;
        color: #212529;
        /* background-color: #C8E0C3; */
        border-radius: 10px;
        /* border-bottom-left-radius: 10px;
        border-bottom-right-radius: 10px; */
        background-color: #ffffff;
        /* margin-bottom: 28px; */
        /* min-height: 200px; */
        background: linear-gradient(135deg, #000000 0%, #010103 60%, #0f3460 100%);
        /* background: linear-gradient(135deg, #1a1a2e 0%, #16213e 60%, #0f3460 100%); */
    }

    .container-fluid .rightbar .module-title .module-body .module-body-child {
        position: relative;
        z-index: 2;
        display: flex;
        gap: 48px;
        align-items: center;
    }

    .container-fluid .rightbar .module-title .module-body .module-links {
        width: 55%;
    }

    .container-fluid .rightbar .module-title .module-body .module-links span {
        color: #ffffff;
        text-transform: uppercase;
        color: var(--green);
        font-weight: 600;
        font-size: 13.5px;
    }

    .container-fluid .rightbar .module-title .module-body .module-links .module-buttons {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        margin-top: 1.5rem;
    }

    .container-fluid .rightbar .module-title .module-body .module-links .module-buttons a:nth-child(1) {
        color: white;
        background-color: var(--green);
    }

    .container-fluid .rightbar .module-title .module-body .module-links .module-buttons a:nth-child(2) {
        border: 2px solid #ffffff;
        /* color: #5F66AC; */
        color: #ffffff;
        padding: 10px 40px;
    }

    .container-fluid .rightbar .module-title .module-body .module-links .module-buttons a {
        text-decoration: none;
        padding: 10px 27px;
        border-radius: 28px;
        text-transform: uppercase;
        font-weight: 600;
        font-size: 13px;
        /* font-size: 15.5px; */
        /* color: #5F66AC; */
    }

    .container-fluid .rightbar .module-title .module-body .module-links h1 {
        font-size: 22px;
        text-transform: uppercase;
        font-family: "Titan", sans-serif;
        /* font-weight: bold; */
        /* color: var(--green-dark); */
        color: #ffffff;
        margin-top: 0.7rem;
    }

    .container-fluid .rightbar .module-title .module-body .module-text {
        width: 45%;
    }

    .container-fluid .rightbar .module-title .module-body .module-text p {
        line-height: 26px;
        margin: 0;
        font-size: 14px;
        /* color: #555555; */
        /* color: #ffffff; */
        color: rgba(255, 255, 255, .85);
    }

    .container-fluid .rightbar .module-parent-progress .module-progress .progress-bar .progress {
        position: absolute;
        padding: 0.7rem;
        left: 0;
        width: 60%;
        background-color: #00C950;
        border-radius: 28px;
    }
</style>

<div class="module-title">
    <!-- <h1>Work Immersion</h1>
    <p>CSS 12-1</p> -->
    <div class="module-body">
        <div class="module-picture"></div>
        <div class="module-body-child">
            <div class="module-links">
                <span>Currently Enrolled</span>
                <h1>Work Immersion</h1>

                <div class="module-buttons">
                    <a href="/learning_management/public/?url=subjects_all">Browse Courses</a>
                    <a href="#">Learn More</a>
                </div>
            </div>
            <div class="module-text">
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore
                    et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut
                    aliquip
                    ex ea commodo consequat. Duis aute irure dolor in </p>
            </div>
        </div>
    </div>
</div>

<div class="parent">
    <!-- <div class="your-module">
        <h2>Category</h2>

        <div class="your-module-category">
            <ul>
                <li>
                    <span>Introduction to Philosophy of Human Person</span>
                </li>

                <li>
                    <span>Introduction to Philosophy of Human Person</span>
                </li>

                <li>
                    <span>Introduction to Philosophy of Human Person</span>
                </li>

                <li>
                    <span>Introduction to Philosophy of Human Person</span>
                </li>
            </ul>
        </div>
    </div> -->

    <div class="module-parent-progress">

        <div class="module-progress" data-module-id="module1">
            <div class="module-progress-header">

            </div>
            <div class="module-progress-body">
                <p>Work</p>
                <h3>Work Immersion</h3>

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
                    <a href="/learning_management/public/?url=subject_lessons&subject=work_immersion&module=module1">Continue
                        learning <i class="fa fa-arrow-right"></i></a>
                </div>
            </div>
        </div>

        <div class="module-progress" data-module-id="module2">
            <div class="module-progress-header">

            </div>
            <div class="module-progress-body">
                <p>Work</p>
                <h3>Work Immersion</h3>

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
                    <a href="/learning_management/public/?url=subject_lessons&subject=work_immersion&module=module2">Continue
                        learning <i class="fa fa-arrow-right"></i></a>
                </div>
            </div>
        </div>

        <div class="module-progress" data-module-id="module3">
            <div class="module-progress-header">

            </div>
            <div class="module-progress-body">
                <p>Work</p>
                <h3>Work Immersion</h3>

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
                    <a href="/learning_management/public/?url=subject_lessons&subject=work_immersion&module=module3">Continue
                        learning <i class="fa fa-arrow-right"></i></a>
                </div>
            </div>
        </div>

        <div class="module-progress" data-module-id="module4">
            <div class="module-progress-header">

            </div>
            <div class="module-progress-body">
                <p>Work</p>
                <h3>Work Immersion</h3>

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
                    <a href="/learning_management/public/?url=subject_lessons&subject=work_immersion&module=module3">Continue
                        learning <i class="fa fa-arrow-right"></i></a>
                </div>
            </div>
        </div>

    </div>
</div>