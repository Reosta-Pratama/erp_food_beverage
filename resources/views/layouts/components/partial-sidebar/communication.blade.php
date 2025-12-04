{{-- Communication (All Users) --}}
<li class="menu-category">
    <span class="category-name">Communication</span>
</li>

<li class="menu-item has-sub">
    <a href="javascript:void(0);" class="menu-link">
        <span class="menu-svg">
            <i class="ti ti-speakerphone"></i>
        </span>
        <span class="menu-label">Announcements</span>
        <i class="ri-arrow-right-s-line menu-icon"></i>
    </a>
    <ul class="menu-item-child child1">
        <li class="menu-item menu-label1">
            <a href="javascript:void(0)">Announcements</a>
        </li>

        {{-- Admin & Finance_HR can create --}}
        @if(auth()->user()->role->role_code === 'admin' || auth()->user()->role->role_code === 'finance_hr')
            @hasPermission('announcements.manage')
                <li class="menu-item">
                    <a href="javascript:void(0)" class="menu-link">Manage Announcements</a>
                </li>
                <li class="menu-item">
                    <a href="javascript:void(0)" class="menu-link">Meetings</a>
                </li>
                <li class="menu-item">
                    <a href="javascript:void(0)" class="menu-link">Broadcast Messages</a>
                </li>
            @endhasPermission
        @else
            {{-- Operator can only view --}}
            <li class="menu-item">
                <a href="javascript:void(0)" class="menu-link">View Announcements</a>
            </li>
            <li class="menu-item">
                <a href="javascript:void(0)" class="menu-link">My Meetings</a>
            </li>
        @endif
    </ul>
</li>