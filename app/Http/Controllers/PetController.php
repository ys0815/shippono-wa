<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use App\Models\PetFamilyLink;
use App\Models\Like;
use App\Models\PetShareLink;
use App\Services\ImageOptimizationService;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

/**
 * ペット管理コントローラー
 * 
 * ペットの登録、編集、表示、検索、シェア機能を提供します：
 * - ペットのCRUD操作（作成、読み取り、更新、削除）
 * - ペット検索機能（種別、性別、保護団体などでフィルタリング）
 * - ペット詳細ページの表示
 * - ペットのシェアリンク生成とQRコード生成
 * - 家族ペットのリンク機能
 * - 投稿の取得（API）
 */
class PetController extends Controller
{
    /**
     * ユーザーのペット一覧表示
     * 
     * @param Request $request
     * @return View ペット一覧ページ
     */
    public function index(Request $request): View
    {
        // ログインユーザーのペット一覧を取得（投稿数、いいね数、保護団体情報も含む）
        $pets = Pet::where('user_id', Auth::id())
            ->with('posts:id,pet_id', 'likes:id,pet_id', 'shelter:id,name')
            ->latest()
            ->get();

        return view('pets.index', compact('pets'));
    }

    /**
     * ペット登録フォーム表示
     * 
     * @param Request $request
     * @return View ペット登録フォーム
     */
    public function create(Request $request): View
    {
        return view('pets.create');
    }

    /**
     * ペット編集フォーム表示
     * 
     * @param Request $request
     * @param int $pet_id 編集するペットのID
     * @return View ペット編集フォーム
     */
    public function edit(Request $request, $pet_id): View
    {
        // ユーザーが所有するペットのみ編集可能
        $pet = Pet::where('user_id', Auth::id())
            ->where('id', $pet_id)
            ->with('shelter:id,name,area,kind')
            ->firstOrFail();
        return view('pets.create', compact('pet'));
    }

    /**
     * ペット詳細ページ表示
     * 
     * @param Pet $pet 表示するペット
     * @return View ペット詳細ページ
     */
    public function show(Pet $pet): View
    {
        // ペットの関連データを読み込み（ユーザー、保護団体、公開投稿、家族リンク）
        $pet->load(['user', 'shelter', 'posts' => function ($query) {
            $query->where('status', 'published')
                ->where('type', 'gallery')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->with('media');
        }, 'familyLinksAsPet1.pet2', 'familyLinksAsPet2.pet1']);

        // 現在のユーザーがこのペットにいいねしているかチェック
        $isLiked = false;
        if (Auth::check()) {
            $isLiked = Like::where('user_id', Auth::id())
                ->where('pet_id', $pet->id)
                ->exists();
        }

        // いいね数を取得
        $likeCount = $pet->likes()->count();

        // 家族ペットを取得（双方向のリンクから）
        $familyPets = collect();
        foreach ($pet->familyLinksAsPet1 as $link) {
            $familyPets->push($link->pet2);
        }
        foreach ($pet->familyLinksAsPet2 as $link) {
            $familyPets->push($link->pet1);
        }

        return view('pets.show', compact('pet', 'isLiked', 'likeCount', 'familyPets'));
    }

