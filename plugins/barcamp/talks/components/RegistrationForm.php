<?php namespace Barcamp\Talks\Components;

use App;
use ApplicationException;
use Barcamp\Talks\Facades\TalksFacade;
use Barcamp\Talks\Models\Category;
use Exception;
use Flash;
use Illuminate\Http\RedirectResponse;
use Input;
use Log;
use October\Rain\Database\ModelException;
use RainLab\User\Components\Account;
use RainLab\User\Models\Settings as UserSettings;
use RainLab\User\Models\User;
use Redirect;
use Request;
use Session;
use Validator;
use ValidationException;

class RegistrationForm extends Account
{
    public function componentDetails()
    {
        return [
            'name'        => 'Registration Form',
            'description' => 'Barcamp registration form',
        ];
    }

    public function defineProperties()
    {
        return [
            'paramCode' => [
                'title'       => 'rainlab.user::lang.account.code_param',
                'description' => 'rainlab.user::lang.account.code_param_desc',
                'type'        => 'string',
                'default'     => 'code',
            ],
        ];
    }

    /**
     * On component run.
     *
     * @return bool|Redirect|string
     */
    public function onRun()
    {
        // activation code supplied
        $routeParameter = $this->property('paramCode');
        if ($activationCode = $this->param($routeParameter)) {
            $this->onActivate($activationCode);
        }

        // init
        $response = false;

        // form sent
        if (Input::get('submit')) {
            $response = $this->formSubmit();
            if ($response instanceof RedirectResponse) {
                return $response;
            }
        }

        // template data
        $this->page['sent'] = Flash::check();
        $this->page['post'] = post();
        $this->page['error'] = $response;
        $this->page['categories'] = $this->getAllCategories();
        $this->page['type'] = Input::get('type');
    }

    /**
     * Registration form submit.
     *
     * @return Redirect|string
     */
    private function formSubmit()
    {
        try {
            // validate form, register user and talk
            $data = Input::all();
            $this->validateForm($data);
            $facade = $this->getFacade();
            $data['user'] = $this->getUser($data);
            $data['type'] = isset($data['type']) ? $data['type'] : 'speaker';
            $facade->register($data);

            // success
            $message = "Vaše registrace byla úspěšně dokončena. Počkejte až se Vám ozve naše programová vedoucí.";
            Flash::success($message);

            return Redirect::to('/' . Request::path() . '?type=' . $data['type'], 303);

        } catch(ModelException $e) {
            $error = $e->getMessage();

        } catch(ApplicationException $e) {
            $error = $e->getMessage();

        } catch(ValidationException $e) {
            $error = $e->getMessage();

        } catch(Exception $e) {
            Log::error($e->getMessage());
            $error = 'Omlouváme se, ale nastala neočekávaná chyba. Formulář nemohl být odeslán.';
        }

        return $error;
    }

    /**
     * Validate form. We could use Model validator, but we need to validate both User's and Task's data at once.
     *
     * For disable more talks for one user, add new rule to email: unique:users.
     *
     * @param $data
     */
    private function validateForm($data)
    {
        $rules = [
            'type' => 'required',
            'registerName' => 'required|min:3',
            'talkName' => 'required|min:10',
            'annotation' => 'required|min:20',
            'phone' => 'required|between:9,13',
            'email' => 'required|between:6,255|email',
            'category' => 'required',
            'photo' => 'required',
        ];

        $messages = [
            'required' => 'Pole :attribute je povinné.',
            'between' => 'Pole :attribute musí mít mezi :min - :max znaky.',
            'min' => 'Pole :attribute musí mít alespoň :min znaků.',
            'file.required' => 'Musíte nahrát Vaší fotku.',
        ];

        $attributes = [
            'type' => 'typ registrace',
            'registerName' => 'jméno a příjmení',
            'talkName' => 'název přednášky',
            'annotation' => 'anotace',
            'phone' => 'telefon',
            'email' => 'e-mail',
            'category' => 'kategorie',
            'photo' => 'fotka',
        ];

        $this->checkToken();
        $this->checkSettings();

        $validation = Validator::make($data, $rules, $messages, $attributes);
        if ($validation->fails()) {
            $errorString = '';
            foreach($validation->errors()->all() as $error) {
                $errorString .= $error . "<br />";
            }
            throw new ApplicationException($errorString);
        }
    }

    /**
     * Check CSRF token.
     *
     * @throws ApplicationException
     */
    private function checkToken()
    {
        if (Session::token() != Input::get('_token')) {
            throw new ApplicationException('Platnost formuláře vypršela, obnovte prosím stránku a zkuste to znovu.');
        }
    }

    /**
     * Check settings.
     *
     * @throws ApplicationException
     */
    private function checkSettings()
    {
        // check if registration is allowed
        if (!UserSettings::get('allow_registration', true)) {
            throw new ApplicationException('Omlouváme se, ale registrace jsou již uzavřené.');
        }

        // check if there is free places
        $facade = $this->getFacade();
        if (!$facade->isRegistrationApproved()) {
            throw new ApplicationException('Omlouváme se, ale registrace jsou již uzavřené.');
        }
    }

    /**
     * Find user by email or create new one.
     *
     * @param array $data
     *
     * @return User
     */
    private function getUser(array $data)
    {
        $user = User::findByEmail($data['email']);

        // if not exists, create new one
        if (!$user) {
            $facade = $this->getFacade();
            $photo = Input::file('photo', null);
            $user = $facade->createUser($data, $photo);
            $this->sendActivationEmail($user);
        }

        return $user;
    }

    /**
     * Get all enabled categories.
     *
     * @return mixed
     */
    private function getAllCategories()
    {
        return Category::isEnabled()->orderBy('sort_order')->get();
    }

    /**
     * Get Talks facade.
     *
     * @return TalksFacade
     */
    private function getFacade()
    {
        return App::make(TalksFacade::class);
    }
}
