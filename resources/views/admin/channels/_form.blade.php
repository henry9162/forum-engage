{{ csrf_field() }}
<div class="mb-4">
    <label for="name" class="tracking-wide font-extrabold uppercase text-grey-darker text-xs block pb-2">Name</label>
    <input type="text" class="form-control w-full p-2 leading-normal rounded" id="name" name="name" value="{{ old('name', $channel->name) }}" required>
</div>

<div class="mb-4">
    <label for="description" class="tracking-wide font-extrabold uppercase text-grey-darker text-xs block pb-2">Description</label>
    <input type="text" class="form-control w-full p-2 leading-normal rounded" id="description" name="description" value="{{ old('description', $channel->description) }}" required>
</div>

<div class="mb-4">
    <label for="color" class="tracking-wide font-extrabold uppercase text-grey-darker text-xs block pb-2">Color</label>
    <input type="text" class="form-control w-full p-2 leading-normal rounded" id="color" name="color" value="{{ old('color', $channel->color) }}" required>
</div>

<div class="flex justify-between">
    <div>
        <label for="archived" class="tracking-wide font-extrabold uppercase text-grey-darker text-xs block pb-2">Status</label>
    
        <select name="archived" id="archived" class="form-control text-xs p-1 rounded">
            <option value="0" {{ old('archived', $channel->archived) ? '' : 'selected' }}>Active</option>
            <option value="1" {{ old('archived', $channel->archived) ? 'selected' : '' }}>Archived</option>
        </select>
    </div>
        
    <div class="mt-5">
        <button type="submit" class="btn bg-blue">{{ $buttonText ?? 'Add Channel' }}</button>
    </div>
</div>

@if (count($errors))
    <ul class="alert alert-danger">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif
