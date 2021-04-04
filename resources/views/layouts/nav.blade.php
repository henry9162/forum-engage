<nav class="nav-class bg-white">
    <div class="flex block my-2 mr-4 justify-between sm:hidden">
        <div class="ml-6">
            <a href="/">
                <img src="/images/header/newengagelogo.png" alt="logo" class="nav-image resize ml-1 rounded-full h-13 w-40 lg:absolute lg:pin-l lg:pin-t lg:-mt-20">
            </a>
        </div>

        <div>
            <button @click="toggle" class="flex items-center mr-4 mt-4 px-3 py-2 border rounded text-blue-dark border-blue-dark hover:text-black hover:border-white">
                <svg class="fill-current h-3 w-3" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><title>Menu</title><path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"/></svg>
            </button>
        </div>
    </div>
    

    <div class="container mx-auto shadow items-center md:py-3 text-blue-lightest pl-6 pr-4 lg:relative">
        <div :class="open ? 'block': 'hidden'" class="w-full flex-grow sm:flex sm:items-center sm:w-auto">
            <div class="text-sm sm:flex-grow flex justify-between">
                
                <div class="w-full lg:w-2/4">
                    <a href="/">
                        <div class="hidden md:block md:nav-image-container" style="margin-left: 12rem">
                            <img src="/images/header/newengagelogo.png" alt="logo" class="nav-image resize ml-10 rounded-full h-13 w-40 lg:absolute lg:pin-l lg:pin-t lg:-mt-20">
                            {{-- <span class="text-2xl" style="font-weight:bolder">{{ config('app.name', 'N.Y.S.C Engage') }}</span> --}}
                        </div>
                    </a>
                </div>
    
    
                <div class="justify-end flex md:mr-8" v-cloak>
                    <div class="search-wrap rounded-full bg-blue-darkest w-10 cursor-pointer h-10 flex items-center justify-center mr-4 relative" @mouseover="search" @mouseout="searching = false">
                        <form method="GET" action="/threads/search">
                            <input type="text"
                                    placeholder="Search..."
                                    name="q"
                                    ref="search"
                                    class="search-input absolute pin-r pin-t h-full rounded bg-blue-darkest border-none pl-3 pr-1 md:pl-6 md:pr-10 text-white">
                        </form>
        
                        @include('svgs.icons.search')
                    </div>
        
                    @if (auth()->check())
                        <chat-notifications></chat-notifications>
                        <user-notifications></user-notifications>
        
                        {{-- User dropdown. --}}
                        <div>
                            <dropdown>
                                <div slot="heading"
                                        class="rounded-full bg-blue-darkest w-10 h-10 flex items-center justify-center cursor-pointer relative z-10"
                                >
                                    <img 
                                        src="{{ auth()->user()->avatar_path }}" 
                                        alt="{{ auth()->user()->username }}" 
                                        class="relative z-10 w-6 rounded-full">
                                </div>
        
                                <template slot="links">
                                    <li class="text-sm pb-3">
                                        <a class="link" href="{{ route('profile', Auth::user()) }}">My Profile</a>
                                    </li>
        
                                    @if (Auth::user()->isAdmin())
                                        <li class="text-sm pb-3">
                                            <a class="link" href="{{ route('admin.dashboard.index') }}">Admin</a>
                                        </li>
                                    @endif
        
                                    <li class="text-sm">
                                        <logout-button route="{{ route('logout') }}" class="link">Logout</logout-button>
                                    </li>
                                </template>
                            </dropdown>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</nav>
