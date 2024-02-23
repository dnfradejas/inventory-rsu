<?php

define('CURRENT_STORE', 'current_store');

/**
 * Get months
 *
 * @return array<string, string>
 */
function get_months() {
    return [
        'Jan',
        'Feb',
        'Mar',
        'Apr',
        'May',
        'Jun',
        'Jul',
        'Aug',
        'Sep',
        'Oct',
        'Nov',
        'Dec',
    ];
}

if (!function_exists('api_response')) {

    /**
     * Api response
     *
     * @param mixed $data
     * @param string $status
     * @param string $message
     * @param integer $code
     * @param string $version
     * 
     * @return array
     */
    function api_response($data, string $status = 'success', string $message = 'OK', int $code = 200, string $version = '1.0.0'){
        return [
            'version' => $version,
            'status' =>  $status,
            'message' => $message,
            'code' => $code,
            'data' => [
                'results' => $data
            ]
        ];
    }
}

if (!function_exists('sluggify')) {
    /**
     * Sluggify string
     *
     * @param string $string
     * 
     * @return string
     */
    function sluggify($string) {
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
     
        return preg_replace('/[^A-Za-z0-9\-]/', '', strtolower($string)); // Removes special chars.
     }
}

if (!function_exists('current_nav')) {
    /**
     * Highlight current nav
     *
     * @param string $nav
     * 
     * @return string
     */
    function current_nav(string $nav) {
        return str_contains(url()->current(), $nav) ? 'active' : '';
    }
}

if (!function_exists('reference_number')) {
 
    /**
     * Reference number generator
     *
     * @return string
     */
    function reference_number() {
        return strtoupper(date('Y') . '-' . uniqid());
    }
}

if (!function_exists('web_user')) {

    /**
     * Get current web user
     *
     * @return \stdClass
     */
    function web_user() {
        $stdClass = new \stdClass();
        if (session()->has('current_user')) {
            $currentUser = session()->get('current_user');
            $stdClass->id = $currentUser->id;
            $stdClass->lastname = $currentUser->lastname;
            $stdClass->firstname = $currentUser->firstname;
            $stdClass->guest = false;
        } else {
            $stdClass->id = config('app.env') !== 'testing' ? csrf_token() : 'testing_id';
            $stdClass->guest = true;
        }

        return $stdClass;
        
    }
}

if (!function_exists('is_testing')) {
    /**
     * Determine if we are in testing mode
     *
     * @return boolean
     */
    function is_testing() {
        return config('app.env') === 'testing';
    }
}

if (!function_exists('camelize')) {
    function camelize($input, $separator = '_') {
        return str_replace($separator, '', ucwords($input, $separator));
    }

}


if (!function_exists('date_to_human')) {

    /**
     * Convert date to a more human readable
     * @param string $date
     * @param string $format
     * 
     * @return string
     */
    function date_to_human($date, $format = 'D M j, Y') {
        return date($format, strtotime($date));
    }
}

if (!function_exists('get_current_weekname')) {

    /**
     * Get current week name
     * 
     * @return \Closure
     */
    function get_current_weekname() {
        return function($dt){
            $dt = is_string($dt) ? $dt : $dt->__toString();
            return date("l", strtotime($dt));
        };
    }
}


if (!function_exists('is_testing')) {

    /**
     * Determine if in testing mode
     * 
     * @return bool
     */
    function is_testing() {
        return config('app.env') == 'testing';
    }
}

if (!function_exists('is_yesterday')) {

    /**
     * Check if $timestamp is more than 1 day
     *
     * @param string $timestamp
     * 
     * @return boolean
     */
    function is_yesterday(string $timestamp) {

        $now = strtotime(now()->format('Y-m-d'));
        $expire = strtotime($timestamp);

        if ($expire == $now) {
            return false;
        }
        return $expire < $now;

    }
}

if (!function_exists('admin')) {

    /**
     * Get currently authenticated admin
     *
     * @return \App\Models\AdminUser
     */
    function admin() {
        return session()->get('admin_session');
    }
}

if (!function_exists('audit_log')) {

    /**
     * Save audit log/transactions
     *
     * @param string $details
     * 
     * @return void
     */
    function audit_log(string $details) {
        \App\Models\TransactionHistory::create([
            'details' => $details
        ]);
    }
}