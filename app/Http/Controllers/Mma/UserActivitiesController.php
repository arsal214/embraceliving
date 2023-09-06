<?php

namespace App\Http\Controllers\Mma;
use App\Http\Controllers\Controller;
use App\Models\UserActivities;
use Illuminate\Http\Request;

class UserActivitiesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexActivities()
    {
        
        return view('MmaPages/Activities/UserActivities');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

                              //Activities Frontend
     public function indexSection(Request $request, $slug)
    {
        if($slug == "Mind"){
            return view('MmaPages/Activities/Mind');
        }
        else if($slug == "Body"){
            return view('MmaPages/Activities/Body');
        }
        else if($slug == "Soul"){
            return view('MmaPages/Activities/Soul');
        }
        else if($slug == "OnlineContent"){
            return view('MmaPages/Activities/OnlineContentMma');
        }
        else if($slug == "MemorySpotlight"){
            return view('MmaPages/Activities/MemorySpotlight/MemorySpotlight');
        }
        else if($slug == "HolisticSessions"){
            return view('MmaPages/Activities/HolisticSessions/HolisticSessions');
        }
        else  if($slug == "Dance"){
            return view('MmaPages/Activities/HolisticSessions/Dance');
        }
        else  if($slug == "Yoga"){
            return view('MmaPages/Activities/HolisticSessions/Yoga');
        }
        else  if($slug == "Music"){
            return view('MmaPages/Activities/HolisticSessions/Music');
        }
        else  if($slug == "Movements"){
            return view('MmaPages/Activities/HolisticSessions/Movements');
        }
        else  if($slug == "Moods"){
            return view('MmaPages/Activities/HolisticSessions/Moods');
        }
        else  if($slug == "Webcams"){
            return view('MmaPages/Activities/HolisticSessions/Webcams');
        }
        else  if($slug == "VirtualTours"){
            return view('MmaPages/Activities/HolisticSessions/VirtualTours');
        }
        else  if($slug == "CreativeMojo"){
            return view('MmaPages/Activities/HolisticSessions/CreativeMojo');
        }
        else  if($slug == "ThemedEvents"){
            return view('MmaPages/Activities/ThemedEvents/ThemedEvents');
        }
        else  if($slug == "TheBigBook"){
            return view('MmaPages/Activities/ThemedEvents/TheBigBook');
        }
        else  if($slug == "LoveLearning"){
            return view('MmaPages/Activities/ThemedEvents/LoveLearning/LoveLearning');
        }
        else  if($slug == "LiveEventPlanner"){
            return view('MmaPages/Activities/LiveEventPlanner/LiveEventPlanner');
        }
        else  if($slug == "Replays"){
            return view('MmaPages/Activities/LiveEventPlanner/Replays/Replays');
        }
        else  if($slug == "CurrentWeekReplays"){
            return view('MmaPages/Activities/LiveEventPlanner/Replays/CurrentWeekReplays');
        }
        else  if($slug == "RadioReplays"){
            return view('MmaPages/Activities/LiveEventPlanner/Replays/RadioReplays');
        }
        else  if($slug == "DanceSingReplays"){
            return view('MmaPages/Activities/LiveEventPlanner/Replays/DanceSingReplays');
        }
        else  if($slug == "LiveTour"){
            return view('MmaPages/Activities/LiveEventPlanner/Replays/LiveTour');
        }
        else  if($slug == "Submit"){
            return view('MmaPages/Activities/ThemedEvents/LoveLearning/Submit');
        }
        else  if($slug == "EmbraceReplays"){
            return view('MmaPages/Activities/LiveEventPlanner/Replays/EmbraceReplays');
        }
        else  if($slug == "EldercateReplays"){
            return view('MmaPages/Activities/LiveEventPlanner/Replays/EldercateReplays/EldercateReplays');
        }
        else  if($slug == "Photography"){
            return view('MmaPages/Activities/LiveEventPlanner/Replays/EldercateReplays/Photography');
        }
        else  if($slug == "SpanishLessons"){
            return view('MmaPages/Activities/LiveEventPlanner/Replays/EldercateReplays/Spanish');
        }
        else  if($slug == "FrenchLessons"){
            return view('MmaPages/Activities/LiveEventPlanner/Replays/EldercateReplays/French');
        }
        else  if($slug == "Monday"){
            return view('MmaPages/Activities/LiveEventPlanner/Monday');
        }
        else  if($slug == "Tuesday"){
            return view('MmaPages/Activities/LiveEventPlanner/Tuesday');
        }
        else  if($slug == "Wednesday"){
            return view('MmaPages/Activities/LiveEventPlanner/Wednesday');
        }
        else  if($slug == "Heros"){
            return view('MmaPages/Activities/Heros/Heros');
        }
        else  if($slug == "WhatuThink"){
            return view('MmaPages/Activities/Heros/WhatuThink');
        }
        else  if($slug == "HHStationery"){
            return view('MmaPages/Activities/Heros/HHStationery');
        }
        else  if($slug == "Thursday"){
            return view('MmaPages/Activities/LiveEventPlanner/Thursday');
        }
        else  if($slug == "Friday"){
            return view('MmaPages/Activities/LiveEventPlanner/Friday');
        }
        else  if($slug == "Saturday"){
            return view('MmaPages/Activities/LiveEventPlanner/Saturday');
        }
        else  if($slug == "PrintableLibrary"){
            return view('MmaPages/Activities/Games/PrintableLibrary/PrintableLibrary');
        }
        else  if($slug == "Coloring"){
            return view('MmaPages/Activities/Games/PrintableLibrary/Coloring');
        }
        else  if($slug == "ColoringChallenges"){
            return view('MmaPages/Activities/Games/PrintableLibrary/ColoringChallenges');
        }
        else  if($slug == "WordSearches"){
            return view('MmaPages/Activities/Games/PrintableLibrary/WordSearches');
        }
        else  if($slug == "Sudoku"){
            return view('MmaPages/Activities/Games/PrintableLibrary/Sudoku');
        }
        else  if($slug == "SpotBall"){
            return view('MmaPages/Activities/Games/PrintableLibrary/SpotBall');
        }
        else  if($slug == "Crosswords"){
            return view('MmaPages/Activities/Games/PrintableLibrary/Crosswords');
        }
        else  if($slug == "DotToDot"){
            return view('MmaPages/Activities/Games/PrintableLibrary/DotToDot');
        }
        else  if($slug == "WordScramble"){
            return view('MmaPages/Activities/Games/PrintableLibrary/WordScramble');
        }
        else  if($slug == "Sunday"){
            return view('MmaPages/Activities/LiveEventPlanner/Sunday');
        }
        else  if($slug == "LearningVideos"){
            return view('MmaPages/Activities/ThemedEvents/LoveLearning/LearningVideos');
        }
        else  if($slug == "Discover"){
            return view('MmaPages/Activities/ThemedEvents/LoveLearning/Discover/Discover');
        }
        else  if($slug == "CaligraphyLessons"){
            return view('MmaPages/Activities/ThemedEvents/LoveLearning/Discover/CaligraphyLessons');
        }
        else  if($slug == "SingingLessons"){
            return view('MmaPages/Activities/ThemedEvents/LoveLearning/Discover/SingingLessons');
        }
        else  if($slug == "Games"){
            return view('MmaPages/Activities/Games/Games');
        }
        else  if($slug == "ActivityQuizzes"){
            return view('MmaPages/Activities/Games/ActivityQuizzes/ActivityQuizzes');
        }
        else  if($slug == "WeeklyQuiz"){
            return view('MmaPages/Activities/Games/ActivityQuizzes/WeeklyQuiz');
        }
        else  if($slug == "FriendlyQuiz"){
            return view('MmaPages/Activities/Games/ActivityQuizzes/FriendlyQuiz');
        }
        else  if($slug == "FeatureQuiz"){
            return view('MmaPages/Activities/Games/ActivityQuizzes/FeatureQuiz');
        }
        else  if($slug == "CompetetionQuiz"){
            return view('MmaPages/Activities/Games/ActivityQuizzes/CompetetionQuiz');
        }
        else  if($slug == "CompetetionQuizResults"){
            return view('MmaPages/Activities/Games/ActivityQuizzes/CompetetionQuizResults');
        }
        else  if($slug == "Puzzles"){
            return view('MmaPages/Activities/Games/Puzzles/Puzzles');
        }
        else  if($slug == "WeeklyPrintables"){
            return view('MmaPages/Activities/Games/WeeklyPrintables/WeeklyPrintables');
        }
        else  if($slug == "Stationery"){
            return view('MmaPages/Activities/ThemedEvents/LoveLearning/Stationery');
        }
        else  if($slug == "Arts"){
            return view('MmaPages/Activities/ThemedEvents/Arts/Arts');
        }
        else  if($slug == "MentalHealthPodcast"){
            return view('MmaPages/Activities/ThemedEvents/MentalHealthPodcast/MentalHealthPodcast');
        }
        else  if($slug == "PageNotAvailable"){
            return view('MmaPages/Activities/ThemedEvents/MentalHealthPodcast/PageNotAvailable');
        }
        else  if($slug == "EventsComingSoon"){
            return view('MmaPages/Activities/ThemedEvents/EventsComingSoon/EventsComingSoon');
        }
        else  if($slug == "MadiGras"){
            return view('MmaPages/Activities/ThemedEvents/EventsComingSoon/MadiGras');
        }
        else  if($slug == "WorldBookDay"){
            return view('MmaPages/Activities/ThemedEvents/EventsComingSoon/WorldBookDay');
        }
        else  if($slug == "CruftsDogShow"){
            return view('MmaPages/Activities/ThemedEvents/EventsComingSoon/CruftsDogShow');
        }
        else  if($slug == "BritishScienceWeek"){
            return view('MmaPages/Activities/ThemedEvents/EventsComingSoon/BritishScienceWeek');
        }
        else  if($slug == "RedNoseDay"){
            return view('MmaPages/Activities/ThemedEvents/EventsComingSoon/RedNoseDay');
        }
        else  if($slug == "Mothersday"){
            return view('MmaPages/Activities/ThemedEvents/EventsComingSoon/Mothersday');
        }
        else  if($slug == "WorldPoetryDay"){
            return view('MmaPages/Activities/ThemedEvents/EventsComingSoon/WorldPoetryDay');
        }
        else  if($slug == "Radio"){
            return view('MmaPages/Activities/Radio/Radio');
        }
        else{
            return view('MmaPages/Activities/ErrorPage');
        }

    }

                                   //Toolkit Frontend
    public function indexSectionToolkit(Request $request, $slug){
        if($slug == "Toolkit"){
            return view('MmaPages/Toolkit/Toolkit');
        }
        else if($slug == "Templates"){
            return view('MmaPages/Toolkit/Templates/Templates');
        }
        else if($slug == "ClubTemplates"){
            return view('MmaPages/Toolkit/Templates/ClubTemplates');
        }
        else if($slug == "TogetherTemplates"){
            return view('MmaPages/Toolkit/Templates/TogetherTemplates');
        }
        else if($slug == "Forms"){
            return view('MmaPages/Toolkit/Forms/Forms');
        }
        else if($slug == "Submissions"){
            return view('MmaPages/Toolkit/Submissions/Submissions');
        }
        else if($slug == "StorySubmissions"){
            return view('MmaPages/Toolkit/Submissions/SubmitStory/StorySubmissions');
        }
        else if($slug == "SubmitaStory"){
            return view('MmaPages/Toolkit/Submissions/SubmitStory/SubmitaStory');
        }
        else if($slug == "Supplies"){
            return view('MmaPages/Toolkit/Supplies/Supplies');
        }
        else if($slug == "Activities"){
            return view('MmaPages/Toolkit/Supplies/Activities');
        }
        else if($slug == "Training"){
            return view('MmaPages/Toolkit/Training/Training');
        }
        else if($slug == "PortalTraining"){
            return view('MmaPages/Toolkit/Training/PortalTraining/PortalTraining');
        }
        else if($slug == "Registration"){
            return view('MmaPages/Toolkit/Training/PortalTraining/Registration');
        }
        else if($slug == "ActivityRecording"){
            return view('MmaPages/Toolkit/Training/PortalTraining/ActivityRecording');
        }
        else if($slug == "StorySubmissions"){
            return view('MmaPages/Toolkit/Training/PortalTraining/StorySubmissions');
        }
        else if($slug == "DementiaTraining"){
            return view('MmaPages/Toolkit/Training/DementiaTraining/DementiaTraining');
        }
        else if($slug == "DementiaManual"){
            return view('MmaPages/Toolkit/Training/DementiaTraining/DementiaManual');
        }
        else if($slug == "DementiaFactsheets"){
            return view('MmaPages/Toolkit/Training/DementiaTraining/DementiaFactsheets');
        }
        else if($slug == "PhotographySupport"){
            return view('MmaPages/Toolkit/Training/PhotographySupport/PhotographySupport');
        }
        else if($slug == "Photography"){
            return view('MmaPages/Toolkit/Training/PhotographySupport/ToolkitPhotographyLessons');
        }
        else if($slug == "SupportTicket"){
            return view('MmaPages/Toolkit/Training/SupportTicket/SupportTicket');
        }
        else if($slug == "ActivityReporting"){
            return view('MmaPages/Toolkit/ActivityReporting/ActivityReporting');
        }
        else if($slug == "RecordActivity"){
            return view('MmaPages/Toolkit/ActivityReporting/RecordActivity');
        }
        else if($slug == "CustomActivity"){
            return view('MmaPages/Toolkit/ActivityReporting/CustomActivity');
        }
        else if($slug == "Report"){
            return view('MmaPages/Toolkit/ActivityReporting/Report');
        }
        else if($slug == "DashboardSummary"){
            return view('MmaPages/Toolkit/ActivityReporting/DashboardSummary');
        }
        else if($slug == "InclusionTracker"){
            return view('MmaPages/Toolkit/ActivityReporting/InclusionTracker');
        }
        else if($slug == "EvidencingDemo"){
            return view('MmaPages/Toolkit/ActivityReporting/EvidencingDemo');
        }
        else{
            return view('MmaPages/Activities/ErrorPage');
        }

    }


    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\UserActivities  $userActivities
     * @return \Illuminate\Http\Response
     */
    public function show(UserActivities $userActivities)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\UserActivities  $userActivities
     * @return \Illuminate\Http\Response
     */
    public function edit(UserActivities $userActivities)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\UserActivities  $userActivities
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserActivities $userActivities)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UserActivities  $userActivities
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserActivities $userActivities)
    {
        //
    }
}
