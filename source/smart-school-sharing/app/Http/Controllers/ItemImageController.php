<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ItemImage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ItemImageController extends Controller
{
    /**
     * Remove the specified image from storage.
     */
    public function destroy($id)
    {
        $image = ItemImage::findOrFail($id);

        // Xóa file ảnh thật khỏi public nếu tồn tại
        $imagePath = public_path($image->image_url);
        if (File::exists($imagePath)) {
            File::delete($imagePath);
        }

        // Xóa bản ghi trong database
        $image->delete();

        return redirect()->back()->with('success', 'Image deleted successfully.');
    }

    public function destroyImage($id)
    {
        $image = ItemImage::findOrFail($id);

        if (Storage::disk('public')->exists($image->image_url)) {
            Storage::disk('public')->delete($image->image_url);
        }

        $image->delete();

        return response()->json(['success' => true]);
    }
}
