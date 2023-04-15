<?php

namespace Horus\Controllers\Auth;

use Horus\Auth;
use Horus\Core\Controller\BaseController;
use Horus\Core\Http\Message\ResponseInterface;
use Horus\Core\Http\Message\ServerRequestInterface;
use Horus\Core\View\View;
use Horus\Models\Session;
use Horus\Models\User;

/**
 * Controller for managing authentication-related views and actions.
 */
class LoginController extends BaseController
{
    /**
     * Display the login view.
     *
     * @return string The rendered view.
     */
    public function show(): string
    {
        return View::render("Auth/login.php");
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param ServerRequestInterface $request The server request instance.
     * @return string | ResponseInterface The rendered view or response instance.
     */
    public function login(ServerRequestInterface $request): string | ResponseInterface
    {
        $body = $request->getParsedBody();
        $user = User::createQueryBuilder()
            ->select()
            ->where("email = ?", $body["username"])
            ->orWhere("id = ?", $body["username"])
            ->getOne();

        if (!$user?->verifyPassword($body["password"])) {
            return View::render("Auth/login.php", [
                "error" => "Invalid username and/or password"
            ]);
        }

        $session = Session::open();
        $session->set("user_id", $user->id);

        return $this->redirect("/")
            ->withAddedHeader(
                "Set-Cookie",
                "session_id=" . $session->id . "; Expires="
                    . gmdate('D, d M Y H:i:s \G\M\T', strtotime($session->expires_at))
            );
    }

    /**
     * Destroy an authenticated session.
     *
     * @return ResponseInterface The response instance.
     */
    public function logout(): ResponseInterface
    {
        Auth::session()?->destroy();

        return $this->redirect(route("login.show"))
            ->withAddedHeader("Set-Cookie", "session_id=null; Expires=" . gmdate('D, d M Y H:i:s \G\M\T', time() - 3600));
    }
}
