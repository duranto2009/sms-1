<?php

use Illuminate\Support\Facades\Route;



Route::view('widget','admin.partials.widget');
Route::middleware(['role:admin','auth'])->prefix('dashboard')->namespace('Admin')->group(function () {
    Route::resource('admin', 'AdminController')->only('index');
    Route::resource('class', 'ClassTableController');
    Route::get('read-data/class', 'ClassTableController@readData')->name('class.readData');
    Route::resource('guardian', 'GuardianController');
    Route::get('read-guardian', 'GuardianController@readData')->name('guardian.readData');
    Route::resource('student', 'StudentController');
    Route::get('student-promotion', 'StudentController@promotion')->name('student.promotion');
    Route::get('student-promotion/update', 'StudentController@promotionUpdate')->name('promotion.update');
    Route::put('student-bulk/promotion/', 'StudentController@bulkPromotion')->name('bulk.promotion');
    Route::get('student-section', 'StudentController@section')->name('student.section');
    Route::get('student-filer', 'StudentController@filter')->name('student.filter');
    Route::get('bulk/student-admission', 'StudentController@bulk')->name('student.bulk');
    Route::post('bulkStore/student-admission', 'StudentController@bulkStore')->name('student.bulkStore');
    Route::get('csv/student-admission', 'StudentController@csv')->name('student.csv');
    Route::post('csvStore/student-admission', 'StudentController@csvStore')->name('student.csvStore');
    Route::resource('session', 'SessionYearController');
    Route::get('read-data/session', 'SessionYearController@readData')->name('session.readData');
    Route::get('session-active', 'SessionYearController@active')->name('session.active');
    Route::resource('department', 'DepartmentController');
    Route::get('read-department', 'DepartmentController@readData')->name('department.readData');

    Route::resource('teacher', 'TeacherController');
    Route::get('read-teacher', 'TeacherController@readData')->name('teacher.readData');
    Route::get('read/teacher-permission/{teacher}', 'TeacherController@getPermission')->name('teacher.getPermission');
    Route::get('/permission/teacher', 'TeacherController@readPermission')->name('teacher.readPermission');
    Route::get('/permission/filter', 'TeacherController@filter')->name('teacher.filter');
    Route::get('/permission/modify/', 'TeacherController@modifyPermision')->name('teacher.modifyPermision');
    Route::resource('accountant', 'AccountantController');
    Route::get('read-accountant', 'AccountantController@readData')->name('accountant.readData');
    Route::resource('librarian', 'LibrarianController');
    Route::get('read-librarian', 'LibrarianController@readData')->name('librarian.readData');
    Route::resource('subject', 'SubjectController');
    Route::get('read-subject', 'SubjectController@readData')->name('subject.readData');
    Route::resource('syllabus', 'SyllabusController');
    Route::get('syllabus-filter', 'SyllabusController@filter')->name('syllabus.filter');
    Route::resource('classroom', 'ClassRoomController');
    Route::get('classroom-filter', 'ClassRoomController@filter')->name('classroom.filter');

    Route::resource('routine', 'ClassRoutineController');
    Route::get('routine-filter', 'ClassRoutineController@filter')->name('routine.filter');

    Route::resource('attendance', 'AttendanceController');
    Route::get('attendance-filter', 'AttendanceController@filter')->name('attendance.filter');
    Route::get('attendance-get/student', 'AttendanceController@getStudent')->name('attendance.student');

    Route::resource('calendar', 'EventController');
    Route::get('read-events', 'EventController@readData')->name('calendar.readData');

    Route::resource('grade', 'GradeController');
    Route::get('read-get', 'GradeController@get')->name('grade.get');

    Route::resource('exam', 'ExamController');

    Route::resource('mark', 'MarkController');
    Route::get('mark_update', 'MarkController@mark_update')->name('mark_update');
    Route::get('get_grade', 'MarkController@get_grade')->name('get_grade');
    Route::get('get_student', 'MarkController@getStudent')->name('mark.student');

    //fullcalender
    // Route::get('events','EventController@index');
    // Route::post('events/create','EventController@create');
    // Route::post('events/update','EventController@update');
    // Route::post('events/delete','EventController@destroy');


});
