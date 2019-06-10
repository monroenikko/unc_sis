<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'HomePageController@home_page')->name('home_page');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/transcript-of-record', 'TranscriptOfRecordController@tor')->name('tor');

/*
|About SJA Pages --------------------------------------------------------------------------
*/

Route::get('/school-profile', 'AboutController@school_profile')->name('school_profile');
Route::get('/vision-mission', 'AboutController@vision_mission')->name('vision_mission');
Route::get('/history', 'AboutController@history')->name('history');
Route::get('/hymn', 'AboutController@hymn')->name('hymn');
Route::get('/award-and-recognition', 'AboutController@award_recognition')->name('award_recognition');
Route::get('/administration-and-offices', 'AboutController@administration_offices')->name('administration_offices');
Route::get('/faculty-and-staff', 'AboutController@faculty_staff')->name('faculty_staff');

/*
|Academic Pages --------------------------------------------------------------------------
*/

Route::get('/junior-high', 'AcademicController@junior_high')->name('junior_high');
Route::get('/senior-high', 'AcademicController@senior_high')->name('senior_high');

/*
|Students Pages --------------------------------------------------------------------------
*/

Route::get('/students-organizations', 'StudentsController@students_organizations')->name('students_organizations');
Route::get('/students-services', 'StudentsController@students_services')->name('students_services');
Route::get('/publication', 'StudentsController@publication')->name('publication');
Route::get('/students-council', 'StudentsController@students_council')->name('students_council');
Route::get('/students-handbook', 'StudentsController@students_handbook')->name('students_handbook');

Route::group(['prefix' => 'finance', 'middleware' => ['auth', 'userroles'], 'roles' => ['finance']], function () {
    Route::get('dashboard', 'Finance\FinanceDashboardController@index')->name('finance.dashboard');

    Route::group(['prefix' => 'maintenance'], function () {

        Route::group(['prefix' => 'tuition-fee'], function () {
            Route::get('', 'Finance\Maintenance\TuitionFeeController@index')->name('finance.maintenance.tuition_fee');
            Route::post('', 'Finance\Maintenance\TuitionFeeController@index')->name('finance.maintenance.tuition_fee');
            Route::post('modal-data', 'Finance\Maintenance\TuitionFeeController@modal_data')->name('finance.maintenance.tuition_fee.modal_data');
            Route::post('save-data', 'Finance\Maintenance\TuitionFeeController@save_data')->name('finance.maintenance.tuition_fee.save_data');
            Route::post('deactivate-data', 'Finance\Maintenance\TuitionFeeController@deactivate_data')->name('finance.maintenance.tuition_fee.deactivate_data');
            Route::post('toggle-current-sy', 'Finance\Maintenance\TuitionFeeController@toggle_current_sy')->name('finance.maintenance.tuition_fee.toggle_current_sy');
        });

        Route::group(['prefix' => 'miscelleneous-fee'], function () {
            Route::get('', 'Finance\Maintenance\MiscelleneousFeeController@index')->name('finance.maintenance.misc_fee');
            Route::post('', 'Finance\Maintenance\MiscelleneousFeeController@index')->name('finance.maintenance.misc_fee');
            Route::post('modal-data', 'Finance\Maintenance\MiscelleneousFeeController@modal_data')->name('finance.maintenance.misc_fee.modal_data');
            Route::post('save-data', 'Finance\Maintenance\MiscelleneousFeeController@save_data')->name('finance.maintenance.misc_fee.save_data');
            Route::post('deactivate-data', 'Finance\Maintenance\MiscelleneousFeeController@deactivate_data')->name('finance.maintenance.misc_fee.deactivate_data');
           Route::post('toggle-current-sy', 'Finance\Maintenance\MiscelleneousFeeController@toggle_current_sy')->name('finance.maintenance.misc_fee.toggle_current_sy');
        });

        Route::group(['prefix' => 'discount-fee'], function () {
            Route::get('', 'Finance\Maintenance\DiscountFeeController@index')->name('finance.maintenance.disc_fee');
            Route::post('', 'Finance\Maintenance\DiscountFeeController@index')->name('finance.maintenance.disc_fee');
            Route::post('modal-data', 'Finance\Maintenance\DiscountFeeController@modal_data')->name('finance.maintenance.disc_fee.modal_data');
            Route::post('save-data', 'Finance\Maintenance\DiscountFeeController@save_data')->name('finance.maintenance.disc_fee.save_data');
            Route::post('deactivate-data', 'Finance\Maintenance\DiscountFeeController@deactivate_data')->name('finance.maintenance.disc_fee.deactivate_data');
           Route::post('toggle-current-sy', 'Finance\Maintenance\DiscountFeeController@toggle_current_sy')->name('finance.maintenance.disc_fee.toggle_current_sy');
        });

        Route::group(['prefix' => 'monthly-fee'], function () {
            Route::get('', 'Finance\Maintenance\MonthlyFeeController@index')->name('finance.maintenance.monthly_fee');
            Route::post('', 'Finance\Maintenance\MonthlyFeeController@index')->name('finance.maintenance.monthly_fee');
            Route::post('modal-data', 'Finance\Maintenance\MonthlyFeeController@modal_data')->name('finance.maintenance.monthly_fee.modal_data');
            Route::post('save-data', 'Finance\Maintenance\MonthlyFeeController@save_data')->name('finance.maintenance.disc_fee.save_data');
            Route::post('deactivate-data', 'Finance\Maintenance\MonthlyFeeController@deactivate_data')->name('finance.maintenance.disc_fee.deactivate_data');
           Route::post('toggle-current-sy', 'Finance\Maintenance\MonthlyFeeController@toggle_current_sy')->name('finance.maintenance.disc_fee.toggle_current_sy');
        });
        
    });
});

