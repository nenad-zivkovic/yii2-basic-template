<?php
namespace app\helpers;

/**
 * -----------------------------------------------------------------------------
 * CssHelper class.
 * -----------------------------------------------------------------------------
 */
class CssHelper
{
    /**
     * =========================================================================
     * Returns the appropriate css class based on the value of user $status.
     * NOTE: used in user/index view.
     * =========================================================================
     *
     * @param  string  $status  User status.
     *
     * @return string           Css class.
     * _________________________________________________________________________
     */
    public static function statusCss($status)
    {
        if ($status === 'Active')
        {
            return "boolean-true";
        } 
        else 
        {
            return "boolean-false";
        }      
    }

    /**
     * =========================================================================
     * Returns the appropriate css class based on the value of role $item_name.
     * NOTE: used in user/index view.
     * =========================================================================
     *
     * @param  string  $role  Role name.
     *
     * @return string         Css class.
     * _________________________________________________________________________
     */
    public static function roleCss($role)
    {
        return "role-".$role."";    
    }
}