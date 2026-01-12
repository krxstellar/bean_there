@extends('layouts.admin')

@section('admin-content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h1 style="font-family: 'Cooper Black', serif; font-size: 2.5rem; color: #4A2C2A; margin: 0;">Notifications</h1>
        <form method="POST" action="{{ route('admin.notifications.markAllRead') }}">
            @csrf
            @method('PATCH')
            <button type="submit" style="background: #4A2C2A; border: none; color: #ffffff; padding: 8px 16px; border-radius: 10px; font-family: 'Poppins'; font-size: 13px; font-weight: 600; cursor: pointer;">
                Mark all as read
            </button>
        </form>
    </div>

    <div style="font-family: 'Poppins', sans-serif;">
        @if($notifications->isEmpty())
            <p style="color:#777; padding:40px; text-align:center;">No notifications.</p>
        @endif

        @foreach($notifications as $n)
            @php
                $data = $n->data;
                $isUnread = is_null($n->read_at);
                $isOrder = !empty($data['order_id']);
                $isProduct = !empty($data['product_id']);

                if ($isOrder) {
                    $containerStyle = $isUnread ? 'background:#F6FFED;border-left:5px solid #52C41A;' : 'background:white;border:1.5px solid #F0F2F5;opacity:0.85;';
                    $iconClass = 'fa-cart-shopping';
                    $iconColor = $isUnread ? '#52C41A' : '#4A2C2A';
                    $iconBorder = $isUnread ? '#B7EB8F' : '#E6E6E6';
                } else {
                    $containerStyle = $isUnread ? 'background:#FFF1F0;border-left:5px solid #F5222D;' : 'background:white;border:1.5px solid #F0F2F5;opacity:0.85;';
                    $iconClass = $isUnread ? 'fa-triangle-exclamation' : 'fa-check-double';
                    $iconColor = $isUnread ? '#F5222D' : '#4A2C2A';
                    $iconBorder = $isUnread ? '#FFA39E' : '#E6E6E6';
                }
            @endphp

            <div style="padding: 20px; border-radius: 15px; margin-bottom: 15px; display: flex; align-items: center; gap: 20px; position: relative; {{ $containerStyle }}">
                <div style="background: white; width: 45px; height: 45px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: {{ $iconColor }}; border: 1px solid {{ $iconBorder }};">
                    <i class="fa-solid {{ $iconClass }}"></i>
                </div>

                <div style="flex-grow: 1; display:flex; justify-content:space-between; align-items:center;">
                    <div>
                        <h4 style="margin: 0; color: #4A2C2A; font-size: 15px;">
                            @if($isOrder)
                                <a href="{{ route('admin.orders.show', $data['order_id']) }}" style="color:#4A2C2A; text-decoration:underline;">Order #{{ $data['order_id'] }}</a>
                                <span style="font-size:12px;color:#888;margin-left:8px;">&middot; New Order</span>
                            @elseif($isProduct)
                                <a href="{{ route('admin.products.show', $data['product_id']) }}" style="color:#4A2C2A; text-decoration:underline;">{{ $data['product_name'] }}</a>
                                <span style="font-size:12px;color:#888;margin-left:8px;">&middot; Low Stock Alert</span>
                            @else
                                {{ $data['title'] ?? 'Notification' }}
                            @endif
                        </h4>

                        <p style="margin: 5px 0 0; font-size: 13px; color: #666;">
                            {{ $data['note'] ?? ($data['message'] ?? '') }}
                            @if(!empty($data['reporter_name']))
                                <br><small style="color:#888;">Reported by: <strong>{{ $data['reporter_name'] }}</strong></small>
                            @endif
                            @if(!empty($data['customer_name']))
                                <br><small style="color:#888;">Customer: <strong>{{ $data['customer_name'] }}</strong></small>
                            @endif
                        </p>
                    </div>

                    <div style="text-align:right;">
                        <span style="font-size: 11px; color: #AEA9A0;">{{ $n->created_at->diffForHumans() }}</span>
                        @if($isOrder)
                            <div style="margin-top:8px;">
                                <a href="{{ route('admin.notifications.viewAndRedirect', $n->id) }}" style="display:inline-block;background:#f6ffed;border:1px solid #d9f7be;padding:6px 10px;border-radius:8px;font-size:12px;color:#237804;text-decoration:none;font-weight:600;">View Order</a>
                            </div>
                        @else
                            <form method="POST" action="{{ route('admin.notifications.markRead', $n->id) }}" style="margin-top:8px;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" style="display:inline-block;background:#fff1f0;border:1px solid #ffa39e;padding:6px 10px;border-radius:8px;font-size:12px;color:#cf1322;text-decoration:none;font-weight:600;">Restocked</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach

        <div style="margin-top:20px;">
            {{ $notifications->links() }}
        </div>
    </div>
@endsection