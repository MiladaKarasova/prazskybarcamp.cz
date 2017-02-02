<?php namespace Barcamp\Contact\Components;

use App;
use Barcamp\Contact\Facades\ContactFacade;
use Cms\Classes\ComponentBase;
use Config;
use Exception;
use Flash;
use Input;
use October\Rain\Exception\AjaxException;
use October\Rain\Exception\ApplicationException;
use Redirect;
use Response;
use Session;

class ContactForm extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'Kontaktní formulář',
            'description' => 'Kontaktní formulář',
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    /**
     * AJAX form submit by October JS Framework.
     *
     * @return mixed
     */
    public function onSubmit()
    {
        try {
            // check token
            if (Session::token() != Input::get('_token')) {
                throw new ApplicationException('Platnost formuláře vypršela, obnovte prosím stránku a odešlete formulář znovu.');
            }

            $this->saveForm();

        } catch (ApplicationException $e) {
            return Response::json($e->getMessage(), 406);

        } catch (Exception $e) {
            return Response::json($e->getMessage(), 406);
        }
    }

    /**
     * Fallback for classic non-ajax POST request.
     *
     * @return mixed
     */
    public function onRun()
    {
        $error = false;

        if (post('submit')) {
            try {
                $this->validateToken();
                $this->saveForm();
                Flash::success('Zpráva byla úspěšně odeslána!');

                return Redirect::to($this->page->url . '#form', 303);

            } catch (ApplicaitonException $e) {
                $error = $e->getMessage();

            } catch (Exception $e) {
                $error = $e->getMessage();
            }
        }

        $this->page['sent'] = Flash::check();
        $this->page['post'] = post();
        $this->page['error'] = $error;
    }

    /**
     * Validate CSRF token.
     */
    private function validateToken()
    {
        if (Session::token() != Input::get('_token')) {
            throw new ApplicationException('Platnost formuláře vypršela, obnovte prosím stránku a odešlete formulář znovu.');
        }
    }

    /**
     * Save contact form.
     */
    private function saveForm()
    {
        /** @var ContactFacade $repository */
        $repository = App::make(ContactFacade::class);
        $data = Input::all();
        $repository->storeMessage($data);
    }
}
