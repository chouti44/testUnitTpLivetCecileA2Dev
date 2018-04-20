<?php

namespace App\Http\Controllers;

use App\Exceptions\EmailAlreadyExistException;
use App\Models\Member;
use App\Services\MemberService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    /**
     * @var $memberService
     */
    private $memberService;

    public function __construct(MemberService $memberService)
    {
        $this->memberService = $memberService;
    }

    /**
     * Il faut retouner le formualire pour enregistrer le mail
     */
    public function index(Request $request)
    {
        $values = $request->only([
            Member::EMAIL
        ]);

        $validator = Validator::make($values, [
            Member::EMAIL => 'required'
        ]);

        if($validator->fails()) {
            return redirect()->action('IndexController@index')
                ->with('alert', [
                    'message' => 'required_fields',
                    'type' => 'warning'
                ]);
        }

         try {
             $this->memberService->create($values[Member::EMAIL]);
         } catch (EmailAlreadyExistException $e) {
             return redirect()->action('IndexController@index')
                 ->with('alert', [
                     'message' => 'already_exist',
                     'type' => 'warning'
                 ]);
         }

        return redirect()->action('IndexController@index')
            ->with('alert', [
                'message' => 'success_message',
                'type' => 'success'
            ]);
    }

}
