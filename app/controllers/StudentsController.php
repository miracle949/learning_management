<?php

require_once "../core/Model.php";
require_once "../app/models/subjects.php";

class StudentsController
{

    public function subjects_all()
    {
        $subjectModel = new subjects();

        $grade_level_id = ($_SESSION['grade_level'] == "Grade 11") ? 1 : 2;

        // ✅ Use the model to fetch student record by user_id
        $user_id = $_SESSION['user_id'];
        $studentRow = $subjectModel->getStudentByUserId($user_id);

        if (!$studentRow) {
            die("Student record not found. Please contact your administrator.");
        }

        $student_id = $studentRow['id'];
        $section_id = $studentRow['section_id'];

        // Store in session for future use
        $_SESSION['student_id'] = $student_id;
        $_SESSION['section_id'] = $section_id;

        // ✅ Handle enroll action
        if (isset($_GET['enroll']) && isset($_GET['subject_id']) && isset($_GET['subject_slug'])) {
            $subject_id = (int) $_GET['subject_id'];
            $subject_slug = $_GET['subject_slug'];

            if (!$subjectModel->isEnrolled($student_id, $subject_id)) {

                // Get the correct section_id based on the subject's grade level
                // and which section this student belongs to
                $correct_section_id = $subjectModel->getSectionForSubject($student_id, $subject_id);

                // Fallback to student's default section if no match found
                $enroll_section_id = $correct_section_id ?? $section_id;

                $subjectModel->enrollStudent($student_id, $subject_id, $enroll_section_id);
            }

            header("Location: /learning_management/public/?url=subjects&subject=" . urlencode($subject_slug));
            exit();
        }

        $subjects = $subjectModel->getSubjectsByGradeLevel($grade_level_id);
        $enrolledSubjectIds = $subjectModel->getEnrolledSubjectIds($student_id);

        require "../app/view/subjects_all.php";
    }
}