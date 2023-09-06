<?php


//admin Routes End//

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//Mma Frontend Starts

// User Activities Mma
Route::get('/UserActivities', [UserActivitiesController::class, 'indexActivities'])->name('index/Activities');

// Frontend Route
Route::get('section/{key}', [UserActivitiesController::class, 'indexSection'])->name('index/Section');

//Frontend Toolkit
Route::get('section/Toolkit/{key}', [UserActivitiesController::class, 'indexSectionToolkit'])->name('index/Section');


//Header & Footer Fronend

Route::get('/section/ActivitiesHeader', function () {
    return view('layouts/Activities/ActivitiesHeader');
});

Route::get('/section/Footer', function () {
    return view('layouts/Activities/Footer');
});

Route::get('/section/Master', function () {
    return view('layouts/Activities/Master');
});

Route::get('/section/MainPagesHeader', function () {
    return view('layouts/Activities/MainPagesHeader');
});

Route::get('/section/MainPagesMaster', function () {
    return view('layouts/Activities/MainPagesMaster');
});

Route::get('/section/Footer', function () {
    return view('layouts/Activities/Footer');
});

Route::get('/section/SubPagesHeader', function () {
    return view('layouts/Activities/SubPagesHeader');
});

Route::get('/section/SubPagesMaster', function () {
    return view('layouts/Activities/SubPagesMaster');
});

Route::get('/section/QuizzesHeader', function () {
    return view('layouts/Activities/QuizzesHeader');
});

Route::get('/section/QuizzesMaster', function () {
    return view('layouts/Activities/QuizzesMaster');
});

Route::get('/section/CustomActivityHeader', function () {
    return view('layouts/Activities/Toolkit/CustomActivityHeader');
});

Route::get('/section/DashboardSummaryHeader', function () {
    return view('layouts/Activities/Toolkit/DashboardSummaryHeader');
});

Route::get('/section/EvidencingDemoHeader', function () {
    return view('layouts/Activities/Toolkit/EvidencingDemoHeader');
});

Route::get('/section/InclusionTrackerHeader', function () {
    return view('layouts/Activities/Toolkit/InclusionTrackerHeader');
});

Route::get('/section/RecordActivityHeader', function () {
    return view('layouts/Activities/Toolkit/RecordActivityHeader');
});

Route::get('/section/ReportHeader', function () {
    return view('layouts/Activities/Toolkit/ReportHeader');
});

Route::get('/section/ErrorPageHeader', function () {
    return view('layouts/Activities/ErrorPageHeader');
});
//Mma Frontend Ends
