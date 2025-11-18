<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Achievement;
use App\Models\AchievementLike;
use App\Models\AchievementComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AchievementController extends Controller
{
    public function index(Request $request)
    {
        $achievements = Achievement::with(['house', 'likes'])->latest()->get();

        $achievementsData = $achievements->map(function($a) {
            return [
                'id' => $a->id,
                'title' => $a->title,
                'description' => \Illuminate\Support\Str::limit($a->description, 120),
                'image' => $a->image,
                'writer' => $a->writer ?? 'Admin',
                'date' => $a->date->format('F j, Y'),
                'house' => $a->house->name ?? 'General',
                'like_count' => $a->likes()->count(),
                'view_count' => (int)($a->view_count ?? 0),
            ];
        });

        return view('guest.achievements.index', compact('achievementsData'));
    }

    /** ================= LIKE SYSTEM ================= */
    public function toggleLike(Request $request, $achievementId)
    {
        if (!auth('web')->check()) {
            return response()->json(['success' => false, 'message' => 'Please login.'], 401);
        }

        // Ensure the achievement exists
        $achievement = Achievement::findOrFail($achievementId);

        $user = auth('web')->user();
        $sessionId = $request->session()->getId();

        // Find existing like by either current user or current session
        $existing = AchievementLike::where('achievement_id', $achievementId)
            ->where(function ($q) use ($user, $sessionId) {
                $q->where('user_id', $user->id)
                  ->orWhere('session_id', $sessionId);
            })
            ->first();

        if ($existing) {
            $existing->delete();
            $liked = false;
        } else {
            AchievementLike::create([
                'achievement_id' => $achievementId,
                'user_id' => $user->id,
                'session_id' => $sessionId,
                'ip_address' => $request->ip(),
            ]);
            $liked = true;
        }

        return response()->json([
            'success' => true,
            'liked' => $liked,
            'like_count' => AchievementLike::where('achievement_id', $achievementId)->count(),
        ]);
    }

    public function getLikeStatus($achievementId)
    {
        $liked = false;
        if(auth('web')->check()){
            $liked = AchievementLike::where('achievement_id',$achievementId)
                ->where('user_id',auth('web')->id())->exists();
        }

        return response()->json([
            'success'=>true,
            'liked'=>$liked,
            'like_count'=>AchievementLike::where('achievement_id',$achievementId)->count()
        ]);
    }

    /** ================= VIEW COUNT ================= */
    public function trackView(Request $request, $achievementId)
    {
        $achievement = Achievement::findOrFail($achievementId);
        $achievement->increment('view_count');

        return response()->json([
            'success' => true,
            'view_count' => (int)$achievement->view_count,
        ]);
    }

    /** ================= COMMENT SYSTEM ================= */
    public function getComments($achievementId)
    {
        $comments = AchievementComment::where('achievement_id',$achievementId)
            ->where('is_approved',true)
            ->latest()->get();

        return response()->json([
            'success'=>true,
            'comments'=>$comments
        ]);
    }

    public function storeComment(Request $request, $achievementId)
    {
        if(!auth('web')->check()){
            return response()->json(['success'=>false,'message'=>'Please login.'],401);
        }

        $validator = Validator::make($request->all(),['content'=>'required|string|max:1000']);
        if($validator->fails()){
            return response()->json(['success'=>false,'errors'=>$validator->errors()],422);
        }

        $user = auth('web')->user();

        $comment = AchievementComment::create([
            'achievement_id'=>$achievementId,
            'user_id'=>$user->id,
            'name'=>$user->name,
            'content'=>$request->input('content'),
            'is_approved'=>true,
            'ip_address'=>$request->ip()
        ]);

        return response()->json(['success'=>true,'comment'=>$comment]);
    }
}