// Route::group(['prefix' => 'finance','middleware' => ['auth', 'userroles'], 'roles' => ['finance']], function () {

    
// });

Route::group(['prefix' => 'registrar', 'middleware' => ['auth', 'userroles'], 'roles' => ['registrar']], function() {
    Route::get('dashboard', 'Registrar\RegistrarDashboardController@index')->name('registrar.dashboard');

    Route::group(['prefix' => 'my-account', 'middleware' => ['auth']], function() {
        Route::get('', 'Registrar\UserProfileController@view_my_profile')->name('registrar.my_account.index');
        // Route::post('change-my-password', 'Registrar\UserProfileController@change_my_password')->name('my_account.change_my_password');
        Route::post('update-profile', 'Registrar\UserProfileController@update_profile')->name('registrar.my_account.update_profile');
        Route::post('fetch-profile', 'Registrar\UserProfileController@fetch_profile')->name('registrar.my_account.fetch_profile');
        Route::post('change-my-photo', 'Registrar\UserProfileController@change_my_photo')->name('registrar.my_account.change_my_photo');
        Route::post('change-my-password', 'Registrar\UserProfileController@change_my_password')->name('registrar.my_account.change_my_password');
    });

    
    Route::group(['prefix' => 'student-grade-sheet'], function() {
        Route::get('', 'Registrar\GradeSheetController@index')->name('registrar.student_grade_sheet');
        Route::post('list-class-subject-details', 'Registrar\GradeSheetController@list_class_subject_details')->name('registrar.student_grade_sheet.list_class_subject_details');
        Route::post('list-students-by-class', 'Registrar\GradeSheetController@list_students_by_class')->name('registrar.student_grade_sheet.list_students_by_class');
    });
    
});

Route::group(['prefix' => 'registrar/class-details', 'middleware' => 'auth', 'roles' => ['admin', 'root', 'registrar']], function() {
    Route::get('', 'Registrar\ClassListController@index')->name('registrar.class_details');
    Route::post('', 'Registrar\ClassListController@index')->name('registrar.class_details');
    Route::post('modal-data', 'Registrar\ClassListController@modal_data')->name('registrar.class_details.modal_data');
    Route::post('save-data', 'Registrar\ClassListController@save_data')->name('registrar.class_details.save_data');
    Route::post('deactivate-data', 'Registrar\ClassListController@deactivate_data')->name('registrar.class_details.deactivate_data');
    Route::post('fetch_section-by-grade-level', 'Registrar\ClassListController@fetch_section_by_grade_level')->name('registrar.class_details.fetch_section_by_grade_level');
});

Route::group(['prefix' => 'registrar/class-subjects/{class_id}', 'middleware' => 'auth'], function() {
    Route::get('', 'Registrar\ClassSubjectsController@index')->name('registrar.class_subjects');
    Route::post('', 'Registrar\ClassSubjectsController@index')->name('registrar.class_subjects');
    Route::post('modal-data', 'Registrar\ClassSubjectsController@modal_data')->name('registrar.class_subjects.modal_data');
    Route::post('save-data', 'Registrar\ClassSubjectsController@save_data')->name('registrar.class_subjects.save_data');
    Route::post('deactivate-data', 'Registrar\ClassSubjectsController@deactivate_data')->name('registrar.class_subjects.deactivate_data');
});

