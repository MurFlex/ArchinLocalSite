<a href="http://192.168.0.15/dev" style="margin-right: 20px; margin-left: 20px" class="text-decoration-none text-dark"> <h3> ООО "ПРИБОРЭЛЕКТРО" </h3> </a>
<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
</button>
<div class="collapse navbar-collapse" id="navbarNavDropdown">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a id="main" class="nav-link" href="http://192.168.0.15/"> <h3> На главную </h3> </a>
        </li>
        <li>
            <a id="archin" class="nav-link" href="http://192.168.0.15/archin"> <h3> Аршин </h3> </a>
        </li>
        @if(request()->ip() == '192.168.0.15')
            <li class="nav-item">
                <a id="admin" class="nav-link" href="http://192.168.0.15/parse"> <h3> Админка </h3> </a>
            </li>
        @endif
    </ul>
</div>
