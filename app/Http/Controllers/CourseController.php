<?php

namespace App\Http\Controllers;

use App\Helper\ResponseHelper;
use Illuminate\Http\Request;
use App\Models\Course;
use Exception;
use Illuminate\Support\Facades\Log; 

/**
 * ALLOW USER TO APPLY FOR A COURSE
 */

class CourseController extends Controller
{
    public function apply(Request $request)
    {
        // Validate the course application request
        $validated = $request->validate([
            'course_id' => 'required|numeric|exists:courses,id',
        ]);

        // Get the authenticated user
        $user = $request->user();

        // Attach the course to the user (many-to-many relationship)
        $user->courses()->attach($validated['course_id']);

        // Return success message
        return response()->json(['message' => 'Course application successful.'], 201);
    }


    /**
    * FETCH & VIEW APPLIED USER
    */

    public function getAppliedUsers($courseId)
    {
        // Find the course by its ID
        $course = Course::with('users')->find($courseId);

        // Check if the course exists
        if (!$course) {
            return response()->json(['message' => 'Course not found.'], 404);
        }

        // Get the users who have applied for this course
        $appliedUsers = $course->users;

        // Return the users in the response
        return response()->json(['message' => 'Users fetched successfully.', 'users' => $appliedUsers], 200);
    }

    /**
     * Create Course
     */

    public function createCourse(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string',
                'description' => 'required|string',
            ]);

            $course = Course::create($validated);
            

            if ($course) {  
  
                return ResponseHelper::success(message: 'Course created successfully!', data: $course, statusCode: 201);
            }
            return ResponseHelper::error(message: 'unable to create course, please try again.', statusCode: 400);
        }
        catch (Exception $e) {
            Log::error('unable to  : ' . $e->getMessage() . ' - Line no. ' . $e->getLine());
            return ResponseHelper::error(message: 'unable to create course, please try again.' . $e->getMessage(), statusCode: 500);
        }
    }

    public function getAllCourses(Request $request)
    {
        // Fetch all courses
        $course = Course::with('users')->get();

        // Return the users in the response
        return response()->json(['message' => 'All courses fetched successfully.', 'courses' => $course], 200);
    }

}