    /**
     * ペット検索ページ表示
     * 
     * @param Request $request 検索条件を含むリクエスト
     * @param string $species 検索する動物種別
     * @return View ペット検索結果ページ
     */
    public function search(Request $request, string $species): View
    {
        // 種別の検証（'all'も許可）
        $validSpecies = ['dog', 'cat', 'rabbit', 'other', 'all'];
        if (!in_array($species, $validSpecies)) {
            abort(404);
        }

        // 種別名の日本語変換
        $speciesNames = [
            'dog' => '犬',
            'cat' => '猫',
            'rabbit' => 'うさぎ',
            'other' => 'その他',
            'all' => 'すべて'
        ];

        // 検索条件の取得
        $filters = [
            'species' => $request->get('species', $species === 'all' ? '' : $species),
            'gender' => $request->get('gender', ''),
            'shelter_kind' => $request->get('shelter_kind', ''),
            'shelter_area' => $request->get('shelter_area', ''),
            'shelter_id' => $request->get('shelter_id', ''),
            'sort' => $request->get('sort', 'newest')
        ];
        // 'null' や 'undefined' の文字列を空として正規化
        foreach ($filters as $k => $v) {
            if ($v === 'null' || $v === 'undefined') {
                $filters[$k] = '';
            }
        }

        // 動的な見出しを生成
        $speciesName = $this->generateDynamicTitle($filters, $speciesNames);

        // ソート順の検証
        $validSorts = ['newest', 'oldest', 'updated'];
        if (!in_array($filters['sort'], $validSorts)) {
            $filters['sort'] = 'newest';
        }

        // ペット一覧の取得
        $query = Pet::with(['user:id,name,display_name', 'shelter:id,name,area,kind,website_url', 'shelter.prefecture:id,name']);

        // 種別でフィルタリング
        if ($filters['species'] && $filters['species'] !== 'all') {
            $query->where('species', $filters['species']);
        }

        // 性別でフィルタリング
        if ($filters['gender']) {
            $query->where('gender', $filters['gender']);
        }

        // 保護施設の種別でフィルタリング
        if ($filters['shelter_kind']) {
            $query->whereHas('shelter', function ($q) use ($filters) {
                $q->where('kind', $filters['shelter_kind']);
            });
        }

        // 保護施設の所在地でフィルタリング
        if ($filters['shelter_area']) {
            $query->whereHas('shelter', function ($q) use ($filters) {
                $q->where('area', $filters['shelter_area']);
            });
        }

        // 保護施設名でフィルタリング
        if ($filters['shelter_id']) {
            $query->where('shelter_id', $filters['shelter_id']);
        }

        // ソート適用
        switch ($filters['sort']) {
            case 'oldest':
                $query->oldest();
                break;
            case 'updated':
                $query->orderBy('updated_at', 'desc');
                break;
            case 'newest':
            default:
                $query->latest();
                break;
        }

        $pets = $query->take(5)->get();
        $totalCount = $query->count();

        // 各ペットにインタビューポストの情報を追加
        $pets->each(function ($pet) {
            $interviewPost = $pet->posts()
                ->where('type', 'interview')
                ->where('status', 'published')
                ->latest()
                ->first();

            $pet->interview_post = $interviewPost ? [
                'id' => $interviewPost->id,
                'title' => $interviewPost->title,
            ] : null;
        });

        // 検索に使用した条件を保持
        $searchParams = $request->only(['species', 'gender', 'shelter_kind', 'shelter_area', 'shelter_id', 'sort']);

        // ビューで使用する名前の配列を準備
        $genderNames = [
            'male' => 'オス',
            'female' => 'メス'
        ];

        $shelterKindNames = [
            'facility' => '保護団体・施設',
            'site' => '里親サイト',
            'unknown' => '不明'
        ];

        $areaNames = [
            'hokkaido_tohoku' => '北海道・東北',
            'kanto' => '関東',
            'chubu_tokai' => '中部・東海',
            'kinki' => '近畿',
            'chugoku_shikoku' => '中国・四国',
            'kyushu_okinawa' => '九州・沖縄',
            'national' => '全国'
        ];

        return view('pets.search', compact('species', 'speciesName', 'pets', 'totalCount', 'filters', 'searchParams', 'speciesNames', 'genderNames', 'shelterKindNames', 'areaNames'));
    }

