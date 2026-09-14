<?php

require_once "../core/Model.php";

class subjects extends Model
{

    public function getSectionForSubject($student_id, $subject_id)
    {
        $sql = "
        SELECT ta.section_id 
        FROM tbl_teacher_assignments ta
        JOIN tbl_students s ON s.section_id = ta.section_id
        WHERE s.id = ? 
        AND ta.subject_id = ?
        AND ta.grade_level_id = (
            SELECT grade_level_id FROM tbl_subjects WHERE id = ?
        )
        LIMIT 1
    ";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("iii", $student_id, $subject_id, $subject_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        return $row ? $row['section_id'] : null;
    }

    public function getGrade11Subjects()
    {
        return $this->getSubjectsByGradeLevel(1);
    }

    public function getGrade12Subjects()
    {
        return $this->getSubjectsByGradeLevel(2);
    }

    public function getStudentByUserId($userId)
    {
        $stmt = $this->db->prepare("SELECT * FROM tbl_students WHERE user_id = ? LIMIT 1");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getSubjectsByGradeLevel($grade_level_id)
    {
        $sql = "SELECT * FROM tbl_subjects WHERE grade_level_id = ? ORDER BY id ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $grade_level_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function isEnrolled($student_id, $subject_id)
    {
        $sql = "SELECT id FROM tbl_student_enrollments WHERE student_id = ? AND subject_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $student_id, $subject_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }

    public function enrollStudent($student_id, $subject_id, $section_id)
    {
        $sql = "INSERT INTO tbl_student_enrollments (student_id, subject_id, section_id, enrolled_at) VALUES (?, ?, ?, NOW())";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("iii", $student_id, $subject_id, $section_id);
        return $stmt->execute();
    }

    public function getEnrolledSubjectIds($student_id)
    {
        $sql = "SELECT subject_id FROM tbl_student_enrollments WHERE student_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $student_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        return array_map('intval', array_column($rows, 'subject_id')); // ← ensure integers
    }

    public function insertSubject(
        string $subject_name,
        int $grade_level_id,
        string $subject_description = '',
        ?string $subject_image = null,
        string $curriculum_type = 'legacy',
        string $strand_group = ''
    ) {
        $curriculum_type = $curriculum_type === 'new' ? 'new' : 'legacy';

        $stmt = $this->db->prepare("
            INSERT INTO tbl_subjects
                (subject_name, grade_level_id, subject_description, subject_image, curriculum_type, strand_group, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())
        ");
        $stmt->bind_param(
            "sissss",
            $subject_name,
            $grade_level_id,
            $subject_description,
            $subject_image,
            $curriculum_type,
            $strand_group
        );
        $stmt->execute();
        return $this->db->insert_id;
    }

    /**
     * Look up a subject by its join code.
     * Returns the subject row (with id, subject_name, subject_code) or null.
     */
    public function getSubjectByCode($code)
    {
        $sql = "
        SELECT s.id, s.subject_name, s.subject_code, 
               ta.section_id, ta.grade_level_id, ta.join_code
        FROM tbl_teacher_assignments ta
        JOIN tbl_subjects s ON s.id = ta.subject_id
        WHERE ta.join_code = ?
        LIMIT 1
    ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $code);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc() ?: null;
    }

    public function getSubjectStats(string $curriculumType = 'legacy'): array
    {
        $curriculumType = $curriculumType === 'new' ? 'new' : 'legacy';

        $stmt = $this->db->prepare("
            SELECT s.id, gl.name AS grade_level
            FROM tbl_subjects s
            JOIN tbl_grade_level gl ON gl.id = s.grade_level_id
            WHERE s.curriculum_type = ?
        ");
        $stmt->bind_param("s", $curriculumType);
        $stmt->execute();
        $result = $stmt->get_result();

        $total = 0;
        $g11 = 0;
        $g12 = 0;
        while ($row = $result->fetch_assoc()) {
            $total++;
            if (stripos($row['grade_level'], '11') !== false)
                $g11++;
            if (stripos($row['grade_level'], '12') !== false)
                $g12++;
        }
        return ['total' => $total, 'grade11' => $g11, 'grade12' => $g12];
    }

    public function getAllSubjectsFiltered(
        string $search = '',
        string $grade = '',
        int $limit = 10,
        int $offset = 0,
        string $strand = '',
        string $curriculumType = 'legacy'
    ): array {
        $curriculumType = $curriculumType === 'new' ? 'new' : 'legacy';

        $where = ["s.curriculum_type = ?"];
        $params = [$curriculumType];
        $types = 's';

        if ($search !== '') {
            $where[] = "s.subject_name LIKE ?";
            $params[] = '%' . $search . '%';
            $types .= 's';
        }
        if ($grade !== '') {
            $where[] = "LOWER(gl.name) = ?";
            $params[] = strtolower($grade);
            $types .= 's';
        }
        if ($strand !== '') {
            $where[] = "LOWER(s.strand_group) = ?";
            $params[] = strtolower($strand);
            $types .= 's';
        }
        $whereClause = implode(' AND ', $where);

        $sql = "
            SELECT s.id, s.subject_name, s.grade_level_id, s.subject_description, s.subject_image,
                s.curriculum_type, s.strand_group,
                gl.name AS grade_level,
                COUNT(DISTINCT ta.teacher_id) AS teacher_count
            FROM tbl_subjects s
            JOIN tbl_grade_level gl ON gl.id = s.grade_level_id
            LEFT JOIN tbl_teacher_assignments ta ON ta.subject_id = s.id
            WHERE $whereClause
            GROUP BY s.id, s.subject_name, s.grade_level_id, s.subject_description, s.subject_image,
                s.curriculum_type, s.strand_group, gl.name
            ORDER BY s.id DESC
            LIMIT ? OFFSET ?
        ";
        $params[] = $limit;
        $params[] = $offset;
        $types .= 'ii';

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function countAllSubjectsFiltered(
        string $search = '',
        string $grade = '',
        string $strand = '',
        string $curriculumType = 'legacy'
    ): int {
        $curriculumType = $curriculumType === 'new' ? 'new' : 'legacy';

        $where = ["s.curriculum_type = ?"];
        $params = [$curriculumType];
        $types = 's';

        if ($search !== '') {
            $where[] = "s.subject_name LIKE ?";
            $params[] = '%' . $search . '%';
            $types .= 's';
        }
        if ($grade !== '') {
            $where[] = "LOWER(gl.name) = ?";
            $params[] = strtolower($grade);
            $types .= 's';
        }
        if ($strand !== '') {
            $where[] = "LOWER(s.strand_group) = ?";
            $params[] = strtolower($strand);
            $types .= 's';
        }
        $whereClause = implode(' AND ', $where);

        $sql = "
        SELECT COUNT(*) AS total
        FROM tbl_subjects s
        JOIN tbl_grade_level gl ON gl.id = s.grade_level_id
        WHERE $whereClause
    ";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        return (int) $stmt->get_result()->fetch_assoc()['total'];
    }

    public function getDistinctStrandGroups(string $curriculumType = 'legacy'): array
    {
        $curriculumType = $curriculumType === 'new' ? 'new' : 'legacy';

        $stmt = $this->db->prepare("
            SELECT DISTINCT strand_group
            FROM tbl_subjects
            WHERE curriculum_type = ?
              AND strand_group IS NOT NULL
              AND strand_group <> ''
            ORDER BY strand_group ASC
        ");
        $stmt->bind_param("s", $curriculumType);
        $stmt->execute();
        return array_column($stmt->get_result()->fetch_all(MYSQLI_ASSOC), 'strand_group');
    }

    public function updateSubject(
        int $id,
        string $name,
        int $gradeLevelId,
        string $subject_description = '',
        ?string $subject_image = null,
        string $curriculum_type = 'legacy',
        string $strand_group = ''
    ): void {
        $curriculum_type = $curriculum_type === 'new' ? 'new' : 'legacy';

        if ($subject_image) {
            $stmt = $this->db->prepare("
                UPDATE tbl_subjects
                SET subject_name = ?, grade_level_id = ?, subject_description = ?, subject_image = ?,
                    curriculum_type = ?, strand_group = ?, updated_at = NOW()
                WHERE id = ?
            ");
            // s      i               s                     s               s                s              i
            $stmt->bind_param(
                "sissssi",
                $name,
                $gradeLevelId,
                $subject_description,
                $subject_image,
                $curriculum_type,
                $strand_group,
                $id
            );
        } else {
            $stmt = $this->db->prepare("
                UPDATE tbl_subjects
                SET subject_name = ?, grade_level_id = ?, subject_description = ?,
                    curriculum_type = ?, strand_group = ?, updated_at = NOW()
                WHERE id = ?
            ");
            $stmt->bind_param(
                "sisssi",
                $name,
                $gradeLevelId,
                $subject_description,
                $curriculum_type,
                $strand_group,
                $id
            );
        }
        $stmt->execute();
    }

    public function deleteSubject(int $id): bool
    {
        $check = $this->db->prepare("SELECT COUNT(*) AS cnt FROM tbl_teacher_assignments WHERE subject_id = ?");
        $check->bind_param("i", $id);
        $check->execute();
        $count = (int) $check->get_result()->fetch_assoc()['cnt'];
        if ($count > 0) {
            return false;
        }
        $stmt = $this->db->prepare("DELETE FROM tbl_subjects WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return true;
    }

    public function getCoreSubjects(int $gradeLevelId, string $curriculumType = 'legacy'): array
    {
        $curriculumType = $curriculumType === 'new' ? 'new' : 'legacy';
        $stmt = $this->db->prepare("
        SELECT id, subject_name, subject_description, subject_image
        FROM tbl_subjects
        WHERE grade_level_id = ? AND curriculum_type = ? AND is_core = 1
        ORDER BY subject_name ASC
    ");
        $stmt->bind_param("is", $gradeLevelId, $curriculumType);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getElectiveSubjectsGrouped(int $gradeLevelId, string $curriculumType, int $sectionId): array
    {
        // $gradeLevelId / $curriculumType are kept in the signature so
        // existing controller calls don't need to change, but they're no
        // longer used to filter here.
        $stmt = $this->db->prepare("
        SELECT DISTINCT s.id, s.subject_name, s.subject_description, s.subject_image,
               COALESCE(NULLIF(s.strand_group, ''), 'Uncategorized') AS strand_group,
               COALESCE(a.is_enabled, 0) AS is_enabled
        FROM tbl_teacher_assignments ta
        JOIN tbl_subjects s ON s.id = ta.subject_id
        LEFT JOIN tbl_subject_section_access a
            ON a.subject_id = s.id AND a.section_id = ta.section_id
        WHERE ta.section_id = ? AND s.is_core = 0
        ORDER BY strand_group ASC, s.subject_name ASC
    ");
        $stmt->bind_param("i", $sectionId);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        $grouped = [];
        foreach ($rows as $row) {
            $key = $row['strand_group'];
            if (!isset($grouped[$key])) {
                $grouped[$key] = ['strand_group' => $key, 'subjects' => []];
            }
            $row['is_enabled'] = (int) $row['is_enabled'];
            $grouped[$key]['subjects'][] = $row;
        }
        return array_values($grouped);
    }

    public function getElectiveCountsForSection(int $gradeLevelId, string $curriculumType, int $sectionId): array
    {
        $stmt = $this->db->prepare("
        SELECT COUNT(DISTINCT ta.subject_id) AS total
        FROM tbl_teacher_assignments ta
        JOIN tbl_subjects s ON s.id = ta.subject_id
        WHERE ta.section_id = ? AND s.is_core = 0
    ");
        $stmt->bind_param("i", $sectionId);
        $stmt->execute();
        $total = (int) $stmt->get_result()->fetch_assoc()['total'];

        $stmt2 = $this->db->prepare("
        SELECT COUNT(DISTINCT a.subject_id) AS enabled
        FROM tbl_subject_section_access a
        JOIN tbl_teacher_assignments ta
            ON ta.subject_id = a.subject_id AND ta.section_id = a.section_id
        JOIN tbl_subjects s ON s.id = a.subject_id
        WHERE a.section_id = ? AND a.is_enabled = 1 AND s.is_core = 0
    ");
        $stmt2->bind_param("i", $sectionId);
        $stmt2->execute();
        $enabled = (int) $stmt2->get_result()->fetch_assoc()['enabled'];

        return ['enabled' => $enabled, 'total' => $total];
    }

    public function setSubjectAccess(int $sectionId, int $subjectId, bool $enabled): void
    {
        $isEnabled = $enabled ? 1 : 0;
        $stmt = $this->db->prepare("
        INSERT INTO tbl_subject_section_access (section_id, subject_id, is_enabled)
        VALUES (?, ?, ?)
        ON DUPLICATE KEY UPDATE is_enabled = VALUES(is_enabled)
    ");
        $stmt->bind_param("iii", $sectionId, $subjectId, $isEnabled);
        $stmt->execute();
    }

    public function bulkSetStrandAccess(int $sectionId, string $strandGroup, string $curriculumType, bool $enabled): void
    {
        $isEnabled = $enabled ? 1 : 0;
 
        $stmt = $this->db->prepare("
        SELECT DISTINCT s.id
        FROM tbl_teacher_assignments ta
        JOIN tbl_subjects s ON s.id = ta.subject_id
        WHERE ta.section_id = ? AND s.is_core = 0
          AND COALESCE(NULLIF(s.strand_group,''), 'Uncategorized') = ?
    ");
        $stmt->bind_param("is", $sectionId, $strandGroup);
        $stmt->execute();
        $subjectIds = array_column($stmt->get_result()->fetch_all(MYSQLI_ASSOC), 'id');
        if (empty($subjectIds))
            return;
 
        $insert = $this->db->prepare("
        INSERT INTO tbl_subject_section_access (section_id, subject_id, is_enabled)
        VALUES (?, ?, ?)
        ON DUPLICATE KEY UPDATE is_enabled = VALUES(is_enabled)
    ");
        foreach ($subjectIds as $subjectId) {
            $insert->bind_param("iii", $sectionId, $subjectId, $isEnabled);
            $insert->execute();
        }
    }

    // Use this wherever students currently fetch their subject list,
// so the toggle actually affects what they see.
    public function getVisibleSubjectsForStudent(int $gradeLevelId, int $sectionId, string $curriculumType = 'legacy'): array
    {
        $curriculumType = $curriculumType === 'new' ? 'new' : 'legacy';
        $stmt = $this->db->prepare("
        SELECT s.*
        FROM tbl_subjects s
        LEFT JOIN tbl_subject_section_access a
            ON a.subject_id = s.id AND a.section_id = ?
        WHERE s.grade_level_id = ? AND s.curriculum_type = ?
          AND (s.is_core = 1 OR COALESCE(a.is_enabled, 0) = 1)
        ORDER BY s.id ASC
    ");
        $stmt->bind_param("iis", $sectionId, $gradeLevelId, $curriculumType);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}