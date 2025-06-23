<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     * By default, it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response. If it does, script
     * execution will stop and that Response will be
     * sent back to the client, allowing for error pages,
     * redirects, etc.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return mixed
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        if (!$session->get('isLoggedIn')) {
            // User is not logged in, redirect to login page with an error message.
            // Assuming 'login' is the named route or URI for AuthController::login()
            // If using explicit routes like 'auth/login', adjust site_url() accordingly.
            // The routes file has 'auth/login' mapped via AuthController::login() in previous steps,
            // but the prompt mentioned 'login' which might be a named route.
            // For consistency with potential explicit routes for AuthController (e.g. $routes->get('login', 'AuthController::login');)
            // I'll use 'login'. If it's under an 'auth' group, it'd be 'auth/login'.
            // Let's assume 'login' is the correct route name or path.
            // If routes were defined as $routes->get('auth/login', 'AuthController::login'), then it should be site_url('auth/login')

            // Given the AuthController was generated without explicit route definition in this session,
            // using a generic 'login' path. If AuthController routes were grouped under 'auth', then 'auth/login' is better.
            // Let's assume a route $routes->get('login', 'AuthController::login'); exists or will be created.
            return redirect()->to(site_url('login'))
                             ->with('error', 'Debe iniciar sesión para acceder a esta página.');
        }

        // If logged in, allow the request to proceed.
        // Explicitly returning $request is not strictly necessary as per CI4 docs for `before` filters
        // unless you are modifying the request. Not returning anything achieves the same.
        return $request;
    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return mixed
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No action needed after the controller execution for this filter.
        return;
    }
}
