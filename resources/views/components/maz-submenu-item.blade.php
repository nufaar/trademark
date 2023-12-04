@props(['title', 'link', 'active' => false])

<li class="submenu-item {{ $active ? 'active' : '' }} ">
    <a href="{{ $link }}" class="submenu-link">{{ $title }}</a>
</li>
