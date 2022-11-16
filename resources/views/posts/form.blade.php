@csrf
<div class="form-group mt-2">

    <label class="form-control border-0 p-0">
        <input type="text" class="form-control" name='title' placeholder="Заголовок поста"
               value="{{ old('title') ?? $post->title ?? '' }}" required>
    </label>

    @error('title')
    <div class="alert alert-danger mt-1"> {{ $message }} </div>
    @enderror
</div>

<div class="form-group mt-2">
    <label for="excerpt"></label>
    <textarea class="form-control" name="excerpt" id="excerpt" rows="5"
              placeholder="Анонс поста"
              required>{{ old('excerpt') ?? $post->excerpt ?? '' }}</textarea>

    @error('excerpt')
    <div class="alert alert-danger mt-1"> {{ $message }} </div>
    @enderror
</div>

<div class="form-group mt-2">
    <label for="body"></label>
    <textarea class="form-control" name="body" id="body" rows="10" placeholder="Текст поста"
              required>{{ old('body')  ?? $post->body ?? '' }}</textarea>

    @error('body')
    <div class="alert alert-danger mt-1"> {{ $message }} </div>
    @enderror
</div>

<div class="form-group mt-2">
    <input type="file" class="form-control form-control-sm" name='image' value="{{ old('img') }}">
    @error('img')
    <div class="alert alert-danger mt-1"> {{ $message }} </div>
    @enderror
</div>
@isset($post->image)
    <div class="form-group form-check">
        <input type="checkbox" class="form-check-input" name="remove" id="remove">
        <label class="form-check-label" for="remove">
            Удалить загруженное <a href="{{ $post->image ?? '' }}" target="_blank">изображение</a>
        </label>
    </div>
@endisset

<div class="form-group  mt-2">
    <button type="submit" class="btn btn-primary">Сохранить пост</button>
</div>
