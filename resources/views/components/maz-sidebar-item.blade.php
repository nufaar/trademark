@props(['title', 'link', 'icon', 'active' => false])

<li class="sidebar-item  {{ $active ? 'active' : '' }}">
    <a href="{{ $link }}" class='sidebar-link'>
        <i class="{{ $icon }}"></i>
        <span>{{ $title }}</span>
    </a>
</li>