Route::group(['prefix' => 'registrar/student-enrollment/{id}', 'middleware' => ['auth'], 'roles' => ['admin', 'root', 'registrar']], function() {
    Route::get('', 'Registrar\StudentEnrollmentController@index')->name('registrar.student_enrollment');
    Route::post('', 'Registrar\StudentEnrollmentController@index')->name('registrar.student_enrollment');
    Route::post('modal-data', 'Registrar\StudentEnrollmentController@modal_data')->name('registrar.student_enrollment.modal_data');
    Route::post('save-data', 'Registrar\StudentEnrollmentController@save_data')->name('registrar.student_enrollment.save_data');
    Route::post('enroll-student', 'Registrar\StudentEnrollmentController@enroll_student')->name('registrar.student_enrollment.enroll_student');
    Route::post('re-enroll-student', 'Registrar\StudentEnrollmentController@re_enroll_student')->name('registrar.student_enrollment.re_enroll_student');
    Route::post('re-enroll-student-all', 'Registrar\StudentEnrollmentController@re_enroll_student_all')->name('registrar.student_enrollment.re_enroll_student_all');
    Route::post('enrolled-student', 'Registrar\StudentEnrollmentController@fetch_enrolled_student')->name('registrar.student_enrollment.fetch_enrolled_student');
    Route::post('cancel-enroll-student', 'Registrar\StudentEnrollmentController@cancel_enroll_student')->name('registrar.student_enrollment.cancel_enroll_student');
    Route::get('print-enrolled-students', 'Registrar\StudentEnrollmentController@print_enrolled_students')->name('registrar.student_enrollment.print_enrolled_students');
});

