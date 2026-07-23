function hideAllGrades($container) {
    for(let $students of $container.querySelectorAll('.grade')) {
        $students.classList.add('collapse');
    }
}

document.addEventListener('DOMContentLoaded', () => {
    let $container = document.querySelector('.studentselector');

    let $gradeContainer = $container.querySelector('.grades');
    let $backButton = $container.querySelector('.back-btn');

    $backButton.classList.add('collapse');

    $backButton.addEventListener('click', (ev) => {
        ev.preventDefault();

        hideAllGrades($container);
        $gradeContainer.classList.remove('collapse');
        $backButton.classList.add('collapse');
    });

    for(let $studentButton of $container.querySelectorAll('[data-selectstudent]')) {
        $studentButton.addEventListener('click', (ev) => {
            ev.preventDefault();

            let studentId = $studentButton.getAttribute('data-selectstudent');
            let $targetSelect = document.querySelector($studentButton.getAttribute('data-target'));

            $targetSelect.tomselect.setValue(studentId);
        });
    }

    for(let $gradeButton of $gradeContainer.querySelectorAll('[data-selectgrade]')) {
        $gradeButton.addEventListener('click', (ev) => {
            ev.preventDefault();

            let grade = $gradeButton.getAttribute('data-selectgrade');

            hideAllGrades($container);

            // Hide grades
            $gradeContainer.classList.add('collapse');
            // Show back button
            $backButton.classList.remove('collapse');


            // Only show specified class
            let $grade = $container.querySelector('#grade-' + grade);
            if($grade !== null) {
                $grade.classList.remove('collapse');
            }
        });
    }
});
