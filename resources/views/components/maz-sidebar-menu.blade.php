@props(['title', 'icon', 'active' => false])

<li class="sidebar-item {{ $active ? 'active' : '' }} has-sub">
    <a href="#" class='sidebar-link'>
        <i class="{{ $icon }}"></i>
        <span>{{ $title }}</span>
    </a>

    <ul class="submenu ">
        {{ $slot }}
    </ul>
</li>