Route::group(['prefix' => 'faculty', 'middleware' => ['auth', 'userroles'], 'roles' => ['faculty']], function() {
    
    Route::get('dashboard', 'Faculty\FacultyDashboardController@index')->name('faculty.dashboard');
    
    Route::group(['prefix' => 'subject-class'], function() {
        Route::get('', 'Faculty\SubjectClassController@index')->name('faculty.subject_class');
        Route::post('list-class-subject-details', 'Faculty\SubjectClassController@list_class_subject_details')->name('faculty.subject_class.list_class_subject_details');
        Route::post('list-students-by-class', 'Faculty\SubjectClassController@list_students_by_class')->name('faculty.subject_class.list_students_by_class');
        Route::get('list-students-by-class-print', 'Faculty\SubjectClassController@list_students_by_class_print')->name('faculty.subject_class.list_students_by_class_print');
    });

    Route::group(['prefix' => 'class-schedules'], function() {
        Route::get('', 'Faculty\SubjectClassController@class_schedules')->name('faculty.faculty_class_schedules');
        Route::get('class-schedules-print', 'Faculty\SubjectClassController@class_schedules_print')->name('faculty.faculty_class_schedules.class_schedules_print');
    });
    
    Route::group(['prefix' => 'student-grade-sheet'], function() {
        Route::get('', 'Faculty\GradeSheetController@index')->name('faculty.student_grade_sheet');
        Route::post('list-class-subject-details', 'Faculty\GradeSheetController@list_class_subject_details')->name('faculty.student_grade_sheet.list_class_subject_details');
        Route::post('list-students-by-class', 'Faculty\GradeSheetController@list_students_by_class')->name('faculty.student_grade_sheet.list_students_by_class');
        Route::post('save-grade', 'Faculty\GradeSheetController@save_grade')->name('faculty.student_grade_sheet.save_grade');
        Route::post('temporary-save-grade', 'Faculty\GradeSheetController@temporary_save_grade')->name('faculty.student_grade_sheet.temporary_save_grade');
        Route::post('finalize-grade', 'Faculty\GradeSheetController@finalize_grade')->name('faculty.student_grade_sheet.finalize_grade');
        Route::get('list-students-by-class-print', 'Faculty\GradeSheetController@list_students_by_class_print')->name('faculty.student_grade_sheet.list_students_by_class_print');    
    
        Route::post('semester', 'Faculty\GradeSheetController@semester')->name('faculty.student_grade_sheet.semester');
        Route::post('list-class-subject-details1', 'Faculty\GradeSheetController@list_class_subject_details1')->name('faculty.student_grade_sheet.list_class_subject_details1');
        Route::post('list-students-by-class1', 'Faculty\GradeSheetController@list_students_by_class1')->name('faculty.student_grade_sheet.list_students_by_class1');
        Route::get('list-students-by-class-senior-print', 'Faculty\GradeSheetController@list_students_by_class_print_senior')->name('faculty.student_grade_sheet.list_students_by_class_print1');    
    
    });



    Route::group(['prefix' => 'my-account', 'middleware' => ['auth']], function() {
        Route::get('', 'Faculty\UserProfileController@view_my_profile')->name('faculty.my_account.index');
        // Route::post('change-my-password', 'Faculty\UserProfileController@change_my_password')->name('my_account.change_my_password');
        Route::post('update-profile', 'Faculty\UserProfileController@update_profile')->name('faculty.my_account.update_profile');
        Route::post('fetch-profile', 'Faculty\UserProfileController@fetch_profile')->name('faculty.my_account.fetch_profile');
        Route::post('change-my-photo', 'Faculty\UserProfileController@change_my_photo')->name('faculty.my_account.change_my_photo');
        Route::post('change-my-password', 'Faculty\UserProfileController@change_my_password')->name('faculty.my_account.change_my_password');
        Route::post('educational-attainment', 'Faculty\UserProfileController@educational_attainment')->name('faculty.my_account.educational_attainment');
        Route::post('educational-attainment-save', 'Faculty\UserProfileController@educational_attainment_save')->name('faculty.my_account.educational_attainment_save');
        Route::post('educational-attainment-fetch-by-id', 'Faculty\UserProfileController@educational_attainment_fetch_by_id')->name('faculty.my_account.educational_attainment_fetch_by_id');
        Route::post('educational-attainment-delete-by-id', 'Faculty\UserProfileController@educational_attainment_delete_by_id')->name('faculty.my_account.educational_attainment_delete_by_id');
        
        Route::post('trainings-seminars', 'Faculty\UserProfileController@trainings_seminars')->name('faculty.my_account.trainings_seminars');
        Route::post('fetch-training-seminar-by-id', 'Faculty\UserProfileController@fetch_training_seminar_by_id')->name('faculty.my_account.fetch_training_seminar_by_id');
        Route::post('save-training-seminar', 'Faculty\UserProfileController@save_training_seminar')->name('faculty.my_account.save_training_seminar');
        Route::post('delete-training-seminar-by-id', 'Faculty\UserProfileController@delete_training_seminar_by_id')->name('faculty.my_account.delete_training_seminar_by_id');
        
    });

    Route::group(['prefix' => 'advisory-class'], function () {
        Route::get('', 'Faculty\AdvisoryClassController@index')->name('faculty.advisory_class.index');
        Route::get('view', 'Faculty\AdvisoryClassController@view_class_list')->name('faculty.advisory_class.view');
        Route::post('manage_attendance', 'Faculty\AdvisoryClassController@manage_attendance')->name('faculty.advisory_class.manage_attendance');
        Route::post('view_edit','Faculty\AdvisoryClassController@manage_demographic_profile')->name('faculty.advisory_class.demographic_profile');
        Route::post('save_attendance', 'Faculty\AdvisoryClassController@save_attendance')->name('faculty.advisory_class.save_attendance');
        
        Route::get('print-class-grades', 'Faculty\AdvisoryClassController@print_student_class_grades')->name('faculty.AdvisoryClass.print_grades');
       
    });

    Route::group(['prefix' => 'my-advisory-class'], function () {
        Route::get('', 'Faculty\MyAdvisoryClassController@index')->name('faculty.my_advisory_class.index');
        Route::post('first-quarter', 'Faculty\MyAdvisoryClassController@firstquarter')->name('faculty.MyAdvisoryClass.firstquarter');
        Route::post('second-quarter', 'Faculty\MyAdvisoryClassController@secondquarter')->name('faculty.MyAdvisoryClass.secondquarter');
        Route::post('third-quarter', 'Faculty\MyAdvisoryClassController@thirdquarter')->name('faculty.MyAdvisoryClass.thirdquarter');
        Route::post('fourth-quarter', 'Faculty\MyAdvisoryClassController@fourthquarter')->name('faculty.MyAdvisoryClass.fourthquarter');
        Route::get('print-first-quarter', 'Faculty\MyAdvisoryClassController@print_firstquarter')->name('faculty.MyAdvisoryClass.print_first_quarter');
        Route::get('print-second-quarter', 'Faculty\MyAdvisoryClassController@print_secondquarter')->name('faculty.MyAdvisoryClass.print_second_quarter');
        Route::get('print-third-quarter', 'Faculty\MyAdvisoryClassController@print_thirdquarter')->name('faculty.MyAdvisoryClass.print_third_quarter');
        Route::get('print-fourth-quarter', 'Faculty\MyAdvisoryClassController@print_fourthquarter')->name('faculty.MyAdvisoryClass.print_fourth_quarter');

        Route::post('list-class-subject-details', 'Faculty\MyAdvisoryClassController@list_class_subject_details')->name('faculty.MyAdvisoryClass.list_class_subject_details');

        Route::post('list_quarter-details', 'Faculty\MyAdvisoryClassController@list_quarter')->name('faculty.MyAdvisoryClass.list_quarter');
        Route::post('list_quarter-sem-details', 'Faculty\MyAdvisoryClassController@list_quarter_sem')->name('faculty.MyAdvisoryClass.list_quarter-sem-details');

        Route::post('first_sem_1quarter', 'Faculty\MyAdvisoryClassController@first_sem_1quarter')->name('faculty.MyAdvisoryClass.first_sem_1quarter');
        Route::post('first_sem_2quarter', 'Faculty\MyAdvisoryClassController@first_sem_2quarter')->name('faculty.MyAdvisoryClass.first_sem_2quarter');
        Route::post('second_sem_1quarter', 'Faculty\MyAdvisoryClassController@first_sem_3quarter')->name('faculty.MyAdvisoryClass.first_sem_3quarter');
        Route::post('second_sem_2quarter', 'Faculty\MyAdvisoryClassController@first_sem_4quarter')->name('faculty.MyAdvisoryClass.first_sem_4quarter');

        Route::get('first-sem/print-first-quarter', 'Faculty\MyAdvisoryClassController@print_firstSem_1quarter')->name('faculty.MyAdvisoryClass.print_firstSem_firstq');
        Route::get('first-sem/print-second-quarter', 'Faculty\MyAdvisoryClassController@print_firstSem_2quarter')->name('faculty.MyAdvisoryClass.print_firstSem_secondq');
        Route::get('second-sem/print-first-quarter', 'Faculty\MyAdvisoryClassController@print_secondSem_1quarter')->name('faculty.MyAdvisoryClass.print_secondSem_firstq');
        Route::get('second-sem/print-second-quarter', 'Faculty\MyAdvisoryClassController@print_secondSem_2quarter')->name('faculty.MyAdvisoryClass.print_secondSem_secondq');

        //average for junior
        Route::post('Junior/GradeSheet_Average', 'Faculty\MyAdvisoryClassController@gradeSheetAverage')->name('faculty.Average');
        Route::get('Junior/first_second_Average/print', 'Faculty\MyAdvisoryClassController@firstSecondGradeSheetAverage_print')->name('faculty.MyAdvisoryClass.first_second_print_average');
        Route::get('Junior/first_third_Average/print', 'Faculty\MyAdvisoryClassController@firstThirdGradeSheetAverage_print')->name('faculty.MyAdvisoryClass.first_third_print_average');
        Route::get('Junior/first_fourth_Average/print', 'Faculty\MyAdvisoryClassController@firstFourthGradeSheetAverage_print')->name('faculty.MyAdvisoryClass.first_fourth_print_average');

        //average for senior
        Route::post('Senior/first_sem/GradeSheet_Average', 'Faculty\MyAdvisoryClassController@seniorFirstSemGradeSheetAverage')->name('faculty.Average_Senior');
        Route::post('Senior/second_sem/GradeSheet_Average', 'Faculty\MyAdvisoryClassController@seniorSecondSemGradeSheetAverage')->name('faculty.Average_Senior_Second_Sem');
        
        Route::get('Senior/first_sem_Average/print', 'Faculty\MyAdvisoryClassController@first_sem_GradeSheetAverage_print')->name('faculty.MyAdvisoryClass.first_sem_print_average');
        Route::get('Senior/second_sem_Average/print', 'Faculty\MyAdvisoryClassController@second_sem_GradeSheetAverage_print')->name('faculty.MyAdvisoryClass.second_sem_print_average');
        Route::get('Senior/Final_Average/print', 'Faculty\MyAdvisoryClassController@finalGradeSheetAverage_print')->name('faculty.MyAdvisoryClass.final_print_average');

    });

    Route::group(['prefix' => 'class-attendance'], function () {
        Route::get('encode-class-attendance', 'Faculty\ClassAttendanceController@index')->name('faculty.class-attendance.index');
        Route::post('encode-class-attendance', 'Faculty\ClassAttendanceController@index')->name('faculty.class-attendance.index');
        Route::post('', 'Faculty\ClassAttendanceController@save_attendance')->name('faculty.save_class_attendance');
        Route::post('first_sem/save', 'Faculty\ClassAttendanceController@save_attendance_senior_first')->name('faculty.save_attendance_senior_first');
        Route::post('second_sem/save', 'Faculty\ClassAttendanceController@save_attendance_senior_second')->name('faculty.save_attendance_senior_second');
        Route::get('print-class-attendance', 'Faculty\ClassAttendanceController@print_attendance')->name('faculty.print_attendance');
    });

    Route::group(['prefix' => 'encode_remarks'], function () {
        Route::get('encode-class-remarks', 'Faculty\EncodeRemarkController@index')->name('faculty.encode-remarks.index');
        Route::post('encode-class-remarks', 'Faculty\EncodeRemarkController@index')->name('faculty.encode-remarks.index');

        Route::post('', 'Faculty\EncodeRemarkController@save')->name('faculty.encode-remarks.save');
        // Route::get('print-class-grades', 'Faculty\EncodeRemarkController@print_student_class_grades')->name('faculty.encode-remarks.print_grades');
    });
    
    Route::group(['prefix' => 'class-demographic-profile'], function () {
        Route::get('encode-class-demographic-profile', 'Faculty\DemographicProfileController@index')->name('faculty.class_demographic_profile.index');
        Route::post('', 'Faculty\DemographicProfileController@save')->name('faculty.save_demographic');
    });



    Route::group(['prefix' => 'data-student'], function (){
        Route::get('create-data-grades', 'Faculty\GradeSheetController@view_student_data')->name('faculty.DataStudent');
        Route::post('section-list', 'Faculty\GradeSheetController@list_class_section')->name('faculty.SectionList');
        // Route::get('section-list', 'Faculty\GradeSheetController@list_class_section')->name('faculty.SectionList');\
    });

});


