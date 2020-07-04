<?php
declare(strict_types = 1);

namespace App\Tests\Behat;

use Behat\Behat\Context\Context;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @author Guillaume MOREL <me@gmorel.io>
 */
class ResetDatabaseContext implements Context
{
    private ManagerRegistry $doctrine;

    /**
     * @param ManagerRegistry $doctrine
     */
    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * @BeforeScenario
     */
    public function dropDatabasesBeforeScenario(): void
    {
        $entityManagers = $this->doctrine->getManagers();

        foreach ($entityManagers as $entityManager) {
            $conn = $this->doctrine->getConnection();
            $stmt = $conn->prepare('SET session_replication_role = replica');
            $stmt->execute();

            $purger = new ORMPurger($entityManager);
            $purger->setPurgeMode(ORMPurger::PURGE_MODE_TRUNCATE);
            $purger->purge();

            $stmt = $conn->prepare('SET session_replication_role = DEFAULT');
            $stmt->execute();
        }
    }
}
