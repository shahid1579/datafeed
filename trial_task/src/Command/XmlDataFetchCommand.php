<?php

namespace App\Command;

use App\Command\EntityManagerInterface; // Add this line
use App\Entity\Product; // Add this line
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'XmlDataFetch',
    description: 'Add a short description for your command',
)]
class XmlDataFetchCommand extends Command
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();

        $this->entityManager = $entityManager;
    }


    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $xmlFilePath = 'pubic/feed.xml';

        // Read XML content
        $xmlContent = file_get_contents($xmlFilePath);

        // Deserialize XML to array
        $encoder = new XmlEncoder();
        $normalizer = new GetSetMethodNormalizer();
        $serializer = new Serializer([$normalizer], [$encoder]);

        $data = $serializer->decode($xmlContent, 'xml');

        // Import data to the database
        foreach ($data['catalog']['item'] as $itemData) {
            $exampleEntity = new Product();
            $exampleEntity->setName($itemData['name']);
            $exampleEntity->setDescription($itemData['description']);
            $exampleEntity->setPrice($itemData['price']);
            $exampleEntity->setCount($itemData['Count']);
            $exampleEntity->setSku($itemData['sku']);
            $exampleEntity->setShortdesc($itemData['shortdesc']);
            $exampleEntity->setImage($itemData['image']);
            $exampleEntity->setLink($itemData['link']);
            $exampleEntity->setBrand($itemData['Brand']);
            $exampleEntity->setRating($itemData['Rating']);
            $exampleEntity->setCaffeineType($itemData['CaffeineType']);
            $exampleEntity->setFlavored($itemData['Flavored']);
            $exampleEntity->setSeasonal($itemData['Seasonal']);
            $exampleEntity->setFacebook($itemData['Facebook']);
            $exampleEntity->setIsKCup($itemData['IsKCup']);


            $this->entityManager->persist($exampleEntity);
        }

        $this->entityManager->flush();

        $io->success('Data imported successfully.');
        return Command::SUCCESS;
    }
}
