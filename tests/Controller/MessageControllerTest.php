<?php

namespace App\Test\Controller;

use App\Entity\Message;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MessageControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/message/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(Message::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Message index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'message[file]' => 'Testing',
            'message[messageId]' => 'Testing',
            'message[referenceId]' => 'Testing',
            'message[subject]' => 'Testing',
            'message[inquiry]' => 'Testing',
            'message[mail]' => 'Testing',
            'message[phone]' => 'Testing',
        ]);

        self::assertResponseRedirects('/sweet/food/');

        self::assertSame(1, $this->getRepository()->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Message();
        $fixture->setFile('My Title');
        $fixture->setMessageId('My Title');
        $fixture->setReferenceId('My Title');
        $fixture->setSubject('My Title');
        $fixture->setInquiry('My Title');
        $fixture->setMail('My Title');
        $fixture->setPhone('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Message');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Message();
        $fixture->setFile('Value');
        $fixture->setMessageId('Value');
        $fixture->setReferenceId('Value');
        $fixture->setSubject('Value');
        $fixture->setInquiry('Value');
        $fixture->setMail('Value');
        $fixture->setPhone('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'message[file]' => 'Something New',
            'message[messageId]' => 'Something New',
            'message[referenceId]' => 'Something New',
            'message[subject]' => 'Something New',
            'message[inquiry]' => 'Something New',
            'message[mail]' => 'Something New',
            'message[phone]' => 'Something New',
        ]);

        self::assertResponseRedirects('/message/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getFile());
        self::assertSame('Something New', $fixture[0]->getMessageId());
        self::assertSame('Something New', $fixture[0]->getReferenceId());
        self::assertSame('Something New', $fixture[0]->getSubject());
        self::assertSame('Something New', $fixture[0]->getInquiry());
        self::assertSame('Something New', $fixture[0]->getMail());
        self::assertSame('Something New', $fixture[0]->getPhone());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Message();
        $fixture->setFile('Value');
        $fixture->setMessageId('Value');
        $fixture->setReferenceId('Value');
        $fixture->setSubject('Value');
        $fixture->setInquiry('Value');
        $fixture->setMail('Value');
        $fixture->setPhone('Value');

        $this->manager->remove($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/message/');
        self::assertSame(0, $this->repository->count([]));
    }
}
