<?php

namespace Horus\Controllers;

use Horus\Auth;
use Horus\Controllers\Auth\PasswordController;
use Horus\Core\Application;
use Horus\Core\Controller\BaseController;
use Horus\Core\Http\Message\Response;
use Horus\Core\Http\Message\ResponseInterface;
use Horus\Core\Http\Message\ServerRequestInterface;
use Horus\Core\View\View;
use Horus\Enums\UserRole;
use Horus\Models\User;

class UserController extends BaseController
{
    public function index(ServerRequestInterface $request): string
    {
        $error = Auth::session()->get("uu_error");
        if (isset($error)) {
            Auth::session()->delete("uu_error");
        }

        $filter = null;
        $qb = User::where([])->orderBy("first_name");

        $params = $request->getQueryParams();
        if (array_key_exists("f", $params)) {
            $role = UserRole::tryFrom($params["f"]);

            if (isset($role)) {
                $filter = $role->value;
                $qb->where("role = ?", $role->value);
            }
        }

        $search = "";
        if (array_key_exists("q", $params)) {
            $search = $params["q"];

            if (!empty($search)) {
                $qb->andWhere("LOWER(CONCAT(first_name, ' ', last_name)) LIKE LOWER(?)", "%{$search}%");
                $qb->orWhere("id LIKE ?", "%{$search}%");
            }
        }

        $users = $qb->getAll();

        $selected = null;
        if (array_key_exists("u", $params) && count($users) > 0) {
            for ($i = 0; $i < count($users); $i++) {
                if ($users[$i]->id == $params["u"]) {
                    $selected = $users[$i];
                    break;
                }
            }
        }

        return View::render("Admin/Users/index.php", [
            "error" => $error,
            "users" => $users,
            "filter" => $filter,
            "search" => $search,
            "selected" => $selected
        ]);
    }

    public function create(): string
    {
        $error = Auth::session()->get("uc_error");
        if (isset($error)) {
            Auth::session()->delete("uc_error");
        }

        $users = User::where([])
            ->orderBy("first_name")
            ->getAll();

        return View::render("Admin/Users/create.php", [
            "users" => $users,
            "error" => $error,
            "filter" => null,
            "search" => null
        ]);
    }

    public function profile(): string
    {
        return View::render("Profiles/index.php", [
            "user" => Auth::user()
        ]);
    }

    public function store(ServerRequestInterface $request): ResponseInterface
    {
        $body = $request->getParsedBody();
        $role = UserRole::tryFrom($body["role"]);
        if (!isset($role)) {
            Auth::session()->set("uc_error", "Please select a valid role.");
            return $this->redirect(route("users.create"));
        }

        if (!PasswordController::isSecure($body["password"])) {
            Auth::session()->set("uc_error", "The password does not match the requirements.");
            return $this->redirect(route("users.create"));
        }

        $password = password_hash($body["password"], PASSWORD_BCRYPT);
        $existing = User::findOne([
            "email" => $body["email"]
        ]);

        if (isset($existing)) {
            Auth::session()->set("uc_error", "The specified email is already in use.");
            return $this->redirect(route("users.create"));
        } else {
            User::create([
                "first_name" => $body["first_name"],
                "last_name" => $body["last_name"],
                "password" => $password,
                "email" => $body["email"],
                "role" => $role->value
            ]);
        }

        $id = Application::getInstance()
            ->getDatabase()
            ->getLastInsertId();

        return $this->redirect(route("users", [ "u" => $id ]));
    }

    public function update(ServerRequestInterface $request): ResponseInterface
    {
        $id = $request->getAttribute("id");
        if (!isset($id)) {
            return new Response(404, "Not Found");
        }

        $body = $request->getParsedBody();
        $existing = User::findOne([
            "email" => $body["email"]
        ]);

        if (isset($existing) && $existing->id != $id) {
            Auth::session()->set("uu_error", "The specified email is already in use.");
        } else {
            User::update($id, [
                "first_name" => $body["first_name"],
                "last_name" => $body["last_name"],
                "email" => $body["email"]
            ]);
        }

        return $this->redirect(route("users", [ "u" => $id ]));
    }
}
