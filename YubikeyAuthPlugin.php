<?php

require_once("$IP/includes/AuthPlugin.php");
require_once(__DIR__ . '/yubicloud.class.php');

class YubikeyAuthPlugin extends AuthPlugin {
    private $yubi;
    private $origUsername;
    private $mapFunc;

    public function __construct($client_id, $secret_key = '', $server_list = null, $https=false) {
        $this->yubi = new Yubicloud($client_id, $secret_key, $server_list, $https);
    }

    /**
     * Verify that a user exists.
     */
    public function userExists ($username) {
        $yubikey_id = $this->getYubikeyId($username);
        if (strlen($yubikey_id)==12) {
            if (is_callable($this->mapFunc)) {
                $translatedUsername = call_user_func($this->mapFunc, $yubikey_id);
                if ($translatedUsername) {
                    return true;
                }
            } else {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if a username+password pair is a valid login.
     */
    public function authenticate ($username, $password) {
        $userLen = strlen($this->origUsername);
        $passLen = strlen($password);

        if ($passLen==44 && $userLen==0 && $this->yubi->isModHex($password)) {
            // Username is user-supplied => check that matches the associated Yubikey ID
            if ($this->getCanonicalName($password)!=$username)
                return false;
            $result = $this->yubi->checkOnYubiCloud(strtolower($password));
            if ($result=='OK')
                return true;
            throw new MWYubikeyAuthError($result);
        } else if ($userLen==44 && passLen==0 && !$this->requireUsername($username)) {
            $result = $this->yubi->checkOnYubiCloud(strtolower($this->origUsername));
            if ($result=='OK')
                return true;
            throw new MWYubikeyAuthError($result);

        }
        
        return false;
    }

/*    function modifyUITemplate (&$template, &$type) {
        # Modify options in the login template.
    }
*/
/*    function setDomain ($domain) {
        # Set the domain this plugin is supposed to use when
        # authenticating.
    }
*/
    public function validDomain ($domain) {
        /**
         * Always returns true (we do not use any domains)
         */
        return true;
    }

    /**
     * Called whenever a user logs in.
     */
    public function updateUser (&$user) {
        return true;
    }

    public function autoCreate () {
        # Return true if the wiki should create a new local account
        # automatically when asked to login a user who doesn't exist
        # locally but does in the external auth database.
        return false;
    }

/*    function allowPropChange ($prop= '') {
        # Allow a property change? Properties are the same as
        # preferences and use the same keys.
    }
*/
    public function allowPasswordChange () {
        return false;
    }

    public function setPassword ($user, $password) {
        return false;
    }

    public function updateExternalDBGroups ($user, $addgroups, $delgroups) {
        return true;
    }

    /**
     * =>Update the external user database with =>preferences.
     *
     * This is called when the user hits 'submit' on Special:Preferences. This
     * function is better then implementing Hooks provided by User::save,
     * because then there is no way to save the local user WITHOUT updating
     * the external database.
     */
    public function updateExternalDB ($user) {
        return true;
    }

    public function canCreateAccounts () {
        return false;
    }

    /**
     * We cannot add user to Yubikey auth server
     */
    public function addUser ($user, $password, $email= '', $realname= '') {
        return false;
    }

    /**
     * Initialize a new user.
     */
    public function initUser (&$user, $autocreate) {
    }

    public function strict () {
        /**
         * Allow normal password authentication as fallback.
         */
        return false;
    }

    public function requireUsername($username) {
        return false;
    }

    public function strictUserAuth ($username) {
        /**
         * Allow normal password authentication as fallback.
         */
        return false;
    }

    public function getCanonicalName ($username) {
        # If you want to munge the case of an account name before the
        # final check, now is your chance.
        // TODO: aus DB holen
        if (strlen($username)==44 && $this->yubi->isModHex($username)) {
            $this->origUsername = strtolower($username);
            $yubikey_id = $this->getYubikeyId($username);
            if (is_callable($this->mapFunc)) {
                $translatedUsername = call_user_func($this->mapFunc, $yubikey_id);
                if ($translatedUsername) {
                    return $translatedUsername;
                }
            } else {
                return $yubikey_id;
            }
        }

        return $username;
    }

/*    function getUserInstance (User &$user) {
        # Get an instance of a User object.
    }
 */

    /**
     * Should MediaWiki store passwords in its local database?
     */
    public function allowSetLocalPassword() {
        return true;
    }
    
    public function setPublicIdMapFunc($mapFunc) {
        $this->mapFunc = $mapFunc;
    }

    private function getYubikeyId($username) {
        return strtolower(substr($username, 0, 12));
    }
}