Route::group(['prefix' => 'admin/student-information', 'middleware' => ['auth', 'userroles'], 'roles' => ['admin', 'root', 'registrar']], function() {
    Route::get('', 'Control_Panel\StudentController@index')->name('admin.student.information');
    Route::post('', 'Control_Panel\StudentController@index')->name('admin.student.information');
    Route::post('modal-data', 'Control_Panel\StudentController@modal_data')->name('admin.student.information.modal_data');
    Route::get('modal-data', 'Control_Panel\StudentController@modal_data')->name('admin.student.information.modal_data');
    Route::post('save-data', 'Control_Panel\StudentController@save_data')->name('admin.student.information.save_data');
    Route::post('deactivate-data', 'Control_Panel\StudentController@deactivate_data')->name('admin.student.information.deactivate_data');
    Route::post('print-student-grade-modal', 'Control_Panel\StudentController@print_student_grade_modal')->name('admin.student.information.print_student_grade_modal');
    
    Route::get('print-student-grades', 'Control_Panel\StudentController@print_student_grades')->name('admin.student.information.print_student_grades');
    Route::post('change-student-photo', 'Control_Panel\StudentController@change_my_photo')->name('admin.student.change_my_photo');
});

Route::group(['prefix' => 'admin/faculty-information', 'middleware' => ['auth', 'userroles'], 'roles' => ['admin', 'root', 'registrar']], function() {
    Route::get('', 'Control_Panel\FacultyController@index')->name('admin.faculty_information');
    Route::post('', 'Control_Panel\FacultyController@index')->name('admin.faculty_information');
    Route::post('modal-data', 'Control_Panel\FacultyController@modal_data')->name('admin.faculty_information.modal_data');
    Route::post('save-data', 'Control_Panel\FacultyController@save_data')->name('admin.faculty_information.save_data');
    Route::post('deactivate-data', 'Control_Panel\FacultyController@deactivate_data')->name('admin.faculty_information.deactivate_data');
    Route::post('additional-information', 'Control_Panel\FacultyController@additional_information')->name('admin.faculty_information.additional_information');

     Route::post('e-signature', 'Control_Panel\FacultyController@change_esignature')->name('admin.faculty.e_signature');
});

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'userroles'], 'roles' => ['admin', 'root']], function() {
    Route::get('dashboard', 'Control_Panel\DashboardController@index')->name('admin.dashboard');

    Route::group(['prefix' => 'registrar-information'], function() {
        Route::get('', 'Control_Panel\RegistrarController@index')->name('admin.registrar_information');
        Route::post('', 'Control_Panel\RegistrarController@index')->name('admin.registrar_information');
        Route::post('modal-data', 'Control_Panel\RegistrarController@modal_data')->name('admin.registrar_information.modal_data');
        Route::post('save-data', 'Control_Panel\RegistrarController@save_data')->name('admin.registrar_information.save_data');
        Route::post('deactivate-data', 'Control_Panel\RegistrarController@deactivate_data')->name('admin.registrar_information.deactivate_data');
    });

    Route::group(['prefix' => 'finance-information'], function() {
        Route::get('', 'Control_Panel\FinanceController@index')->name('admin.finance_information');
        Route::post('', 'Control_Panel\FinanceController@index')->name('admin.finance_information');
        Route::post('modal-data', 'Control_Panel\FinanceController@modal_data')->name('admin.finance_information.modal_data');
        Route::post('save-data', 'Control_Panel\FinanceController@save_data')->name('admin.finance_information.save_data');
        Route::post('deactivate-data', 'Control_Panel\FinanceController@deactivate_data')->name('admin.finance_information.deactivate_data');
    });
    
    Route::group(['prefix' => 'transcript-of-record-archieve'], function() {
        Route::get('', 'Control_Panel\TranscriptArchiveController@index')->name('admin.transcript_archieve');
        Route::post('', 'Control_Panel\TranscriptArchiveController@index')->name('admin.transcript_archieve');
        Route::post('modal-data', 'Control_Panel\TranscriptArchiveController@modal_data')->name('admin.transcript_archieve.modal_data');
        Route::post('save-transcript', 'Control_Panel\TranscriptArchiveController@save_transcript')->name('admin.transcript_archieve.save_transcript');
        Route::post('delete-data', 'Control_Panel\TranscriptArchiveController@delete_data')->name('admin.transcript_archieve.delete_data');
        Route::post('download-tor', 'Control_Panel\TranscriptArchiveController@download_tor')->name('admin.transcript_archieve.download_tor');
    });
    
    Route::group(['prefix' => 'articles'], function() {
        Route::get('', 'Control_Panel\ArticlesController@index')->name('admin.articles');
        Route::post('', 'Control_Panel\ArticlesController@index')->name('admin.articles');
        Route::post('modal-data', 'Control_Panel\ArticlesController@modal_data')->name('admin.articles.modal_data');
        Route::post('save-data', 'Control_Panel\ArticlesController@save_data')->name('admin.articles.save_data');
    });
    
    Route::group(['prefix' => 'maintenance'], function() {
        Route::group(['prefix' => 'school-year'], function() {
            Route::get('', 'Control_Panel\Maintenance\SchoolYearController@index')->name('admin.maintenance.school_year');
            Route::post('', 'Control_Panel\Maintenance\SchoolYearController@index')->name('admin.maintenance.school_year');
            Route::post('modal-data', 'Control_Panel\Maintenance\SchoolYearController@modal_data')->name('admin.maintenance.school_year.modal_data');
            Route::post('save-data', 'Control_Panel\Maintenance\SchoolYearController@save_data')->name('admin.maintenance.school_year.save_data');
            Route::post('deactivate-data', 'Control_Panel\Maintenance\SchoolYearController@deactivate_data')->name('admin.maintenance.school_year.deactivate_data');
            Route::post('toggle-current-sy', 'Control_Panel\Maintenance\SchoolYearController@toggle_current_sy')->name('admin.maintenance.school_year.toggle_current_sy');
        });

        Route::group(['prefix' => 'semester'], function () {
            Route::get('', 'Control_Panel\Maintenance\SemesterController@index')->name('admin.maintenance.semester');
            Route::post('', 'Control_Panel\Maintenance\SemesterController@index')->name('admin.maintenance.semester');
            Route::post('toggle-current-sy', 'Control_Panel\Maintenance\SemesterController@toggle_current_sy')->name('admin.maintenance.semester.toggle_current_sy');
        });

        Route::group(['prefix' => 'subjects'], function() {
            Route::get('', 'Control_Panel\Maintenance\SubjectController@index')->name('admin.maintenance.subjects');
            Route::post('', 'Control_Panel\Maintenance\SubjectController@index')->name('admin.maintenance.subjects');
            Route::post('modal-data', 'Control_Panel\Maintenance\SubjectController@modal_data')->name('admin.maintenance.subjects.modal_data');
            Route::post('save-data', 'Control_Panel\Maintenance\SubjectController@save_data')->name('admin.maintenance.subjects.save_data');
            Route::post('deactivate-data', 'Control_Panel\Maintenance\SubjectController@deactivate_data')->name('admin.maintenance.subjects.deactivate_data');
        });
        
        Route::group(['prefix' => 'class-rooms'], function() {
            Route::get('', 'Control_Panel\Maintenance\RoomController@index')->name('admin.maintenance.classrooms');
            Route::post('', 'Control_Panel\Maintenance\RoomController@index')->name('admin.maintenance.classrooms');
            Route::post('modal-data', 'Control_Panel\Maintenance\RoomController@modal_data')->name('admin.maintenance.classrooms.modal_data');
            Route::post('save-data', 'Control_Panel\Maintenance\RoomController@save_data')->name('admin.maintenance.classrooms.save_data');
            Route::post('deactivate-data', 'Control_Panel\Maintenance\RoomController@deactivate_data')->name('admin.maintenance.classrooms.deactivate_data');
        });

        Route::group(['prefix' => 'section-details'], function() {
            Route::get('', 'Control_Panel\Maintenance\SectionController@index')->name('admin.maintenance.section_details');
            Route::post('', 'Control_Panel\Maintenance\SectionController@index')->name('admin.maintenance.section_details');
            Route::post('modal-data', 'Control_Panel\Maintenance\SectionController@modal_data')->name('admin.maintenance.section_details.modal_data');
            Route::post('save-data', 'Control_Panel\Maintenance\SectionController@save_data')->name('admin.maintenance.section_details.save_data');
            Route::post('deactivate-data', 'Control_Panel\Maintenance\SectionController@deactivate_data')->name('admin.maintenance.section_details.deactivate_data');
        });

        Route::group(['prefix' => 'date-remarks'], function () {
            Route::get('', 'Control_Panel\Maintenance\DateRemarkController@index')->name('admin.maintenance.date_remarks_for_class_card');
            Route::post('', 'Control_Panel\Maintenance\DateRemarkController@index')->name('admin.maintenance.date_remarks_for_class_card');
            Route::post('save-data', 'Control_Panel\Maintenance\DateRemarkController@save_data')->name('admin.maintenance.date_remarks_for_class.save_data');
            Route::post('modal-data', 'Control_Panel\Maintenance\DateRemarkController@modal_data')->name('admin.maintenance.date_remarks_for_class.modal_data');
        });

        Route::group(['prefix' => 'strand'], function () {
            Route::get('', 'Control_Panel\Maintenance\StrandController@index')->name('admin.maintenance.strand');
            Route::post('', 'Control_Panel\Maintenance\StrandController@index')->name('admin.maintenance.strand');
            Route::post('modal-data', 'Control_Panel\Maintenance\StrandController@modal_data')->name('admin.maintenance.strand.modal_data');
            Route::post('save-data', 'Control_Panel\Maintenance\StrandController@save_data')->name('admin.maintenance.strand.save_data');
        });
    });
    
    Route::group(['prefix' => 'my-account', 'middleware' => ['auth']], function() {
        Route::get('', 'Control_Panel\UserProfileController@view_my_profile')->name('my_account.index');
        // Route::post('change-my-password', 'Control_Panel\UserProfileController@change_my_password')->name('my_account.change_my_password');
        Route::post('update-profile', 'Control_Panel\UserProfileController@update_profile')->name('my_account.update_profile');
        Route::post('fetch-profile', 'Control_Panel\UserProfileController@fetch_profile')->name('my_account.fetch_profile');
        Route::post('change-my-photo', 'Control_Panel\UserProfileController@change_my_photo')->name('my_account.change_my_photo');
        Route::post('change-my-password', 'Control_Panel\UserProfileController@change_my_password')->name('my_account.change_my_password');
    });
    

});


