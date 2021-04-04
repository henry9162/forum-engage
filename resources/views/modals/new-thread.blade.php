@if (auth()->check())
    <thread inline-template>
        <modal name="new-thread" :min-width="350" :max-width="600" width="80%" :adaptive="true" height="auto" transition="slide">
            <form method="POST" @submit.prevent="postThread" class="p-6 py-8">
                {{ csrf_field() }}

                <div class="md:flex mb-6 -mx-4">
                    <div class="flex-1 mb-6 md:mb-0 px-4">
                        <label for="title" class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2">Title</label>
                        <input type="text" class="w-full p-2 leading-normal" id="title" name="title" value="{{ old('title') }}" v-model="form.title" required>
                    </div>

                    <div class="md:flex-1 px-4">
                        <label for="channel_id" class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2">Channel</label>

                        <select name="channel_id" v-model="form.channel_id" id="channel_id" class="block appearance-none w-full bg-white rounded-none border border-grey-light text-grey-darker py-2 px-4 leading-normal pr-8" required>
                            <option value="">Choose One...</option>

                            @foreach ($channels as $channel)
                                <option value="{{ $channel->id }}" {{ old('channel_id') == $channel->id ? 'selected' : '' }}>
                                    {{ $channel->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- <div class="mb-6">
                    <div class="hobbies">
                        <h3 v-if="enabled" class="block uppercase tracking-wide text-grey-darker text-xs font-bold mb-2">Add Images</h3>
                        <button v-if="enabled" @click="onAddImage" type="button" class="bg-blue-light text-white font-normal text-sm py-2 px-6 rounded font-semibold hover:bg-blue-dark mb-4">Add Image</button>
                            <div class="Image-list text-center">
                                <div class="input mb-2" v-for="(imageInput, index) in imageInputs" :key="imageInput.id">
                                    <label class="text-xs font-bold" :for="imageInput.id">Image #<span v-text="index"></span></label>
                                    <input type="file" ref="inputImage" :id="imageInput.id" @change="onChange" class="mr-1 text-sm text-white bg-blue-darkest">
                                    
                                    <button @click="onDeleteImage(imageInput.id)" type="button">
                                        <i class="fas fa-backspace"></i>
                                    </button>
                                </div>
                            </div>
                    </div>
                </div> -->


                <div class="mb-6">
                    <wysiwyg name="body" v-model="form.body"></wysiwyg>
                </div>

                <div class="mb-6">
                    <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.key') }}"></div>
                </div>

                <div class="flex justify-end">
                    <a href="#" class="btn mr-4" @click="$modal.hide('new-thread')">Cancel</a>
                    <button type="submit" class="btn is-green" :class="loading ? 'loader' : ''">Publish</button>
                </div>

                @if (count($errors))
                    <ul class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif
            </form>
        </modal>
    </thread> 
@endif
