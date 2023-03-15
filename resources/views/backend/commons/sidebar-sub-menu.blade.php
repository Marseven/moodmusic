@if(\App\Models\Role::getValue($permission))
    <a class="collapse-item @if(Request::route()->getName() == $route) active @endif" href="{{ route($route) }}"><i class="fas {{ $icon }}"></i> <span>{{ $name }}</span></a>
@endif