    /**
     * ペット検索API（AJAX用）
     * 
     * @param Request $request 検索条件を含むリクエスト
     * @param string $species 検索する動物種別
     * @return \Illuminate\Http\JsonResponse ペット検索結果のJSONレスポンス
     */
    public function searchApi(Request $request, string $species)
    {
        // 種別の検証（'all'も許可）
        $validSpecies = ['dog', 'cat', 'rabbit', 'other', 'all'];
        if (!in_array($species, $validSpecies)) {
            return response()->json(['error' => 'Invalid species'], 400);
        }

        $page = $request->get('page', 1);
        $perPage = 5;

        // 検索条件の取得
        $filters = [
            'species' => $request->get('species', $species === 'all' ? '' : $species),
            'gender' => $request->get('gender', ''),
            'shelter_kind' => $request->get('shelter_kind', ''),
            'shelter_area' => $request->get('shelter_area', ''),
            'shelter_id' => $request->get('shelter_id', ''),
            'sort' => $request->get('sort', 'newest')
        ];
        // 'null' や 'undefined' の文字列を空として正規化
        foreach ($filters as $k => $v) {
            if ($v === 'null' || $v === 'undefined') {
                $filters[$k] = '';
            }
        }

        // ソート順の検証
        $validSorts = ['newest', 'oldest', 'updated'];
        if (!in_array($filters['sort'], $validSorts)) {
            $filters['sort'] = 'newest';
        }

        $query = Pet::with(['user:id,name,display_name', 'shelter:id,name,area,kind,website_url', 'shelter.prefecture:id,name']);

        // 種別でフィルタリング
        if ($filters['species'] && $filters['species'] !== 'all') {
            $query->where('species', $filters['species']);
        }

        // 性別でフィルタリング
        if ($filters['gender']) {
            $query->where('gender', $filters['gender']);
        }

        // 保護施設の種別でフィルタリング
        if ($filters['shelter_kind']) {
            $query->whereHas('shelter', function ($q) use ($filters) {
                $q->where('kind', $filters['shelter_kind']);
            });
        }

        // 保護施設の所在地でフィルタリング
        if ($filters['shelter_area']) {
            $query->whereHas('shelter', function ($q) use ($filters) {
                $q->where('area', $filters['shelter_area']);
            });
        }

        // 保護施設名でフィルタリング
        if ($filters['shelter_id']) {
            $query->where('shelter_id', $filters['shelter_id']);
        }

        // ソート適用
        switch ($filters['sort']) {
            case 'oldest':
                $query->oldest();
                break;
            case 'updated':
                $query->orderBy('updated_at', 'desc');
                break;
            case 'newest':
            default:
                $query->latest();
                break;
        }

        // 総数を先に取得
        $totalCount = $query->count();

        $pets = $query->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->get();

        // hasMoreの正しい計算：現在のページ以降にまだペットがあるかどうか
        $hasMore = (($page - 1) * $perPage + $pets->count()) < $totalCount;

        return response()->json([
            'pets' => $pets->map(function ($pet) {
                // インタビューポストを取得
                $interviewPost = $pet->posts()
                    ->where('type', 'interview')
                    ->where('status', 'published')
                    ->latest()
                    ->first();

                return [
                    'id' => $pet->id,
                    'name' => $pet->name,
                    'species' => $pet->species,
                    'gender' => $pet->gender,
                    'breed' => $pet->breed,
                    'age_years' => $pet->age_years,
                    'age_months' => $pet->age_months,
                    'estimated_age' => $pet->estimated_age,
                    'profile_description' => $pet->profile_description,
                    'profile_image_url' => $pet->profile_image_url,
                    'user' => [
                        'name' => $pet->user->display_name ?? $pet->user->name,
                    ],
                    'shelter' => $pet->shelter ? [
                        'name' => $pet->shelter->name,
                        'area' => $pet->shelter->area,
                        'website_url' => $pet->shelter->website_url,
                    ] : null,
                    'interview_post' => $interviewPost ? [
                        'id' => $interviewPost->id,
                        'title' => $interviewPost->title,
                    ] : null,
                    'created_at' => $pet->created_at->setTimezone('Asia/Tokyo')->format('Y年n月j日'),
                    'updated_at' => $pet->updated_at->setTimezone('Asia/Tokyo')->format('Y年n月j日'),
                ];
            }),
            'hasMore' => $hasMore,
            'totalCount' => $totalCount,
        ]);
    }

