<?php

namespace Botble\Blog\Supports;

use Botble\ACL\Models\User;
use Botble\Base\Enums\BaseStatusEnum;

final class PostStatusWorkflow
{
    public static function allowedValues(?User $user): array
    {
        $roleNames = collect($user?->roles ?? [])
            ->map(fn ($role): string => strtolower(trim((string) $role->name)))
            ->all();

        if (in_array('pimred', $roleNames, true)) {
            return [
                BaseStatusEnum::DRAFT,
                BaseStatusEnum::PENDING,
                BaseStatusEnum::PUBLISHED,
            ];
        }

        if (in_array('redaktur', $roleNames, true)) {
            return [
                BaseStatusEnum::DRAFT,
                BaseStatusEnum::PENDING,
            ];
        }

        if (in_array('wartawan', $roleNames, true)) {
            return [BaseStatusEnum::DRAFT];
        }

        return array_values(BaseStatusEnum::toArray());
    }

    public static function choices(?User $user): array
    {
        $labels = BaseStatusEnum::labels();
        $choices = [];

        foreach (self::allowedValues($user) as $value) {
            if (array_key_exists($value, $labels)) {
                $choices[$value] = $value === BaseStatusEnum::PENDING ? 'Review' : $labels[$value];
            }
        }

        return $choices;
    }
}
