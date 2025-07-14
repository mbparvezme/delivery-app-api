<?php

/**
 * Base Eloquent Model with common scopes and helpers.
 *
 * @author M B Parvez
 * @link https://github.com/mbparvez
 * @link https://www.theui.dev
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class BaseModel extends Model
{

    /**
     * Scope a query to only include active records.
     *
     * Usage: Model::active()->get()
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

    /**
     * Scope a query to only include inactive records.
     *
     * Usage: Model::inactive()->get()
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInactive($query)
    {
        return $query->where('active', 0);
    }

    /**
     * Set the current model as inactive.
     *
     * Usage: $model->deactivate()
     *
     * @return bool
     */
    public function scopeDeactivate()
    {
        $this->active = 0;
        return $this->save();
    }

    /**
     * Set the current model as active.
     *
     * Usage: $model->reactivate()
     *
     * @return bool
     */
    public function scopeReactivate()
    {
        $this->active = 1;
        return $this->save();
    }


}
