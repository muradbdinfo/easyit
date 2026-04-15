<?php
namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $posts = Post::published()
            ->with('category:id,name,slug', 'user:id,name')
            ->when($request->search, fn($q, $s) => $q->where('title', 'like', "%{$s}%")->orWhere('body', 'like', "%{$s}%"))
            ->latest('published_at')
            ->paginate(12)
            ->withQueryString();

        $posts->getCollection()->transform(function ($post) {
            $post->featured_image_url = $post->getFirstMediaUrl('featured_image');
            return $post;
        });

        return Inertia::render('Public/Blog/Index', [
            'posts' => $posts,
            'categories' => Category::withCount(['posts' => fn($q) => $q->where('status', 'published')])->orderBy('name')->get(),
            'tags' => Tag::withCount('posts')->orderBy('name')->get(),
            'filters' => $request->only('search'),
        ]);
    }

    public function show(Post $post)
    {
        if ($post->status !== 'published') abort(404);

        $post->increment('views');
        $post->load('category:id,name,slug', 'tags:id,name,slug', 'user:id,name');
        $post->featured_image_url = $post->getFirstMediaUrl('featured_image');

        $relatedPosts = Post::published()
            ->where('id', '!=', $post->id)
            ->when($post->category_id, fn($q) => $q->where('category_id', $post->category_id))
            ->latest('published_at')
            ->take(3)
            ->get(['id', 'title', 'slug', 'excerpt', 'published_at']);

        $relatedPosts->transform(function ($p) {
            $p->featured_image_url = $p->getFirstMediaUrl('featured_image');
            return $p;
        });

        return Inertia::render('Public/Blog/Show', [
            'post' => $post,
            'readingTime' => $post->reading_time,
            'relatedPosts' => $relatedPosts,
        ]);
    }

    public function byCategory(Category $category)
    {
        $posts = Post::published()
            ->where('category_id', $category->id)
            ->with('user:id,name', 'category:id,name,slug')
            ->latest('published_at')
            ->paginate(12);

        $posts->getCollection()->transform(function ($post) {
            $post->featured_image_url = $post->getFirstMediaUrl('featured_image');
            return $post;
        });

        return Inertia::render('Public/Blog/Index', [
            'posts' => $posts,
            'categories' => Category::withCount(['posts' => fn($q) => $q->where('status', 'published')])->orderBy('name')->get(),
            'tags' => Tag::withCount('posts')->orderBy('name')->get(),
            'currentCategory' => $category,
            'filters' => [],
        ]);
    }
}
