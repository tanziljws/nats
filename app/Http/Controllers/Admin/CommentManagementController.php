<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FacilityPhotoComment;
use App\Models\FacilityCategory;
use App\Models\FacilityPhotoLike;
use App\Models\HogwartsProphetComment;
use App\Models\HogwartsProphetLike;
use App\Models\AchievementComment;
use App\Models\AchievementLike;
use App\Models\FacilityPhoto;
use App\Models\HogwartsProphet;
use App\Models\Achievement;
use App\Models\House;

class CommentManagementController extends Controller
{
    // Dashboard with statistics
    public function index()
    {
        $stats = [
            'facility_photos' => [
                'total_likes' => FacilityPhotoLike::count(),
                'total_comments' => FacilityPhotoComment::count(),
                'pending_comments' => FacilityPhotoComment::where('is_approved', false)->count(),
            ],
            'hogwarts_prophet' => [
                'total_likes' => HogwartsProphetLike::count(),
                'total_comments' => HogwartsProphetComment::count(),
                'pending_comments' => HogwartsProphetComment::where('is_approved', false)->count(),
            ],
            'achievements' => [
                'total_likes' => AchievementLike::count(),
                'total_comments' => AchievementComment::count(),
                'pending_comments' => AchievementComment::where('is_approved', false)->count(),
            ],
        ];

        return view('admin.comments.index', compact('stats'));
    }

    // Facility Photo Comments
    public function facilityComments(Request $request)
    {
        $categoryId = $request->query('category_id');
        $photoId = $request->query('photo_id');

        $commentsQuery = FacilityPhotoComment::with('photo.category')
            ->when($categoryId, function ($q) use ($categoryId) {
                $q->whereHas('photo', function ($p) use ($categoryId) {
                    $p->where('facility_category_id', $categoryId);
                });
            })
            ->when($photoId, function ($q) use ($photoId) {
                $q->where('facility_photo_id', $photoId);
            })
            ->orderBy('created_at', 'desc');

        $comments = $commentsQuery->paginate(10)->appends($request->query());

        $categories = FacilityCategory::orderBy('name')->get(['id','name']);
        $photos = [];
        if ($categoryId) {
            $photos = FacilityPhoto::where('facility_category_id', $categoryId)
                ->orderBy('name')
                ->get(['id','name']);
        }

        return view('admin.comments.facility-photos', [
            'comments' => $comments,
            'categories' => $categories,
            'selectedCategoryId' => $categoryId,
            'photos' => $photos,
            'selectedPhotoId' => $photoId,
        ]);
    }

    // HogwartsProphet Comments
    public function prophetComments(Request $request)
    {
        $articleId = $request->query('article_id');

        $commentsQuery = HogwartsProphetComment::with('article')
            ->when($articleId, function ($q) use ($articleId) {
                $q->where('hogwarts_prophet_id', $articleId);
            })
            ->orderBy('created_at', 'desc');

        $comments = $commentsQuery->paginate(10)->appends($request->query());

        $articles = HogwartsProphet::orderBy('title')->get(['id','title']);

        return view('admin.comments.hogwarts-prophet', [
            'comments' => $comments,
            'articles' => $articles,
            'selectedArticleId' => $articleId,
        ]);
    }

    // Achievement Comments
    public function achievementComments(Request $request)
    {
        $houseId = $request->query('house_id');
        $achievementId = $request->query('achievement_id');

        $commentsQuery = AchievementComment::with(['achievement.house'])
            ->when($houseId, function ($q) use ($houseId) {
                $q->whereHas('achievement', function ($a) use ($houseId) {
                    $a->where('house_id', $houseId);
                });
            })
            ->when($achievementId, function ($q) use ($achievementId) {
                $q->where('achievement_id', $achievementId);
            })
            ->orderBy('created_at', 'desc');

        $comments = $commentsQuery->paginate(10)->appends($request->query());

        $houses = House::orderBy('name')->get(['id','name']);
        $achievements = [];
        if ($houseId) {
            $achievements = Achievement::where('house_id', $houseId)
                ->orderBy('title')
                ->get(['id','title','image','house_id']);
        }

        return view('admin.comments.achievements', [
            'comments' => $comments,
            'houses' => $houses,
            'achievementsList' => $achievements,
            'selectedHouseId' => $houseId,
            'selectedAchievementId' => $achievementId,
        ]);
    }

    // Delete Facility Photo Comment
    public function deleteFacilityComment($id)
    {
        $comment = FacilityPhotoComment::findOrFail($id);
        $comment->delete();

        return redirect()->back()->with('success', 'Comment deleted successfully!');
    }

    // Delete HogwartsProphet Comment
    public function deleteProphetComment($id)
    {
        $comment = HogwartsProphetComment::findOrFail($id);
        $comment->delete();

        return redirect()->back()->with('success', 'Comment deleted successfully!');
    }

    // Delete Achievement Comment
    public function deleteAchievementComment($id)
    {
        $comment = AchievementComment::findOrFail($id);
        $comment->delete();

        return redirect()->back()->with('success', 'Comment deleted successfully!');
    }

    // Toggle Approval Status
    public function toggleApproval(Request $request)
    {
        $type = $request->type;
        $id = $request->id;

        switch ($type) {
            case 'facility':
                $comment = FacilityPhotoComment::findOrFail($id);
                break;
            case 'prophet':
                $comment = HogwartsProphetComment::findOrFail($id);
                break;
            case 'achievement':
                $comment = AchievementComment::findOrFail($id);
                break;
            default:
                return response()->json(['success' => false], 400);
        }

        $comment->is_approved = !$comment->is_approved;
        $comment->save();

        return response()->json([
            'success' => true,
            'is_approved' => $comment->is_approved
        ]);
    }

    // Likes Statistics
    public function likesStats()
    {
        // Top liked facility photos
        $topFacilityPhotos = FacilityPhoto::withCount('likes')
            ->orderBy('likes_count', 'desc')
            ->limit(10)
            ->get();

        // Top liked articles
        $topArticles = HogwartsProphet::withCount('likes')
            ->orderBy('likes_count', 'desc')
            ->limit(10)
            ->get();

        // Top liked achievements
        $topAchievements = Achievement::withCount('likes')
            ->orderBy('likes_count', 'desc')
            ->limit(10)
            ->get();

        return view('admin.comments.likes-stats', compact('topFacilityPhotos', 'topArticles', 'topAchievements'));
    }
}
