<?php

namespace App\Test\Controller;

use App\Entity\MailAccount;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MailAccountControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/mail/account/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(MailAccount::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('MailAccount index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'mail_account[name]' => 'Testing',
            'mail_account[host]' => 'Testing',
            'mail_account[port]' => 'Testing',
            'mail_account[username]' => 'Testing',
            'mail_account[password]' => 'Testing',
            'mail_account[group_]' => 'Testing',
        ]);

        self::assertResponseRedirects('/sweet/food/');

        self::assertSame(1, $this->getRepository()->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new MailAccount();
        $fixture->setName('My Title');
        $fixture->setHost('My Title');
        $fixture->setPort('My Title');
        $fixture->setUsername('My Title');
        $fixture->setPassword('My Title');
        $fixture->setGroup_('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('MailAccount');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new MailAccount();
        $fixture->setName('Value');
        $fixture->setHost('Value');
        $fixture->setPort('Value');
        $fixture->setUsername('Value');
        $fixture->setPassword('Value');
        $fixture->setGroup_('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'mail_account[name]' => 'Something New',
            'mail_account[host]' => 'Something New',
            'mail_account[port]' => 'Something New',
            'mail_account[username]' => 'Something New',
            'mail_account[password]' => 'Something New',
            'mail_account[group_]' => 'Something New',
        ]);

        self::assertResponseRedirects('/mail/account/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getName());
        self::assertSame('Something New', $fixture[0]->getHost());
        self::assertSame('Something New', $fixture[0]->getPort());
        self::assertSame('Something New', $fixture[0]->getUsername());
        self::assertSame('Something New', $fixture[0]->getPassword());
        self::assertSame('Something New', $fixture[0]->getGroup_());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new MailAccount();
        $fixture->setName('Value');
        $fixture->setHost('Value');
        $fixture->setPort('Value');
        $fixture->setUsername('Value');
        $fixture->setPassword('Value');
        $fixture->setGroup_('Value');

        $this->manager->remove($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/mail/account/');
        self::assertSame(0, $this->repository->count([]));
    }
}
