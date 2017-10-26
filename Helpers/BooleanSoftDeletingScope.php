<?php

namespace App\Helpers;

use \Illuminate\Database\Eloquent\Builder;
use \Illuminate\Database\Eloquent\Model;

class BooleanSoftDeletingScope extends \Illuminate\Database\Eloquent\SoftDeletingScope {


    public function apply(Builder $builder, Model $model)
    {
        $model = $builder->getModel();

        $builder->where($model->getQualifiedDeletedAtColumn(), '=', '0');

        $this->extend($builder);
    }

    public function extend(Builder $builder)
    {
        foreach ($this->extensions as $extension) {
            $this->{"add{$extension}"}($builder);
        }

        $builder->onDelete(function (Builder $builder) {
            $column = $this->getDeletedAtColumn($builder);

            return $builder->update([
                $column => '1',
            ]);
        });
    }

    protected function addRestore(Builder $builder)
    {
        $builder->macro('restore', function (Builder $builder) {
            $builder->withTrashed();

            return $builder->update([$builder->getModel()->getDeletedAtColumn() => '0']);
        });
    }

    protected function addWithoutTrashed(Builder $builder)
    {
        $builder->macro('withoutTrashed', function (Builder $builder) {
            $model = $builder->getModel();

            $builder->withoutGlobalScope($this)->where
            (
                $model->getQualifiedDeletedAtColumn(),'=','0'
            );

            return $builder;
        });
    }

    protected function addOnlyTrashed(Builder $builder)
    {
        $builder->macro('onlyTrashed', function (Builder $builder) {
            $model = $builder->getModel();

            $builder->withoutGlobalScope($this)->where
            (
                $model->getQualifiedDeletedAtColumn(),'=','0'
            );

            return $builder;
        });
    }
}