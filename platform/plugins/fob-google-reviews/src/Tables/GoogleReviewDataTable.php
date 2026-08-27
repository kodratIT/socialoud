<?php

namespace FriendsOfBotble\GoogleReviews\Tables;

use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\Actions\DeleteAction;
use Botble\Table\Actions\EditAction;
use Botble\Table\BulkActions\DeleteBulkAction;
use Botble\Table\BulkChanges\CreatedAtBulkChange;
use Botble\Table\BulkChanges\NameBulkChange;
use Botble\Table\BulkChanges\StatusBulkChange;
use Botble\Table\Columns\Column;
use Botble\Table\Columns\CreatedAtColumn;
use Botble\Table\Columns\DateColumn;
use Botble\Table\Columns\IdColumn;
use Botble\Table\Columns\NameColumn;
use Botble\Table\Columns\StatusColumn;
use Botble\Table\HeaderActions\CreateHeaderAction;
use FriendsOfBotble\GoogleReviews\Models\GoogleReviewData;
use Illuminate\Database\Eloquent\Builder;

class GoogleReviewDataTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(GoogleReviewData::class)
            ->addHeaderAction(CreateHeaderAction::make()->route('google-reviews.create'))
            ->addActions([
                EditAction::make()->route('google-reviews.edit'),
                DeleteAction::make()->route('google-reviews.destroy'),
            ])
            ->addColumns([
                IdColumn::make(),
                NameColumn::make('author_name')
                    ->title(trans('plugins/fob-google-reviews::google-reviews.table.author_name'))
                    ->route('google-reviews.edit'),
                Column::make('rating')
                    ->title(trans('plugins/fob-google-reviews::google-reviews.table.rating'))
                    ->alignCenter()
                    ->width(100),
                DateColumn::make('review_date')
                    ->title(trans('plugins/fob-google-reviews::google-reviews.table.review_date'))
                    ->width(150),
                Column::make('order')
                    ->title(trans('plugins/fob-google-reviews::google-reviews.table.order'))
                    ->alignCenter()
                    ->width(100),
                CreatedAtColumn::make(),
                StatusColumn::make(),
            ])
            ->addBulkActions([
                DeleteBulkAction::make()->permission('google-reviews.destroy'),
            ])
            ->addBulkChanges([
                NameBulkChange::make()
                    ->name('author_name')
                    ->title(trans('plugins/fob-google-reviews::google-reviews.table.author_name')),
                StatusBulkChange::make(),
                CreatedAtBulkChange::make(),
            ])
            ->queryUsing(function (Builder $query) {
                return $query
                    ->select([
                        'id',
                        'author_name',
                        'rating',
                        'review_date',
                        'order',
                        'status',
                        'created_at',
                    ])
                    ->orderBy('order')
                    ->latest('review_date');
            });
    }
}
