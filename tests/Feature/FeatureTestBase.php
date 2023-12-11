<?php

namespace Tests\Feature;

use App\Models\Card;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class FeatureTestBase extends TestCase
{
    protected function generateUserWithCards(int $cardsCount = 5): User
    {
        $user = User::factory()->create();
        Card::factory()
            ->count($cardsCount)
            ->for($user)
            ->create();

        return $user;
    }

    protected function generateUserWithAvailableDraws(int $availableDraws): User
    {
        return User::factory()->create([
            'available_draws' => $availableDraws,
        ]);
    }
}

/*
namespace Afi\Tests;

use Afi\User\Infrastructure\Repository\UserDoctrineRepository;
use ApiTestCase\JsonApiTestCase;
use DAMA\DoctrineTestBundle\Doctrine\DBAL\StaticDriver;
use DateTime;

class TestBase extends JsonApiTestCase
{
    protected const API_KEY = 'YNKmytzBMmO0xr29uUlNcCVT685BQ4EhJbWew5yFwe3uG3TmfCi9CqvCx0cKQJU7P350L8zOTsslipZrwR1VyFpAOW9DHizpfRYPvYjVoVmjn8DUmw6K8tQOt2QkUR6s';

    protected function setUp(): void
    {
        StaticDriver::setKeepStaticConnections(true);
        parent::setUp();
    }

    protected function tearDown(): void
    {
        StaticDriver::setKeepStaticConnections(false);
        parent::tearDown();
    }

    public static function setUpBeforeClass(): void
    {
        StaticDriver::beginTransaction();
        parent::setUpBeforeClass();
    }

    public static function tearDownAfterClass(): void
    {
        StaticDriver::rollBack();
        parent::tearDownAfterClass();
    }

    protected function loginAsApi(): void
    {
        $this->client
            ->setServerParameters([
                'HTTP_X-API-KEY' => self::API_KEY,
                'HTTP_HOST' => 'localhost'
            ]);
    }

    protected function loginAsSalesUser(): void
    {
        $userRepository = static::getContainer()->get(UserDoctrineRepository::class);
        $user = $userRepository->findOneBy(['email' => 'sales@test.com']);
        $this->client->loginUser($user);
    }

    protected function loginAsSuperAdminUser(): void
    {
        $userRepository = static::getContainer()->get(UserDoctrineRepository::class);
        $user = $userRepository->findOneBy(['email' => 'super_admin@test.com']);
        $this->client->loginUser($user);
    }

    protected function requestWithJsonBody(string $method, string $uri, array $jsonBody): void
    {
        $this->client->request(
            $method,
            $uri,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($jsonBody)
        );
    }

    protected function getBirthDate(): DateTime
    {
        return (new DateTime())->modify(sprintf('-%d years', rand(20, 60)));
    }

    protected function post(string $uri, array $jsonBody): void
    {
        $this->requestWithJsonBody('POST', $uri, $jsonBody);
    }

    protected function patch(string $uri, array $jsonBody): void
    {
        $this->requestWithJsonBody('PATCH', $uri, $jsonBody);
    }

    protected function put(string $uri, array $jsonBody): void
    {
        $this->requestWithJsonBody('PUT', $uri, $jsonBody);
    }

    protected function delete(string $uri, ?array $jsonBody = null): void
    {
        $this->client->request(
            'DELETE',
            $uri,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            $jsonBody !== null ? json_encode($jsonBody) : null
        );
    }
}
*/
