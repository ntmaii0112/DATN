@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto p-6 bg-white rounded shadow">
        <h2 class="text-2xl font-bold text-green-700 mb-6">Create New Item</h2>

        <form action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label class="block font-semibold mb-1">Item Name</label>
                <input type="text" name="name" class="w-full border rounded p-2" required>
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-1">Description</label>
                <textarea name="description" class="w-full border rounded p-2" rows="4"></textarea>
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-1">Category</label>
                <select name="category_id" class="w-full border rounded p-2" required>
                    <option value="">-- Select Category --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-1">Condition</label>
                <select name="item_condition" class="w-full border rounded p-2" required>
                    <option value="new">New</option>
                    <option value="used">Used</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-1">Status</label>
                <select name="status" class="w-full border rounded p-2" required>
                    <option value="available">Available</option>
                    <option value="unavailable">Unavailable</option>
                </select>
            </div>

            <div class="mb-6" x-data="fileUpload()">
                <label class="block font-semibold mb-2 text-gray-700">Images (Multiple)</label>

                <!-- File Input -->
                <input type="file" name="images[]" multiple accept="image/*" class="hidden"
                       x-ref="fileInput" @change="handleFileSelect($event)">

                <!-- Upload Area -->
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center cursor-pointer hover:border-green-500 transition-colors duration-200"
                     @click="$refs.fileInput.click()">

                    <!-- Upload Prompt -->
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

                    <!-- Image Previews -->
                    <div class="grid grid-cols-3 gap-4 mt-4" x-show="previews.length > 0">
                        <template x-for="(preview, index) in previews" :key="index">
                            <div class="relative group">
                                <img :src="preview.image" class="h-32 w-full object-cover rounded border">
                                <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-50 text-white p-1 text-xs truncate" x-text="preview.name"></div>
                                <button type="button" @click.stop="removePreview(index)"
                                        class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                    ✕
                                </button>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <script>
                document.addEventListener('alpine:init', () => {
                    Alpine.data('fileUpload', () => ({
                        previews: [],

                        init() {
                            console.log('File upload component initialized');
                        },

                        handleFileSelect(event) {
                            const files = event.target.files;
                            if (!files || files.length === 0) return;

                            // Clear previous selections if not holding Ctrl/Cmd
                            if (!event.ctrlKey && !event.metaKey) {
                                this.previews = [];
                            }

                            if (this.previews.length + files.length > 5) {
                                alert('Maximum 5 images allowed in total');
                                event.target.value = '';
                                return;
                            }

                            Array.from(files).forEach(file => {
                                if (file.size > 2 * 1024 * 1024) {
                                    alert(`File ${file.name} exceeds 2MB limit and was skipped`);
                                    return;
                                }

                                const reader = new FileReader();
                                reader.onload = (e) => {
                                    this.previews.push({
                                        image: e.target.result,
                                        name: file.name,
                                        size: file.size,
                                        file: file  // Keep original file reference
                                    });
                                };
                                reader.readAsDataURL(file);
                            });
                        },

                        removePreview(index) {
                            this.previews.splice(index, 1);
                            const input = this.$refs.fileInput;
                            if (!input || !input.files) return;

                            // Create new FileList without the removed file
                            const dataTransfer = new DataTransfer();

                            // Add all files except the removed one
                            this.previews.forEach(preview => {
                                if (preview.file) {
                                    dataTransfer.items.add(preview.file);
                                }
                            });

                            input.files = dataTransfer.files;
                        },

                        // Optional: Drag and drop support
                        ['@dragover.prevent']() {},
                        ['@drop.prevent'](event) {
                            this.$refs.fileInput.files = event.dataTransfer.files;
                            this.handleFileSelect({ target: this.$refs.fileInput });
                        }
                    }));
                });
            </script>

            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                Submit
            </button>
        </form>
    </div>
@endsection
