<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use App\Models\PetFamilyLink;
use App\Models\Like;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PetController extends Controller
{
    public function index(Request $request): View
    {
        $pets = Pet::where('user_id', Auth::id())
            ->with('posts:id,pet_id', 'likes:id,pet_id', 'shelter:id,name')
            ->latest()
            ->get();

        return view('pets.index', compact('pets'));
    }

    public function create(Request $request): View
    {
        return view('pets.create');
    }

    public function edit(Request $request, $pet_id): View
    {
        $pet = Pet::where('user_id', Auth::id())
            ->where('id', $pet_id)
            ->with('shelter:id,name,area,kind')
            ->firstOrFail();
        return view('pets.create', compact('pet'));
    }

    public function show(Pet $pet): View
    {
        $pet->load(['user', 'shelter', 'posts' => function ($query) {
            $query->where('status', 'published')->latest()->limit(5);
        }]);

        // 現在のユーザーがこのペットにいいねしているかチェック
        $isLiked = false;
        if (Auth::check()) {
            $isLiked = Like::where('user_id', Auth::id())
                ->where('pet_id', $pet->id)
                ->exists();
        }

        // いいね数
        $likeCount = $pet->likes()->count();

        return view('pets.show', compact('pet', 'isLiked', 'likeCount'));
    }

    public function store(Request $request): RedirectResponse
    {
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

        // 画像アップロード（とりあえずローカルstorage/app/publicに保存）
        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('pets/profile', 'public');
            $pet->profile_image_url = '/storage/' . $path;
        } else {
            $pet->profile_image_url = '/images/icon.png';
        }

        if ($request->hasFile('header_image')) {
            $path = $request->file('header_image')->store('pets/header', 'public');
            $pet->header_image_url = '/storage/' . $path;
        } else {
            $pet->header_image_url = '/images/icon.png';
        }

        $pet->save();

        return redirect()->route('mypage.pets')->with('status', 'pet-created');
    }

    public function update(Request $request, $pet_id): RedirectResponse
    {
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

        // 画像アップロード処理（新しい画像がアップロードされた場合のみ更新）
        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('pets/profile', 'public');
            $pet->profile_image_url = '/storage/' . $path;
        }
        // 新しい画像がアップロードされない場合は既存の画像を保持

        if ($request->hasFile('header_image')) {
            $path = $request->file('header_image')->store('pets/header', 'public');
            $pet->header_image_url = '/storage/' . $path;
        }
        // 新しい画像がアップロードされない場合は既存の画像を保持

        $pet->save();

        return redirect()->route('mypage.pets')->with('status', 'pet-updated');
    }

    public function links(Request $request): View
    {
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

    public function saveLinks(Request $request): RedirectResponse
    {
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
     * 家族リンクをグループ化する
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
     * 深さ優先探索でグループを構築
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
}
