<?php

declare(strict_types=1);

namespace Shopsys\FrontendApiBundle\Model\Resolver\Transport;

use Overblog\GraphQLBundle\Definition\Resolver\AliasedInterface;
use Overblog\GraphQLBundle\Definition\Resolver\ResolverInterface;
use Overblog\GraphQLBundle\Error\UserError;
use Ramsey\Uuid\Uuid;
use Shopsys\FrameworkBundle\Model\Transport\Transport;
use Shopsys\FrameworkBundle\Model\Transport\TransportFacade;

class TransportResolver implements ResolverInterface, AliasedInterface
{
    /**
     * @var \Shopsys\FrameworkBundle\Model\Transport\TransportFacade
     */
    protected $transportFacade;

    /**
     * @param \Shopsys\FrameworkBundle\Model\Transport\TransportFacade $transportFacade
     */
    public function __construct(TransportFacade $transportFacade)
    {
        $this->transportFacade = $transportFacade;
    }

    /**
     * @param string $uuid
     * @return \Shopsys\FrameworkBundle\Model\Transport\Transport
     */
    public function resolver(string $uuid): Transport
    {
        if (Uuid::isValid($uuid) === false) {
            throw new UserError('Provided argument is not valid UUID.');
        }

        try {
            return $this->transportFacade->getByUuid($uuid);
        } catch (\Shopsys\FrameworkBundle\Model\Transport\Exception\TransportNotFoundException $transportNotFoundException) {
            throw new UserError($transportNotFoundException->getMessage());
        }
    }

    /**
     * @return string[]
     */
    public static function getAliases(): array
    {
        return [
            'resolver' => 'transport',
        ];
    }
}