    /**
     * ペットの投稿取得API（AJAX用）
     * 
     * @param Request $request ページネーション・ソート・フィルター条件を含むリクエスト
     * @param Pet $pet 投稿を取得するペット
     * @return \Illuminate\Http\JsonResponse 投稿一覧のJSONレスポンス
     */
    public function getPosts(Request $request, Pet $pet)
    {
        $page = $request->get('page', 1);
        $perPage = 5;
        $sort = $request->get('sort', 'newest');
        $timeFilter = $request->get('time_filter', 'all');

        $query = $pet->posts()
            ->where('status', 'published')
            ->where('type', 'gallery')
            ->with('media');

        // 期間フィルター
        if ($timeFilter !== 'all') {
            $now = now();
            switch ($timeFilter) {
                case 'today':
                    $query->whereDate('created_at', $now->toDateString());
                    break;
                case 'week':
                    $query->where('created_at', '>=', $now->subWeek());
                    break;
                case 'month':
                    $query->where('created_at', '>=', $now->subMonth());
                    break;
            }
        }

        // ソート
        switch ($sort) {
            case 'oldest':
                $query->oldest();
                break;
            case 'popular':
                $query->orderBy('view_count', 'desc');
                break;
            case 'newest':
            default:
                $query->latest();
                break;
        }

        $posts = $query->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->get();

        // 投稿総数を取得（フィルター適用後）
        $totalPosts = $query->count();

        return response()->json([
            'posts' => $posts->map(function ($post) {
                return [
                    'id' => $post->id,
                    'title' => $post->title,
                    'content' => $post->content,
                    'created_at' => $post->created_at->setTimezone('Asia/Tokyo')->format('Y年n月j日 H:i'),
                    'media' => $post->media->map(function ($media) {
                        // URLを正しいパスに変換
                        $url = $media->url;
                        if (strpos($url, 'http') !== 0) {
                            $url = '/storage/' . $url;
                        }

                        return [
                            'url' => $url,
                            'type' => $media->type,
                        ];
                    }),
                    'user_id' => $post->user_id,
                ];
            }),
            'hasMore' => $posts->count() === $perPage,
            'totalPosts' => $totalPosts,
        ]);
    }

    /**
     * ペットシェアページ表示
     * 
     * @param string $token シェアトークン
     * @return View ペットシェアページ
     */
    public function share(string $token): View
    {
        // シェアリンクを取得（アクティブなもののみ）
        $shareLink = PetShareLink::where('share_token', $token)
            ->where('is_active', true)
            ->with(['pet.user', 'pet.shelter', 'pet.posts' => function ($query) {
                $query->where('status', 'published')->latest()->limit(5);
            }])
            ->firstOrFail();

        // 有効期限チェック
        if ($shareLink->isExpired()) {
            abort(404, 'シェアリンクの有効期限が切れています。');
        }

        $pet = $shareLink->pet;

        // ビューカウントを増加
        $shareLink->incrementViewCount();

        // 現在のユーザーがこのペットにいいねしているかチェック
        $isLiked = false;
        if (Auth::check()) {
            $isLiked = Like::where('user_id', Auth::id())
                ->where('pet_id', $pet->id)
                ->exists();
        }

        // いいね数
        $likeCount = $pet->likes()->count();

        return view('pets.share', compact('pet', 'isLiked', 'likeCount', 'shareLink'));
    }

