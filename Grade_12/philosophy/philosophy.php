<style>
    .container-fluid .rightbar .module-title {
        width: 100%;
        /* height: 270px; */
        /* background-color: #00C950; */
        /* border: 1px solid rgba(0, 0, 0, 0.2); */
        /* padding: 3rem; */
        color: white;
        /* box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 4px; */
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06), 0 1px 2px rgba(0, 0, 0, 0.04);
        animation: slideIn 0.4s ease both;
    }

    .container-fluid .rightbar .module-title:hover {
        box-shadow: 0 8px 32px rgba(108, 63, 232, 0.13), 0 2px 8px rgba(0, 0, 0, 0.07);
        transform: translateY(-2px);
        cursor: pointer;
        transition: all 0.3s ease-in-out;
    }

    .container-fluid .rightbar .module-title .module-picture {
        width: 100%;
        height: 200px;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
        background-image: url('../images/philosophy_picture.jpg');
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
    }

    .container-fluid .rightbar .module-title .module-body {
        padding: 2.5rem 3rem 2.5rem 3rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: #212529;
        /* background-color: #C8E0C3; */
        border-bottom-left-radius: 10px;
        border-bottom-right-radius: 10px;
        background-color: #ffffff;
    }

    .container-fluid .rightbar .module-title .module-body .module-links {
        max-width: 450px;
    }

    .container-fluid .rightbar .module-title .module-body .module-links .module-buttons {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        margin-top: 1.5rem;
    }

    .container-fluid .rightbar .module-title .module-body .module-links .module-buttons a:nth-child(1) {
        color: white;
        /* background-color: #5F66AC; */
        background-color: #9333ea;
    }

    .container-fluid .rightbar .module-title .module-body .module-links .module-buttons a:nth-child(2) {
        /* border: 2px solid #5F66AC; */
        border: 2px solid #9333ea;
        /* color: #5F66AC; */
        color: #9333ea;
        padding: 10px 40px;
    }

    .container-fluid .rightbar .module-title .module-body .module-links .module-buttons a {
        text-decoration: none;
        padding: 10px 30px;
        border-radius: 28px;
        text-transform: uppercase;
        font-weight: 600;
        /* font-size: 15.5px; */
        /* color: #5F66AC; */
    }

    .container-fluid .rightbar .module-title .module-body .module-links h1 {
        font-size: 23px;
        text-transform: uppercase;
        font-family: "Titan", sans-serif;
        /* font-weight: bold; */
    }

    .container-fluid .rightbar .module-title .module-body .module-text {
        max-width: 550px;
    }

    .container-fluid .rightbar .module-title .module-body .module-text p {
        line-height: 30px;
        margin: 0;
        font-size: 16px;
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
    <!-- <h1>Introduction to Philosophy of Human Person</h1>
    <p><?= $_SESSION['section'] ?></p> -->

    <div class="module-picture">

    </div>
    <div class="module-body">
        <div class="module-links">
            <h1>Introduction to Philosophy of Human Person</h1>

            <div class="module-buttons">
                <a href="/learning_management/public/?url=subjects_all">Browse Courses</a>
                <a href="#">Learn More</a>
            </div>
        </div>
        <div class="module-text">
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et
                dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                ex ea commodo consequat. Duis aute irure dolor in </p>
        </div>
    </div>
</div>

<div class="parent">
    <div class="your-module">
        <!-- <h2>Category</h2>

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
        </div> -->
    </div>

    <div class="module-parent-progress">

        <div class="module-progress" data-module-id="module1">
            <h3>Introduction to Philosophy of Human Person</h3>

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
                <a href="/learning_management/public/?url=subject_lessons&subject=philosophy&module=module1">Start
                    Now</a>
            </div>
        </div>

        <div class="module-progress" data-module-id="module2">
            <h3>Introduction to Philosophy of Human Person</h3>

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
                <a href="/learning_management/public/?url=subject_lessons&subject=philosophy&module=module2">Start
                    Now</a>
            </div>
        </div>

        <div class="module-progress" data-module-id="module3">
            <h3>Introduction to Philosophy of Human Person</h3>

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
                <a href="/learning_management/public/?url=subject_lessons&subject=philosophy&module=module3">Start
                    Now</a>
            </div>
        </div>


        <div class="module-progress" data-module-id="module4">
            <h3>Introduction to Philosophy of Human Person</h3>

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
                <a href="/learning_management/public/?url=subject_lessons&subject=philosophy&module=module3">Start
                    Now</a>
            </div>
        </div>

    </div>
</div>