<div class="page-wrapper has-sidebar">
    <div class="page-inner page-inner-fill w-100">
        <div class="conversations">
            <div class="message-header">
                <div class="message-title">
                    <a class="btn btn-light" href="{{route('chat')}}">
                        <i class="fa fa-flip-horizontal fa-share"></i>
                    </a>
                    <div class="user ml-2">
                        @if (auth()->user()->role->role_type == 'admin')
                        <div class="avatar avatar-offline">
                            <img src="{{$chat->user->profile_photo_url}}" alt="..."
                                class="avatar-img rounded-circle border border-white">
                        </div>
                        <div class="info-user ml-2">
                            <span class="name">{{$chat->user->name}}</span>
                            <span class="last-active">Member</span>
                        </div>
                        @else
                        <div class="avatar avatar-offline">
                            <img src="{{$chat->seller->profile_photo_url}}" alt="..."
                                class="avatar-img rounded-circle border border-white">
                        </div>
                        <div class="info-user ml-2">
                            <span class="name">{{$chat->seller->name}}</span>
                            <span class="last-active">Penjual</span>
                        </div>
                        @endif

                    </div>
                </div>
            </div>
            <div class="conversations-body">
                <div class="conversations-content bg-white">
                    @foreach ($chats as $chatt)
                    @if ($chatt->sender_id != auth()->user()->id)
                    <div class="message-content-wrapper">
                        <div class="message message-in">
                            <div class="avatar avatar-sm">
                                @if (auth()->user()->role->role_type == 'admin')
                                <img src="{{$chat->user->profile_photo_url}}" alt="..."
                                    class="avatar-img rounded-circle border border-white">
                                @else
                                <img src="{{$chat->seller->profile_photo_url}}" alt="..."
                                    class="avatar-img rounded-circle border border-white">
                                @endif
                            </div>
                            <div class="message-body">
                                <div class="message-content">
                                    @if (auth()->user()->role->role_type == 'admin')
                                    <div class="name">{{$chat->user->name}}</div>
                                    @else
                                    <div class="name">{{$chat->seller->name}}</div>
                                    @endif
                                    <div class="content">{{$chatt->pesan}}</div>
                                </div>
                                @foreach ($chatt->childrens as $item)
                                <div class="message-content">
                                    <div class="content">
                                        {{$item->pesan}}
                                    </div>
                                </div>
                                @endforeach
                                @if (count($chatt->childrens) > 0)
                                <div class="date">{{date('H:i',strtotime($item->created_at))}}</div>
                                @else
                                <div class="date">{{date('H:i',strtotime($chatt->created_at))}}</div>
                                @endif

                            </div>
                        </div>
                    </div>
                    @endif

                    @if ($chatt->sender_id == auth()->user()->id)
                    <div class="message-content-wrapper">
                        <div class="message message-out">
                            <div class="message-body">
                                <div class="message-content">
                                    <div class="content">
                                        {{$chatt->pesan}}
                                    </div>
                                </div>
                                @foreach ($chatt->childrens as $item)
                                <div class="message-content">
                                    <div class="content">
                                        {{$item->pesan}}
                                    </div>
                                </div>
                                @endforeach
                                @if (count($chatt->childrens) > 0)
                                <div class="date">{{date('H:i',strtotime($item->created_at))}}</div>
                                @else
                                <div class="date">{{date('H:i',strtotime($chatt->created_at))}}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>
            <div class="messages-form">
                <div class="messages-form-control">
                    <input type="text" placeholder="Type here" class="form-control input-pill input-solid message-input"
                        wire:model="pesan">
                </div>
                <div class="messages-form-tool">
                    <a href="#" class="attachment" wire:click="send">
                        <i class="fas fa-paper-plane"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>