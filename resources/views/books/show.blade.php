@extends('layouts.tri')

@section('container-attrs')
    component="entity-search"
    option:entity-search:entity-id="{{ $book->id }}"
    option:entity-search:entity-type="book"
@stop

@push('social-meta')
    <meta property="og:description" content="{{ Str::limit($book->description, 100, '...') }}">
    @if($book->cover)
        <meta property="og:image" content="{{ $book->getBookCover() }}">
    @endif
@endpush

@section('body')

    <div class="mb-s">
        @include('entities.breadcrumbs', ['crumbs' => [
            $book,
        ]])
    </div>

    <main class="content-wrap card">
        <h1 class="break-text">{{$book->name}}</h1>
        <div refs="entity-search@contentView" class="book-content">
            <p class="text-muted">{!! nl2br(e($book->description)) !!}</p>
            @if(count($bookChildren) > 0)
                <div class="entity-list book-contents">
                    @foreach($bookChildren as $childElement)
                        @if($childElement->isA('chapter'))
                            @include('chapters.parts.list-item', ['chapter' => $childElement])
                        @else
                            @include('pages.parts.list-item', ['page' => $childElement])
                        @endif
                    @endforeach
                </div>
            @else
                <div class="mt-xl">
                    <hr>
                    <p class="text-muted italic mb-m mt-xl">{{ trans('entities.books_empty_contents') }}</p>

                    <div class="icon-list block inline">
                        @if(userCan('page-create', $book))
                            <a href="{{ $book->getUrl('/create-page') }}" class="icon-list-item text-page">
                                <span class="icon">@icon('page')</span>
                                <span>{{ trans('entities.books_empty_create_page') }}</span>
                            </a>
                        @endif
                        @if(userCan('chapter-create', $book))
                            <a href="{{ $book->getUrl('/create-chapter') }}" class="icon-list-item text-chapter">
                                <span class="icon">@icon('chapter')</span>
                                <span>{{ trans('entities.books_empty_add_chapter') }}</span>
                            </a>
                        @endif
                    </div>

                </div>
            @endif
        </div>

        @include('entities.search-results')
    </main>

@stop

@section('right')
    @if(userCan('settings-manage'))
        <div class="mb-xl">
            <h5>{{ trans('common.details') }}</h5>
            <div class="text-small text-muted blended-links">
                @include('entities.meta', ['entity' => $book])
                @if($book->restricted)
                    <div class="active-restriction">
                        @if(userCan('restrictions-manage', $book))
                            <a href="{{ $book->getUrl('/permissions') }}">@icon('lock'){{ trans('entities.books_permissions_active') }}</a>
                        @else
                            @icon('lock'){{ trans('entities.books_permissions_active') }}
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <div class="actions mb-xl">
            <h5>{{ trans('common.actions') }}</h5>
            <div class="icon-list text-primary">

                @if(userCan('page-create', $book))
                    <a href="{{ $book->getUrl('/create-page') }}" class="icon-list-item">
                        <span>@icon('add')</span>
                        <span>{{ trans('entities.pages_new') }}</span>
                    </a>
                @endif
                @if(userCan('chapter-create', $book))
                    <a href="{{ $book->getUrl('/create-chapter') }}" class="icon-list-item">
                        <span>@icon('add')</span>
                        <span>{{ trans('entities.chapters_new') }}</span>
                    </a>
                @endif

                <hr class="primary-background">

                @if(userCan('book-update', $book))
                    <a href="{{ $book->getUrl('/edit') }}" class="icon-list-item">
                        <span>@icon('edit')</span>
                        <span>{{ trans('common.edit') }}</span>
                    </a>
                    <a href="{{ $book->getUrl('/sort') }}" class="icon-list-item">
                        <span>@icon('sort')</span>
                        <span>{{ trans('common.sort') }}</span>
                    </a>
                @endif
                @if(userCan('restrictions-manage', $book))
                    <a href="{{ $book->getUrl('/permissions') }}" class="icon-list-item">
                        <span>@icon('lock')</span>
                        <span>{{ trans('entities.permissions') }}</span>
                    </a>
                @endif
                @if(userCan('book-delete', $book))
                    <a href="{{ $book->getUrl('/delete') }}" class="icon-list-item">
                        <span>@icon('delete')</span>
                        <span>{{ trans('common.delete') }}</span>
                    </a>
                @endif

                <hr class="primary-background">

                @if(signedInUser())
                    @include('entities.favourite-action', ['entity' => $book])
                @endif
                @if(userCan('content-export'))
                    @include('entities.export-menu', ['entity' => $book])
                @endif
            </div>
        </div>
    @endif
@stop

@section('left')
    @if(userCan('settings-manage'))
        @include('entities.search-form', ['label' => trans('entities.books_search_this')])

        @if($book->tags->count() > 0)
            <div class="mb-xl">
                @include('entities.tag-list', ['entity' => $book])
            </div>
        @endif

        @if(count($bookParentShelves) > 0)
            <div class="actions mb-xl">
                <h5>{{ trans('entities.shelves_long') }}</h5>
                @include('entities.list', ['entities' => $bookParentShelves, 'style' => 'compact'])
            </div>
        @endif

        @if(count($activity) > 0)
            <div class="mb-xl">
                <h5>{{ trans('entities.recent_activity') }}</h5>
                @include('common.activity-list', ['activity' => $activity])
            </div>
        @endif
    @endif
@stop

