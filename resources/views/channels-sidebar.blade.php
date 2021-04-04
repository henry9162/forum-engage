<div class="hidden md:block md:bg-grey-light md:mr-5">
    <div class="mt-6 flex-shrink">
        <advert-carousel></advert-carousel>
    </div>

    <div class="mt-6 p-5 pb-1 rounded bg-white w-64 shadow">
        <div class="widget">
            <h4 class="widget-heading">Channels</h4>
    
            <ul class="list-reset">
                @foreach ($channels as $channel)
                    <li class="text-sm pb-3 flex">
                        <span class="rounded-full h-3 w-3 mr-2" style="background: {{ $channel->color }}"></span>
    
                        <a href="{{ route('channels', $channel) }}" class="hover:text-blue hover:font-bold">
                            {{ ucwords($channel->name) }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <div class="mt-6 flex-shrink">
        <advert-carousel></advert-carousel>
    </div>
</div>
