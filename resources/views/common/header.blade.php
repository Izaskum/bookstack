<header id="header" component="header-mobile-toggle" class="primary-background">
    <div class="grid mx-l">
        <div>
            <a href="{{ url('/shelves/tin-nong') }}" class="logo">
                @if (setting('app-logo', '') !== 'none')
                    <img class="logo-image"
                        src="{{ setting('app-logo', '') === '' ? url('/logo.png') : url(setting('app-logo', '')) }}"
                        alt="Logo">
                @endif
                @if (setting('app-name-header'))
                    <span class="logo-text">{{ setting('app-name') }}</span>
                @endif
            </a>
            <button type="button" refs="header-mobile-toggle@toggle" title="{{ trans('common.header_menu_expand') }}"
                aria-expanded="false" class="mobile-menu-toggle hide-over-l">@icon('more')</button>
        </div>

        <div class="flex-container-row justify-center hide-under-l">
            @if (hasAppAccess())
                <form action="{{ url('/search') }}" method="GET" class="search-box" role="search">
                    <button id="header-search-box-button" type="submit" aria-label="{{ trans('common.search') }}"
                        tabindex="-1">@icon('search') </button>
                    <input id="header-search-box-input" type="text" name="term"
                        aria-label="{{ trans('common.search') }}" placeholder="{{ trans('common.search') }}"
                        value="{{ isset($searchTerm) ? $searchTerm : '' }}">
                </form>
            @endif
        </div>

        <div class="text-right">
            <nav refs="header-mobile-toggle@menu" class="header-links">
                <div class="links text-center">
                    @if (hasAppAccess())
                        <a class="hide-over-l"
                            href="{{ url('/search') }}">@icon('search'){{ trans('common.search') }}</a>
                        @if (userCanOnAny('view', \BookStack\Entities\Models\Bookshelf::class) || userCan('bookshelf-view-all') || userCan('bookshelf-view-own'))
                            <a href="{{ url('/shelves') }}">@icon('bookshelf'){{ trans('entities.shelves') }}</a>
                        @endif
                        <a href="{{ url('/books') }}">@icon('books'){{ trans('entities.books') }}</a>
                        <a href="/homeStudent">Trắc nghiệm</a>
                        @if (signedInUser() && userCan('settings-manage'))
                            <a href="{{ url('/settings') }}">@icon('settings'){{ trans('settings.settings') }}</a>
                        @endif
                        @if (signedInUser() && userCan('users-manage') && !userCan('settings-manage'))
                            <a href="{{ url('/settings/users') }}">@icon('users'){{ trans('settings.users') }}</a>
                        @endif
                    @endif

                    @if (!signedInUser())
                        @if (setting('registration-enabled') && config('auth.method') === 'standard')
                            <a href="{{ url('/register') }}">@icon('new-user'){{ trans('auth.sign_up') }}</a>
                        @endif
                        <a href="{{ url('/login') }}">@icon('login'){{ trans('auth.log_in') }}</a>
                    @endif
                </div>
                @if (signedInUser())
                    <?php $currentUser = user(); ?>
                    <div class="dropdown-container" component="dropdown" option:dropdown:bubble-escapes="true">
                        <span class="user-name py-s hide-under-l" refs="dropdown@toggle" aria-haspopup="true"
                            aria-expanded="false" aria-label="{{ trans('common.profile_menu') }}" tabindex="0">
                            <img class="avatar" src="{{ $currentUser->getAvatar(30) }}"
                                alt="{{ $currentUser->name }}">
                            <span class="name">{{ $currentUser->getShortName(30) }}</span>
                            @icon('caret-down')
                        </span>
                        <ul refs="dropdown@menu" class="dropdown-menu" role="menu">
                            <li>
                                <a
                                    href="{{ url('/favourites') }}">@icon('star'){{ trans('entities.my_favourites') }}</a>
                            </li>
                            <li>
                                <a
                                    href="{{ $currentUser->getProfileUrl() }}">@icon('user'){{ trans('common.view_profile') }}</a>
                            </li>
                            <li>
                                <a
                                    href="{{ $currentUser->getEditUrl() }}">@icon('edit'){{ trans('common.edit_profile') }}</a>
                            </li>

                            <li>
                                @if (config('auth.method') === 'saml2')
                                    <a
                                        href="{{ url('/saml2/logout') }}">@icon('logout'){{ trans('auth.logout') }}</a>
                                @else
                                    <a href="{{ url('/logout') }}">@icon('logout'){{ trans('auth.logout') }}</a>
                                @endif
                            </li>

                        </ul>
                    </div>
                @endif
            </nav>
        </div>

    </div>
    <ul id="main-menu" class="sm sm-mint">
        @foreach (shelfs() as $shelf)
            @if (signedInUser() && userCan('bookshelf-view', $shelf))
                <li>
                    <a href="/shelves/{{ $shelf->slug }}">{{ $shelf->name }}</a>
                    @if ($shelf->books->count() > 0)
                        <ul>
                            @foreach ($shelf->books as $book)
                                @if (usercan('book-view', $book))
                                    <li>
                                        <a href="/books/{{ $book->slug }}">{{ $book->name }}</a>
                                        @if ($book->chapters->count() > 0 || $book->directPages->count() > 0)
                                            <ul>
                                                @if ($book->chapters->count() > 0)
                                                    @foreach ($book->chapters as $chapter)
                                                        @if (usercan('chapter-view', $chapter))
                                                            <li><a
                                                                    href="/books/{{ $book->slug }}/chapter/{{ $chapter->slug }}">{{ $chapter->name }}</a>
                                                                @if ($chapter->pages->count() > 0)
                                                                    <ul>
                                                                        @foreach ($chapter->pages->sortByDesc('created_at') as $cpage)
                                                                            @if (usercan('page-view', $cpage))
                                                                                <li><a
                                                                                        href="/books/{{ $book->slug }}/page/{{ $cpage->slug }}">{{ $cpage->name }}</a>
                                                                                </li>
                                                                            @endif
                                                                        @endforeach
                                                                    </ul>
                                                                @endif
                                                            </li>
                                                        @endif
                                                    @endforeach
                                                @endif
                                                @if ($book->directPages->count() > 0)
                                                    @foreach ($book->directPages->sortByDesc('created_at') as $page)
                                                        @if (usercan('page-view', $page))
                                                            <li><a
                                                                    href="/books/{{ $book->slug }}/page/{{ $page->slug }}">{{ $page->name }}</a>
                                                            </li>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </ul>
                                        @endif
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    @endif
                </li>
            @endif
        @endforeach
        @if (signedInUser())
            <li>
                <a href="/review/choose-question">Ôn tập</a>
                @if (userCan('settings-manage'))
                    <ul>
                        <li>
                            <a href="/review">Câu hỏi ôn tập</a>
                        </li>
                    </ul>
                @endif
            </li>
        @endif

        <li component='delete-button'></li>
    </ul>
</header>
