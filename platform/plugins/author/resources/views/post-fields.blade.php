<div class="form-group">
    <label for="author_id">{{ trans('plugins/author::author.author') }}</label>
    {!! Form::customSelect('author_id', ['' => trans('plugins/author::author.select_author')] + $authorsArray, $authorId, [
        'class' => 'form-control',
    ]) !!}
</div>
