<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    // Add a review
    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string'
        ]);

        $review = Review::create([
            'user_id' => Auth::id(),
            'course_id' => $request->course_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return response()->json(['message' => 'Review submitted successfully', 'review' => $review], 201);
    }

    // Fetch all reviews for a course
    public function index($courseId)
    {
        $reviews = Review::where('course_id', $courseId)->with('user')->get();
        return response()->json($reviews);
    }

    // Delete a review (Only review owner can delete)
    public function destroy($id)
    {
        $review = Review::findOrFail($id);

        if (Auth::id() !== $review->user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $review->delete();
        return response()->json(['message' => 'Review deleted successfully']);
    }
}

