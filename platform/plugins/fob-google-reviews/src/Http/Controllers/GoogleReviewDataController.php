<?php

namespace FriendsOfBotble\GoogleReviews\Http\Controllers;

use Botble\Base\Facades\PageTitle;
use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Supports\Breadcrumb;
use FriendsOfBotble\GoogleReviews\Forms\GoogleReviewDataForm;
use FriendsOfBotble\GoogleReviews\Http\Requests\GoogleReviewDataRequest;
use FriendsOfBotble\GoogleReviews\Models\GoogleReviewData;
use FriendsOfBotble\GoogleReviews\Tables\GoogleReviewDataTable;

class GoogleReviewDataController extends BaseController
{
    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add(trans('plugins/fob-google-reviews::google-reviews.name'))
            ->add(trans('plugins/fob-google-reviews::google-reviews.reviews'));
    }

    public function index(GoogleReviewDataTable $table)
    {
        PageTitle::setTitle(trans('plugins/fob-google-reviews::google-reviews.reviews'));

        return $table->renderTable();
    }

    public function create()
    {
        PageTitle::setTitle(trans('plugins/fob-google-reviews::google-reviews.create_review'));

        return GoogleReviewDataForm::create()->renderForm();
    }

    public function store(GoogleReviewDataRequest $request)
    {
        $form = GoogleReviewDataForm::create();

        $form
            ->setRequest($request)
            ->save();

        return $this
            ->httpResponse()
            ->withCreatedSuccessMessage()
            ->setPreviousRoute('google-reviews.index')
            ->setNextRoute('google-reviews.edit', $form->getModel()->getKey());
    }

    public function edit(GoogleReviewData $googleReview)
    {
        PageTitle::setTitle(trans('core/base::forms.edit_item', ['name' => $googleReview->author_name]));

        return GoogleReviewDataForm::createFromModel($googleReview)->renderForm();
    }

    public function update(GoogleReviewData $googleReview, GoogleReviewDataRequest $request)
    {
        GoogleReviewDataForm::createFromModel($googleReview)
            ->setRequest($request)
            ->save();

        return $this
            ->httpResponse()
            ->withUpdatedSuccessMessage()
            ->setPreviousRoute('google-reviews.index')
            ->setNextRoute('google-reviews.edit', $googleReview->getKey());
    }

    public function destroy(GoogleReviewData $googleReview)
    {
        return DeleteResourceAction::make($googleReview);
    }
}
