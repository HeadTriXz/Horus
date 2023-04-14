<?php

namespace Horus\Controllers\Auth;

use Horus\Auth;
use Horus\Core\Controller\BaseController;
use Horus\Core\Http\Message\ResponseInterface;
use Horus\Core\Http\Message\ServerRequestInterface;
use Horus\Core\View\View;

class PasswordController extends BaseController
{
    protected const MIN_LENGTH = 10;

    public function update(ServerRequestInterface $request): string | ResponseInterface
    {
        $body = $request->getParsedBody();
        if ($body["new_password"] !== $body["confirm_password"]) {
            return View::render("Profiles/index.php", [
                "error" => "The password confirmation does not match."
            ]);
        }

        if (preg_match("/\s/", $body["new_password"])) {
            return View::render("Profiles/index.php", [
                "error" => "The password may not contain any whitespaces."
            ]);
        }

        if (!static::isSecure($body["new_password"])) {
            return View::render("Profiles/index.php", [
                "error" => "The password does not match the requirements."
            ]);
        }

        $user = Auth::user();
        if (!$user->verifyPassword($body["current_password"])) {
            return View::render("Profiles/index.php", [
                "error" => "Your password is invalid."
            ]);
        }

        $user->password = password_hash($body["new_password"], PASSWORD_BCRYPT);
        $user->save();

        return $this->redirect(route("profile"));
    }

    public static function isSecure(string $password): bool
    {
        if (strlen($password) < self::MIN_LENGTH) {
            return false;
        }

        if (!preg_match("/[A-Z]/", $password)) {
            return false;
        }

        if (!preg_match("/[a-z]/", $password)) {
            return false;
        }

        if (!preg_match("/[0-9]/", $password)) {
            return false;
        }

        if (!preg_match("/\W/", $password)) {
            return false;
        }

        return true;
    }
}