Route::group(['prefix' => 'shared/faculty-class-schedule', 'middleware' => ['auth', 'userroles'], 'roles' => ['admin', 'root', 'registrar']], function() {
    Route::get('', 'Control_Panel\ClassScheduleController@index')->name('shared.faculty_class_schedules.index');
    Route::post('', 'Control_Panel\ClassScheduleController@index')->name('shared.faculty_class_schedules.index');
    Route::post('get-faculty-class-schedule', 'Control_Panel\ClassScheduleController@get_faculty_class_schedule')->name('shared.faculty_class_schedules.get_faculty_class_schedule');
    Route::get('print-handled-subject', 'Control_Panel\ClassScheduleController@print_handled_subject')->name('shared.faculty_class_schedules.print_handled_subject');
    Route::get('print-handled-subject-all', 'Control_Panel\ClassScheduleController@print_handled_subject_all')->name('shared.faculty_class_schedules.print_handled_subject_all');
});


Route::group(['prefix' => 'student', 'middleware' => ['auth', 'userroles'], 'roles' => ['student']], function() {
    Route::get('dashboard', 'Control_Panel_Student\DashboardController@index')->name('student.dashboard');

    Route::group(['prefix' => 'class-schedule'], function() {
        Route::get('', 'Control_Panel_Student\ClassScheduleController@index')->name('student.class_schedule.index');
        Route::post('', 'Control_Panel_Student\ClassScheduleController@index')->name('student.class_schedule.index');
    });
    Route::group(['prefix' => 'grade-sheet'], function() {
        Route::get('', 'Control_Panel_Student\GradeSheetController@index')->name('student.grade_sheet.index');
        Route::post('', 'Control_Panel_Student\GradeSheetController@index')->name('student.grade_sheet.index');
        Route::get('print-grades', 'Control_Panel_Student\GradeSheetController@print_grades')->name('student.grade_sheet.print_grades');
    });
    
    Route::group(['prefix' => 'my-account', 'middleware' => ['auth']], function() {
        Route::get('', 'Control_Panel_Student\AccountProfileController@view_my_profile')->name('student.my_account.index');
        // Route::post('change-my-password', 'Control_Panel_Student\AccountProfileController@change_my_password')->name('my_account.change_my_password');
        Route::post('update-profile', 'Control_Panel_Student\AccountProfileController@update_profile')->name('student.my_account.update_profile');
        Route::post('fetch-profile', 'Control_Panel_Student\AccountProfileController@fetch_profile')->name('student.my_account.fetch_profile');
        Route::post('change-my-photo', 'Control_Panel_Student\AccountProfileController@change_my_photo')->name('student.my_account.change_my_photo');
        Route::post('change-my-password', 'Control_Panel_Student\AccountProfileController@change_my_password')->name('student.my_account.change_my_password');
    });
});



