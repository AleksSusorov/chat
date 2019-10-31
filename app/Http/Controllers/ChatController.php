<?php

namespace App\Http\Controllers;

use App\Dialog;
use App\Message;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Intervention\Image\Facades\Image;
use Redirect;

class ChatController extends Controller
{

    const DEFAULT_DIALOG = 5;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return redirect('dialogs/' . self::DEFAULT_DIALOG);
    }

    public function storeMessage(Request $request)
    {
        $message = new Message();

        $validatedData = $request->validate([
            'send-message' => 'required'
        ]);

        $message->user_id = auth()->user()->id;
        $message->dialog_id = $request->get('id-dialog');
        $message->message = $request->get('send-message');

        $message->save();

        return Redirect::back();
    }

    public function showDialog($id)
    {

        $dialogsList = $this->loadDialogsList();

        $dialog = Dialog::findOrFail($id);
        $users = $dialog->users;

        $userFrom = $users->firstWhere('id', '=', auth()->user()->id);
        $userTo = $users->firstWhere('id', '!=', auth()->user()->id);

        return view('chat', compact('dialog', 'userFrom', 'userTo', 'dialogsList'));
    }

    public function loadDialogsList()
    {
        $dialogsRaw = auth()->user()->dialogs;

        $dialogsList = [];

        foreach ($dialogsRaw as $dialog) {
            $dialogsList[] = [
                'id' => $dialog->id,
                'userTo' => $dialog->users->firstWhere('id', '!=', auth()->user()->id),
                'lastMessage' => $dialog->messages->last(),
            ];
        }

        return $dialogsList;
    }

    public function createDialog(Request $request)
    {
        $dialog = new Dialog();
        $dialog->save();

        $user = User::find([1, 4]);
        $dialog->users()->attach($user);

        return 'Success';
    }

    public function makePhoto()
    {
        Image::make(public_path('/storage/user/rostova.png'))->fit(50, 50)->save();
        return 'Success';
    }

}
