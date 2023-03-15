@if(\App\Models\Role::getValue($permission))
    <li class="nav-item @if(Request::route()->getName() == $route) active @endif"><a class="nav-link" href="{{ route($route) }}"><i class="fas {{ $icon }}"></i> <span>{{ $name }}</span></a></li>
@endif