<?php

use Botble\Author\Models\Author;
use Botble\Base\Forms\FieldOptions\NumberFieldOption;
use Botble\Base\Forms\FieldOptions\SelectFieldOption;
use Botble\Base\Forms\Fields\NumberField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Blog\Forms\PostForm;
use Botble\Theme\Events\RenderingThemeOptionSettings;
PostForm::extend(function (PostForm $form) {
    $form->addAfter(
        'author_id',
        'editor',
        SelectField::class,
        SelectFieldOption::make()
            ->label(__('Editor'))
            ->choices(fn () => Author::query()->wherePublished()->orderBy('name')->pluck('name', 'id')->all())
            ->emptyValue(__('Select editor'))
            ->allowClear()
            ->searchable()
            ->metadata()
            ->toArray()
    );

    if (! auth()->user()?->isSuperUser()) {
        return $form;
    }

    return $form->addAfter(
        'editor',
        'views',
        NumberField::class,
        NumberFieldOption::make()
            ->label(__('Views'))
            ->min(0)
            ->max(2147483647)
            ->step(1)
            ->defaultValue((int) $form->getModel()->views)
            ->toArray()
    );
});

PostForm::beforeSaving(function (PostForm $form): void {
    if (! auth()->user()?->isSuperUser()) {
        return;
    }

    $views = filter_var($form->getRequest()->input('views'), FILTER_VALIDATE_INT, [
        'options' => ['min_range' => 0, 'max_range' => 2147483647],
    ]);

    if ($views !== false) {
        $form->getModel()->views = $views;
    }
});

app('events')->listen(RenderingThemeOptionSettings::class, function (): void {
    $copyrightField = theme_option()->getField('copyright');
    if ($copyrightField) {
        $copyrightField['attributes']['value'] = '©%Y Socialoud. All right reserved.';
        theme_option()->setField($copyrightField);
    }

    foreach ([
        [
            'id' => 'socialoud_footer_description',
            'section_id' => 'opt-text-subsection-general',
            'type' => 'textarea',
            'label' => __('Footer description'),
            'attributes' => [
                'name' => 'socialoud_footer_description',
                'value' => null,
                'options' => [
                    'class' => 'form-control',
                    'rows' => 3,
                    'placeholder' => __('Short description about your company'),
                ],
            ],
        ],
        [
            'id' => 'socialoud_company_address',
            'section_id' => 'opt-text-subsection-general',
            'type' => 'textarea',
            'label' => __('Company address'),
            'attributes' => [
                'name' => 'socialoud_company_address',
                'value' => null,
                'options' => [
                    'class' => 'form-control',
                    'rows' => 3,
                    'placeholder' => __('Full company address'),
                ],
            ],
        ],
        [
            'id' => 'socialoud_company_phone',
            'section_id' => 'opt-text-subsection-general',
            'type' => 'text',
            'label' => __('Company phone / WhatsApp'),
            'attributes' => [
                'name' => 'socialoud_company_phone',
                'value' => null,
                'options' => [
                    'class' => 'form-control',
                    'placeholder' => __('Example: +62 812 3456 7890'),
                ],
            ],
        ],
        [
            'id' => 'socialoud_company_email',
            'section_id' => 'opt-text-subsection-general',
            'type' => 'text',
            'label' => __('Company email'),
            'attributes' => [
                'name' => 'socialoud_company_email',
                'value' => null,
                'options' => [
                    'class' => 'form-control',
                    'placeholder' => __('Example: hello@example.com'),
                ],
            ],
        ],
    ] as $field) {
        theme_option()->setField($field);
    }
});
