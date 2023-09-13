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
            $groupId = $group->id;
            $themes = \App\Models\Theme::whereHas('groups', function ($query) use ($groupId) {
                $query->where('group_id', $groupId)
                    ->where('group_themes.status', 'Active');
            })->first();
            if($themes){
                return $themes;
            }
            return $group;
        }
    }
    return null;
}

