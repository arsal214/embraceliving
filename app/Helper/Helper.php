<?php

/**
 * Get listing of a resource.
 *
 * @return array
 */
function getThemeData() {
    $userType = auth()->user()->type;
    if ($userType == 'GroupAdmin') {
        $group = auth()->user()->group;
        if ($group) {
            $themes = $group->themes()->where('status', 'Active')->first();
            if($themes){
                return $themes;
            }
            return $group;
        }
    }
    return null;
}