    /**
     * ペットシェアリンク生成
     * 
     * @param Request $request
     * @param Pet $pet シェアリンクを生成するペット
     * @return RedirectResponse ペット詳細ページへのリダイレクト
     */
    public function generateShareLink(Request $request, Pet $pet): RedirectResponse
    {
        // ペットの所有者かチェック
        if ($pet->user_id !== Auth::id()) {
            abort(403, 'このペットのシェアリンクを生成する権限がありません。');
        }

        // 既存のアクティブなシェアリンクをチェック
        $existingLink = $pet->shareLinks()
            ->where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->first();

        if ($existingLink) {
            return redirect()->route('pets.show', $pet)
                ->with('share_url', $existingLink->getShareUrl())
                ->with('status', 'share-link-exists');
        }

        // 新しいシェアリンクを作成
        $shareLink = PetShareLink::create([
            'pet_id' => $pet->id,
            'share_token' => PetShareLink::generateToken(),
            'title' => $pet->name . 'のプロフィール',
            'description' => $pet->profile_description ?: $pet->name . 'のプロフィールページです。',
            'is_active' => true,
            'expires_at' => now()->addDays(30), // 30日間有効
        ]);

        return redirect()->route('pets.show', $pet)
            ->with('share_url', $shareLink->getShareUrl())
            ->with('status', 'share-link-generated');
    }

    /**
     * ペットQRコード生成
     * 
     * @param Request $request
     * @param Pet $pet QRコードを生成するペット
     * @return \Illuminate\Http\Response SVG形式のQRコード画像
     */
    public function generateQrCode(Request $request, Pet $pet)
    {
        // ペットの所有者かチェック
        if ($pet->user_id !== Auth::id()) {
            abort(403, 'このペットのQRコードを生成する権限がありません。');
        }

        // 既存のアクティブなシェアリンクを取得
        $shareLink = $pet->shareLinks()
            ->where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->first();

        if (!$shareLink) {
            return redirect()->route('pets.show', $pet)
                ->with('error', 'シェア用URLが存在しません。まずシェア用URLを生成してください。');
        }

        // QRコードを生成
        $qrCode = QrCode::size(200)
            ->format('svg')
            ->generate($shareLink->getShareUrl());

        return response($qrCode)
            ->header('Content-Type', 'image/svg+xml')
            ->header('Content-Disposition', 'inline; filename="' . $pet->name . '_qr_code.svg"');
    }

