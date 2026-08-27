<?php

namespace Tests\Feature;

use Botble\ACL\Models\Role;
use Botble\ACL\Models\User;
use Botble\Blog\Http\Requests\PostRequest;
use Botble\Blog\Supports\PostStatusWorkflow;
use Botble\Base\Enums\BaseStatusEnum;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PostStatusWorkflowTest extends TestCase
{
    #[DataProvider('roleStatusProvider')]
    public function test_role_statuses(array $roles, array $expected): void
    {
        $user = new User();
        $user->setRelation('roles', collect(array_map(
            fn (string $name) => (new Role())->forceFill(['name' => $name]),
            $roles
        )));

        self::assertSame($expected, PostStatusWorkflow::allowedValues($user));
    }

    public function test_post_request_rejects_status_outside_role_workflow(): void
    {
        foreach ([
            'wartawan' => [BaseStatusEnum::DRAFT],
            'redaktur' => [BaseStatusEnum::DRAFT, BaseStatusEnum::PENDING],
            'pimred' => [BaseStatusEnum::DRAFT, BaseStatusEnum::PENDING, BaseStatusEnum::PUBLISHED],
        ] as $roleName => $allowedStatuses) {
            $user = new User();
            $user->setRelation('roles', collect([(new Role())->forceFill(['name' => $roleName])]));

            foreach (BaseStatusEnum::toArray() as $status) {
                $request = PostRequest::create('/', 'POST', ['status' => $status]);
                $request->setUserResolver(fn () => $user);

                $passes = Validator::make(['status' => $status], ['status' => $request->rules()['status']])->passes();

                self::assertSame(in_array($status, $allowedStatuses, true), $passes, $roleName . ':' . $status);
            }
        }
    }

    public static function roleStatusProvider(): array
    {
        return [
            'wartawan' => [['wartawan'], [BaseStatusEnum::DRAFT]],
            'redaktur' => [['redaktur'], [BaseStatusEnum::DRAFT, BaseStatusEnum::PENDING]],
            'pimred' => [['pimred'], [BaseStatusEnum::DRAFT, BaseStatusEnum::PENDING, BaseStatusEnum::PUBLISHED]],
            'highest role wins' => [['wartawan', 'pimred'], [BaseStatusEnum::DRAFT, BaseStatusEnum::PENDING, BaseStatusEnum::PUBLISHED]],
            'admin unchanged' => [['Admin'], [BaseStatusEnum::PUBLISHED, BaseStatusEnum::DRAFT, BaseStatusEnum::PENDING]],
        ];
    }
}
