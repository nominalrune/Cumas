<?php

namespace App\Test\Controller;

use App\Entity\Inquiry;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class InquiryControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/inquiry/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(Inquiry::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Inquiry index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'inquiry[vategoryId]' => 'Testing',
            'inquiry[contactId]' => 'Testing',
            'inquiry[status]' => 'Testing',
            'inquiry[departmentId]' => 'Testing',
            'inquiry[agentId]' => 'Testing',
            'inquiry[notes]' => 'Testing',
            'inquiry[createdAt]' => 'Testing',
            'inquiry[updatedAt]' => 'Testing',
            'inquiry[category]' => 'Testing',
            'inquiry[department]' => 'Testing',
            'inquiry[agent]' => 'Testing',
        ]);

        self::assertResponseRedirects('/sweet/food/');

        self::assertSame(1, $this->getRepository()->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Inquiry();
        $fixture->setVategoryId('My Title');
        $fixture->setContactId('My Title');
        $fixture->setStatus('My Title');
        $fixture->setDepartmentId('My Title');
        $fixture->setAgentId('My Title');
        $fixture->setNotes('My Title');
        $fixture->setCreatedAt('My Title');
        $fixture->setUpdatedAt('My Title');
        $fixture->setCategory('My Title');
        $fixture->setDepartment('My Title');
        $fixture->setAgent('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Inquiry');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Inquiry();
        $fixture->setVategoryId('Value');
        $fixture->setContactId('Value');
        $fixture->setStatus('Value');
        $fixture->setDepartmentId('Value');
        $fixture->setAgentId('Value');
        $fixture->setNotes('Value');
        $fixture->setCreatedAt('Value');
        $fixture->setUpdatedAt('Value');
        $fixture->setCategory('Value');
        $fixture->setDepartment('Value');
        $fixture->setAgent('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'inquiry[vategoryId]' => 'Something New',
            'inquiry[contactId]' => 'Something New',
            'inquiry[status]' => 'Something New',
            'inquiry[departmentId]' => 'Something New',
            'inquiry[agentId]' => 'Something New',
            'inquiry[notes]' => 'Something New',
            'inquiry[createdAt]' => 'Something New',
            'inquiry[updatedAt]' => 'Something New',
            'inquiry[category]' => 'Something New',
            'inquiry[department]' => 'Something New',
            'inquiry[agent]' => 'Something New',
        ]);

        self::assertResponseRedirects('/inquiry/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getVategoryId());
        self::assertSame('Something New', $fixture[0]->getContactId());
        self::assertSame('Something New', $fixture[0]->getStatus());
        self::assertSame('Something New', $fixture[0]->getDepartmentId());
        self::assertSame('Something New', $fixture[0]->getAgentId());
        self::assertSame('Something New', $fixture[0]->getNotes());
        self::assertSame('Something New', $fixture[0]->getCreatedAt());
        self::assertSame('Something New', $fixture[0]->getUpdatedAt());
        self::assertSame('Something New', $fixture[0]->getCategory());
        self::assertSame('Something New', $fixture[0]->getDepartment());
        self::assertSame('Something New', $fixture[0]->getAgent());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Inquiry();
        $fixture->setVategoryId('Value');
        $fixture->setContactId('Value');
        $fixture->setStatus('Value');
        $fixture->setDepartmentId('Value');
        $fixture->setAgentId('Value');
        $fixture->setNotes('Value');
        $fixture->setCreatedAt('Value');
        $fixture->setUpdatedAt('Value');
        $fixture->setCategory('Value');
        $fixture->setDepartment('Value');
        $fixture->setAgent('Value');

        $this->manager->remove($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/inquiry/');
        self::assertSame(0, $this->repository->count([]));
    }
}
