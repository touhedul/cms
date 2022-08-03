<li id="cms" class="nav-item"><a href="#"><i class="fa fa-folder"></i><span class="menu-title" data-i18n="">CMS</span></a>
    <ul class="menu-content">
        <li id="blog"><a class="menu-item" href="/admin/blog">Blog</a></li>
        <li id="pages"><a class="menu-item" href="/admin/pages">Pages</a></li>
        @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('manager') || Auth::user()->hasRole('director'))
        <li id="documents" class="nav-item"><a href="/admin/documents"><i class="ft-file"></i><span class="menu-title" data-i18n="">Documents</span></a>
        </li>
        @endif
    </ul>
</li>