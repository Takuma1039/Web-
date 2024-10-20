<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 fixed top-0 w-full h-auto z-50" aria-label="Main Navigation">
    <!-- Primary Navigation Menu -->
    <div class="bg-white shadow">
        <div class="flex flex-col md:flex-row items-center py-4 px-4">
            <a href="/" aria-label="Dashboard">
                <h1 class="text-xl font-mono font-bold">DayTrip Planner</h1>
            </a>
            <div class="px-4">
                @include('components.breadcrumb') {{-- パンくずリスト --}}
            </div>
            
            @php
                $yourDeveloperId = 1; // 開発者のIDを指定
            @endphp
            
            <div class="ml-auto flex flex-col sm:flex-row items-center space-x-0 sm:space-x-4 space-y-2 sm:space-y-0">
                @if (auth()->check() && auth()->user()->id === $yourDeveloperId)
                    <form action="{{ route('history.clear') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-white text-red-500 border-2 border-red-500 rounded-full px-4 py-1 font-bold uppercase tracking-wide hover:bg-red-500 hover:text-white transition-all duration-300 flex items-center justify-center">履歴を削除</button>
                    </form>
                    <a href="/spots/create" class="bg-white text-gray-800 border-2 border-gray-800 rounded-full px-4 py-1 font-bold uppercase tracking-wide hover:bg-gray-800 hover:text-white transition-all duration-300 flex items-center justify-center">スポット作成</a>
                @endif
                
                <div class="flex flex-row items-center gap-2">
                    <!-- 新規登録 & ログインボタン -->
                    <div class="text-sm flex flex-row gap-2 items-center">
                        <form action="{{ route('history.clear') }}" method="POST" class="inline-block" id="history-btn">
                            @csrf
                            <button type="submit" id="clear-history" class="bg-white text-red-500 border-2 border-red-500 rounded-full px-4 py-1 font-bold uppercase tracking-wide hover:bg-red-500 hover:text-white transition-all duration-300 flex items-center justify-center">履歴クリア</button>
                        </form>
                        @guest
                            <a href="/register" class="bg-white text-gray-800 border-2 border-gray-800 rounded-full px-4 py-1 font-bold uppercase tracking-wide hover:bg-gray-800 hover:text-white transition-all duration-300 flex items-center justify-center">
                                新規登録
                            </a>
                            <a href="/login" class="bg-white text-gray-800 border-2 border-gray-800 rounded-full px-4 py-1 font-bold uppercase tracking-wide hover:bg-gray-800 hover:text-white transition-all duration-300 flex items-center justify-center">
                                ログイン
                            </a>
                        @endguest
                    </div>
                
                    <!-- Windowbar ボタン -->
                    <button class="text-gray-500 hover:text-gray-600 touch-manipulation" id="open-sidebar" aria-expanded="false" aria-controls="sidebar" @click="open = !open">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div id="sidebar" class="absolute z-50 text-white w-56 min-h-screen overflow-y-auto transition-transform transform -translate-x-full ease-in-out duration-300" :class="{'-translate-x-full': !open}">
        <!-- Your Sidebar Content -->
        <div class="flex flex-col flex-1 overflow-y-auto">
            <nav class="flex flex-col flex-1 overflow-y-auto bg-gradient-to-b from-zinc-950 to-zinc-400 px-2 py-4 gap-3 rounded-2xl">
                <!-- My Icon -->
                <div class="flex items-center">
                    @php
                        $user = Auth::user();
                        $isOnline = $user ? $user->isOnline() : false;
                    @endphp
                    @if ($user && $user->image_path)
                        <img class="h-10 w-10 rounded-full object-cover" src="{{ $user->image_path }}" alt="{{ $user->name }}" />
                    @else
                        <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                            <i class="fas fa-user text-gray-600 text-xl"></i>
                        </div>
                    @endif
                    <div class="ml-4 flex flex-col">
                        <p class="text-lg font-medium">{{ $user ? $user->name : 'ゲスト' }}</p>
                        @if ($user)
                            <span class="flex items-center">
                                <svg class="w-2 h-2 mr-2 {{ $isOnline ? 'text-green-500' : 'text-red-500' }}" fill="currentColor" viewBox="0 0 8 8" aria-label="{{ $isOnline ? 'オンライン' : 'オフライン' }}">
                                    <circle cx="4" cy="4" r="3"></circle>
                                </svg>
                                <p class="text-xs text-gray-400">{{ $isOnline ? 'オンライン' : 'オフライン' }}</p>
                            </span>
                        @else
                             <p class="text-xs text-gray-400">ゲストユーザー</p>
                        @endif
                    </div>
                </div>

                <div class="flex flex-col flex-1 gap-3">
                    @if (Auth::check())
                        <a href="/mypage" class="flex items-center px-4 py-2 mt-2 text-gray-100 hover:bg-gray-400 hover:bg-opacity-25 rounded-2xl" aria-label="My Page">
                            <i class="fas fa-house" style="margin-right: 8px;"></i>
                                マイページ
                        </a>
                        <a href="/profile" class="flex items-center px-4 py-2 mt-2 text-gray-100 hover:bg-gray-400 hover:bg-opacity-25 rounded-2xl" aria-label="Profile">
                            <i class="fas fa-id-card" style="margin-right: 8px;"></i>
                                プロフィール
                        </a>
                        <a href="/plans/create" class="flex items-center px-4 py-2 mt-2 text-gray-100 hover:bg-gray-400 hover:bg-opacity-25 rounded-2xl" aria-label="Create Travel Plan">
                            <i class="fas fa-plus-circle" style="margin-right: 8px;"></i>
                                旅行プラン作成
                        </a>
                    @endif
                    <a href="/planposts/index" class="flex items-center px-4 py-2 mt-2 text-gray-100 hover:bg-gray-400 hover:bg-opacity-25 rounded-2xl" aria-label="Favorites">
                        <i class="fas fa-people-group" style="margin-right: 8px;"></i>
                            みんなの旅行計画
                    </a>
                    <a href="/reviews" class="flex items-center px-4 py-2 mt-2 text-gray-100 hover:bg-gray-400 hover:bg-opacity-25 rounded-2xl" aria-label="Reviews">
                        <i class="fas fa-comment-dots" style="margin-right: 8px;"></i>
                            口コミ投稿一覧
                    </a>
                    <a href="/use" class="flex items-center px-4 py-2 mt-2 text-gray-100 hover:bg-gray-400 hover:bg-opacity-25 rounded-2xl" aria-label="Settings">
                        <i class="fas fa-cog" style="margin-right: 8px;"></i>
                            使い方
                    </a>
                    @if (Auth::check())
                        <a href="{{ route('logout') }}" 
                            class="flex items-center px-4 py-2 mt-2 text-gray-100 hover:bg-gray-400 hover:bg-opacity-25 rounded-2xl" 
                            aria-label="Logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt" style="margin-right: 8px;"></i>
                                ログアウト
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    @endif
                </div>
            </nav>
        </div>
    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const sidebarToggle = document.getElementById('open-sidebar');
        const sidebar = document.getElementById('sidebar');

        sidebarToggle.addEventListener('click', () => {
            const isOpen = sidebarToggle.getAttribute('aria-expanded') === 'true';
            sidebarToggle.setAttribute('aria-expanded', !isOpen);
            sidebar.classList.toggle('-translate-x-full', isOpen);
        });


        // サイドバー外をクリックした場合、サイドバーを閉じる
        document.addEventListener('click', (event) => {
            if (!sidebar.contains(event.target) && !sidebarToggle.contains(event.target)) {
                sidebar.classList.add('-translate-x-full');
                sidebarToggle.setAttribute('aria-expanded', 'false');
            }
        });
        
        //ナビゲーションバーとコンテンツの連動処理
        const navbar = document.querySelector('nav');
        const content = document.querySelector('.mx-auto');

        const adjustMarginTop = () => {
            const navbar_height = navbar.offsetHeight; //ナビゲーションバーの高さを取得
            content.style.marginTop = `${navbar_height + 20}px`; // ナビゲーションバーの高さ分だけmargin-topを設定
        };
        // 初回実行
        adjustMarginTop();

        //再調整用
        window.addEventListener('resize', adjustMarginTop);
    });
    
    document.getElementById('clear-history').addEventListener('click', function() {
        alert('本当に履歴をクリアしますか？');
    });
</script>

