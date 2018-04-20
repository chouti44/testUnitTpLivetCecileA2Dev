<?php

namespace Tests\Unit\app\Services;

use Tests\TestCase;
use App\Models\Member;
use App\Services\MemberService;
use App\Exceptions\EmailAlreadyExistException;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MemberServiceTest extends TestCase
{
    /**
     * @var \Mockery\MockInterface
     */
    private $memberMocked;

    /**
     * La méthode "setUp" est appelée à chaque excecution de test
     */
    public function setUp()
    {
        parent::setUp();

        $this->memberMocked = \Mockery::mock(Member::class);
    }

    /**
     * Doit ajouter un nouvel email en base de donnée
     * Doit aussi vérifier qu'un email est en cours d'envoi dans le gestionnaire de queue
     *
     * 2 Points
     */

    public function testCreate_Success_NominalCase()
    {
        // Arrange = contexte
        $emailMember = 'john.doe@domain.tld';

        // Assert  = résultat attendu
        $this->memberMocked->shouldReceive('where')
            ->once()
            ->with([
                Member::EMAIL => $emailMember
            ])
            ->andReturn($this->memberMocked);

        $this->memberMocked->shouldReceive('first')
            ->once()
            ->andReturnNull();

        $this->memberMocked->shouldReceive('create')
            ->once()
            ->with([
                Member::EMAIL => $emailMember
            ])
            ->andReturnTrue();

        $memberService = new MemberService($this->memberMocked);

        // Act = scénario
        $memberService->create($emailMember);
    }

    /**
     * Doit retourner une exception de type EmailAlreadyExistException
     * si l'email est déjà existant
     *
     * 2 Points
     */

    public function testCreate_ExpectException_ExceptionCase()
    {
        // Arrange
        $emailMember = 'john.doe@domain.tld';

        $this->memberMocked->shouldReceive('where')
            ->once()
            ->with([
                Member::EMAIL => $emailMember
            ])
            ->andReturn($this->memberMocked);

        $this->memberMocked->shouldReceive('first')
            ->once()
            ->andReturn(new Member());

        $this->memberMocked->shouldReceive('create')
            ->times(0);

        $memberService = new MemberService($this->memberMocked);

        // Assert
        $this->expectException(EmailAlreadyExistException::class);

        // Act
        $memberService->create($emailMember);
    }
}
