@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto p-6 bg-white rounded shadow">
        <h2 class="text-2xl font-bold text-green-700 mb-6">Edit Item</h2>

        <form action="{{ route('items.update', $item->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block font-semibold mb-1">Item Name</label>
                <input type="text" name="name" class="w-full border rounded p-2"
                       value="{{ old('name', $item->name) }}" required>
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-1">Description</label>
                <textarea name="description" class="w-full border rounded p-2" rows="4">{{ old('description', $item->description) }}</textarea>
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-1">Category</label>
                <select name="category_id" class="w-full border rounded p-2" required>
                    <option value="">-- Select Category --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ $item->category_id == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-1">Condition</label>
                <select name="item_condition" class="w-full border rounded p-2" required>
                    <option value="new" {{ $item->item_condition == 'new' ? 'selected' : '' }}>New</option>
                    <option value="used" {{ $item->item_condition == 'used' ? 'selected' : '' }}>Used</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block font-semibold mb-1">Deposit Amount (VND)</label>
                <input type="number" name="deposit_amount" min="0" class="w-full border rounded p-2"
                       value="{{ old('deposit_amount', $item->deposit_amount) }}" required>
            </div>

            <div class="mb-6" x-data="fileUpload()">
                <input type="hidden" name="deleted_image_ids" :value="JSON.stringify(deletedImageIds)">
                <label class="block font-semibold mb-2 text-gray-700">Images</label>

                <!-- Current Images -->
                <div class="mb-4">
                    <h3 class="text-sm font-medium text-gray-700 mb-2">Current Images</h3>
                    <div class="grid grid-cols-3 gap-4">
                        @foreach($item->images as $image)
                            <div class="relative group" data-image-id="{{ $image->id }}">
                                <img src="{{ asset('/'.$image->image_url) }}"
                                     class="h-32 w-full object-cover rounded border">
                                <button type="button"
                                        @click="deleteExistingImage('{{ $image->id }}')"
                                        class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                    ✕
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>

                <input type="file" name="images[]" multiple accept=".png,.jpg,.jpeg" class="hidden"
                       x-ref="fileInput" @change="handleFileSelect($event)">

                <!-- Upload Area for New Images -->
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center cursor-pointer hover:border-green-500 transition-colors duration-200"
                     @click="$refs.fileInput.click()">

                    <template x-if="previews.length === 0">
                        <div>
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <p class="mt-2 text-sm text-gray-600">
                                <span class="font-medium text-green-600 hover:text-green-500">Click to upload</span> or drag and drop
                            </p>
                            <p class="text-xs text-gray-500">PNG, JPG, JPEG up to 2MB each (max 5 images)</p>
                        </div>
                    </template>

                    <div class="grid grid-cols-3 gap-4 mt-4" x-show="previews.length > 0">
                        <template x-for="(preview, index) in previews" :key="index">
                            <div class="relative group">
                                <img :src="preview.image" class="h-32 w-full object-cover rounded border">
                                <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-50 text-white p-1 text-xs truncate"
                                     x-text="preview.name"></div>
                                <button type="button" @click.stop="removePreview(index)"
                                        class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                    ✕
                                </button>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <div class="flex justify-between">
                <a href="{{ route('items.show', $item->id) }}"
                   class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400">
                    Cancel
                </a>
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                    Update Item
                </button>
            </div>

        </form>

        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('fileUpload', () => ({
                    previews: [],
                    deletedImageIds: [],

                    init() {
                        // Không cần event listener nữa vì xử lý qua form submit
                    },

                    handleFileSelect(event) {
                        const files = event.target.files;
                        if (!files || files.length === 0) return;

                        if (this.previews.length + files.length > 5) {
                            alert('You can upload maximum 5 images');
                            event.target.value = '';
                            return;
                        }

                        Array.from(files).forEach(file => {
                            if (file.size > 2 * 1024 * 1024) {
                                alert(`File ${file.name} is too large (max 2MB)`);
                                return;
                            }

                            const reader = new FileReader();
                            reader.onload = (e) => {
                                this.previews.push({
                                    image: e.target.result,
                                    name: file.name,
                                    file: file
                                });
                            };
                            reader.readAsDataURL(file);
                        });
                    },

                    removePreview(index) {
                        this.previews.splice(index, 1);
                        this.updateFileInput();
                    },

                    deleteExistingImage(imageId) {
                        if (confirm('Are you sure you want to delete this image?')) {
                            this.deletedImageIds.push(imageId);
                            document.querySelector(`[data-image-id="${imageId}"]`).remove();
                        }
                    },
                    updateFileInput() {
                        const input = this.$refs.fileInput;
                        const dataTransfer = new DataTransfer();

                        this.previews.forEach(preview => {
                            dataTransfer.items.add(preview.file);
                        });

                        input.files = dataTransfer.files;
                    },

                    ['@dragover.prevent']() {},
                    ['@drop.prevent'](event) {
                        this.$refs.fileInput.files = event.dataTransfer.files;
                        this.handleFileSelect({ target: this.$refs.fileInput });
                    }
                }));
            });
        </script>
    </div>
@endsection
