<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Providers\ImageUploader;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit-new', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $user = $request->user();
        unset($data['cropped_image'], $data['imagem']);
    
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }
    
        $user->fill($data);
        $user->save();
    
        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Update only the user's profile image.
     */
    public function updateImage(Request $request): RedirectResponse
    {
        $request->validate([
            'cropped_image' => ['required', 'string'],
        ]);

        $user = $request->user();
        $imageParts = explode(';base64,', $request->cropped_image, 2);

        if (count($imageParts) !== 2) {
            return Redirect::route('profile.edit')
                ->withErrors(['cropped_image' => 'Imagem invalida.']);
        }

        $imageTypeAux = explode('image/', $imageParts[0], 2);

        if (count($imageTypeAux) !== 2) {
            return Redirect::route('profile.edit')
                ->withErrors(['cropped_image' => 'Imagem invalida.']);
        }

        $imageBase64 = base64_decode($imageParts[1]);

        if ($imageBase64 === false) {
            return Redirect::route('profile.edit')
                ->withErrors(['cropped_image' => 'Imagem invalida.']);
        }

        $imageName = uniqid() . '.png';
        $imageFullPath = sys_get_temp_dir() . '/' . $imageName;
        file_put_contents($imageFullPath, $imageBase64);

        $uploader = new ImageUploader();
        $uploader->setCompression(30);
        $uploader->setResolution(480);
        $uploader->setDestinationPath('users/');
        $user->imagem = $uploader->upload(new \Illuminate\Http\File($imageFullPath), $user->imagem);
        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-image-updated');
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
}
