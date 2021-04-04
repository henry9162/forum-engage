@forelse ($threads as $thread)
    <div class="flex {{ $loop->last ? '' : 'pb-4' }}">
        {{-- <div class="mr-4">
            <img src="{{ $thread->creator->avatar_path }}"
                     alt="{{ $thread->creator->username }}"
                     class="w-8 h-8 bg-blue-darker rounded-full p-2">
        </div> --}}

        <div class="flex-1 {{ $loop->last ? '' : 'border-b border-blue-lightest' }}">
            <h3 class="font-normal mb-2 tracking-tight">
                <a href="{{ $thread->path() }}" class="text-blue">
                    @if ($thread->pinned)
                        <span class="mr-2"><i class="fas fa-thumbtack"></i></span>
                    @endif

                    @if (auth()->check() && $thread->hasUpdatesFor(auth()->user()))
                        <strong>
                            {{ $thread->title }}
                        </strong>
                    @else
                        {{ $thread->title }}
                    @endif
                </a>
            </h3>

            <p class="text-2xs text-grey-darkest mb-2">
                Posted By: <a href="{{ route('profile', $thread->creator) }}" class="text-blue">{{$thread->creator->isAdmin() ? 'admin' : $thread->creator->username }}</a>
            </p>

            @if (count($thread->pictures))
                <div class="mt-2">
                    <div class="container">
                        <div class="grid">
                            @foreach ($thread->pictures as $picture)
                                <a href="{{ $thread->path() }}">
                                    <img class="resize rounded h-32" src="{{ $picture->url }}" alt="">
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <thread-view :thread="{{ $thread }}" inline-template class="mb-2 mt-4 text-grey-darkest leading-loose pr-1">
                <highlight :content="body"></highlight>
            </thread-view>

            <div class="flex items-center text-xs mb-6">
                <a class="btn bg-grey-light text-grey-darkest py-2 px-3 mr-4 text-2xs flex items-center" href="/threads/{{ $thread->channel->slug }}">
                    <span class="rounded-full h-2 w-2 mr-2" style="background: {{ $thread->channel->color }}"></span>

                    {{ ucwords($thread->channel->name) }}
                </a>

                <span class="mr-2 flex items-center text-grey-darker text-2xs font-semibold mr-4">
                    @include ('svgs.icons.eye', ['class' => 'mr-2'])
                    {{ $thread->visits }} visits
                </span>

                <a href="{{ $thread->path() }}" class="mr-2 flex items-center text-grey-darker text-2xs font-semibold">
                    @include ('svgs.icons.book', ['class' => 'mr-2'])
                    {{ $thread->replies_count }} {{ str_plural('reply', $thread->replies_count) }}
                </a>

                <a class="btn ml-auto is-outlined text-grey-darker py-2 text-xs" href="{{ $thread->path() }}">read more</a>
            </div>
        </div>
    </div>
@empty
    <p class="text-sm">There are no relevant results at this time.</p>
@endforelse
