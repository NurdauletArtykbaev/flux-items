<?php

namespace Nurdaulet\FluxItems\Services;


class StoreEmployeeService
{

    /**
     * @throws \Throwable
     */
    public function getLordId($user)
    {
        $user->loadMissing(['roles' => fn($query) => $query->withPivot('lord_id', 'deleted_at')->wherePivotNull('deleted_at')]);
        $role = $user->roles->first();
        if ($role?->id) {
            if ($role->pivot->lord_id) {
                return  $role->pivot->lord_id;
            }
            $user->load(['roles' => fn($query) => $query->withPivot('lord_id')]);
            $lordId = $role->pivot->lord_id;
            throw_if(empty($lordId), abort(404,'Магазин не найден'));
            return $lordId;
        }
        return $user->id;

    }
}