    /**
     * ペット登録処理
     * 
     * @param Request $request ペット情報を含むリクエスト
     * @return RedirectResponse マイページペット一覧へのリダイレクト
     */
    public function store(Request $request): RedirectResponse
    {
        // バリデーション
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'species' => ['required', 'in:dog,cat,rabbit,other'],
            'breed' => ['nullable', 'string', 'max:255'],
            'gender' => ['required', 'in:male,female,unknown'],
            'birth_date' => ['nullable', 'date'],
            'age_years' => ['nullable', 'integer', 'min:0', 'max:40'],
            'age_months' => ['nullable', 'integer', 'min:0', 'max:11'],
            'estimated_age' => ['nullable', 'integer', 'min:0', 'max:480'], // 40歳×12ヶ月
            'shelter_area' => ['nullable', 'in:hokkaido_tohoku,kanto,chubu_tokai,kinki,chugoku_shikoku,kyushu_okinawa,national'],
            'shelter_kind' => ['nullable', 'in:facility,site,unknown'],
            'shelter_id' => ['nullable', 'exists:shelters,id'],
            'rescue_date' => ['nullable', 'date'],
            'profile_description' => ['nullable', 'string', 'max:2000'],
            'profile_image' => ['nullable', 'file', 'mimes:jpg,jpeg,png', 'max:5120'],
            'header_image' => ['nullable', 'file', 'mimes:jpg,jpeg,png', 'max:8192'],
        ]);


        $pet = new Pet();
        $pet->user_id = Auth::id();
        $pet->name = $validated['name'];
        $pet->species = $validated['species'];
        $pet->breed = $validated['breed'] ?? null;
        $pet->gender = $validated['gender'];
        $pet->birth_date = $validated['birth_date'] ?? null;
        $pet->age_years = $validated['age_years'] ?? null;
        $pet->age_months = $validated['age_months'] ?? null;
        $pet->estimated_age = $validated['estimated_age'] ?? null;
        $pet->rescue_date = $validated['rescue_date'] ?? null;
        $pet->shelter_id = $validated['shelter_id'] ?? null;
        $pet->profile_description = $validated['profile_description'] ?? null;

        // 画像アップロード（最適化）
        $imageService = new ImageOptimizationService();

        if ($request->hasFile('profile_image')) {
            $optimizedImages = $imageService->optimizeAndSave($request->file('profile_image'), 'pets/profile');
            $path = $optimizedImages['large'] ?? $optimizedImages['medium'] ?? $optimizedImages['thumbnail'];
            $pet->profile_image_url = '/storage/' . $path;
        } else {
            $pet->profile_image_url = '/images/icon.png';
        }

        if ($request->hasFile('header_image')) {
            $optimizedImages = $imageService->optimizeAndSave($request->file('header_image'), 'pets/header');
            $path = $optimizedImages['large'] ?? $optimizedImages['medium'] ?? $optimizedImages['thumbnail'];
            $pet->header_image_url = '/storage/' . $path;
        } else {
            $pet->header_image_url = '/images/icon.png';
        }

        $pet->save();

        return redirect()->route('mypage.pets')->with('status', 'pet-created');
    }

    /**
     * ペット更新処理
     * 
     * @param Request $request 更新するペット情報を含むリクエスト
     * @param int $pet_id 更新するペットのID
     * @return RedirectResponse マイページペット一覧へのリダイレクト
     */
    public function update(Request $request, $pet_id): RedirectResponse
    {
        // ユーザーが所有するペットのみ更新可能
        $pet = Pet::where('user_id', Auth::id())
            ->where('id', $pet_id)
            ->firstOrFail();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'species' => ['required', 'in:dog,cat,rabbit,other'],
            'breed' => ['nullable', 'string', 'max:255'],
            'gender' => ['required', 'in:male,female,unknown'],
            'birth_date' => ['nullable', 'date'],
            'age_years' => ['nullable', 'integer', 'min:0', 'max:40'],
            'age_months' => ['nullable', 'integer', 'min:0', 'max:11'],
            'estimated_age' => ['nullable', 'integer', 'min:0', 'max:480'], // 40歳×12ヶ月
            'shelter_area' => ['nullable', 'in:hokkaido_tohoku,kanto,chubu_tokai,kinki,chugoku_shikoku,kyushu_okinawa,national'],
            'shelter_kind' => ['nullable', 'in:facility,site,unknown'],
            'shelter_id' => ['nullable', 'exists:shelters,id'],
            'rescue_date' => ['nullable', 'date'],
            'profile_description' => ['nullable', 'string', 'max:2000'],
            'profile_image' => ['nullable', 'file', 'mimes:jpg,jpeg,png', 'max:5120'],
            'header_image' => ['nullable', 'file', 'mimes:jpg,jpeg,png', 'max:8192'],
        ]);

        $pet->name = $validated['name'];
        $pet->species = $validated['species'];
        $pet->breed = $validated['breed'] ?? null;
        $pet->gender = $validated['gender'];
        $pet->birth_date = $validated['birth_date'] ?? null;
        $pet->age_years = $validated['age_years'] ?? null;
        $pet->age_months = $validated['age_months'] ?? null;
        $pet->estimated_age = $validated['estimated_age'] ?? null;
        $pet->rescue_date = $validated['rescue_date'] ?? null;
        $pet->shelter_id = $validated['shelter_id'] ?? null;
        $pet->profile_description = $validated['profile_description'] ?? null;

        // 画像アップロード処理（新しい画像がアップロードされた場合のみ更新・最適化）
        if ($request->hasFile('profile_image')) {
            $imageService = new ImageOptimizationService();
            $optimizedImages = $imageService->optimizeAndSave($request->file('profile_image'), 'pets/profile');
            $path = $optimizedImages['large'] ?? $optimizedImages['medium'] ?? $optimizedImages['thumbnail'];
            $pet->profile_image_url = '/storage/' . $path;
        }
        // 新しい画像がアップロードされない場合は既存の画像を保持

        if ($request->hasFile('header_image')) {
            $imageService = new ImageOptimizationService();
            $optimizedImages = $imageService->optimizeAndSave($request->file('header_image'), 'pets/header');
            $path = $optimizedImages['large'] ?? $optimizedImages['medium'] ?? $optimizedImages['thumbnail'];
            $pet->header_image_url = '/storage/' . $path;
        }
        // 新しい画像がアップロードされない場合は既存の画像を保持

        $pet->save();

        return redirect()->route('mypage.pets')->with('status', 'pet-updated');
    }

    /**
     * 家族ペットリンク管理ページ表示
     * 
     * @param Request $request
     * @return View 家族ペットリンク管理ページ
     */
    public function links(Request $request): View
    {
        // ユーザーのペット一覧を取得
        $pets = Pet::where('user_id', Auth::id())
            ->with('shelter:id,name')
            ->latest()
            ->get();

        // 既存の家族リンクを取得してグループ化
        $links = PetFamilyLink::where('user_id', Auth::id())
            ->with(['pet1', 'pet2'])
            ->get();

        // ペットIDをグループ化して家族グループを作成
        $familyGroups = $this->groupFamilyLinks($links);

        return view('pets.links', compact('pets', 'familyGroups'));
    }

    /**
     * 家族ペットリンク保存処理
     * 
     * @param Request $request 選択されたペットIDを含むリクエスト
     * @return RedirectResponse 家族ペットリンク管理ページへのリダイレクト
     */
    public function saveLinks(Request $request): RedirectResponse
    {
        // バリデーション（最低2匹のペットが必要）
        $validated = $request->validate([
            'selected_pets' => ['required', 'array', 'min:2'],
            'selected_pets.*' => ['exists:pets,id'],
        ]);

        $selectedPetIds = $validated['selected_pets'];
        $userId = Auth::id();

        // 選択されたペットがユーザーのものかチェック
        $userPetIds = Pet::where('user_id', $userId)
            ->whereIn('id', $selectedPetIds)
            ->pluck('id')
            ->toArray();

        if (count($userPetIds) !== count($selectedPetIds)) {
            return back()->withErrors(['selected_pets' => '無効なペットが選択されています。']);
        }

        try {
            DB::transaction(function () use ($selectedPetIds, $userId) {
                // 既存の家族リンクを削除
                PetFamilyLink::where('user_id', $userId)->delete();

                // 新しい家族リンクを作成（全てのペットを相互にリンク）
                for ($i = 0; $i < count($selectedPetIds); $i++) {
                    for ($j = $i + 1; $j < count($selectedPetIds); $j++) {
                        PetFamilyLink::create([
                            'user_id' => $userId,
                            'pet1_id' => $selectedPetIds[$i],
                            'pet2_id' => $selectedPetIds[$j],
                        ]);
                    }
                }
            });

            return redirect()->route('mypage.pets.links')
                ->with('status', 'family-links-created');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => '家族リンクの作成に失敗しました。']);
        }
    }

    /**
     * 家族ペットリンク削除処理
     * 
     * @param Request $request
     * @return RedirectResponse 家族ペットリンク管理ページへのリダイレクト
     */
    public function destroyLinks(Request $request): RedirectResponse
    {
        try {
            // 全ての家族リンクを削除
            PetFamilyLink::where('user_id', Auth::id())->delete();

            return redirect()->route('mypage.pets.links')
                ->with('status', 'family-links-deleted');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => '家族リンクの解除に失敗しました。']);
        }
    }

    /**
     * 家族リンクをグループ化する（プライベートメソッド）
     * 
     * @param \Illuminate\Database\Eloquent\Collection $links 家族リンクのコレクション
     * @return \Illuminate\Support\Collection グループ化された家族ペットのコレクション
     */
    private function groupFamilyLinks($links)
    {
        if ($links->isEmpty()) {
            return collect();
        }

        // ペットIDの関係をグラフとして構築
        $graph = [];
        foreach ($links as $link) {
            $pet1Id = $link->pet1_id;
            $pet2Id = $link->pet2_id;

            if (!isset($graph[$pet1Id])) {
                $graph[$pet1Id] = [];
            }
            if (!isset($graph[$pet2Id])) {
                $graph[$pet2Id] = [];
            }

            $graph[$pet1Id][] = $pet2Id;
            $graph[$pet2Id][] = $pet1Id;
        }

        // 連結成分を見つけてグループ化
        $visited = [];
        $groups = [];

        foreach (array_keys($graph) as $petId) {
            if (!isset($visited[$petId])) {
                $group = [];
                $this->dfs($graph, $petId, $visited, $group);
                if (!empty($group)) {
                    $groups[] = $group;
                }
            }
        }

        // 各グループのペット情報を取得
        $familyGroups = [];
        foreach ($groups as $group) {
            $pets = Pet::whereIn('id', $group)->get();
            $familyGroups[] = [
                'pets' => $pets,
                'created_at' => $links->first()->created_at
            ];
        }

        return collect($familyGroups);
    }

    /**
     * 深さ優先探索でグループを構築（プライベートメソッド）
     * 
     * @param array $graph ペットIDの関係グラフ
     * @param int $petId 現在のペットID
     * @param array &$visited 訪問済みペットIDの配列（参照渡し）
     * @param array &$group 現在のグループのペットID配列（参照渡し）
     */
    private function dfs($graph, $petId, &$visited, &$group)
    {
        $visited[$petId] = true;
        $group[] = $petId;

        if (isset($graph[$petId])) {
            foreach ($graph[$petId] as $neighborId) {
                if (!isset($visited[$neighborId])) {
                    $this->dfs($graph, $neighborId, $visited, $group);
                }
            }
        }
    }

    /**
     * 検索条件に基づいて動的な見出しを生成
     * 
     * @param array $filters 検索条件
     * @param array $speciesNames 種別名の配列
     * @return string 生成された見出し
     */
    private function generateDynamicTitle(array $filters, array $speciesNames): string
    {
        $titleParts = [];

        // 優先度1: 種別の条件を追加
        if (!empty($filters['species']) && $filters['species'] !== 'all') {
            $titleParts[] = $speciesNames[$filters['species']];
        }

        // 優先度2: 性別の条件を追加
        if (!empty($filters['gender'])) {
            $genderNames = [
                'male' => 'オス',
                'female' => 'メス'
            ];
            $titleParts[] = $genderNames[$filters['gender']] ?? $filters['gender'];
        }

        // 優先度3: 保護施設の種別を追加
        if (!empty($filters['shelter_kind'])) {
            $shelterKindNames = [
                'facility' => '保護団体・施設',
                'site' => '里親サイト',
                'unknown' => '不明'
            ];
            $titleParts[] = $shelterKindNames[$filters['shelter_kind']] ?? $filters['shelter_kind'];
        }

        // 優先度4: 保護施設の所在地を追加
        if (!empty($filters['shelter_area'])) {
            $areaNames = [
                'hokkaido_tohoku' => '北海道・東北',
                'kanto' => '関東',
                'chubu_tokai' => '中部・東海',
                'kinki' => '近畿',
                'chugoku_shikoku' => '中国・四国',
                'kyushu_okinawa' => '九州・沖縄',
                'national' => '全国'
            ];
            $titleParts[] = $areaNames[$filters['shelter_area']] ?? $filters['shelter_area'];
        }

        // 優先度5: 特定の保護施設を追加（省略版）
        if (!empty($filters['shelter_id'])) {
            $shelter = \App\Models\Shelter::find($filters['shelter_id']);
            if ($shelter) {
                // 施設名を短縮（8文字以内に制限）
                $shortName = mb_strlen($shelter->name) > 8
                    ? mb_substr($shelter->name, 0, 8) . '...'
                    : $shelter->name;
                $titleParts[] = $shortName;
            }
        }

        // 見出しを組み立て
        if (empty($titleParts)) {
            return 'すべての家族';
        }

        return implode('×', $titleParts) . 'の家族';
    }
}
