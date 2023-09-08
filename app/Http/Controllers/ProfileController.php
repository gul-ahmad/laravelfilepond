<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\TemporaryFile;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();


        $tmpCnic = TemporaryFile::where('folder', $request->cnic)->first();


        Storage::copy('public/cnic/tmp/' . $tmpCnic->folder . '/' . $tmpCnic->file, 'public/cnicfinal/' . $tmpCnic->folder . '/' . $tmpCnic->file);
        $realPath = $tmpCnic->folder . '/' . $tmpCnic->file;
        // Storage::deleteDirectory('/products/tmp/' .$tmp_file->folder);
        Storage::deleteDirectory(('public/cnic/tmp/') . $tmpCnic->folder);
        // $image->delete();
        $request->user()->cnicImage()->create([
            'path' => $realPath,
            'type' => 'cnic'
        ]);

        foreach ($request['images'] as $image) {

            $tmp_file = TemporaryFile::where('folder', $image)->first();
            Storage::copy('public/images/tmp/' . $tmp_file->folder . '/' . $tmp_file->file, 'public/imagesfinal/' . $tmp_file->folder . '/' . $tmp_file->file);
            $realPath = $tmp_file->folder . '/' . $tmp_file->file;
            // Storage::deleteDirectory('/products/tmp/' .$tmp_file->folder);
            Storage::deleteDirectory(('public/images/tmp/') . $tmp_file->folder);
            // $image->delete();
            $request->user()->selfImages()->create([
                'path' => $realPath,
                'type' => 'self'
            ]);
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function filePondProcess(Request $request)
    {

        if ($request->has('cnic')) {
            $cnic = $request->file('cnic');
            $fileName = $cnic->getClientOriginalName();

            //create a unique id using php unique function

            $folder = uniqid('cnic', true);
            $cnicImagePath = $cnic->storeAs('public/cnic/tmp/' . $folder, $fileName);
            TemporaryFile::create([

                'folder' => $folder,
                'file' => $fileName,

            ]);

            return $folder;
        }
        if ($request->has('images')) {
            $images = $request->images;
            $folder = uniqid('images', true);
            foreach ($images as $image) {

                $imageName = $image->getClientOriginalName();
                $image->storeAs('public/images/tmp/' . $folder, $imageName);
                TemporaryFile::create([
                    'folder' => $folder,
                    'file' => $imageName,
                ]);
            }

            return $folder;
        }
    }


    public function filepondDelete(Request $request)
    {

        $fileId = $request->getContent();

        $cnicDelete = Str::startswith($fileId, 'cnic');
        $userImageDelete = Str::startsWith($fileId, 'images');

        if ($cnicDelete) {

            $tempFile = TemporaryFile::where('folder', $fileId)->first();

            if ($tempFile) {

                Storage::deleteDirectory('public/cnic/tmp/' . $fileId);
            }
        }
        if ($userImageDelete) {

            $tempFile = TemporaryFile::where('folder', $fileId)->first();
            //  dd($tempFile);
            if ($tempFile) {

                Storage::deleteDirectory('public/images/tmp/' . $fileId);
            }
        }
    }

    public function userDetails()
    {

        $userDetails = User::with('selfImages', 'cnicImage')->find(Auth::id());

        return view('dashboard', ['userDetails' => $userDetails]);
    }
}
