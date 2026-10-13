<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\User;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(): View
    {
        $articles = Article::with('user')->latest()->get();
        $users = User::orderBy('name')->get();

        return view('admin.dashboard', compact('articles', 'users'));
    }
}
