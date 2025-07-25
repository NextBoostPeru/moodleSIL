<?php
// components/course_card.php
function renderCourseCard($course) {
    $image = $course['image'] ?? '/assets/img/default-course.jpg';
    $name = htmlspecialchars($course['fullname'], ENT_QUOTES, 'UTF-8');
    $category = htmlspecialchars($course['categoryname'], ENT_QUOTES, 'UTF-8');
    $link = "/course/view.php?id=" . (int)$course['id'];

    echo "<div class='col-md-4 mb-4'>
            <div class='card shadow-sm h-100'>
                <img src='{$image}' class='card-img-top' alt='Imagen del curso'>
                <div class='card-body'>
                    <h5 class='card-title'>{$name}</h5>
                    <p class='card-text'><small class='text-muted'>Categoría: {$category}</small></p>
                    <a href='{$link}' class='btn btn-primary btn-sm'>Ir al curso</a>
                </div>
            </div>
          </div>";
}
