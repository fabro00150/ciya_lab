<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class App extends BaseConfig
{
    /**
     * --------------------------------------------------------------------------
     * Base Site URL
     * --------------------------------------------------------------------------
     *
     * URL to your CodeIgniter root. Typically, this will be your base URL,
     * WITH a trailing slash:
     *
     *   http://localhost:8080/
     *
     * If this is not set, CodeIgniter will try to guess the protocol and path
     * your installation lies under. However, this guess is not always accurate,
     * so you are encouraged to set it manually.
     *
     * @var string
     */
    public $baseURL = 'http://localhost:8080/'; // Set as requested

    /**
     * --------------------------------------------------------------------------
     * Index File
     * --------------------------------------------------------------------------
     *
     * Typically, this will be your index.php file, unless you've renamed it to
     * something else. If you are using mod_rewrite to remove the page set this
     * variable so that it is blank.
     *
     * @var string
     */
    public $indexPage = ''; // Set as requested

    /**
     * --------------------------------------------------------------------------
     * URI Protocol
     * --------------------------------------------------------------------------
     *
     * This item determines which server global should be used to retrieve the
     * URI string.  The default setting of 'REQUEST_URI' works for most servers.
     * If your links do not seem to work, try one of the other delicious flavors:
     *
     * 'REQUEST_URI'    Uses $_SERVER['REQUEST_URI']
     * 'QUERY_STRING'   Uses $_SERVER['QUERY_STRING']
     * 'PATH_INFO'      Uses $_SERVER['PATH_INFO']
     *
     * WARNING: If you set this to 'PATH_INFO', URIs will only be valid if they
     *          contain matched query string separators. If not, CodeIgniter
     *          will simply ignore the URI and treat it as if it were empty.
     *
     * @var string
     */
    public $uriProtocol = 'REQUEST_URI';

    /**
     * --------------------------------------------------------------------------
     * Default Locale
     * --------------------------------------------------------------------------
     *
     * The Locale roughly represents the language and location that your visitor
     * is viewing the site from. It affects the language strings and other
     * strings (like currency markers, numbers, etc), that your program
     * displays.
     *
     * @var string
     */
    public $defaultLocale = 'en';

    /**
     * --------------------------------------------------------------------------
     * Negotiate Locale
     * --------------------------------------------------------------------------
     *
     * If true, the current Locale will be negotiated according to the user's
     * preferences (HTTP_ACCEPT_LANGUAGE header), provided that it is supported
     * by the application.
     *
     * If false, the Default Locale will always be used.
     *
     * @var bool
     */
    public $negotiateLocale = false;

    /**
     * --------------------------------------------------------------------------
     * Supported Locales
     * --------------------------------------------------------------------------
     *
     * An array of Locale codes that the application supports.
     *
     * @var string[]
     */
    public $supportedLocales = ['en', 'es']; // Added 'es' as an example

    /**
     * --------------------------------------------------------------------------
     * Application Timezone
     * --------------------------------------------------------------------------
     *
     * The default timezone that will be used in your application to display
     * dates with the Time class. For example, America/Los_Angeles.
     *
     * @var string
     */
    public $appTimezone = 'UTC';

    /**
     * --------------------------------------------------------------------------
     * Default Character Set
     * --------------------------------------------------------------------------
     *
     * This determines which character set is used by default in various methods
     * that require a character set to be provided.
     *
     * @see http://php.net/manual/en/ zowel-δας.php
     *
     * @var string
     */
    public $charset = 'UTF-8';

    /**
     * --------------------------------------------------------------------------
     * Force Global Secure Requests
     * --------------------------------------------------------------------------
     *
     * If true, this will force every request made to this application to be
     * made via a secure connection (HTTPS). If the incoming request is not
     * secure, the user will be redirected to a secure version of the page
     * and a Strict-Transport-Security header will be set.
     *
     * @var bool
     */
    public $forceGlobalSecureRequests = false;

    /**
     * --------------------------------------------------------------------------
     * Session Variables
     * --------------------------------------------------------------------------
     *
     * 'sessionDriver'
     *
     *   The storage driver to use: Files, Database, Memcached, Redis
     *
     * 'sessionCookieName'
     *
     *   The session cookie name, must contain only [0-9a-zA-Z_-] characters
     *
     * 'sessionExpiration'
     *
     *   The number of SECONDS you want the session to last.
     *   Setting to 0 (zero) means expire when the browser is closed.
     *
     * 'sessionSavePath'
     *
     *   The location to save sessions to, driver dependent.
     *
     *   For the 'files' driver, it's a path to a writable directory.
     *   WARNING: Only absolute paths are supported!
     *
     *   For the 'database' driver, it's a table name.
     *   Please read up the manual session driver section for more info.
     *
     *   For the 'memcached' driver, it's a string in the form of:
     *     host:port[,host:port]
     *
     *   For the 'redis' driver, it's a string in the form of:
     *     host:port[,host:port]
     *
     * 'sessionMatchIP'
     *
     *   Whether to match the user's IP address when reading the session data.
     *
     *   WARNING: If you're using the database driver, don't forget to update
     *            your session table's PRIMARY KEY when changing this setting.
     *
     * 'sessionTimeToUpdate'
     *
     *   How many seconds between CI regenerating the session ID.
     *
     * 'sessionRegenerateDestroy'
     *
     *   Whether to destroy session data associated with the old session ID
     *   when auto-regenerating the session ID. When set to FALSE, the old
     *   session data will be garbage collected.
     *
     * @var array<string, mixed>
     */
    public $sessionDriver            = 'CodeIgniter\Session\Handlers\FileHandler';
    public $sessionCookieName        = 'ci_session';
    public $sessionExpiration        = 7200;
    public $sessionSavePath          = WRITEPATH . 'session';
    public $sessionMatchIP           = false;
    public $sessionTimeToUpdate      = 300;
    public $sessionRegenerateDestroy = false;

    /**
     * --------------------------------------------------------------------------
     * Cookie Related Variables
     * --------------------------------------------------------------------------
     *
     * 'cookiePrefix'   Set a cookie name prefix if you need to avoid collisions
     * 'cookieDomain'   Set to .your-domain.com for site-wide cookies
     * 'cookiePath'     Typically will be a forward slash
     * 'cookieSecure'   Cookie will only be set if a secure HTTPS connection exists.
     * 'cookieHTTPOnly' Cookie will only be accessible via HTTP(S) (no javascript)
     * 'cookieSameSite' Configure the SameSite attribute of the cookie. Acceptable values are: None, Lax, Strict, ''.
     *
     * Defaults to RESTRICTED for None, Lax, Strict or ''
     *
     * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Set-Cookie/SameSite
     *
     * @var array<string, mixed>
     */
    public $cookiePrefix   = '';
    public $cookieDomain   = '';
    public $cookiePath     = '/';
    public $cookieSecure   = false;
    public $cookieHTTPOnly = true; // Changed to true for better security by default
    public $cookieSameSite = 'Lax';

    /**
     * --------------------------------------------------------------------------
     * Cross Site Request Forgery (CSRF) Protection Settings
     * --------------------------------------------------------------------------
     *
     * Enables a CSRF cookie token to be set. When set to true, token will be
     * checked on all incoming POST requests. Matches token name to "cookieName" if set.
     *
     * 'csrfTokenName' = The token name
     * 'csrfHeaderName' = The header name
     * 'csrfCookieName' = The cookie name
     * 'csrfExpire' = The number in seconds the token should expire.
     * 'csrfRegenerate' = Regenerate token on every submission
     * 'csrfRedirect' = Redirect to previous page on failure
     * 'csrfExcludeURIs' = Array of URIs which will not be protected
     * 'csrfSameSite' = Configure the SameSite attribute of the cookie. Acceptable values are: None, Lax, Strict, ''.
     *
     * @var array<string, mixed>
     */
    public $CSRFProtection   = true; // Changed to true for better security by default
    public $CSRFTokenName    = 'csrf_test_name';
    public $CSRFHeaderName   = 'X-CSRF-TOKEN';
    public $CSRFCookieName   = 'csrf_cookie_name';
    public $CSRFExpire       = 7200;
    public $CSRFRegenerate   = true;
    public $CSRFRedirect     = true;
    public $CSRFExcludeURIs  = ['api/*']; // Example: exclude API routes
    public $CSRFSameSite     = 'Lax';

    /**
     * --------------------------------------------------------------------------
     * Content Security Policy
     * --------------------------------------------------------------------------
     *
     * Enables the Response's Content Security Policy to restrict the sources
     * that browsers will allow to load content.
     *
     * @see http://www.html5rocks.com/en/tutorials/security/content-security-policy/
     * @see http://www.w3.org/TR/CSP/
     *
     * @var bool
     */
    public $CSPEnabled = false; // Default is false, enable if needed

    /**
     * --------------------------------------------------------------------------
     * Fix events that might be caused by Content Security Policy errors
     * --------------------------------------------------------------------------
     *
     * If true, the framework will try to fix some events that might be
     * caused by CSP errors. E.g. codeigniter.jquery.js would not be able
     * to load because of an inline script error.
     *
     * @var bool
     */
    public $CSPFixEvents = true; // Default is true
}
