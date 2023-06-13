<?php

namespace App\Quiz\Infrastructure\DataFixtures;

use App\Quiz\Domain\Entity\Answer;
use App\Quiz\Domain\Entity\Category;
use App\Quiz\Domain\Entity\Group;
use App\Quiz\Domain\Entity\Language;
use App\Quiz\Domain\Entity\Question;
use App\Quiz\Domain\Entity\School;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public const SYMFONY_REFERENCE = 'Symfony';
    public const SYMFONY_V3_REFERENCE = 'Symfony 3';
    public const SYMFONY_V4_REFERENCE = 'Symfony 4';

    public function load(ObjectManager $manager)
    {
        $this->loadLanguages($manager);
        for ($i = 0; $i < 4 ; $i++) {
            $this->loadSchools($manager, $i);
        }

        //$this->loadGroups($manager);

        //$this->loadCategories($manager);
    }


    private function loadSchools(ObjectManager $manager, $i)
    {
        $school = new School();
        $school->setName('School ' . $i);
        $school->setCode('Code ' . $i);
        for ($j= 0; $j < 5; $j++) {
            $group = new Group();
            $group->setName('Group ' . $i . ' ' . $j);
            $group->setCode('Code ' . $i . ' ' . $j);
            $group->setShortname('Gruppi' . $i . $j);
            $group->setSchool($school);
            $manager->persist($group);
        }

        $manager->persist($school);
        $manager->flush();
    }


    /**
     * @throws \Exception
     */
    private function loadLanguages(ObjectManager $manager) {

        $language = new Language();
        $language->setEnglishName('French');
        $language->setNativeName('Français');

        $language1 = new Language();
        $language1->setEnglishName('Germany');
        $language1->setNativeName('Deutsch');

        $language2 = new Language();
        $language2->setEnglishName('English');
        $language2->setNativeName('Englisch');



        for ($i = 0; $i < 4 ; $i++) {
            $category = new Category();
            $category->setLanguage($language);
            $category->setShortname('Symfony ' . $i);
            $category->setLongname('Symfony (all versions) ' . $i);



            for ($j = 0; $j < 300 ; $j ++) {
                $question = new Question();
                $question->setLanguage($language1);
                $question->setText('How many' . $j . ' In the box ?');
                $question->setMaxDuration(random_int(10, 50));
                $question->setComplicated(random_int(0, 10));
                $question->addCategory($category);


                for ($k = 0; $k < 5; $k++) {
                    $answer = new Answer();
                    $answer->setText(rand(1, 7));
                    $answer->setQuestion($question);
                    $answer->setCorrect((bool)random_int(0, 1));
                    $question->addAnswer($answer);
                    $manager->persist($answer);
                }



                $manager->persist($question);
            }

            $category->addQuestion($question);
            $manager->persist($category);
        }


        $manager->persist($language);
        $manager->persist($language1);
        $manager->persist($language2);
        $manager->flush();
    }

    private function loadCategories(ObjectManager $manager)
    {
        // SYMFONY_REFERENCE
        $category = $manager->getRepository(Category::class)->create();
        $category->setShortname('Symfony');
        $category->setLongname('Symfony (all versions)');
        $manager->persist($category);
        $this->addReference(self::SYMFONY_REFERENCE, $category);

        // SYMFONY_V3_REFERENCE
        $category = $manager->getRepository(Category::class)->create();
        $category->setShortname('Symfony3');
        $category->setLongname('Symfony version 3');
        $manager->persist($category);
        $this->addReference(self::SYMFONY_V3_REFERENCE, $category);

        // SYMFONY_V4_REFERENCE
        $category = $manager->getRepository(Category::class)->create();
        $category->setShortname('Symfony4');
        $category->setLongname('Symfony version 4');
        $manager->persist($category);
        $this->addReference(self::SYMFONY_V4_REFERENCE, $category);

        $manager->flush();
    }


}
