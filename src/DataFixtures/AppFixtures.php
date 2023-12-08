<?php

namespace App\DataFixtures;

use App\Entity\Option;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $options[] = new Option('Título do blog', 'blog_title', 'Meu blog', TextType::class);
        $options[] = new Option('Texto de direitos autorais', 'blog_copyright', 'Todos os direitos reservados', TextType::class);
        $options[] = new Option('Número de artigos por página', 'blog_article_limit', 5 , NumberType::class);
        $options[] = new Option('Qualquer pessoa pode se inscrever', 'users_can_register', true, CheckboxType::class);

        foreach ($options as $option) {
            $manager->persist($option);
        }
        
        $manager->flush();
    }
}
