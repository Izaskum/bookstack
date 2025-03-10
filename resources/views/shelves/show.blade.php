@extends('layouts.tri')

@push('social-meta')
    <meta property="og:description" content="{{ Str::limit($shelf->description, 100, '...') }}">
    @if($shelf->cover)
        <meta property="og:image" content="{{ $shelf->getBookCover() }}">
    @endif
@endpush

@section('body')

    <div class="mb-s">
        @include('entities.breadcrumbs', ['crumbs' => [
            $shelf,
        ]])
    </div>

    <main class="card content-wrap">

        <div class="flex-container-row wrap v-center">
            <h1 class="flex fit-content break-text">{{ $shelf->name }}</h1>
            <div class="flex"></div>
            <div class="flex fit-content text-m-right my-m ml-m">
                @include('entities.sort', ['options' => [
                    'default' => trans('common.sort_default'),                    
                    'name' => trans('common.sort_name'),
                    'created_at' => trans('common.sort_created_at'),
                    'updated_at' => trans('common.sort_updated_at'),
                    'no' => "Thứ tự hiển thị menu",
                ], 'order' => $order, 'sort' => $sort, 'type' => 'shelf_books'])
            </div>
        </div>

        <div class="book-content">
            <p class="text-muted">{!! nl2br(e($shelf->description)) !!}</p>
            @if(count($sortedVisibleShelfBooks) > 0)
                @if($view === 'list')
                    <div class="entity-list">
                        @foreach($sortedVisibleShelfBooks as $book)
                            @include('books.parts.list-item', ['book' => $book])
                        @endforeach
                    </div>
                @else
                    <div class="grid third">
                        @foreach($sortedVisibleShelfBooks as $book)
                            @include('entities.grid-item', ['entity' => $book])
                        @endforeach
                    </div>
                @endif
            @else
                <div class="mt-xl">
                    <hr>
                    <p class="text-muted italic mt-xl mb-m">{{ trans('entities.shelves_empty_contents') }}</p>
                    <div class="icon-list inline block">
                        @if(userCan('book-create-all') && userCan('bookshelf-update', $shelf))
                            <a href="{{ $shelf->getUrl('/create-book') }}" class="icon-list-item text-book">
                                <span class="icon">@icon('add')</span>
                                <span>{{ trans('entities.books_create') }}</span>
                            </a>
                        @endif
                        @if(userCan('bookshelf-update', $shelf))
                            <a href="{{ $shelf->getUrl('/edit') }}" class="icon-list-item text-bookshelf">
                                <span class="icon">@icon('edit')</span>
                                <span>{{ trans('entities.shelves_edit_and_assign') }}</span>
                            </a>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </main>

@stop

@section('left')
    @if(userCan('settings-manage'))
        @if($shelf->tags->count() > 0)
            <div id="tags" class="mb-xl">
                @include('entities.tag-list', ['entity' => $shelf])
            </div>
        @endif

        <div id="details" class="mb-xl">
            <h5>{{ trans('common.details') }}</h5>
            <div class="text-small text-muted blended-links">
                @include('entities.meta', ['entity' => $shelf])
                @if($shelf->restricted)
                    <div class="active-restriction">
                        @if(userCan('restrictions-manage', $shelf))
                            <a href="{{ $shelf->getUrl('/permissions') }}">@icon('lock'){{ trans('entities.shelves_permissions_active') }}</a>
                        @else
                            @icon('lock'){{ trans('entities.shelves_permissions_active') }}
                        @endif
                    </div>
                @endif
            </div>
        </div>

        @if(count($activity) > 0)
            <div class="mb-xl">
                <h5>{{ trans('entities.recent_activity') }}</h5>
                @include('common.activity-list', ['activity' => $activity])
            </div>
        @endif
    @endif
@stop

@section('right')
    @if(userCan('settings-manage'))
        <div class="actions mb-xl">
            <h5>{{ trans('common.actions') }}</h5>
            <div class="icon-list text-primary">
                @if(userCan('book-create-all') && userCan('bookshelf-update', $shelf))
                    <a href="{{ $shelf->getUrl('/create-book') }}" class="icon-list-item">
                        <span class="icon">@icon('add')</span>
                        <span>{{ trans('entities.books_new_action') }}</span>
                    </a>
                @endif

                @include('entities.view-toggle', ['view' => $view, 'type' => 'shelf'])

                <hr class="primary-background">

                @if(userCan('bookshelf-update', $shelf))
                    <a href="{{ $shelf->getUrl('/edit') }}" class="icon-list-item">
                        <span>@icon('edit')</span>
                        <span>{{ trans('common.edit') }}</span>
                    </a>
                @endif

                @if(userCan('restrictions-manage', $shelf))
                    <a href="{{ $shelf->getUrl('/permissions') }}" class="icon-list-item">
                        <span>@icon('lock')</span>
                        <span>{{ trans('entities.permissions') }}</span>
                    </a>
                @endif

                @if(userCan('bookshelf-delete', $shelf))
                    <a href="{{ $shelf->getUrl('/delete') }}" class="icon-list-item">
                        <span>@icon('delete')</span>
                        <span>{{ trans('common.delete') }}</span>
                    </a>
                @endif

                @if(signedInUser())
                    <hr class="primary-background">
                    @include('entities.favourite-action', ['entity' => $shelf])
                @endif

            </div>
        </div>
    @endif
@stop




